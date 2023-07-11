<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Server_api_module_settings_model extends App_Model
{

    public static $table_name = 'server_api_module_settings';
    public function __construct()
    {
        parent::__construct();
        $this->db->db_debug = false;
    }

    public function get_last_update()
    {
        $this->db->select('MAX(updated_at) AS last_udpate, count(*) as recCount');
        return $this->db->get(db_prefix() . self::$table_name)->row();
    }

    public function get($id = '')
    {
        if ($id != '') {
            $this->db->where('id', $id);
            $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
            return count($result) > 0 ? $result[0] : [];
        } else {
            $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
            return $result;
        }
    }
    public function checkTableExists()
    {

        $query = $this->db->query("SHOW TABLES LIKE '" . self::$table_name . "';");
        if ($this->db->table_exists(self::$table_name)) {
            return "1";
            exit;
        } else {
            $errors['error'] = " Youtube Modül Ayarlar tablosu yok.";
            return $errors;
            exit;
        }
    }
    public function getIp($ip)
    {
        $this->db->where('ip', $ip);
        $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
        return count($result) > 0 ? $result[0] : [];
    }
    public function getFolder()
    {

        $this->db->order_by('id', 'asc');
        $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
        return count($result) > 0 ? $result[0] : [];
    }
    public function getModules()
    {
        $this->db->select('perfex_module_name AS id, perfex_module_name AS title');
        $this->db->group_by('perfex_module_name');
        $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
        for ($i = 0; $i < count($result); $i++) {
            $result[$i]["title"] = _l($result[$i]["title"]);
        }
        return $result;
    }


    public function get_all()
    {
        $this->db->order_by('id', 'asc');
        $vlc_services = $this->db->get(db_prefix() . self::$table_name)->result_array();
        return array_values($vlc_services);
    }

    public function add($data)
    {
        foreach ($data as $key => &$value) {
            if ($value == '') {
                $value = null;
            }
        }
        $this->db->insert(db_prefix() . self::$table_name, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New System_care_logs Added [ID:' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function update($data, $id)
    {
        foreach ($data as $key => &$value) {
            if ($value == '') {
                $value = null;
            }
        }
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . self::$table_name, $data);
        return true;
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
        $sql = "delete from " . db_prefix() . self::$table_name . " WHERE id IN ?";
        $this->db->query($sql, [$ids_array]);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items [IDs:' . $ids . '] deleted');
            return true;
        }
        return false;
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
    public function apiGet()
    {
        $this->db->select('api_key');
        $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
        return $result[0];
    }
    public function blacklistCheck()
    {
        $this->db->select('blacklist');
        $result = $this->db->get(db_prefix() . self::$table_name)->result_array();

        return $result[0]['blacklist'];
    }
}
