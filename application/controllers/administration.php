<?php defined('SYSPATH') or die('No direct script access.');

class Administration_Controller extends Template_Controller {
	
	public $template = 'admin';
	public $session;

	public function __construct()
	{
		parent::__construct();
		
		(IN_PRODUCTION === FALSE) AND new Profiler;
		
		$this->session = new Session;

		// check if user is logged in or not. also check if he has admin role
		if ( ! Auth::factory()->logged_in('admin'))
		{
        	$this->session->set('redirect_me_to', url::current());
        	url::redirect('admin/auth/login');
        }
		
		// Javascripts
		$this->template->meta .= html::script('media/js/jquery.js');
		$this->template->meta .= html::script('media/admin/js/ui.tabs.js');
		$this->template->meta .= html::script('media/admin/js/stuff.js');
		
		// Stylesheets
		$this->template->meta .= html::stylesheet('media/admin/css/ui.tabs.css', 'screen');
			
		$this->template->tasks = array();
		
        $this->template->title = '';
        $this->template->message = $this->session->get('info_message', NULL);
		$this->template->error = $this->session->get('error_message', NULL);
		$this->template->content = '';
	}
	
	public function recent_entries($number = 10) {
		return NULL;
	}

}