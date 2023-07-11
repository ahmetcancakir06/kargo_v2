<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Server_api_staff_model extends App_Model
{

    public static $table_name = 'staff';
    public $fields = [];
    public function __construct()
    {
        parent::__construct();
        $this->db->db_debug = true;
    }
    public function getAll()
    {
        $sql = "select * from " . db_prefix() . self::$table_name;
        $dbqr = $this->db->query($sql)->result_array();
        if (!$dbqr) {
            return [];
        }
        return count($dbqr) > 0 ? $dbqr : [];
    }
    public function getWho($who)
    {
        if (is_numeric($who)) {
            $this->db->where('staffid', $who);
            $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
            return count($result) > 0 ? $result[0] : [];
        }
    }
}
