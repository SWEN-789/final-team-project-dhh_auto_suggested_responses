<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Login Controller for DHH_Chat
 * Author: Matt Arlauckas (mfabbu@rit.edu)
 * Date: 2019-04-12
 * Time: 11:00AM
 * @property  Login_Model
 */

class Login extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Login_Model');
	}

	public function index() {
		$this->template->load('view_login');
	}

	public function ajax_check_login() {
		$username = $this->security->xss_clean($this->input->post('username'));
		$password = $this->security->xss_clean($this->input->post('password'));
		//log_message('debug', 'username: ' . $username);
		//log_message('debug', 'password: ' . $password);
		$data_array = array(
			'username' => $username,
			'password' => $password
		);
		//log_message('debug', print_r($data_array, true));
		$auth = array();
		$auth = $this->Login_Model->check_login($data_array);
		log_message('debug', 'auth: ' . print_r($auth, true));
		if ($auth['user_authenticated']) {
			$this->Login_Model->set_online($auth['user_id']);
			$this->session->set_userdata('user_session', $auth);
			$this->session->set_userdata('last_chat_message_id_'.$_SESSION['user_session']['user_id'], 0);
			if ($auth['user_admin'] === 0) {
				$this->session->set_userdata('chat_id', $_SESSION['user_session']['user_id']);
			}
			$result = array(
				'admin' => $_SESSION['user_session']['user_admin'],
				'authenticated' => true
			);
		} else {
			$result = array(
				'authenticated' => false
			);
		}

		echo json_encode($result);
	}

	public function ajax_logout() {
		$this->Login_Model->remove_user_online($_SESSION['user_session']['user_id']);
		unset($_SESSION['user_session']);
		echo json_encode(array('logout' => true));
	}

}