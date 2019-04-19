<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * DataCollector Model for DHH_Chat
 * Author: Matt Arlauckas (mfabbu@rit.edu)
 * Date: 2019-04-09
 * Time: 12:24 PM
 */

class DataCollector_Model extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function write_to_file($user, $data) {
		$chat_context = $_SESSION['context'];
		$chat_mode = $_SESSION['mode'];
		if ($chat_mode) $mode = '_api';
		else $mode = '';
		$timestamp = "(" . date('Y-m-d H:i:s') . ")";
		$path = "../data/" . $user . "_". $chat_context . $mode . ".txt";
		log_message('debug', 'PATH: ' . $path);
		write_file($path, $timestamp . " " .$data.PHP_EOL, 'a');
		return true;
	}
}
