<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct()
        {
        header('Access-Control-Allow-Origin: *');
                header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
                $method = $_SERVER['REQUEST_METHOD'];
                if($method == "OPTIONS") {
                die();
                }
        }

	public function index()
	{
		echo json_encode(array('foo'=>456465,'bar'=>8879878));
	}
		public function logout()
	{
		$this->load->database();
		$req = json_decode($this->input->raw_input_stream);	
		if(isset($req->result))
		{
			$qr =$this->db->delete('log', array('fingerprint' =>$req->result));
			// $this->db->query($qr);
			$arr= array();
			if($qr){
				$arr['url']= 'login';		
				$arr['success']= true;
			}
			echo json_encode($arr);
		}
	}
	public function login()
	{
		$this->load->model('log');
		$this->log->query("select *from log")->result_array();
	// 	$this->load->database();
	// 	$this->load->library('user_agent');
	// 	$req = json_decode($this->input->raw_input_stream);	
	// 	$arr = array();
	// 	if(isset($req->result) && isset($req->user) && isset($req->pass))
	// 	$user = $this->db->query("SELECT username FROM `user` WHERE `username` = '".$req->user."' && `pass` = '".md5($req->pass)."'")->result_array();
	// 	if(count($user) > 0){
	// 		$logArr =array(
	// 			'user'=>$user[0]['username'],
	// 			'agent'=>$this->agent->agent_string(),
	// 			'endDate'=>date('Y-m-d H:i:s', time() + (60 * 60 * 60)),
	// 			'fingerprint'=>$req->result,
	// 			'ip'=>$this->input->ip_address()
	// 		);
	// 	$this->db->insert('log', $logArr);
	// 	$arr['user'] = $user[0];	
	// 	$arr['token']=$this->generateRandomString();
	// 	$arr['url']= '/main';
	// 	$arr['success']=true;
	// }
	// else{
	// 	$arr['success']=false;
	// }
	// 	echo json_encode($arr);
	}
	public function fingerprintcheck()
	{
		$req = json_decode($this->input->raw_input_stream);	
		$arr = array();
		$this->load->database();
		$log =  $this->db->query("SELECT * FROM `log` WHERE `fingerprint` = '".$req->id."' AND `endDate` >  '".date('Y-m-d H:i:s', time())."'")
		->result_array();
		if(count($log) != 0)
		{
			$arr['success']=true;
			$arr['url']='/main';
		}else
		{
			$arr['success']=false;
			$arr['url']='/login';
			$arr['qr']= "SELECT * FROM `log` WHERE `fingerprint` = '".$req->id."' AND `endDate` >  '".date('Y-m-d H:i:s', time())."'";
		}
		echo json_encode($arr);	
		//echo "SELECT * FROM `log` WHERE `fingerprint` = '".$req->id."' AND `endDate` >  '".date('Y-m-d H:i:s', time())."'";
	}
	//
	public function generateRandomString($length = 64) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0
     ,$charactersLength - 1)];
    }
    return $randomString;
	}
}
