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
class Blogpost_Model extends ORM {
	
	protected $has_many = array('comments');
	protected $belongs_to = array('user');
	
	/**
	 * Allows Blogposts to be loaded by id or uri title.
	 */
	public function unique_key($id = NULL)
	{
		if( ! empty($id) AND is_string($id) AND ! ctype_digit($id))
		{
			return 'uri';
		}
		
		return parent::unique_key($id);
	}
	
	/**
	 * increment the comment counter on each new comment
	 */
	public function add_comment($object)
	{
		/*
		 * set the user_id if the poster is logged in
		 */
		if(isset($_SESSION['auth_user']))
		{
			$object->user_id = $_SESSION['auth_user']->id;
		}

		$object->blogpost_id = $this->id;
		$object->save();
		
		$this->comment_count += 1;
		$this->save();
		
	}
	
	public function count_posts()
	{
		return count(Database::instance()->select('id')->get('blogposts'));
	}
	
	public function all_tags()
	{
		$tags = array();
		$query = Database::instance()->select('tags')->get('blogposts');
		
		foreach ($query as $result)
		{   
            $exploded = explode(',', $result->tags);
            
            foreach($exploded as $tag) {
                $tag = trim($tag);
                
                if(empty($tag)) {continue;}
                
                if(array_key_exists($tag,$tags))
                {   
	                $tags[$tag]++;
	            }
	            else
	            {
	                $tags[$tag] = 1;
	            }
            }
        }
        
        return $tags;
	}
	
}