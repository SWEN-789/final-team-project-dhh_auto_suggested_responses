<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class that will handle how views are called and take care of page templating
 *
 * @author Saunders Technical Support Team
 */

class Template
{
	/*
	|--------------------------------------------------------------------------
	| Variables
	|--------------------------------------------------------------------------
	*/
	/**
	 * Instance of CodeIgniter
	 *
	 * @var CI_Controller
	 */
	var $ci;

	/**
	 * Main Constructor
	 */
	function __construct()
	{
		$this->ci =& get_instance();
	}


	/*
	|--------------------------------------------------------------------------
	| Methods
	|--------------------------------------------------------------------------
	*/
	/**
	 * Load view and template views at the same time. First check if the view exists, then verify data
	 *
	 * @param $body_view
	 * @param null $data
	 */
	public function load($body_view, $data = null)
	{
		if (file_exists(APPPATH . "views/" . $body_view . ".php"))
		{
			$body = $this->ci->load->view($body_view,$data,TRUE);
		}
		else
		{
			show_404($body_view, FALSE);//temp false to avoid logging while testing, remove FALSE to switch to TRUE later
		}

		if ( is_null($data) )
		{
			$data = array('body' => $body);
		}
		else if ( is_array($data) )
		{
			$data['body'] = $body;
		}
		else if ( is_object($data) )
		{
			$data->body = $body;
		}
		$this->ci->load->view('templates/header');
		$this->ci->load->view('templates/body',$data);
		$this->ci->load->view('templates/footer');
	}
}