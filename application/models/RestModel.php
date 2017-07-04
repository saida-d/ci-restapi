<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class RestModel extends CI_Model{
	
	 var $client_service='clientapi';
	 var $auth_key= 'restapikey';
	 
	 public function check_auth_client(){
		 
		 $client_service = $this->input->get_request_header('Client-Service',true);  
		 $auth_key       = $this->input->get_request_header('Auth-Key',true);
		 
		 if($client_service==$this->client_service && $auth_key== $this->auth_key){
			 return true;
		 }else{
			 return json_format(401,array('status'=>401,'message'=>'Unauthorized headers'));
		 }
	 }
	 
	 public function add_user($name,$username,$password){
		 
		 //check user 
		 $this->db->select('*');
		 $this->db->from('users');
		 $this->db->where('username',$username);
		 $query=$this->db->get();
		 
		 if($query->num_rows()>0){
			 return array('status'=>204,'message'=>'Sorry username already taken.');
			
		 }else{
		
			$data=array("username"=>$username,
					    "password"=>@crypt($password),
					    "name"=>$name);
			$this->db->trans_start();
            $this->db->insert("users",$data);
			if($this->db->trans_status()===false){
				$this->db->trans_rollback();
				return array('status'=>500,'message'=>'Internal Error/Something went wrong');
			}else{
				$this->db->trans_commit();
				return array('status'=>200,'message'=>'User added successfully.');
			}
			
		 }
		 
	 }
	 public function get_users(){
		 $this->db->select('*');
		 $this->db->from('users');
		 $query=$this->db->get();
		 
		 if($query->num_rows()>0){
			 $result= $query->result();
			 return array('status'=>200,'result'=>$result);
			 
		 }else{
			 return array('status'=>204,'message'=>'User list empty!');
		 }
		 
	 }
	 public function get_user_byid($id){
		 $this->db->select('*');
		 $this->db->from('users');
		 $this->db->where('id',$id);
		 $query=$this->db->get();
		 
		 if($query->num_rows()>0){
			 $result= $query->result();
			 return array('status'=>200,'result'=>$result);
			 
		 }else{
			 return array('status'=>204,'message'=>'User list empty!');
		 }
	 }
	 public function login($username,$password){
		 
		 $this->db->select('*');
		 $this->db->from('users');
		 $this->db->where('username',$username);
		 //$this->db->where('password',$password);
		 
		 $query=$this->db->get();
		 
		 if($query->num_rows()>0){
			 $result=$query->result();
			  
			 $id    =$result[0]->id;
			 $hashed_password  =$result[0]->password;
			 
			 if(hash_equals($hashed_password,crypt($password,$hashed_password))){
				 $last_login = date('Y-m-d H:i:s');
				 $token= md5(substr( md5(rand(100,1000)), 0, 7));
						 
				 $expired_date= date('Y-m-d H:i:s',strtotime("+12 hours"));
				 
				  
				 // check user 
				 $this->db->select('*');
				 $this->db->from('users_authentication');
				 $this->db->where('users_id',$id);
				 $token_check=$this->db->get(); 
				 
				 if($token_check->num_rows()==0){
					 $this->db->trans_start();
					 // update last login
					 $dataset1=array('last_login'=>$last_login);
					 $this->db->where('id',$id);
					 $this->db->update('users',$dataset1);
					 
					 // insert hash token 
					 $dataset2=array('users_id'=>$id,'token'=>$token,'expired_at'=>$expired_date);
					 $this->db->insert('users_authentication',$dataset2);
					 
					 if($this->db->trans_status()===false){
						 $this->db->trans_rollback();
						 return array('status'=>500,'message'=>'Internal Error');
					 }else{
						 $this->db->trans_commit();
						 return array('status'=>200,'message'=>'Login success','id'=>$id,'token'=>$token);
					 }
				 }else{
					 $this->db->trans_start();
					 // update last login
					 $dataset1=array('last_login'=>$last_login);
					 $this->db->where('id',$id);
					 $this->db->update('users',$dataset1);
					 
					 // update hash token 
					 $dataset2=array('token'=>$token,'expired_at'=>$expired_date);
					 $this->db->where('users_id',$id);
					 $this->db->update('users_authentication',$dataset2);
					 
					 if($this->db->trans_status()===false){
						 $this->db->trans_rollback();
						 return array('status'=>500,'message'=>'Internal Error');
					 }else{
						 $this->db->trans_commit();
						 return array('status'=>200,'message'=>'Login success','id'=>$id,'token'=>$token);
					 }
				 }
				 
			 }else{
				 return array('status'=>204,'message'=>'Wrong password');
			 }
			 
			 
		 }else{
			 return array('status'=>204,'message'=>'Username not found');
		 }
		 
	 }
	 public function logout(){
		 
		  $user_id=$this->input->get_request_header('User-ID',true);
		  $token=$this->input->get_request_header('Authorization',true);
		  
		  $this->db->trans_start();
		  $this->db->where('users_id',$user_id);
		  $this->db->where('token',$token);
		  $this->db->delete('users_authentication');
		  $transaction=$this->db->trans_status();
		  if($transaction===false){
			  return array('status'=>500,'message'=>'Internal Error');
		  }else{
			  $this->db->trans_commit();
			  return array('status'=>200,'message'=>'Logout success');
		  }
		  
	 }
}

?>