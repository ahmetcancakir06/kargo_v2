<?php

defined('BASEPATH') or exit('No direct script access allowed');
include_once "AdminControllerBase.php";

class Kargo extends AdminControllerBase
{

    public function __construct()
    {
        parent::__construct();
    }

//------------------- kargo_update ------------------------------------
    public function kargo_report() {
        $contents = "";
        if (file_exists(dirname(__FILE__)."/update_report.txt"))
            $contents = file_get_contents(dirname(__FILE__)."/update_report.txt");
        $data = [
            "logs" => $contents
        ];
        $this->flsuhJson($data);
        die;
    }

    public function kargo_update()
    {
        if (!has_permission('kargo', '', 'kargo_update')) {
            access_denied('kargo');
        }
        $this->load->library(SERVER_API_MODULE_NAME . '/' . 'update_module');
        $this->update_module->update();
        $data['title'] = _l('kargo_update');
        $this->load->view('kargo_update', $data);
    }
//\------------------- kargo_update ------------------------------------

    public function kargo_all()
    {
        if (!has_permission('kargo', '', 'view')) {
            access_denied('kargo');
        }
        //$this->load->library('');

        // if ($this->input->is_ajax_request()) {
        //     $this->app->get_table_data(module_views_path('kargo', '/services/kargo_services_table'));
        // }
        $this->app_scripts->add('select2-js', module_dir_url('kargo', 'assets/js/select2.full.min.js'), 'admin', ['app-js']);
        $this->app_css->add('select2-css', module_dir_url('kargo', 'assets/css/select2.min.css'), 'admin', ['app-css']);
        $this->app_css->add('select2-theme-css', module_dir_url('kargo', 'assets/css/select2-bootstrap.min.css'), 'admin', ['app-css']);
        $data['title'] = _l('kargo');
        $this->load->view('kargo_all', $data);
    }


}
