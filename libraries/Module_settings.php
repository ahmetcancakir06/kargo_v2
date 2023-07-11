<?php

/** @package  Module settings */
class Module_settings
{
    private $ci;
    private $MODULE_NAME = KARGO_MODULE_NAME;
    private $insertSQL = null;
    private $updateSQL = null;
    private $getSQL = null;
    private $getAllSQL = null;


    protected $module_settings_array = [
        "REMOTE_SQL_SERVER" => ["DEFAULT_VALUE"=>"1","DESCRIPTION" => "Uzak sunucu sql girilmesi gerek çünkü veriler uzaktaki sunucudan çekilmekte. Sistem bakım dosyasından sql girmeniz gerek."],
    ];

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->insertSQL = "
            INSERT INTO ".db_prefix()."server_api_module_settings (
                perfex_module_name,
                settings_key,
                settings_value,
                description
            )
            VALUES
                (
                ?,
                ?,
                ?,
                ?
                );
        ";
        $this->getSQL = "select SQL_NO_CACHE * from ".db_prefix()."server_api_module_settings where perfex_module_name = ? and settings_key = ?;";
        $this->getAllSQL = "select SQL_NO_CACHE * from ".db_prefix()."server_api_module_settings where perfex_module_name = ?;";
        $this->updateSQL = "udpate from ".db_prefix()."server_api_module_settings set settings_value = ? where perfex_module_name = ? and settings_key = ?;";
 //       $this->ci->load->model('server_api/server_api_scripts_model');
    }

    public function installSettings(){
        foreach ($this->getDefaultsArray() as $key => $values) {
            $data = $this->ci->db->query($this->getSQL,[$this->MODULE_NAME, $key]);
            if (!$data || count($data->result_array()) == 0){
                $this->ci->db->query($this->insertSQL, [$this->MODULE_NAME, $key, $values["DEFAULT_VALUE"], $values["DESCRIPTION"]]);
            }
        }
    }

    public function getDefaultsArray(){
        return $this->module_settings_array;
    }

    public function getAllSettings(){
        $dataqr = $this->ci->db->query($this->getAllSQL,[$this->MODULE_NAME]);
        return $dataqr->result_array();
    }

    public function checkSettingExist($key){
        //check key exist
        $dataqr = $this->ci->db->query($this->getSQL,[$this->MODULE_NAME, $key]);
        $data = $dataqr ? $dataqr->result_array() : [];
        return count($data);
    }

    public function createSetting($key, $value, $description){
        //check key exist
        if ($this->checkSettingExist($key)){
            return "Hata: Ayar anahtarı zaten var!";
        }
        if ($key == '') {
            return "Hata: Ayar anahtarı boş olamaz!";
        }
        $this->ci->db->query($this->insertSQL, [$this->MODULE_NAME, $key, $value, $description]);
        return $this->ci->db->affected_rows() > 0;
    }

    public function getSetting($key, $default=''){
        //check key exist in settings array (canceled module can contain settings without defaults)
        // if (array_key_exists("",$this->module_settings_array) == false) {
        //     return [false, $key.' modül ayarlarında bulunamadı!'];
        // }
        //check key exist
        $dataqr = $this->ci->db->query($this->getSQL,[$this->MODULE_NAME, $key]);
        $data = $dataqr ? $dataqr->result_array() : [];
        //if not create it
        if (count($data) == 0){
            if (array_key_exists($key,$this->module_settings_array) == false) {
                if ($default != '')  {
                    return $default;
                }
                return "Hata: Ayar anahtarı database de bulunamadı!, Anahtar: $key";
            }
            $this->ci->db->query($this->insertSQL, [$this->MODULE_NAME, $key, $this->module_settings_array[$key]["DEFAULT_VALUE"], $this->module_settings_array[$key]["DESCRIPTION"]]);
            return $default == '' ? $this->module_settings_array[$key]["DEFAULT_VALUE"] : $default;
        }
        //get value
        return $data[0]["settings_value"];
    }

    public function putSetting($key, $value, $check_exist = true){
        $dataqr = $this->ci->db->query($this->getSQL,[$this->MODULE_NAME, $key]);
        $data = $dataqr ? $dataqr->result_array() : [];
        //check key exist
        if ($check_exist && count($data) == 0){
            return "Hata: Ayar anahtarı database de bulunamadı!, Anahtar: $key";
        }
        if (count($data) > 0) {
            $this->ci->db->query($this->updateSQL,[$value, $this->MODULE_NAME, $key]);
        } else {
            if (array_key_exists($key,$this->module_settings_array) == true) {
                $this->ci->db->query($this->insertSQL, [$this->MODULE_NAME, $key, $value, $this->module_settings_array[$key]["DESCRIPTION"]]);
            } else {
                $this->ci->db->query($this->insertSQL, [$this->MODULE_NAME, $key, $this->module_settings_array[$key]["DEFAULT_VALUE"], '']);
            }
        }
    }

}