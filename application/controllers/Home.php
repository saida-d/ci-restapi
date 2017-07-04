<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct(){
		Parent::__construct();
		$this->load->helper(array('url','form'));
	}	
	
	public function index()
	{
		$this->load->view('login');
	}
	public function login_byapi(){
		
		 
		$username= $this->input->post('username'); 
		$password= $this->input->post('password'); 
		$postUrl="http://localhost/codeig_restapi/index.php/auth/login";

		$params=array("username"=> $username, 
		"password"=> $password);

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($params));


		$result = curl_exec($ch);
		print $result;

		curl_close($ch);

	}
}
