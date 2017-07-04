<?php 
defined("BASEPATH") or exit('No direct script access allowed');

class Users extends CI_Controller{
	
	
	 public function __construct(){
		 parent::__construct();
		 
		 $this->load->helper(array('form','url'));
		 $this->load->library('session');
		 $this->load->helper('json_output_helper');
		 $this->load->model('restmodel','',true);
	 }
	 
	 public function index(){
		 
		
		 
	 }
	 
	 public function add(){
		
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_format(400,array('status'=>400,'message'=>'Bad request'));
		 }else{
			 
			     $params= json_decode(file_get_contents('php://input'), TRUE);
				 $name= $params['name'];
				 $username= $params['username'];
				 $password= $params['password'];
				 if($name!='' && $username !='' && $password!=''){
					$response= $this->restmodel->add_user($name,$username,$password);
					if($response['status']!='200'){
						json_format($response['status'],array('status'=>400,'message'=>'Bad request'));
					}else{
						json_format($response['status'],$response);	 
					}
				 }else{
					 json_format(400,array('status'=>400,'message'=>'Inputs error'));
				 }
				 
				 
		        
		 }
	 }
	 public function all(){
		 header('Access-Control-Allow-Origin: *');
		 header('Content-Type: application/json');
		 $method=$_SERVER['REQUEST_METHOD'];
		 if($method!='GET'){
			 json_format(400,array('status'=>400,'message'=>'Request not allowed'));
		 }else{
			 $response=$this->restmodel->get_users();
			 json_format($response['status'],$response['result']);
		 }
	 }
	 
	 public function details($id=null){
		 
		  $method = $_SERVER['REQUEST_METHOD'];
		  if($method !='GET' || $this->uri->segment(3) =='' || is_numeric($this->uri->segment(3)) == FALSE){
			  json_format(400,array('status'=>400,'message'=>'Bad request'));
		  }
		  else{
			 $response=$this->restmodel->get_user_byid($id);
			 if($response['status']!=204){
				json_format($response['status'],$response['result']);
			 }else{
				json_format(204,array('status'=>204,'message'=>'No users found')); 
			 }
		  }
		  
		 
	 }
	 
	 
}
?>