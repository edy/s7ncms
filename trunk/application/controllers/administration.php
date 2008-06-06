<?php defined('SYSPATH') or die('No direct script access.');

class Administration_Controller extends Template_Controller {

	public $template = 'admin';
	public $session;

	public function __construct()
	{
		parent::__construct();

		$this->session = Session::instance();

		// check if user is logged in or not. also check if he has admin role
		if ( ! Auth::factory()->logged_in('admin'))
		{
			$this->session->set('redirect_me_to', url::current());
			url::redirect('admin/auth/login');
		}
		$this->head = Head::instance();

		// Javascripts
		$this->head['javascript']->append_file('media/js/jquery.js');
		$this->head['javascript']->append_file('media/admin/js/ui.tabs.js');
		$this->head['javascript']->append_file('media/admin/js/stuff.js');

		// Stylesheets
		$this->head['css']->append_file('media/admin/css/layout');
		$this->head['css']->append_file('media/admin/css/ui.tabs');

		$this->head['title']->set('S7Nadmin');

		$this->template->tasks = array();

		$this->template->title = '';
		$this->template->message = $this->session->get('info_message', NULL);
		$this->template->error = $this->session->get('error_message', NULL);
		$this->template->content = '';
		$this->template->head = $this->head;

		$this->template->searchbar = FALSE;
		$this->template->searchvalue = '';
	}

	public function recent_entries($number = 10) {
		return '';
	}

}