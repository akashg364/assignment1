<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Datatable_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
     //$order = array('DATE(createdon)' => 'desc'); // default order 
    private function get_datatables_query($table,$column_search,$order, $where)
    {

        $this->db->where($where);
        $this->db->from($table);
        $i = 0;
        
       if(!empty($_POST['search']['value'])) // if datatable send POST for search
         {
            foreach ($column_search as $item) // loop column 
            {
                
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($column_search) - 1 == $i) //last loop
                $this->db->group_end(); //close bracket
                $i++;
            }
            
        }
         
        if(!empty($_POST['order'])) // here order processing
        {
            $this->db->order_by('id', $_POST['order']['0']['dir']);
        } 
        else if(!empty($order))
        {
           
            //$this->db->order_by(key($orderDesc), $order[key($orderDesc)]);
            $this->db->order_by($order);
        }
    }
 
    public function get_datatables($table,$column_search,$order,$where)
    {
        $this->get_datatables_query($table,$column_search,$order,$where);
        if(!empty($_POST['length']) && $_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }
 
    public function count_filtered($table,$column_search,$order,$where)
    {
        $this->get_datatables_query($table,$column_search,$order,$where);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($table,$column_search,$order)
    {
        $this->db->from($table);
        return $this->db->count_all_results();
    }
  
}