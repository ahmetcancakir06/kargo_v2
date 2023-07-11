<?php

defined('BASEPATH') or exit('No direct script access allowed');
include_once "AdminControllerBase.php";

class Server_api_commands extends AdminControllerBase
{

    public function __construct()
    {
        parent::__construct();
        $this->ci = &get_instance();
    }

    //------------------- server_api_commands ------------------------------------

    public function index()
    {
        if (!has_permission('server_api', '', 'server_api_commands')) {
            access_denied('server_api');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('server_api', 'server_api/commands/commands_table'));
        }
        //test
        //$this->app_scripts->add('circle-progress-js','assets/plugins/jquery-circle-progress/circle-progress.min.js');
        // $data['title'] = _l('server_api_commands');
        // $this->load->view('server_api_commands_list', $data);
    }
    public function getServerInfo($id)
    {
        $this->load->model("server_api_system_care_servers_model");
        $requestserver = $this->server_api_system_care_servers_model->getServertitle($id);
        return $requestserver;
    }
    public function api()
    {
       
        $_SESSION['staff_user_id']=$_GET['staff_user_id'];
        $sessionstaff = $_SESSION['staff_user_id'];
        if (isset($_GET['outside']) && $_GET['outside'] == 1) {
            if (isset($_GET['api_key']) && !empty($_GET['api_key'])) {
                $this->load->model("server_api_module_settings_model");
                $checkserverip = $this->server_api_module_settings_model->getIp($_SERVER['REMOTE_ADDR']);

                if (empty($checkserverip)) {
                    echo "error 403!";
                    $this->insertLog("", "", "illagel sorgu yapıldı.Bize sorgu gönderen ip:" . $_GET['ip'] . " Geldiği sunucu ip:" . $_SERVER['REMOTE_ADDR'] . " ayarlardaki sunucu ip uyuşmadı. Komut id :".$_GET['id']);
                    exit;
                }
                $this->load->model("server_api_commands_per_model");
                $commands_per = $this->server_api_commands_per_model->requestApiKey($_GET['api_key'], $_GET['ip']);
                if ($commands_per == "") {
                    echo "error: api 403";
                    $this->insertLog("", "", "api bulunamadı uyuşmadı.Bize sorgu gönderen ip:" . $_GET['ip'] . " Geldiği sunucu ip:" . $_SERVER['REMOTE_ADDR'] . " ayarlardaki sunucu ip uyuşmadı Komut id :".$_GET['id']);
                    exit;
                } else {
                    //echo "başarılı";
                    $getcommands = $this->getIn($commands_per['commands_id']);

                    if ($getcommands['active'] == "1") {
                        if ($getcommands['in_system'] == "0" || $getcommands['in_system'] == "2") {
                            $serverInfo = $this->getServerInfo($commands_per['which_server']);
                            $this->load->library(SERVER_API_MODULE_NAME . '/' . 'run_module_remote');
                            $this->run_module_remote->setModule($serverInfo);
                            $command_new = "";
                            $getkeys = [];
                            foreach ($_GET as $key => $value) {
                                if ($key != "outside" && $key != "api_key" && $key != "ip") {
                                    if ($command_new == "") {
                                        $command_new = str_replace("#" . rawurldecode($key) . "#", rawurldecode($value), $getcommands['commands']);
                                    } else {
                                        $command_new = str_replace("#" . rawurldecode($key) . "#", rawurldecode($value), $command_new);
                                    }
                                }
                            }
                            $expcommands = str_split($getcommands['commands']);
                            $gir = 0;
                            $degisken = "";
                            foreach ($expcommands as $command_part) {
                                if ($command_part == "#" && $gir == 0) {
                                    $gir = 1;
                                } else if ($command_part != "#" && $gir == 1) {
                                    $degisken .= $command_part;
                                } else if ($command_part == "#" && $gir == 1) {
                                    $gir = 0;
                                    array_push($getkeys, $degisken);
                                    $degisken = "";
                                }
                            }

                            $getok = 1;
                            foreach ($getkeys as $keys) {
                                if (preg_match("/" . $keys . "/i", $command_new)) {
                                    $getok = 0;
                                }
                            }
                            if ($command_new == "" || $getok == 0) {
                                echo "error: commands 404";
                                $this->insertLog("", "", "Dışarıdan komut çalıştı. ancak argümanlar eksik gibi" . $getcommands['id'] . ".Bize sorgu gönderen ip:" . $_GET['ip'] . " Geldiği sunucu ip:" . $_SERVER['REMOTE_ADDR'] . " ayarlardaki sunucu ip uyuşmadı");
                                die;
                            }
                            $this->insertLog("", "", "Dışarıdan komut gönderildi." . $getcommands['id'] . ".Bize sorgu gönderen ip:" . $_GET['ip'] . " Geldiği sunucu ip:" . $_SERVER['REMOTE_ADDR'] . " Gönderilen komut:" . $command_new);
                            [$result, $rdata] = $this->run_module_remote->runCommand($command_new);
                            if ($result == "1") {
                                $this->insertLog("", "", "Dışarıdan komut çalıştı." . $getcommands['id'] . ".Bize sorgu gönderen ip:" . $_GET['ip'] . " Geldiği sunucu ip:" . $_SERVER['REMOTE_ADDR'] . " Gönderilen komut:" . $command_new);
                            }
                        } else {
                            echo "error: commands 403_2";
                            $this->insertLog("", "", "Komut dışarıya kapalı." . $getcommands['id'] . ".Bize sorgu gönderen ip:" . $_GET['ip'] . " Geldiği sunucu ip:" . $_SERVER['REMOTE_ADDR'] . " ayarlardaki sunucu ip uyuşmadı");
                        }
                    } else {
                        echo "error: commands 403";
                        $this->insertLog("", "", "Komut kapalı." . $getcommands['id'] . ".Bize sorgu gönderen ip:" . $_GET['ip'] . " Geldiği sunucu ip:" . $_SERVER['REMOTE_ADDR'] . " ayarlardaki sunucu ip uyuşmadı");
                    }
                }
            } else {
                echo "error: api 404";
                $this->insertLog("", "", "Api girilmemiş.Bize sorgu gönderen ip:" . $_GET['ip'] . " Geldiği sunucu ip:" . $_SERVER['REMOTE_ADDR'] . " ayarlardaki sunucu ip uyuşmadı Komut id :".$_GET['id']);
                exit;
            }
        } else {
            if (isset($_GET['staff'])) {
                if ($_GET['staff'] == $sessionstaff) {
                    if (isset($_GET['id']) || isset($_GET['module_id'])){
                        $this->load->model("server_api_commands_per_model");
                        $commands_per = $this->server_api_commands_per_model->requestPermission($_GET['id'], $_GET['staff'], $_GET['module_id']);

                        if ($commands_per == "") {
                            echo "error: module/id 403";
                            $this->insertLog($_GET['id'], $_SESSION['staff_user_id'], "İçeriden komut çalıştı.Bize sorgu gönderen ip:" . $_SERVER['REMOTE_ADDR']);
                            die;
                        } else {
                            $getcommands = $this->getIn($commands_per['commands_id']);
                            if ($getcommands['active'] == "1") {
                                if ($getcommands['in_system'] == "1" || $getcommands['in_system'] == "2") {
                                    $serverInfo = $this->getServerInfo($commands_per['which_server']);
                                    $this->load->library(SERVER_API_MODULE_NAME . '/' . 'run_module_remote');
                                    $this->run_module_remote->setModule($serverInfo);

                                    $command_new = "";
                                    $getkeys = [];
                                    foreach ($_GET as $key => $value) {
                                        if ($key != "id" && $key != "module_id" && $key != "staff") {
                                            if ($command_new == "") {
                                                $command_new = str_replace("#" . rawurldecode($key) . "#", rawurldecode($value), $getcommands['commands']);
                                            } else {
                                                $command_new = str_replace("#" . rawurldecode($key) . "#", rawurldecode($value), $command_new);
                                            }
                                        }
                                    }
                                    
                                    $expcommands = str_split($getcommands['commands']);
                                    $gir = 0;
                                    $degisken = "";
                                    foreach ($expcommands as $command_part) {
                                        if ($command_part == "#" && $gir == 0) {
                                            $gir = 1;
                                        } else if ($command_part != "#" && $gir == 1) {
                                            $degisken .= $command_part;
                                        } else if ($command_part == "#" && $gir == 1) {
                                            $gir = 0;
                                            array_push($getkeys, $degisken);
                                            $degisken = "";
                                        }
                                    }

                                    $getok = 1;
                                    foreach ($getkeys as $keys) {
                                        if (preg_match("/#" . $keys . "#/i", $command_new)) {
                                            echo $keys."<br>";
                                            $getok = 0;
                                        }
                                    }
                                    if ($command_new == "" || $getok == 0) {
                                        echo "error: commands 404";
                                        $this->insertLog("", "", "Dışarıdan komut çalıştı. ancak argümanlar eksik gibi" . $getcommands['id'] . " Geldiği sunucu ip:" . $_SERVER['REMOTE_ADDR'] . " ayarlardaki sunucu ip uyuşmadı");
                                        die;
                                    }
                                    $this->insertLog($_GET['id'], $_SESSION['staff_user_id'], "İçeriden komut gönderildi.Bize sorgu gönderen ip:" . $_SERVER['REMOTE_ADDR'] . " Gönderilen komut:" . $command_new);
                                    $command_new=html_entity_decode($command_new);
                                    [$result, $rdata] = $this->run_module_remote->runCommand($command_new);
                                    if ($result == true) {
                                        return "success";
                                        $this->insertLog($_GET['id'], $_SESSION['staff_user_id'], "İçeriden komut çalıştı.Bize sorgu gönderen ip:" . $_SERVER['REMOTE_ADDR'] . " Gönderilen komut:" . $command_new);
                                        
                                    }
                                } else {
                                    echo "error: commands 404";
                                    $this->insertLog($_GET['id'], $_SESSION['staff_user_id'], "Komut içeriden çalışmaya devre dışı.Bize sorgu gönderen ip:" . $_SERVER['REMOTE_ADDR']);
                                    die;
                                }
                            } else {
                                echo "error: commands 404";
                                $this->insertLog($_GET['id'], $_SESSION['staff_user_id'], "Komut içeriden çalıştırıldı ancak devre dışı.Bize sorgu gönderen ip:" . $_SERVER['REMOTE_ADDR']);
                                die;
                            }
                        }
                    } else {
                        echo "error: module/id 404";
                        $this->insertLog($_GET['id'], $_SESSION['staff_user_id'], "Komut içeriden çalıştırıldı ancak devre dışı.Bize sorgu gönderen ip:" . $_SERVER['REMOTE_ADDR']);
                        die;
                    }
                } else {
                    echo "error: user 403";
                    $this->insertLog($_GET['id'], $_SESSION['staff_user_id'], "Komut içeriden çalıştırıldı ancak Gönderilen ve sisteme girişli olan kullanıcı aynı değil.Bize sorgu gönderen ip:" . $_SERVER['REMOTE_ADDR']."Gönderilen kullanıcı.".$_GET['staff']);
                    die;
                }
            } else {
                echo "error: user 404";
                $this->insertLog($_GET['id'], $_SESSION['staff_user_id'], "Komut içeriden çalıştırıldı ancak Gönderilen bir kullanıcı id yok.Bize sorgu gönderen ip:" . $_SERVER['REMOTE_ADDR']);
                die;
            }
        }
       
    }
    public function requestAPI()
    {

        $data = [];
        foreach ($this->input->post() as $key => $value) {
            $data[$key] = $value;
        }
        $this->insertLog($data['id'], $_SESSION['staff_user_id'], "Komut içeriden Test olarak çalıştırıldı");

        $data['staff_user_id']=$_SESSION['staff_user_id'];
        $this->load->model('server_api_module_settings_model');
        $logininfo = $this->server_api_module_settings_model->get("1");
        if(empty($logininfo['username']) || empty($logininfo['password']) || $logininfo['username'] == "" || $logininfo['password'] == ""){
            $this->insertLog("", $_SESSION['staff_user_id'], "Kullanıcı adı şifre girilmemiş");
            $this->respErrorAjax("Ayarlardan kullanıcı adı şifre giriniz.");
            die;
        }
        $this->api_request->username=$logininfo['username'];
        $this->api_request->password=$logininfo['password'];
        $urltest="https://".$_SERVER['HTTP_HOST']."/admin/server_api/server_api_commands/api";
        $this->api_request->api_url = $urltest;
       
        $test = $this->api_request->callAPI($data);
        $this->insertLog($data['id'], $_SESSION['staff_user_id'], "Komut içeriden Test olarak çalıştırıldı apiye gönderildi");

        print_r($test);
        exit;
    }
    public function updateActive()
    {
        if (!has_permission('server_api', '', 'server_api_commands_updateStatus')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        $status = $this->input->post('status', true);

        $this->load->model('server_api_commands_model');
        $data = $this->server_api_commands_model->updateActive($id, $status);
        $this->insertLog($id, $_SESSION['staff_user_id'], "active güncelledi " . $status);
        echo $data;
        die;
    }
    public function updateInSystem()
    {
        if (!has_permission('server_api', '', 'server_api_commands_status')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        $value = $this->input->post('value', true);

        $this->load->model('server_api_commands_model');
        $data = $this->server_api_commands_model->updateInSystem($id, $value);
        $this->insertLog($id, $_SESSION['staff_user_id'], "in system güncelledi " . $value);
        echo $data;
        die;
    }
    public function insertLog($id, $kim, $details)
    {
        $this->load->model("server_api_logs_model");
        $insert = $this->server_api_logs_model->insertLog($id, $kim, $details);
    }
    public function get_last_update()
    {
        if (!has_permission('server_api', '', 'server_api_commands')) {
            $this->access_denied_ajax();
        }
        $this->load->model('server_api_commands_model');
        $data = $this->server_api_commands_model->get_last_update();
        echo json_encode($data);
        die;
    }

    public function get_id_title()
    {
        if (!has_permission('server_api', '', 'server_api_commands')) {
            $this->access_denied_ajax();
        }
        $this->load->model('server_api_commands_model');
        $data = $this->server_api_commands_model->getIdAndTitle();
        echo json_encode($data);
        die;
    }

    public function getIn($id)
    {
        $this->load->model('server_api_commands_model');
        $response = $this->server_api_commands_model->get($id);
        return $response;
    }
    public function get()
    {
        if (!has_permission('server_api', '', 'server_api_commands')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        if (!$id) {
            $this->respErrorAjax("id gelmedi");
        }
        $this->load->model('server_api_commands_model');
        $response = $this->server_api_commands_model->get($id);
        echo json_encode($response);
        die;
    }

    public function delete_multiple()
    {
        if (!has_permission('server_api', '', 'server_api_commands')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        if (!$ids) {
            $this->respErrorAjax("idler gelmedi");
        }
        $this->load->model('server_api_commands_model');
        $response = $this->server_api_commands_model->delete_multiple($ids);
        if ($response == true) {
            $this->respSuccesAjax("Kayıtlar silindi");
        } else {
            $this->respErrorAjax("Kayıtlar silinemedi");
        }
    }

    public function undelete_multiple()
    {
        if (!has_permission('server_api', '', 'server_api_commands')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        if (!$ids) {
            $this->respErrorAjax("idler gelmedi");
        }
        $this->load->model('server_api_commands_model');
        $response = $this->server_api_commands_model->undelete_multiple($ids);
        if ($response == true) {
            $this->respSuccesAjax("Kayıtlar geri alındı");
        } else {
            $this->respErrorAjax("Kayıtlar geri alınamadı");
        }
    }

    public function add_update()
    {
        if (!has_permission('server_api', '', 'server_api_commands')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);

        $this->load->model('server_api_commands_model');
        if ($id == null or $id == '') {
            try {
                $id = $this->server_api_commands_model->add($this->input->post());
                if ($id !== false) {
                    $insertLogs = $this->insertLog($id, $_SESSION['staff_user_id'], "Yeni komut eklendi");
                    $response = $this->server_api_commands_model->get($id);
                    echo json_encode($response);
                    die;
                } else {
                    $this->respErrorAjax($this->db->error()["message"]);
                }
            } catch (\Throwable $th) {
                $this->respErrorAjax($th->getMessage());
            }
        } else {
            try {
                $success = $this->server_api_commands_model->update($this->input->post(), $id);
                if ($success) {
                    $insertLogs = $this->insertLog($id, $_SESSION['staff_user_id'], "komut güncellendi : " . $id);
                    $response = $this->server_api_commands_model->get($id);
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


    public function batch_update()
    {
        if (!has_permission('server_api', '', 'server_api_commands')) {
            $this->access_denied_ajax();
        }
        if (!has_permission('server_api', '', 'server_api_commands_batch')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        $field = $this->input->post('field', true);
        $value = $this->input->post('value', true);
        $search = $this->input->post('search', true);
        if ($field == null) {
            $this->respErrorAjax("Güncellenecek alanı belirtmelisiniz.");
        }
        $this->load->model('server_api_commands_model');
        try {
            $counts = $this->server_api_commands_model->update_field($ids, $field, $value, $search);
            if ($counts !== false) {
                $this->respSuccesAjax($counts . " kayıt güncellendi");
            } else {
                $this->respErrorAjax($this->db->error()["message"]);
            }
        } catch (\Throwable $th) {
            $this->respErrorAjax($th->getMessage());
        }
    }
}
