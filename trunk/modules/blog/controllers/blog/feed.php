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
class Feed_Controller extends Controller {

	protected $cache;

	public function __construct()
	{
		parent::__construct();

		$this->cache = new Cache(array
			(
				'lifetime' => 60*60
			)
		);
	}

	public function index()
	{
		header('Content-Type: text/xml; charset=UTF-8', TRUE);
		
		if ($cache = $this->cache->get('s7n_blog_feed'))
		{
			echo $cache;
		}
		else
		{
			$posts = ORM::factory('blog_post')->orderby('id', 'desc')->find_all(10);

			$info = array
			(
				'title' => Kohana::config('s7n.site_title'),
				'description' => '',
				'link' => Router::$routed_uri,
				'generator' => 'S7Ncms - http://www.s7n.de/'
			);

			$items = array();
			foreach ($posts as $post)
			{
				$items[] = array
				(
					'author'      => 'example@example.com ('.$post->user->username.')',
					'pubDate'     => date('r', strtotime($post->date)),
					'title'       => $post->title,
					'description' => $post->content,
					'link'        => $post->get_url(),
					'guid'        => $post->get_url(),
				);
			}

			$feed = feed::create($info, $items);
			$this->cache->set('s7n_blog_feed', $feed);
			echo $feed;
		}
	}

	public function comments()
	{
		header('Content-Type: text/xml; charset=UTF-8', TRUE);
		
		if ($cache = $this->cache->get('s7n_blog_feed_comments'))
		{
			echo $cache;
		}
		else
		{
			$comments = ORM::factory('blog_comment')->orderby('id', 'desc')->find_all(20);
	
			$info = array
			(
				'title' => Kohana::config('s7n.site_title').' (Latest Comments)',
				'link' => Router::$routed_uri,
				'generator' => 'S7Ncms - http://www.s7n.de/'
			);
	
			$items = array();
			foreach ($comments as $comment)
			{
				$items[] = array
				(
					'author'      => $comment->author,
					'pubDate'     => date('r', strtotime($comment->date)),
					'title'       => 'New comment for "'.$comment->blog_post->title.'"',
					'description' => $comment->content,
					'link'        => $comment->blog_post->get_url(),
					'guid'        => $comment->blog_post->get_url(),
				);
			}
	
			$feed = feed::create($info, $items);
			$this->cache->set('s7n_blog_feed_comments', $feed);
			echo $feed;
		}
	}

}