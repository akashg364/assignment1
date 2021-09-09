<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assignment extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Datatable_model');
		$this->load->model('Common_mdl');
	}
	public function index()
	{
		$this->load->view('site_details');
	}

	public function dataparser() {

		//add custom filter here
        $whr_cond = "isDeleted != -1"; 
        if(!empty($_POST['lead_status'])) { if($_POST['lead_status'] == 'pending') { $lead_stat = 0; } else { $lead_stat = $_POST['lead_status']; }  $whr_cond .= ' and leadstatus = '.$lead_stat; } else { $whr_cond .= ""; }
        //if($_POST['lead_status']==0) { $whr_cond .= ' and leadstatus = '.$_POST['lead_status']; } else { $whr_cond .= ""; }
        if(!empty($_POST['user'])) { $whr_cond .= ' and updatedby='.$_POST['user']; } else { $whr_cond .= ""; }
        if(!empty($_POST['fromdate']) && !empty($_POST['todate'])) { $whr_cond .= ' and (DATE(updatedon) between "'.$_POST['fromdate'] .'" and "' .$_POST['todate'].'")'; } else { $whr_cond .= '';}
         
        $table 			= 'tblrouter';
	    $column_order	= array('id');
	    $column_search	= array();
	    $order 			= 'DATE(createdon) desc';
        $retArr  		= $this->Datatable_model->get_datatables($table,$column_search,$order,$whr_cond);
           
       	$i = 1;
       	
		foreach($retArr as $key => $value) { 
			$editBtn  = '<a href="" class="editingTRbutton" data-toggle="modal" data-target="#myModal">Edit</a>';
			//$editLink = '<a href="javascrip:void(0)" class="editingTRbutton">Edit</a>';
			$deleteLink = ($value['isDeleted'] != 1)?'<a href="'.base_url().'assignment/update/'.$value['id']."/".$value['isDeleted'].'">Delete</a>':'<a href="'.base_url().'assignment/update/'.$value['id']."/".$value['isDeleted'].'">Recover</a>';
	     	$sub_array = array();  
	     	$sub_array[] = $value['id'];  
	        $sub_array[] = $value['sapid'];
	        $sub_array[] = $value['hostname'];
	        $sub_array[] = long2ip($value['loopback']);
	        $sub_array[] = $value['mac_address'];
	        $sub_array[] = date('d/m/Y', strtotime($value['createdon']));  
	        $sub_array[] = ($value['isDeleted']===0)?"True":"False";
	        $sub_array[] = $editBtn." | ".$deleteLink;  
	        $data[] = $sub_array;  

			$i++; 
		}
		if(!empty($_POST["draw"])) { $draw = $_POST["draw"]; } else { $draw = 0;}
		if(!empty($data )) {
			$output = array(  
	            "draw"            => intval($draw),  
	            "recordsTotal" 	  => $this->Datatable_model->count_all($table,$column_search,$order , $whr_cond),
                "recordsFiltered" => $this->Datatable_model->count_filtered($table,$column_search,$order , $whr_cond), 
	            "data"            => $data  
	       );
		} else {
			$output = array(  
	            "draw"            => intval($draw),  
	            "recordsTotal" 	  =>  $this->Datatable_model->count_all($table,$column_search,$order , $whr_cond),
                "recordsFiltered" => $this->Datatable_model->count_filtered($table,$column_search,$order , $whr_cond), 
	            "data"            => ""
	       );
		}
		  
	  echo json_encode($output);  
	}
	public function add(){
		$this->form_validation->set_error_delimiters("<div class='error' style='color:red;'>","</div>");
		$this->form_validation->set_rules('sap_id', 'Sap Id', 'required|trim');
		$this->form_validation->set_rules('hostname', 'Hostname', 'required|trim');
		$this->form_validation->set_rules('loopback', 'Loopback', 'required|trim|valid_ip');
		$this->form_validation->set_rules('mac_address', 'Mac Address', 'required|trim');

		if ($this->form_validation->run() == FALSE)
		{
			$response['status'] = FALSE;
			$response['errors'] = array(
				'sap_id' => form_error('sap_id'),
				'hostname' => form_error('hostname'),
				'loopback' => form_error('loopback'),
				'mac_address' => form_error('mac_address'),
			);
			header('Content-type: application/json');
			echo json_encode($response);
		}
		else
		{
			$post_data = array(
				'sapid' 	=> $this->input->post('sap_id', TRUE),
				'hostname' 	=> $this->input->post('hostname', TRUE),
				'loopback' 	=> ip2long($this->input->post('loopback')),
				'mac_address' => $this->input->post('mac_address', TRUE),
				'status' 	=> 1,
				'createdon' => date('Y-m-d H:i:s'),
			);
			$res = $this->Common_mdl->insert($table="tblrouter", $post_data);

			if($res > 0)
			{
				$response['success'] = "new entry has been created successfully";
			}
			else
			{
				$response['success'] = "Something went wrong, please try again";
			}
			$response['status'] = TRUE;
			header('Content-type: application/json');
			echo json_encode($response);
		
		}
	}
	public function edit(){
		if(empty($_POST['rec_id'])){
			$this->session->set_flashdata('error_message', 'Invalid record id, please try again!');
			redirect('Invalid record id, please try again!');
		}
		$this->form_validation->set_error_delimiters("<div class='error' style='color:red;'>","</div>");
		$this->form_validation->set_rules('sap_id', 'Sap Id', 'required|trim');
		$this->form_validation->set_rules('hostname', 'Hostname', 'required|trim');
		$this->form_validation->set_rules('loopback', 'Loopback', 'required|trim|valid_ip');
		$this->form_validation->set_rules('mac_address', 'Mac Address', 'required|trim');

		if ($this->form_validation->run() == FALSE)
		{
			$response['status'] = FALSE;
			$response['errors'] = array(
				'sap_id' => form_error('sap_id'),
				'hostname' => form_error('hostname'),
				'loopback' => form_error('loopback'),
				'mac_address' => form_error('mac_address'),
			);
			header('Content-type: application/json');
			echo json_encode($response);
		}
		else
		{
			$post_data = array(
				'sapid' 	=> $this->input->post('sap_id', TRUE),
				'hostname' 	=> $this->input->post('hostname', TRUE),
				'loopback' 	=> ip2long($this->input->post('loopback')),
				'mac_address' => $this->input->post('mac_address', TRUE),
				'status' 	=> 1,
				'updatedon' => date('Y-m-d H:i:s'),
			);
			$res = $this->Common_mdl->update($table="tblrouter", $post_data, array('id' => $_POST['rec_id']));

			if($res > 0)
			{
				$response['success'] = "new entry has been created successfully";
			}
			else
			{
				$response['success'] = "Something went wrong, please try again";
			}
			$response['status'] = TRUE;
			header('Content-type: application/json');
			echo json_encode($response);
		
		}
	}
	public function update(){
		if($this->uri->segment(4)==0) {  $status = 1; } else { $status = 0; } 
		$post_data = array('isDeleted' =>  $status);
		$where = array('id' => $this->uri->segment(3));
		$updated_records = $this->Common_mdl->update($table="tblrouter", $post_data, $where);
		$this->session->set_flashdata('success_message', 'Route details has been updated successfully!.');
		redirect('assignment');
	}

	public function create_n_unique_records($count){
		$existRec = $this->db->query('select loopback from tblrouter order by id desc')->row_array();
		$start_ip = ip2long('127.0.0.1');
		if(!empty($existRec['loopback'])){
			$start_ip = ip2long(long2ip($existRec['loopback']));
		}
		
		//$end_ip = ip2long('192.168.1.1');
		for($i=1;$i<=$count;$i++){
			$post_data = array(
				'sapid' 	=> 'sampleSapId_'.$i,
				'hostname' 	=> 'hostname'.$i,
				'loopback' 	=> $start_ip,
				'mac_address' => 'mac_address'.$i,
				'status' 	=> 1,
				'createdon' => date('Y-m-d H:i:s'),
			);
			$res = $this->Common_mdl->insert($table="tblrouter", $post_data);
			long2ip($start_ip);
			$start_ip++;
		}
		
	}

	public function drawGeoGraphicFigure(){

		$img = imagecreatetruecolor(300, 300);
		// allocate some colors
		$color = imagecolorallocate($img, 255, 255, 255);
		//draw circle
		imageellipse($img, 150, 150, 140, 140, $color);
		//draw rantangle
		imagerectangle($img, 120, 120, 180, 180, $color);

		imagepolygon($img, array(
			150, 50, // Point 1 (x, y)
		    55, 119, // Point 2 (x, y)
		    91, 231, // Point 3 (x, y)
		    209, 231, // Point 4 (x, y)
		    245, 119  // Point 5 (x, y)
		),5, $color);
		// output image in the browser
		header("Content-type: image/png");
		imagepng($img);
		// free memory
		imagedestroy($img);

	}

}
