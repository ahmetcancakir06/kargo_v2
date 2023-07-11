<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Server_api_system_care_servers_model extends App_Model
{

    public static $table_name = 'system_care_servers';
    public function __construct()
    {
        parent::__construct();
        $this->db->db_debug = false;
    }

    public function getServers(){
        $this->db->order_by('id','asc');
        $result=$this->db->get(self::$table_name)->result_array();
        return array_values($result);
    }
    public function getServertitle($server_id)
    {
        
        $this->db->where('id',$server_id);
        $result=$this->db->get(db_prefix().self::$table_name)->result_array();
        return count($result) > 0 ? $result[0] : [];
    }
    
}