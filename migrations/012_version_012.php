<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_012 extends App_module_migration
{

    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $ci = &get_instance();
        $ci->db->query('ALTER TABLE `tblkargolist` ADD `tarih` VARCHAR(255) NULL AFTER `bayi_id`;  ');
        $ci->db->query('ALTER TABLE `tblkargolist` ADD `staffid` INT NULL AFTER `tarih`;  ');
        $ci->db->query('ALTER TABLE `tblkargolist` ADD `updated_at` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3); ');

    }
}