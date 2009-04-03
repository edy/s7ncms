<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * S7Ncms - www.s7n.de
 *
 * Copyright (c) 2007-2009, Eduard Baun <eduard at baun.de>
 * All rights reserved.
 *
 * See license.txt for full text and disclaimer
 *
 * @author Eduard Baun <eduard at baun.de>
 * @copyright Eduard Baun, 2007-2009
 * @version $Id$
 */
class Blog_Controller extends Administration_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->template->tasks = array(
			array('admin/blog/newpost', 'New Post'),
			array('admin/blog/settings', 'Edit Settings')
		);

		$this->head->title->append('Blog');
		$this->template->title = html::anchor('admin/blog', 'Blog').' | ';
	}

	public function index()
	{
		$this->template->searchbar = TRUE;

		$posts = array();

		$q = trim($this->input->get('q'));

		if ( ! empty($q))
		{
			$this->template->searchvalue = $q;
			$this->template->title .= 'Filter: '.$q;
			$this->head->title->append('Filter: '.$q);

			$posts = ORM::factory('blog_post')->orlike(array(
				'title' => '%'.$q.'%',
				'excerpt' => '%'.$q.'%',
				'content' => '%'.$q.'%',
				'tags' => '%'.$q.'%'
			))->find_all();
		}
		else
		{
			$this->template->title .= 'All Posts';
			$this->head->title->append('All Posts');

			$posts = ORM::factory('blog_post')->find_all();
		}

		$this->template->content = View::factory('blog/index', array('posts' => $posts));
	}

	public function newpost()
	{
		if($_POST)
		{
			$post = new Blog_post_Model;
			$post->user_id = $_SESSION['auth_user']->id;
			$post->title = html::specialchars($this->input->post('form_title'), FALSE);

			$uri = url::title($this->input->post('form_title'));

			// Check if uri already exists and add a suffix
			$result = $this->db->select('uri')->like('uri', $uri.'%', FALSE)->get('blog_posts');
			if (count($result) > 0)
			{
				$max = 0;
				foreach ($result as $row)
				{
					$suffix = substr($row->uri, strlen($uri)+1);
					if(ctype_digit($suffix) AND $suffix > $max)
						$max = $suffix;
				}

				if ($max === 0)
					$uri .= '-2';
				else
					$uri .= '-'.($max+1);
			}

			$post->uri = $uri;
			$post->content = $this->input->post('form_content');
			$post->tags = html::specialchars($this->input->post('form_tags'), FALSE);
			$post->save();

			// delete feed cache
			Cache::instance()->delete('s7n_blog_feed');

			message::info('Post created successfully', 'admin/blog');
		}
		else
		{
			$this->head->javascript->append_file('vendor/tiny_mce/tiny_mce.js');
			$this->head->title->append('New Post');

			$this->template->title .= 'New Post';
			$this->template->tabs = array('Content', 'Advanced');
			$this->template->content = View::factory('blog/newpost');
		}
	}

	public function edit()
	{
		if($_POST)
		{
			$post = ORM::factory('blog_post', (int) $this->input->post('form_id'));
			$post->title = html::specialchars($this->input->post('form_title'), FALSE);
			// TODO uri-check einbauen
			$post->uri = url::title($this->input->post('form_title'));
			$post->content = $this->input->post('form_content');
			$post->tags = html::specialchars($this->input->post('form_tags'), FALSE);
			$post->save();

			// delete feed cache
			Cache::instance()->delete('s7n_blog_feed');

			message::info('Post edited successfully', 'admin/blog');
		}
		else
		{
			$post = ORM::factory('blog_post', (int) $this->uri->segment(4));

			$this->template->tabs = array('Content', 'Advanced');

			$this->head->javascript->append_file('vendor/tiny_mce/tiny_mce.js');
			$this->head->title->append('Edit: '. $post->title);
			$this->template->title .= 'Edit: '. $post->title;

			$this->template->content = View::factory('blog/edit', array('post' => $post));
		}
	}

	public function comments($action, $id = NULL)
	{
		// accept only valid actions
		if (in_array($action, array('open', 'close', 'edit', 'delete')))
		{
			$function_name = 'comments_'.$action;

			if(ctype_digit($id))
				$this->$function_name($id);
			else
				Event::run('system.404');
		}
		else
		{
			if(ctype_digit($action))
				$this->comments_view($action);
			else
				Event::run('system.404');
		}
	}

	private function comments_view($id)
	{
		$post = ORM::factory('blog_post', (int) $id);
		$this->template->content = new View('blog/comments');
		$this->template->content->comments = $post->blog_comments;

		$this->head->title->append('Comments for: '. $post->title);
		$this->template->title .= 'Comments for: '. $post->title;
	}

	private function comments_open($id)
	{
		$this->comments_status('open', $id);
	}

	private function comments_close($id)
	{
		$this->comments_status('close', $id);
	}

	private function comments_status($status, $id)
	{
		$post = ORM::factory('blog_post', (int) $id);

		if ( ! $post->loaded)
			message::error('Invalid ID', 'admin/blog');

		$post->comment_status = $status;
		$post->save();

		message::info('Comment status changed to "'.$status.'"', 'admin/blog');
	}

	private function comments_edit($id)
	{
		if($_POST)
		{
			$comment = ORM::factory('blog_comment', (int) $id);
			$comment->author = $this->input->post('form_author');
			$comment->email = $this->input->post('form_email');
			$comment->url = $this->input->post('form_url');
			$comment->content = $this->input->post('form_content');
			$comment->save();

			Cache::instance()->delete('s7n_blog_feed');

			message::info('Comment edited successfully', 'admin/blog/comments/'.$comment->blog_post_id);
		}
		else
		{
			$comment = ORM::factory('blog_comment', (int) $id);

			$this->head->javascript->append_file('vendor/tiny_mce/tiny_mce.js');
			$this->head->title->append('Edit: Comment #'. $comment->id);
			$this->template->title .= 'Edit: Comment #'. $comment->id;

			$this->template->content = View::factory('blog/editcomment', array(
				'comment' => $comment
			));
		}
	}

	private function comments_delete($id)
	{
		$comment = ORM::factory('blog_comment', (int) $id);
		if ( ! $comment->loaded)
			message::error('Invalid ID', 'admin/blog');
		
		$post = ORM::factory('blog_post', (int) $comment->blog_post_id);
		$post->comment_count -= 1;
		$post->save();

		$comment->delete();

		Cache::instance()->delete('s7n_blog_feed_comments');

		message::info('Comment deleted successfully', 'admin/blog/comments/'.$post->id);
	}

	public function delete($id)
	{
		$post = ORM::factory('blog_post', (int) $id);

		if ( ! $post->loaded)
			message::error('Invalid ID', 'admin/blog');
		
		// remove comments first
		Database::instance()->where('blog_post_id', (int) $post->id)->delete('blog_comments');

		// then delete the post
		$post->delete();

		Cache::instance()->delete('s7n_blog_feed');
		Cache::instance()->delete('s7n_blog_feed_comments');

		message::info('Post deleted successfully', 'admin/blog');
	}

	public function settings()
	{
		if($_POST)
		{
			$comment_status = ($this->input->post('comment_status') === 'open') ? 'open' : 'closed';

			config::set('blog.comment_status', $comment_status);
			config::set('blog.items_per_page', (int) $this->input->post('items_per_page'));

			message::info('Settings changed successfully', 'admin/blog');
		}
		else
		{
			$this->head->title->append('Settings');
			$this->template->title .= 'Settings';

			$this->template->content = new View('blog/settings');
			$this->template->content->items_per_page = config::get('blog.items_per_page');
			$this->template->content->comment_status = config::get('blog.comment_status') == 'open' ? TRUE : FALSE;
		}
	}

}