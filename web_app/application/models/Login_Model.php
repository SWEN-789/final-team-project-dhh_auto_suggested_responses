<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Login Model for DHH_Chat
 * Author: Matt Arlauckas (mfabbu@rit.edu)
 * Date: 2019-04-12
 * Time: 11:00 AM
 */

class Login_Model extends MY_Model {
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
			$result = array('user_authenticated' => false);
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



	public function remove_user_online($user) {
		$this->db->delete('users_online', array('user_id' => $user));
		return true;
	}
}
