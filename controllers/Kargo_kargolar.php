<?php

defined('BASEPATH') or exit('No direct script access allowed');
include_once "AdminControllerBase.php";

class Kargo_kargolar extends AdminControllerBase
{

    public function __construct()
    {
        parent::__construct();
    }

    //------------------- kargo_kargolar ------------------------------------

    public function index()
    {
        if (!has_permission('kargo', '', 'kargo_kargolar')) {
            access_denied('kargo');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('kargo', 'kargo/kargolar/kargolar_table'));
        }
        //$this->app_scripts->add('circle-progress-js','assets/plugins/jquery-circle-progress/circle-progress.min.js');
        // $data['title'] = _l('kargo_kargolar');
        // $this->load->view('kargo_kargolar_list', $data);
    }
    public function urun_ekle()
    {
        $this->load->library('urunekle');
        $id=$this->input->post('urunsayac');
        // AJAX isteğine yanıt olarak HTML kodunu alın
        $html = $this->urunekle->getHTMLResponse($id);

        // HTML kodunu JSON formatına dönüştürerek döndürün
        $response = array('html' => $html);
        echo json_encode($response);
    }

    public function get_musteri_bilgi(){
        $id=$this->input->post("id");
        $this->load->model('clients_model');
        $result=$this->clients_model->get($id);
        echo json_encode($result);
    }
    public function get_urun(){
        $id=$this->input->post('id');
        $this->load->model('kargo_settings_model');
        $result=$this->kargo_settings_model->get($id);
        echo json_encode($result);
    }
    public function get_last_update()
    {
        if (!has_permission('kargo', '', 'kargo_kargolar')) {
            $this->access_denied_ajax();
        }
        $this->load->model('kargo_kargolar_model');
        $data = $this->kargo_kargolar_model->get_last_update();
        echo json_encode($data);
        die;
    }

    public function get_id_title()
    {
        if (!has_permission('kargo', '', 'kargo_kargolar')) {
            $this->access_denied_ajax();
        }
        $this->load->model('kargo_kargolar_model');
        $data = $this->kargo_kargolar_model->getIdAndTitle();
        echo json_encode($data);
        die;
    }

    public function get()
    {
        if (!has_permission('kargo', '', 'kargo_kargolar')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        if (!$id) {
            $this->respErrorAjax("id gelmedi");
        }
        $this->load->model('kargo_kargolar_model');
        $response = $this->kargo_kargolar_model->get($id);
        echo json_encode($response);
        die;
    }

    public function delete_multiple()
    {
        if (!has_permission('kargo', '', 'kargo_kargolar')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        if (!$ids) {
            $this->respErrorAjax("idler gelmedi");
        }
        $this->load->model('kargo_kargolar_model');
        $response = $this->kargo_kargolar_model->delete_multiple($ids);
        if ($response == true) {
            $this->respSuccesAjax("Kayıtlar silindi");
        } else {
            $this->respErrorAjax("Kayıtlar silinemedi");
        }
    }

    public function undelete_multiple()
    {
        if (!has_permission('kargo', '', 'kargo_kargolar')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $ids = $this->input->post('ids', true);
        if (!$ids) {
            $this->respErrorAjax("idler gelmedi");
        }
        $this->load->model('kargo_kargolar_model');
        $response = $this->kargo_kargolar_model->undelete_multiple($ids);
        if ($response == true) {
            $this->respSuccesAjax("Kayıtlar geri alındı");
        } else {
            $this->respErrorAjax("Kayıtlar geri alınamadı");
        }
    }

    public function add_update()
    {
        if (!has_permission('kargo', '', 'kargo_kargolar')) {
            $this->access_denied_ajax();
        }
        if (!$this->input->post()) {
            $this->respErrorAjax("parametre vermelisiniz.");
        }
        $id = $this->input->post('id', true);
        $this->load->model('kargo_kargolar_model');
        $data=$this->input->post();

        $data['urun_resim']=str_replace("[removed]","data:image/png;base64,",$data['urun_resim']);
        $data['staffid'] = $_SESSION['staff_user_id'];
        $data['tarih'] = date("d/m/Y H:i:s");

        if ($id == null or $id == '') {
            try {

                $id = $this->kargo_kargolar_model->add($data);
                if ($id !== false) {
                    $response = $this->kargo_kargolar_model->get($id);
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
                $success = $this->kargo_kargolar_model->update($data, $id);
                if ($success) {
                    $response = $this->kargo_kargolar_model->get($id);
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
