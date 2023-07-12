<?php

defined('BASEPATH') or exit('No direct script access allowed');
include_once "AdminControllerBase.php";

class Kargo_settings extends AdminControllerBase
{

    public function __construct()
    {
        parent::__construct();
    }

    //------------------- kargo_settings ------------------------------------

    public function index()
    {
        if (!has_permission('kargo', '', 'kargo_settings')) {
            access_denied('kargo');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('kargo', 'kargo/settings/settings_table'));
        }
        //$this->app_scripts->add('circle-progress-js','assets/plugins/jquery-circle-progress/circle-progress.min.js');
        // $data['title'] = _l('kargo_settings');
        // $this->load->view('kargo_settings_list', $data);
    }

    public function get_last_update()
    {
        if (!has_permission('kargo', '', 'kargo_settings')) {
            $this->access_denied_ajax();
        }
        $this->load->model('kargo_settings_model');
        $data = $this->kargo_settings_model->get_last_update();
        echo json_encode($data);
        die;
    }

    public function get_id_title()
    {
        if (!has_permission('kargo', '', 'kargo_settings')) {
            $this->access_denied_ajax();
        }
        $this->load->model('kargo_settings_model');
        $data = $this->kargo_settings_model->getIdAndTitle();
        echo json_encode($data);
        die;
    }

    public function get()
    {
        if (!has_permission('kargo', '', 'kargo_settings')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        if (!$id) {
            $this->respErrorAjax("id gelmedi");
        }
        $this->load->model('kargo_settings_model');
        $response = $this->kargo_settings_model->get($id);
        echo json_encode($response);
        die;
    }

    public function delete_multiple()
    {
        if (!has_permission('kargo', '', 'kargo_settings')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        if (!$ids) {
            $this->respErrorAjax("idler gelmedi");
        }
        $this->load->model('kargo_settings_model');
        $response = $this->kargo_settings_model->delete_multiple($ids);
        if ($response == true) {
            $this->respSuccesAjax("Kayıtlar silindi");
        } else {
            $this->respErrorAjax("Kayıtlar silinemedi");
        }
    }

    public function undelete_multiple()
    {
        if (!has_permission('kargo', '', 'kargo_settings')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        if (!$ids) {
            $this->respErrorAjax("idler gelmedi");
        }
        $this->load->model('kargo_settings_model');
        $response = $this->kargo_settings_model->undelete_multiple($ids);
        if ($response == true) {
            $this->respSuccesAjax("Kayıtlar geri alındı");
        } else {
            $this->respErrorAjax("Kayıtlar geri alınamadı");
        }
    }

    public function add_update()
    {
        if (!has_permission('kargo', '', 'kargo_settings')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        $this->load->model('kargo_settings_model');
        print_r($this->input->post());
        die;
        if ($id == null or $id == '') {
            try {

                $id = $this->kargo_settings_model->add($this->input->post());
                if ($id !== false) {
                    $response = $this->kargo_settings_model->get($id);
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
                $success = $this->kargo_settings_model->update($this->input->post(), $id);
                if ($success) {
                    $response = $this->kargo_settings_model->get($id);
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
