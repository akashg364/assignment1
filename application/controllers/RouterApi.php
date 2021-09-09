<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class RouterApi extends REST_Controller
{
	public function __construct() {
        parent::__construct();
        // Load the user model
        $this->load->model('Common_mdl');
    }

    public function index_post(){
        $postData = json_decode(trim(file_get_contents('php://input')), true);
        if(!empty($postData)){
            $existRec = $this->Common_mdl->select_single_row('tblrouter', array('hostname' => ip2long($postData['loopback']), 'hostname' => $postData['hostname']));
            if(!$existRec){
                $data = array(
                    "sapid"     =>  $postData['sapid'],
                    "hostname"  =>  $postData['hostname'],
                    "loopback"  =>  ip2long($postData['loopback']),
                    "mac_address" =>    $postData['mac_address'],
                    "createdon" => date('Y-m-d H:i:s')
                );
                $res = $this->Common_mdl->insert('tblrouter',$data);
                if($res){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Success: Record has been added successfully!',
                        'data' => array()
                    ], REST_Controller::HTTP_OK);    
                }else{
                    $this->response([
                        'status' => TRUE,
                        'message' => 'something went wrong, please try again!',
                        'data' => array()
                    ], REST_Controller::HTTP_OK);
                }
            }else{
                $this->response([
                    'status' => TRUE,
                    'message' => 'Error: Hostname & loopback should be unique!',
                    'data' => array()
                ], REST_Controller::HTTP_OK);
            }
            
        }else{
            $this->response([
                'status' => TRUE,
                'message' => 'Invalid input data!',
                'data' => array()
            ], REST_Controller::HTTP_OK);
        }
    }
    public function index_put(){
        $postData = json_decode(trim(file_get_contents('php://input')), true);
        if(!empty($postData['loopback'])){
            $data = array(
                "sapid"     =>  $postData['sapid'],
                "hostname"  =>  $postData['hostname'],
                //"loopback"  =>  ip2long($postData['loopback']),
                "mac_address" =>    $postData['mac_address'],
                "updatedon" => date('Y-m-d H:i:s')
            );
            $res = $this->Common_mdl->update('tblrouter',$data, array('loopback' => ip2long($postData['loopback'])));
            
            if($res > 0){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Success: Record has been updated successfully!',
                    'data' => array()
                ], REST_Controller::HTTP_OK);    
            }else{
                $this->response([
                    'status' => TRUE,
                    'message' => 'No record found for update!',
                    'data' => array()
                ], REST_Controller::HTTP_OK);
            }  
        }else{
            $this->response([
                'status' => TRUE,
                'message' => 'Invalid input data!',
                'data' => array()
            ], REST_Controller::HTTP_OK);
        }
    }

    public function index_get(){
        $sapId = $this->input->get('sapId');
        $ipStart = $this->input->get('ipStart');
        $ipEnd = $this->input->get('ipEnd');
        $condtion = "status=1";
        if(!empty($sapId)){
            $condtion .= " AND sapid='".$sapId."'";
        }else if(!empty($ipStart) && !empty($ipEnd)){
            $condtion .= " AND loopback between INET_ATON('".$ipStart."') AND INET_ATON('".$ipEnd."')";
        }
        $res = $this->db->query("select id, sapid, hostname, INET_NTOA(loopback) as loopback, mac_address, createdon from tblrouter where ".$condtion."")->result_array();
        if($res){
            $this->response([
                'status' => TRUE,
                'message' => 'Router Details found!',
                'data' => $res
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => TRUE,
                'message' => 'No records found!',
                'data' => array()
            ], REST_Controller::HTTP_OK);
        }
    }
    public function index_delete(){
        $loopback = $this->input->get('loopback');
        if(!empty($loopback)){
            $res = $this->Common_mdl->delete('tblrouter', array('loopback' => ip2long($loopback)));
            if($res){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Record has been deleted successfully!',
                    'data' => array()
                ], REST_Controller::HTTP_OK);    
            }else{
                $this->response([
                    'status' => TRUE,
                    'message' => 'no matching record found for delete!',
                    'data' => array()
                ], REST_Controller::HTTP_OK);
            }
            
        }else{
            $this->response([
                'status' => TRUE,
                'message' => 'Invalid input ip address!',
                'data' => array()
            ], REST_Controller::HTTP_OK);
        }
        
    }

}