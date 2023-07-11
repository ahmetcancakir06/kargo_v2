<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Server_api_commands_model extends App_Model
{

    public static $table_name = 'server_api_commands';
    public $fields = [];

    public function __construct()
    {
        parent::__construct();
        $ci = &get_instance();

        $this->load->library(SERVER_API_MODULE_NAME . '/' . 'module_settings');
        $remoteSql = $this->module_settings->getSetting('REMOTE_SQL_SERVER');
        $sqlInfo = $this->getSQLInfo(intval($remoteSql));
        $this->remoteDB = $this->connectDb($ci, $sqlInfo);
        $this->remoteDB->db_debug = true;
        $this->db->db_debug = false;
        $this->getColumnList();
    }
    function connectDb($ci, $credential, $prefix = '')
    {

        $config['hostname'] = $credential['sql_host'] . ':' . $credential['sql_port'];
        $config['username'] = $credential['sql_username'];
        $config['password'] = $credential['sql_password'];
        $config['database'] = $credential['sql_database'];
        $config['dbdriver'] = "mysqli";
        $config['dbprefix'] = $prefix;
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = "";
        $config['char_set'] = "utf8";
        $config['dbcollat'] = "utf8_turkish_ci";

        $DB2 = $ci->load->database($config, true);
        return $DB2;
    }
    public function getColumnList()
    {
        $sql = "DESC " . db_prefix() . self::$table_name;
        $dbqr = $this->db->query($sql)->result_array();
        if (!$dbqr) {
            return [];
        }
        $this->fields = [];
        foreach ($dbqr as $data) {
            $default = 'null';
            if (!(substr($data["Default"], 0, 17) == 'CURRENT_TIMESTAMP') and $data["Default"] != null and $data["Default"] != '') {
                $default = "'" . $data["Default"] . "'";
            }
            $this->fields[] = [$data["Field"], $default];
        }
        return $this->fields;
    }

    public function updateActive($id, $status)
    {
        $data = array(
            'active' => $status,
        );
        $this->db->where_in('id', $id);
        $this->db->update(self::$table_name, $data);
        return true;
    }
    public function updateInSystem($id, $value)
    {
        $data = array(
            'in_system' => $value,
        );
        $this->db->where_in('id', $id);
        $this->db->update(self::$table_name, $data);
        return true;
    }


    public function get_last_update()
    {
        $this->db->select('MAX(updated_at) AS last_udpate, count(*) as recCount');
        return $this->db->get(db_prefix() . self::$table_name)->row();
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
            return count($result) > 0 ? $result[0] : [];
        }
    }
    
    public function getByIds($ids = '', $select = '*')
    {
        if ($ids != '') {
            $this->db->where('id IN (' . $ids . ')');
        }
        $this->db->select($select);
        $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
        return $result;
    }

    public function getIdAndTitle($id = '')
    {
        $this->db->select('id, title');
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        }
        $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
        //array_unshift($result,["id"=>0,"title"=>_l('all_programs')]);
        return $result;
    }

    public function getAll()
    {
        $this->db->order_by('id', 'asc');
        $vlc_services = $this->db->get(db_prefix() . self::$table_name)->result_array();
        return array_values($vlc_services);
    }

    public function getSQLInfo($id)
    {
        $sql = "select * from " . db_prefix() . "sql_connections WHERE id = ?";
        $dbqr = $this->db->query($sql, [$id])->result_array();
        if (!$dbqr) {
            return [];
        }
        return count($dbqr) > 0 ? $dbqr[0] : [];
    }

    public function add($data)
    {
        $veriler=[
            'title'=>$data['title'],
            'commands'=>html_entity_decode($data['commands']),
        ];
        
        $this->db->insert(db_prefix() . self::$table_name, $veriler);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }

    public function toggleCompletedOff($ids)
    {
        $data = array(
            'is_complated' => 0,
            'error_count' => 0,
        );
        $this->db->where_in('prog_id', $ids);
        $this->db->update(db_prefix() . 'foxtv_episodes', $data);
        return true;
    }

    public function update($data, $id)
    {
        $veriler=[
            'title'=>$data['title'],
            'commands'=>html_entity_decode($data['commands']),
        ];

        $this->db->where('id', $id);

        $update = $this->db->update(db_prefix() . self::$table_name, $veriler);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function update_field($ids = '', $field, $value, $search = '')
    {
        if ($value == '') {
            $value = null;
        }
        if ($ids == null) {
            $ids = '';
        }
        if ($ids == '') {
            if ($search != "") {
                $sql = "update " . db_prefix() . self::$table_name . " set " . $field . "=REPLACE(" . $field . ", ?, ?);";
                $this->db->query($sql, [$search, $value]);
            } else {
                $sql = "update " . db_prefix() . self::$table_name . " set " . $field . "=?;";
                $this->db->query($sql, [$value]);
            }
            return $this->db->affected_rows();
        } else {
            $ids_array = explode(",", $ids);
            if ($search != "") {
                $sql = "update " . db_prefix() . self::$table_name . " set " . $field . "=REPLACE(" . $field . ", ?, ?) WHERE id IN ?;";
                $this->db->query($sql, [$search, $value, $ids_array]);
            } else {
                $sql = "update " . db_prefix() . self::$table_name . " set " . $field . "=? WHERE id IN ?;";
                $this->db->query($sql, [$value, $ids_array]);
            }
            return $this->db->affected_rows();
        }
        return false;
    }

    public function delete_multiple($ids)
    {
        $ids_array = explode(",", $ids);
        foreach ($ids_array as $program) {
            if ($program != null && $program != '') {
                $this->db->trans_start();

                $sql = "delete from " . db_prefix() . self::$table_name . " WHERE id = ?";
                $this->db->query($sql, [$program]);

                $this->db->trans_complete();
            }
        }
        return true;
    }

    public function undelete_multiple($ids)
    {
        $ids_array = explode(",", $ids);
        $sql = "update " . db_prefix() . self::$table_name . " set is_deleted=0 WHERE id IN ?";
        $this->db->query($sql, [$ids_array]);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items [IDs:' . $ids . '] marked as undeleted');
            return true;
        }
        return false;
    }
}
