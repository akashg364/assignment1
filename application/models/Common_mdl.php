<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_mdl extends CI_Model {

	public function __construct()
	{
	  parent::__construct();
	}

	public function select($tbl , $where)
	{
		if(!empty($where)){
			$this->db->order_by('id','DESC');
			$this->db->where($where);
			$result = $this->db->get($tbl)->result_array();
			return $result;
		}
		else {
			$result = $this->db->get($tbl)->result_array();
			return $result;
		}
	}
	public function select_single_row($tbl , $where)
	{
		if(!empty($where)){
			$this->db->where($where);
			$result = $this->db->get($tbl)->row_array();
			return $result;
		}
	}
	public function insert($table, $data){
		$this->db->insert($table, $data); 
		return $this->db->insert_id();
	}

	public function update($table, $data, $where){
		$status = $this->db->update($table, $data, $where);
		if ($this->db->affected_rows($status) > 0)
		  return 1;
		else
		  return 0;
	}
	public function check($where, $table){
		$this->db->where($where);
		$this->db->limit(1);
		$result = $this->db->get($table)->result();
		return $result;
	}
	public function delete($table , $where){
		$status = $this->db->delete($table, $where);
		if ($this->db->affected_rows($status) > 0)
		  return 1;
		else
		  return 0;
	}

}	



