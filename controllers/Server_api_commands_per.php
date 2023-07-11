<?php

defined('BASEPATH') or exit('No direct script access allowed');
include_once "AdminControllerBase.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Server_api_commands_per extends AdminControllerBase
{

    public function __construct()
    {
        parent::__construct();
    }

//------------------- server_api_commands_per ------------------------------------

    public function index()
    {
        if (!has_permission('server_api', '', 'server_api_commands_per')) {
            access_denied('server_api');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('server_api', 'server_api/commands_per/commands_per_table'));
        }
        //$this->app_scripts->add('circle-progress-js','assets/plugins/jquery-circle-progress/circle-progress.min.js');
        // $data['title'] = _l('server_api_commands_per');
        // $this->load->view('server_api_commands_per_list', $data);
    }

    public function getServers()
    {
        $this->load->model('server_api_system_care_servers_model');
        $data = $this->server_api_system_care_servers_model->getServers();
        echo json_encode($data);
        die;
    }

    public function getAllModules()
    {
        $this->load->model('server_api_modules_model');
        $data = $this->server_api_modules_model->getAllModules();

        echo json_encode($data);
        die;
    }

    public function getModules()
    {
        $this->load->model('server_api_modules_model');
        $data = $this->server_api_modules_model->getModules();
        echo json_encode($data);
        die;
    }

    public function getCommandsIds()
    {

        $this->load->model('server_api_commands_model');
        $data = $this->server_api_commands_model->getAll();

        echo json_encode($data);
        die;
    }

    public function getCommandsId()
    {
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        if (!$id) {
            $this->respErrorAjax("id gelmedi");
        }
        $this->load->model('server_api_commands_model');
        $data = $this->server_api_commands_model->get($id);
        echo json_encode($data);
        die;
    }

    public function get_last_update()
    {
        if (!has_permission('server_api', '', 'server_api_commands_per')) {
            $this->access_denied_ajax();
        }
        $this->load->model('server_api_commands_per_model');
        $data = $this->server_api_commands_per_model->get_last_update();
        echo json_encode($data);
        die;
    }

    public function get_id_title()
    {
        if (!has_permission('server_api', '', 'server_api_commands_per')) {
            $this->access_denied_ajax();
        }
        $this->load->model('server_api_commands_per_model');
        $data = $this->server_api_commands_per_model->getIdAndTitle();
        echo json_encode($data);
        die;
    }

    public function get()
    {
        if (!has_permission('server_api', '', 'server_api_commands_per')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        if (!$id) {
            $this->respErrorAjax("id gelmedi");
        }
        $this->load->model('server_api_commands_per_model');
        $response = $this->server_api_commands_per_model->get($id);
        echo json_encode($response);
        die;
    }

    public function delete_multiple()
    {
        if (!has_permission('server_api', '', 'server_api_commands_per')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        if (!$ids) {
            $this->respErrorAjax("idler gelmedi");
        }

        $this->load->model('server_api_commands_per_model');
        $response = $this->server_api_commands_per_model->delete_multiple($ids);
        if ($response == true) {
            $this->respSuccesAjax("Kayıtlar silindi");
        } else {
            $this->respErrorAjax("Kayıtlar silinemedi");
        }
    }

    public function undelete_multiple()
    {
        if (!has_permission('server_api', '', 'server_api_commands_per')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        if (!$ids) {
            $this->respErrorAjax("idler gelmedi");
        }
        $this->load->model('server_api_commands_per_model');
        $response = $this->server_api_commands_per_model->undelete_multiple($ids);
        if ($response == true) {
            $this->respSuccesAjax("Kayıtlar geri alındı");
        } else {
            $this->respErrorAjax("Kayıtlar geri alınamadı");
        }
    }

    public function insertLog($id, $kim, $details)
    {
        $this->load->model("server_api_logs_model");
        $insert = $this->server_api_logs_model->insertLog($id, $kim, $details);
    }

    public function staffusers()
    {
        $this->load->model('server_api_staff_model');
        $data = $this->server_api_staff_model->getAll();
        echo json_encode($data);
        die;

    }

    public function add_update()
    {
        if (!has_permission('server_api', '', 'server_api_commands_per')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }

        $id = $this->input->post('id', true);
        $this->load->model('server_api_commands_per_model');
        if ($id == null or $id == '') {
            try {
                $staffs="";
                $moduless="";
                if($this->input->post("staff_user_id", true) !== null){
                    foreach($this->input->post("staff_user_id",true) as $staf){
                        if($staffs == ""){
                            $staffs=$staf;
                        }else{
                            $staffs.=",".$staf;
                        }
                    }
                }
                if($this->input->post("module_name", true) !== null){
                    foreach($this->input->post("module_name",true) as $module){
                        if($moduless == ""){
                            $moduless=$module;
                        }else{
                            $moduless.=",".$module;
                        }
                    }
                }
                if($staffs == "" || $moduless == ""){
                    $this->respErrorAjax("Boş bırakmayın Kime veya Modül ismini");
                }

                        $datasend = array(
                            "id" => "",
                            "commands_id" => $this->input->post("commands_id", true),
                            "which_server" => $this->input->post("which_server", true),
                            "allowed" => $this->input->post("allowed", true),
                            "staff_user_id" => $staffs,
                            "module_name" => $moduless,
                            "ip_address" => $this->input->post("ip_address", true),
                            "api_key" => $this->input->post("api_key", true),

                        );
                        $id = $this->server_api_commands_per_model->add($datasend, md5(time()));
                        $insertLogs = $this->insertLog($id, $_SESSION['staff_user_id'], "Yeni komut yetkileri eklendi");


                $response = $this->server_api_commands_per_model->get($id);
                echo json_encode($response);
                die;

            } catch (\Throwable $th) {
                $this->respErrorAjax($th->getMessage());
            }
        } else {
            try {
                $datas=[];
                foreach($this->input->post() as $key=>$value){
                    if($key == "staff_user_id" || $key == "module_name"){
                        $first="";
                        foreach($value as $val){
                            if($first == ""){
                                $first=$val;
                            }else{
                                $first.=",".$val;
                            }
                        }
                        $datas[$key]=$first;
                    }else {
                        $datas[$key] = $value;
                    }
                }

                $success = $this->server_api_commands_per_model->update($datas, $id);
                $insertLogs = $this->insertLog($id, $_SESSION['staff_user_id'], "Komut yetkileri güncellendi :" . $id);
                if ($success) {
                    $response = $this->server_api_commands_per_model->get($id);
                    echo json_encode($response);
                    die;
                } else {
                    $this->respErrorAjax($this->db->error()["message"]);
                }
            } catch (\Throwable $th) {
                $this->respErrorAjax($th->getMessage());
            }
        }
    }


//\------------------- server_api_commands_per ------------------------------------

}
