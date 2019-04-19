<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reports Controller for DHH_Chat
 * Author: Matt Arlauckas (mfabbu@rit.edu)
 * Date: 2019-04-15
 * Time: 12:23 PM
 * @property  Reports_Model
 */

class Reports extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Reports_Model');
	}

	public function index($chat_id = null) {
		$data['messages'] = $this->Reports_Model->get_all_messages($chat_id);
		//$data['messages']['timestamp'] = strtotime($data['messages']['create_date']);
		$this->session->set_userdata('chatid', $chat_id);
		$this->template->load('reports_view2', $data);
	}

	// Export data in CSV format
	public function exportCSV(){
		// file name
		$filename = 'users_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; ");

		// get data
		$chatData = $this->Reports_Model->get_all_messages_array($_SESSION['chatid']);

		// file creation
		$file = fopen('php://output', 'a');

		$header = array("id","chat_id","user_id","context","mode","message_content","create_date","timestamp");
		fputcsv($file, $header);
		foreach ($chatData as $chat_data) {
			if ($chat_data['mode'] == 1) $mode = 'Yes';
			else $mode = 'No';
			$timestamp = strtotime($chat_data['create_date']);

			$chat_data2 = array(
				'id' => $chat_data['id'],
				'chat_id' => $chat_data['chat_id'],
				'user_id' => $chat_data['user_id'],
				'context' => $chat_data['context'],
				'mode' => $mode,
				'message_content' => $chat_data['message_content'],
				'create_date' => $chat_data['create_date'],
				'timestamp' => $timestamp
			);

			fputcsv($file,$chat_data2);
		}
		fclose($file);
		exit;
	}
}