<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Controller for DHH_Chat
 * Author: Matt Arlauckas (mfabbu@rit.edu)
 * Date: 2019-04-12
 * Time: 10:15AM
 * @property  Admin_Model
 */

class Admin extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_Model');
	}

	public function index() {
		$data['users_online'] = $this->Admin_Model->get_users_online();
		$this->template->load('view_admin', $data);
	}

}