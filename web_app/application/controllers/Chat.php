<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Chat Controller for DHH_Chat
 * Author: Matt Arlauckas (mfabbu@rit.edu)
 * Date: 2019-03-30
 * Time: 1:51 PM
 * @property  Chat_Model
 */

class Chat extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Chat_Model');
	}

	public function index() {

		$chat_id = 1;

		$this->session->set_userdata('last_chat_message_id_'.$chat_id, 0);

//		if ($_SESSION['user_session']['user_authenticated']) {
//			$data = $_SESSION['user_session'];
//			$this->template->load('view_chats', $data);
//		} else {
			$this->template->load('view_main');
//		}
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
		$auth = $this->Chat_Model->check_login($data_array);
		log_message('debug', 'auth: ' . print_r($auth, true));
		if ($auth['user_authenticated']) {
			$this->Chat_Model->set_online($auth['user_id']);
			$this->session->set_userdata('user_session', $auth);
		}
		$result = array(
			'authenticated' => $auth
		);
		echo json_encode($result);
	}

	public function ajax_logout() {

	}

	public function view_chats() {
		$data['users_online'] = $this->Chat_Model->get_users_online();
		$this->template->load('view_chats', $data);
	}

	public function ajax_add_chat_message() {

		$chat_id = $this->input->post('chat_id');
		$user_id = $this->input->post('user_id');
		$message_content = $this->security->xss_clean($this->input->post('message_content'));

		$result = $this->Chat_Model->add_chat_message($chat_id, $user_id, $message_content);
		$this->session->set_userdata('last_chat_message_id_'.$chat_id, $result);
		$this->Chat_Model->set_last_chat_message($result);
		//echo json_encode(array('status' => $result));
		// grab and return messages
		echo $this->_get_chat_messages($chat_id);
	}

	public function ajax_get_chat_messages() {
		$chat_id = 1;
		echo $this->_get_chat_messages($chat_id);
	}

	function _get_chat_messages($chat_id) {

		$last_chat_message_id = (int)$this->session->userdata('last_chat_message_id_'. $chat_id);

		$chat_messages = $this->Chat_Model->get_chat_messages($chat_id, $last_chat_message_id);
		if ($chat_messages) {
			// store the last chat message id
			// we have a chat - return something
			$chat_messages_html = '<ul>';
			foreach ($chat_messages as $chat_message) {
				$chat_messages_html .= '<li class="message">(';
				$chat_messages_html .= $chat_message->name . ") ";
				$chat_messages_html .= $chat_message->message_content;
				$chat_messages_html .= '</li>';
				$last_chat_message_id = $chat_message->chat_message_id;
			}
			$chat_messages_html .= '</ul>';

			$this->session->set_userdata('last_chat_message_id_'.$chat_id, $last_chat_message_id);

			$results = array(
				'status' => true,
				'content' => $chat_messages_html
			);
			return json_encode($results);
			exit();
		} else {
			// no chat yet
			$results = array(
				'status' => true,
				'content' => ''
			);
			return json_encode($results);
			exit();
		}
	}

	public function ajax_get_api_results($message) { //($method, $url, $data) {
		//$message = 'what would you like to order';
		//log_message('debug', 'message: '.$message);
		$message = str_replace('-',' ', $message);
		$method = "POST";
		$url = "http://localhost:8080/get-suggested-responses";
		$data = json_encode( array(
			'context' => 'food service',
			'message' => $message
		));
		$results = $this->Chat_Model->callAPI($method, $url, $data);
		echo $results;
	}

	public function ajax_add_api_results() { //($method, $url, $data) {
		$method = "POST";
		$url = "http://localhost:8080/add-suggested-response";
		$data = json_encode( array(
			'context' => 'food service',
			'suggestedResponse' => 'Could I please get a chef salad'
		));
		$results = $this->Chat_Model->callAPI($method, $url, $data);
		echo $results;
	}
}
