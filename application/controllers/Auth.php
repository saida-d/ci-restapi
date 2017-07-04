<?php 
defined("BASEPATH") or exit('No direct script access allowed');

class Auth extends CI_Controller{
	
	
	 public function __construct(){
		 parent::__construct();
		 
		 $this->load->helper(array('form','url'));
		 $this->load->library('session');
		 $this->load->helper('json_output_helper');
		 $this->load->model('restmodel','',true);
	 }
	 
	 public function index(){
		 
		 echo "APIs";
	 }
	 
	 public function login(){
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_format(400,array('status'=>'400','message'=>'Bad request'));
		 }else{
			 
			 /*$is_authorized=$this->restmodel->check_auth_client();
			 if($is_authorized){
				  
				 $params= json_decode(file_get_contents('php://input'), TRUE);
				 $username= $params['username'];
				 $password= $params['password'];
				 
				 $response= $this->restmodel->login($username,$password);
				 
				 json_format($response['status'],$response);
				 
			 }*/
			     $params= json_decode(file_get_contents('php://input'), TRUE);
				 $username= $params['username'];
				 $password= $params['password'];
				 
				 $response= $this->restmodel->login($username,$password);
				
				 json_format($response['status'],$response);
				 
		        
		 }
	 }
	 
	 public function logout(){
		 
		 $method=$_SERVER['REQUEST_METHOD'];
		 if($method!='POST'){
			 json_format(400,array('status'=>400,'message'=>'Bad request'));
		 }else{
			 $is_authorized=$this->restmodel->check_auth_client();
			 if($is_authorized){
				$response=$this->restmodel->logout(); 
				json_format($response['status'],$response);
			 }
		 }
	 }
	 
	 
}
?>