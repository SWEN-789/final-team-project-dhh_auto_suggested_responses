<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Reports Model for DHH_Chat
 * Author: Matt Arlauckas (mfabbu@rit.edu)
 * Date: 2019-04-15
 * Time: 12:24 PM
 */

class Reports_Model extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_all_messages($chat_id) {
		$this->db->select('messages.id as id', FALSE);
		$this->db->select('messages.chat_id as chat_id', FALSE);
		$this->db->select('users.username as user_id', FALSE);
		$this->db->select('messages.context as context', FALSE);
		$this->db->select('messages.mode as mode', FALSE);
		$this->db->select('messages.message_content as message_content', FALSE);
		$this->db->select('messages.create_date', FALSE);
		$this->db->from('messages');
		$this->db->join('users','messages.user_id = users.id');
		$this->db->where('chat_id', $chat_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_messages_array($chat_id) {
		$this->db->select('messages.id as id', FALSE);
		$this->db->select('messages.chat_id as chat_id', FALSE);
		$this->db->select('users.username as user_id', FALSE);
		$this->db->select('messages.context as context', FALSE);
		$this->db->select('messages.mode as mode', FALSE);
		$this->db->select('messages.message_content as message_content', FALSE);
		$this->db->select('messages.create_date', FALSE);
		$this->db->from('messages');
		$this->db->join('users','messages.user_id = users.id');
		$this->db->where('chat_id', $chat_id);
		$query = $this->db->get();
		return $query->result_array();
	}
}
