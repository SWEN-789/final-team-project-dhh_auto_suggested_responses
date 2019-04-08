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

	public function check_login($data) {
		//log_message('debug', print_r($data, true));
		$this->db->db_select('dhh_chat');
		$this->db->select('*', FALSE);
		$this->db->from('users');
		$this->db->where($data);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$result = array('authenticated' => false);
		} else {
			while ($row = $query->unbuffered_row()) {
				$result = array(
					'user_id' => $row->id,
					'user_username' => $row->username,
					'user_fullname' => $row->name,
					'user_admin' => $row->admin,
					'user_authenticated' => true
				);
				//log_message('debug', print_r($result, true));
			}
		}
		return $result;
	}

	public function set_online($user) {
		// if already online, update last_activity, else insert new record
		$this->db->select("*", FALSE);
		$this->db->from('users_online');
		$this->db->where('user_id', $user);
		$query = $this->db->get();
		$result = $query->row();
		if ($result) {
			log_message('debug', 'already logged in.');
		} else {
			$this->db->insert('users_online', array('user_id' => $user));
		}
		return true;
	}

	public function get_users_online() {
		$this->db->select('users_online.user_id AS user_id', FALSE);
		$this->db->select('users.username AS user_username', FALSE);
		$this->db->select('users.name AS user_fullname', FALSE);
		$this->db->from('users_online');
		$this->db->join('users', 'users_online.user_id = users.id');
		$query = $this->db->get();
		return $query->result();
	}

	public function remove_user_online($user) {
		$this->db->delete('user_online', array('user_id' => $user));
		return true;
	}

	public function add_chat_message($chat_id, $user_id, $message_content) {
		$data = array(
			'chat_id' => $chat_id,
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

	public function callAPI($method, $url, $data) {
		$curl = curl_init();

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
			'APIKEY: 1111111111',
			'Content-Type: application/json',
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($curl, CURLOPT_HTTPPATH, CURLAUTH_BASIC);

		// execute
		$result = curl_exec($curl);
		if (!$result) { die("Connection Failure"); }
		curl_close($curl);
		return $result;
	}
}
