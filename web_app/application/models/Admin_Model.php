<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Admin Model for DHH_Chat
 * Author: Matt Arlauckas (mfabbu@rit.edu)
 * Date: 2019-04-09
 * Time: 12:24 PM
 */

class Admin_Model extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
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

}
