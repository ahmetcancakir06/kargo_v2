<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Server_api_commands_per_model extends App_Model
{

    public static $table_name = 'server_api_commands_per';
    public $fields = [];

    public function __construct()
    {
        parent::__construct();
        $this->db->db_debug = true;
        $this->getColumnList();
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
    public function insertList($title, $sure, $link, $channel_name, $tv_series, $filename)
    {
        $data = array(
            'title' => $title,
            'duration' => $sure,
            'link' => $link,
            'channel_name' => $channel_name,
            'tv_series' => $tv_series,
            'file_name' => $filename,
        );
        $this->db->insert(db_prefix() . self::$table_name, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        } else {
            return "0";
        }
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
    public function getLink($link = '')
    {
        $this->db->where('link', $link);
        $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
        return count($result) > 0 ? $result[0] : [];
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
    public function checkWasAdd($video_link)
    {

        $this->db->where('link', $video_link);
        $result = $this->db->get(db_prefix() . self::$table_name)->result_array();
        if (empty($result)) {
            return "0";
        } else {
            return "1";
        }
    }
    public function get_all()
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
    public function requestApiKey($apikey, $ip)
    {
        $firstsql = "SELECT * FROM " . db_prefix() . self::$table_name . " WHERE api_key = ? AND ip_address = ? AND allowed = ?;";
        $firstrequest = $this->db->query($firstsql, [$apikey, "*", "1"])->result_array();
        if (empty($firstrequest)) {
            $sql = "SELECT * FROM " . db_prefix() . self::$table_name . " WHERE api_key = ? AND (ip_address LIKE ? OR ip_address LIKE ? OR ip_address LIKE ?) AND allowed = ?";
            $request = $this->db->query($sql, [$apikey, "%," . $ip . ",%", $ip . ",%", "%," . $ip, "1"])->result_array();

            if (empty($request)) {
                return "";
            } else {
                return count($request) > 0 ? $request[0] : [];
            }
        } else {
            return count($firstrequest) > 0 ? $firstrequest[0] : [];
        }
    }
    public function requestPermission($id, $staff, $module_id)
    {
        //SELECT * FROM `tblserver_api_commands_per` WHERE id = "1" AND (staff_user_id = "1" OR staff_user_id LIKE "%,1,%" OR staff_user_id LIKE "1,%" OR staff_user_id LIKE "%,1") AND module_name = "1" AND allowed = "1";
        $sql = "SELECT * FROM " . db_prefix() . self::$table_name . " WHERE id = ? AND (staff_user_id = ? OR staff_user_id LIKE ? OR staff_user_id LIKE ? OR staff_user_id LIKE ?) AND (module_name = ? OR module_name LIKE ? OR module_name LIKE ? OR module_name LIKE ?) AND allowed = ?;";
        $request = $this->db->query($sql, [$id,$staff,"%,".$staff.",%","%,".$staff,$staff.",%",$module_id,"%,".$module_id.",%","%,".$module_id,$module_id.",%", "1"])->result_array();
        if (empty($request)) {
            return "";
        } else {
            return count($request) > 0 ? $request[0] : [];
        }
    }
    public function add($data, $api)
    {
        foreach ($data as $key => &$value) {
            if ($value == '') {
                $value = null;
            }
        }
        $data['api_key'] = $api;
        $this->db->insert(db_prefix() . self::$table_name, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New foxtv_programs Added [ID:' . $insert_id . ']');
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
