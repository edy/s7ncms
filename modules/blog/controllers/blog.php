<?php defined('SYSPATH') or die('No direct script access.');
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
	
	protected $blog;
	
	public function __construct()
	{
		parent::__construct();
		$this->blog = new Blogpost_Model;
		$this->head['link']->append_link('blog/feed');
	}

	public function _remap($method, $arguments)
	{
		if ($method == 'page')
		{
			$method = 'index';
		}
		elseif ( ! method_exists($this, $method))
		{
			$arguments = $method;
			$method = 'view';				
		}
		
		call_user_func_array(array($this, $method), $arguments);
	}
	
	public function index()
	{
		$this->pagination = new Pagination(array(
			'uri_segment'    => 'page',
			'items_per_page' => (int) config::item('blog.items_per_page'),
			'total_items'    => $this->blog->count_posts(),
			'style'          => 'digg'
		));
		
		$view = new View('blog/index');
		$view->blogposts = $this->blog->orderby('id', 'desc')->limit((int) config::item('blog.items_per_page'), $this->pagination->sql_offset())->find_all();
		
		$this->template->content = $view;
		$this->template->content->pagination = $this->pagination;
	}
	
	public function view($uri)
	{
		$view = new View('blog/view');
		$view->blogpost = $this->blog->find((string) $uri);
		
		// Show 404 if we don't find blogposts
		if ((int) $view->blogpost->id === 0)
			Event::run('system.404');
			
		$this->head['javascript']->append_file('media/js/jquery.js');
		$this->head['javascript']->append_file('modules/blog/media/js/comments.js');
		
		$view->comments = $this->blog->find_related_comments();
		$view->form = '';
		
		if ($this->blog->comment_status === 'open' AND config::item('blog.comment_status') === 'open')
		{
			$form = new Forge();
			$form->error_format('<span class="error">{message}</span><br />');
			$form->input('form_name')->label('Name')->rules('required|length[3,40]');
			$form->input('form_email')->label('E-Mail')->rules('valid_email');
			$form->input('form_homepage')->label('Homepage');
			$form->textarea('form_comment')->label('Kommentar')->rules('required');
			$form->submit('submit');
	 
			if ($form->validate())
			{
			    $comment = new Comment_Model;
				$comment->author = $form->form_name->value;
				$comment->email = $form->form_email->value;
				$comment->url = $form->form_homepage->value;
				$comment->ip = $this->input->ip_address();
				$comment->agent = Kohana::$user_agent;
				$comment->content = $form->form_comment->value;
				$comment->date = date("Y-m-d H:i:s", time());
				
				// our 'honeypot'
				if($this->input->post('location') === 'none' OR $this->session->get('location') === 'none')
				{
					$this->blog->add_comment($comment);
					$this->session->delete('location');
				}
				
				url::redirect('blog/'.$uri);
			}
			else
			{
				if ($this->input->post('location') === 'none')
					$this->session->set('location', 'none');
	
			    $view->form = $form->render('blog/form_comment', TRUE);
			}
		}
		
		$this->template->content = $view;
		
		$this->head['title']->prepend($view->blogpost->title);
	}
	
	public function feed()
	{
		$this->auto_render = FALSE;
		if($this->profiler)
		{
			$this->profiler->disable();
		}
			
		$view = new View('blog/feed');
		$view->posts = $this->blog->limit(10)->orderby('id', 'desc')->find_all();
		
		header('Content-Type: text/xml; charset=UTF-8', TRUE);
		echo $view;
	}
	
	public function commentfeed()
	{
		$this->auto_render = FALSE;
		if($this->profiler)
		{
			$this->profiler->disable();
		}
			
		$view = new View('blog/commentfeed');
		$view->comments = ORM::factory('comment')->orderby('id', 'desc')->find_all(20);
		
		header('Content-Type: text/xml; charset=UTF-8', TRUE);
		echo $view;
	}

}