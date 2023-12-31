<?php

defined('BASEPATH') or exit('No direct script access allowed');
include_once "AdminControllerBase.php";

class Server_api_logs extends AdminControllerBase
{

    public function __construct()
    {
        parent::__construct();
    }

    //------------------- server_api_logs ------------------------------------

    public function index()
    {
        if (!has_permission('server_api', '', 'server_api_logs')) {
            access_denied('server_api');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('server_api', 'server_api/logs/logs_table'));
        }
        //$this->app_scripts->add('circle-progress-js','assets/plugins/jquery-circle-progress/circle-progress.min.js');
        // $data['title'] = _l('server_api_logs');
        // $this->load->view('server_api_logs_list', $data);
    }

    public function get_last_update()
    {
        if (!has_permission('server_api', '', 'server_api_logs')) {
            $this->access_denied_ajax();
        }
        $this->load->model('server_api_logs_model');
        $data = $this->server_api_logs_model->get_last_update();
        echo json_encode($data);
        die;
    }

    public function get_id_title()
    {
        if (!has_permission('server_api', '', 'server_api_logs')) {
            $this->access_denied_ajax();
        }
        $this->load->model('server_api_logs_model');
        $data = $this->server_api_logs_model->getIdAndTitle();
        echo json_encode($data);
        die;
    }

    public function get()
    {
        if (!has_permission('server_api', '', 'server_api_logs')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        if (!$id) {
            $this->respErrorAjax("id gelmedi");
        }
        $this->load->model('server_api_logs_model');
        $response = $this->server_api_logs_model->get($id);
        echo json_encode($response);
        die;
    }

    public function delete_multiple()
    {
        if (!has_permission('server_api', '', 'server_api_logs')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        if (!$ids) {
            $this->respErrorAjax("idler gelmedi");
        }
        $this->load->model('server_api_logs_model');
        $response = $this->server_api_logs_model->delete_multiple($ids);
        if ($response == true) {
            $this->respSuccesAjax("Kayıtlar silindi");
        } else {
            $this->respErrorAjax("Kayıtlar silinemedi");
        }
    }

    public function undelete_multiple()
    {
        if (!has_permission('server_api', '', 'server_api_logs')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        if (!$ids) {
            $this->respErrorAjax("idler gelmedi");
        }
        $this->load->model('server_api_logs_model');
        $response = $this->server_api_logs_model->undelete_multiple($ids);
        if ($response == true) {
            $this->respSuccesAjax("Kayıtlar geri alındı");
        } else {
            $this->respErrorAjax("Kayıtlar geri alınamadı");
        }
    }

    public function add_update()
    {
        if (!has_permission('server_api', '', 'server_api_logs')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        $this->load->model('server_api_logs_model');
        if ($id == null or $id == '') {
            try {
                $id = $this->server_api_logs_model->add($this->input->post());
                if ($id !== false) {
                    $response = $this->server_api_logs_model->get($id);
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
                $success = $this->server_api_logs_model->update($this->input->post(), $id);
                if ($success) {
                    $response = $this->server_api_logs_model->get($id);
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
}
