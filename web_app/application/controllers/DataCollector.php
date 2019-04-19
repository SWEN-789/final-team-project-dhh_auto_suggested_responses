<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Chat Controller for DHH_Chat
 * Author: Matt Arlauckas (mfabbu@rit.edu)
 * Date: 2019-04-09
 * Time: 12:23 PM
 * @property  DataCollector_Model
 */

class DataCollector extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('DataCollector_Model');
	}

	public function index() {

//		$chat_id = 1;
//
//		$this->session->set_userdata('last_chat_message_id_'.$chat_id, 0);

//		if ($_SESSION['user_session']['user_authenticated']) {
//			$data = $_SESSION['user_session'];
//			$this->template->load('view_chats', $data);
//		} else {
		$this->template->load('data_view');
//		}
	}

	public function ajax_write_data() {

		log_message('debug', 'AJAX_WRITE_DATA CALLED');

		$user = $this->input->post('user');
		$data = $this->input->post('data');

		$result = $this->DataCollector_Model->write_to_file($user, $data);
		echo json_encode(array('status' => $result));
	}

}