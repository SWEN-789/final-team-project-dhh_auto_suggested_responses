<?php
/**
 * Created by PhpStorm.
 * User: mfabbu
 *
 * Modified from: https://code.bitbebop.com/email-log-messages-library-codeigniter/
 *
 * Date: 11/27/2017
 * Time: 12:13 PM
 */

class MY_Log extends CI_Log {

	public function __construct() {
		parent::__construct();
	}

	function write_log($level = 'error', $msg, $php_error = FALSE)
	{
		$result = parent::write_log($level, $msg, $php_error);

//		if ($result == TRUE && strtoupper($level) == 'EDIT') {
//			$message = "The following change has been made in Inside2: \n\n";
//			$message .= $level.' - '.date($this->_date_fmt). ' --> '.$msg."\n";
//
//			$to = 'scbotrsdev@rit.edu';
//			$subject = 'IER';
//			$headers = 'From: Saunders Tech Support <noreply@rit.edu>' . "\r\n";
//			$headers .= 'Content-type: text/plain; charset=utf-8\r\n';
//
//			mail($to, $subject, $message, $headers);
//		}
		return $result;
	}
}