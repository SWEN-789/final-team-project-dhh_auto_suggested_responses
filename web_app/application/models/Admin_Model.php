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

}
