<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Chat Model for DHH_Chat
 * Author: Matt Arlauckas (mfabbu@rit.edu)
 * Date: 2019-03-30
 * Time: 1:51 PM
 */

class Chat_Model extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}





	public function add_chat_message($chat_id, $context, $mode, $user_id, $message_content) {
		$data = array(
			'chat_id' => $chat_id,
			'context' => $context,
			'mode' => $mode,
			'user_id' => $user_id,
			'message_content' => $message_content
		);
		$this->db->insert('messages', $data);
		//$this->session->set_userdata('last_chat_message_id_'.$chat_id, $this->db->insert_id());
		return $this->db->insert_id();
	}

	public function get_chat_messages($chat_id, $last_chat_message_id = 0) {
		$this->db->select('messages.user_id AS user_id', FALSE);
		$this->db->select('messages.message_content AS message_content', FALSE);
		$this->db->select('users.name AS name', FALSE);
		$this->db->select('messages.create_date AS create_date', FALSE);
		$this->db->select('messages.id AS chat_message_id', FALSE);
		$this->db->from('messages');
		$this->db->join('users', 'messages.user_id = users.id');
		$this->db->where('messages.chat_id', $chat_id);
		//$this->db->where('messages.id >', $last_chat_message_id);
		$this->db->order_by('messages.id', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function set_last_chat_message($message_id,$user_admin) {
		$this->db->set('last_chat', $_SESSION['chat_id'], FALSE);
		$this->db->set('last_chat_message', $message_id, FALSE);
		$this->db->set('last_sender', $user_admin, FALSE);
		$this->db->update('sys_variables');
		return $this->db->affected_rows();
	}

	public function get_last_chat_message() {
		$this->db->select('last_chat_message', FALSE);
		$this->db->from('sys_variables');
		$this->db->where('last_chat', $_SESSION['chat_id']);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_last_sender() {
		$this->db->select('last_sender', FALSE);
		$this->db->from('sys_variables');
		$this->db->where('last_chat', $_SESSION['chat_id']);
		$query = $this->db->get();
		return $query->row();
		//$row = $query->row();
		//if ($row->last_sender) return 1;
		//else return 0;
	}

	public function get_last_message() {
		$this->db->select('messages.message_content as message', FALSE);
		$this->db->from('messages');
		$this->db->join('sys_variables', 'messages.id = sys_variables.last_chat_message');
		$query = $this->db->get();
		return $query->row();
	}

	public function callAPI($method, $url, $data) {
		$curl = curl_init();

		log_message('debug', 'curl error PRE');
		log_message('debug', $data);

		switch($method) {
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;


			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			default:
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}

		// options
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'APIKEY: 111111111111111111111',
			'Content-Type: application/json',
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($curl, CURLOPT_HTTPPATH, CURLAUTH_BASIC);
//
//		// execute
//		$result = curl_exec($curl);


//		curl_setopt_array($curl, array(
//			CURLOPT_URL => "https://dhh-service.herokuapp.com/get-suggested-responses",
//			CURLOPT_RETURNTRANSFER => true,
//			CURLOPT_ENCODING => "",
//			CURLOPT_MAXREDIRS => 10,
//			CURLOPT_TIMEOUT => 30,
//			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//			CURLOPT_CUSTOMREQUEST => "POST",
//			CURLOPT_POSTFIELDS => "{\r\n    \"context\": \"food service\",\r\n    \"message\": \"what would you like to order\"\r\n}",
//			CURLOPT_HTTPHEADER => array(
//				"Content-Type: application/json",
//				//"Postman-Token: e9d0bbd1-13d6-43df-b055-26cf4bd50b8f",
//				"cache-control: no-cache"
//			),
//		));



		$result = curl_exec($curl);
		log_message('debug', 'curl error POST');
		$err = curl_error($curl);
		log_message('debug', 'curl error: ' . $err);

		if (!$result) {
			die("Connection Failure");
			//log_message('debug', 'curl error');
		}
		curl_close($curl);

		return $result;
	}
}
