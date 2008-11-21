<?php
/**
 * S7Ncms - www.s7n.de
 *
 * Copyright (c) 2007-2008, Eduard Baun <eduard at baun.de>
 * All rights reserved.
 *
 * See license.txt for full text and disclaimer
 *
 * @author Eduard Baun <eduard at baun.de>
 * @copyright Eduard Baun, 2007-2008
 * @version $Id$
 */
class Blog_Controller extends Website_Controller {

	public function __construct()
	{
		parent::__construct();
		// TODO: this is buggy in 2.2.1. needs to be fixed
		$this->head->link->append(Router::$routed_uri.'/feed');

		Sidebar::instance()->add
		(
			'Tagcloud',
			array
			(
					'tags' => ORM::factory('blog_post')->all_tags()
			)
		);
	}

	public function __call($method, $arguments)
	{
		$this->index($method);
	}

	public function page()
	{
		$this->index();
	}

	public function index($uri = NULL)
	{
		if ($uri === NULL)
		{
			$pagination = new Pagination(array(
				'uri_segment'    => 'page',
				'items_per_page' => (int) Kohana::config('blog.items_per_page'),
				'total_items'    => ORM::factory('blog_post')->count_posts(),
				'style'          => 'digg'
			));

			$this->template->content = View::factory('blog/index')->set(array(
				'posts' => ORM::factory('blog_post')->orderby('id', 'desc')->find_all((int) Kohana::config('blog.items_per_page'), $pagination->sql_offset),
				'pagination' => $pagination
			))->render();
		}
		else
		{
			$post = ORM::factory('blog_post', (string) $uri);

			// Show 404 if we don't find posts
			if ( ! $post->loaded)
				Event::run('system.404');

			$this->head->title->prepend($post->title);

			$this->head->javascript->append_file('media/js/jquery.js');
			$this->head->javascript->append_file('modules/blog/media/js/comments.js');

			$form = NULL;

			if ($post->comment_status === 'open' AND Kohana::config('blog.comment_status') === 'open')
			{
				$fields = array
				(
					'author'  => '',
					'email'   => '',
					'url'     => '',
					'content' => '',
				);

				$errors = $fields;

				if ($_POST)
				{
					$_POST = new Validation($_POST);

					$_POST
					->pre_filter('trim')

					->post_filter('html::specialchars', 'author', 'url', 'content')
					->post_filter('format::url', 'url')

					->add_rules('author', 'required', 'length[2,40]')
					->add_rules('email', 'valid::email')
					->add_rules('content', 'required');

					if ($_POST->validate())
					{
						// prevents CSRF
						if ($this->session->get('form_key') === $_POST['form_key'])
						{
							// our 'honeypot' part one
							if($this->input->post('location') === 'none' OR $this->session->get('location') === 'none')
							{
								$comment = ORM::factory('blog_comment');
								$comment->author  = $_POST['author'];
								$comment->email   = $_POST['email'];
								$comment->content = $_POST['content'];
								$comment->url     = $_POST['url'];
								$comment->ip      = $this->input->ip_address();
								$comment->agent   = html::specialchars(Kohana::$user_agent);
								$comment->date    = date("Y-m-d H:i:s", time());

								$post->add_comment($comment);

								$this->session->delete('location');
							}
						}

						url::redirect($post->get_url());
					}
					else
					{
						// our 'honeypot' part two
						if ($this->input->post('location') === 'none')
							$this->session->set('location', 'none');

						$fields = arr::overwrite($_POST->as_array());
						$errors = arr::overwrite($_POST->errors('blog_form_error_messages'));
					}
				}

				$form = View::factory('blog/form_comment')->set(array(
					'fields' => $fields,
					'errors' => $errors,
					'form_key' => $_SESSION['form_key'] = text::random('alnum', 16)
				))->render();
			}

			$this->template->content = View::factory('blog/view')->set(array(
				'post' => $post,
				'comments' => $post->blog_comments,
				'form' => $form
			))->render();
		}
	}

	public function tag($tag)
	{
		$this->template->content = View::factory('blog/index')->set(array(
			'posts' => ORM::factory('blog_post')->like('tags', $tag)->orderby('id', 'desc')->find_all()
		))->render();
	}

}