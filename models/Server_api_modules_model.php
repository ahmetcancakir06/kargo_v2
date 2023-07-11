<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Server_api_modules_model extends App_Model
{

    public static $table_name = 'modules';
    public $fields = [];
    public function __construct()
    {
        parent::__construct();
        $this->db->db_debug = false;
    }
    
    public function getAllModules(){
        $this->db->where('active',"1");
        $result=$this->db->get(db_prefix().self::$table_name)->result_array();
        return count($result) > 0 ? $result : [];
    }
    public function getModules($id){
        if(is_numeric($id)){
            $this->db->where('id',$id);
            $result=$this->db->get(db_prefix().self::$table_name)->result_array();
            return count($result) > 0 ? $result[0] : [];
        }
    }
    

}
