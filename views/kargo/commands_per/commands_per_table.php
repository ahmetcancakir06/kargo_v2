<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'commands_id',
    'which_server',
    'module_name',
    'staff_user_id',
    'ip_address',
    'api_key',
    'allowed',
];

$tabName = 'server_api_commands_per';
$itemForm = 'item' . $tabName;

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'server_api_commands_per';

$ci = &get_instance();

// $only_id_number = $ci->input->get('only_id_number', true);

$ci->load->model('server_api_modules_model');
$ci->load->model('server_api_commands_model');

$ci->load->model('server_api_system_care_servers_model');
$ci->load->model('server_api_staff_model');

$where = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, ["id"]);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'id') {
            $_data = '<div style="white-space: nowrap;"><input type="checkbox" id="' . $tabName . '-cbox-' . $aRow['id'] . '" data-value="' . $aRow['id'] . '" style="float: left" onchange="addRemoveToBatch(\'' . $tabName . '\',\'' . trim($aRow['id']) . '\');" />&nbsp;&nbsp;';
            $_data .= '&nbsp;' . $aRow['id'] . '&nbsp;&nbsp;&nbsp;</div>';
        } else if ($aColumns[$i] == 'commands_id') {
            $getcommands = $ci->server_api_commands_model->get($aRow['commands_id']);
            if (isset($getcommands['title'])) {
                $_data = $getcommands['title'];
            } else {
                $_data = $aRow['commands_id'];
            }
        } else if ($aColumns[$i] == 'which_server') {
            $getserver_title = $ci->server_api_system_care_servers_model->getServertitle($aRow['which_server']);
            if (isset($getserver_title['title'])) {
                $_data = $getserver_title['title'];
            } else {
                $_data = $aRow['which_server'];
            }
        } else if ($aColumns[$i] == 'module_name') {
            if(!empty($aRow['module_name'])) {
                $explode=explode(",",$aRow['module_name']);
                foreach ($explode as $key => $ex) {
                    $getmodule_title = $ci->server_api_modules_model->getModules($ex);
                    if (isset($getmodule_title['module_name'])) {
                        if($key == "0") {
                            $_data = $getmodule_title['module_name'];
                        }else{
                            $_data.=", ".$getmodule_title['module_name'];
                        }
                    } else {
                        if($key == "0") {
                            $_data = $ex;
                        }else{
                            $_data.=",".$ex;
                        }
                    }
                }
            }
        } else if ($aColumns[$i] == 'staff_user_id') {
            if(!empty($aRow['staff_user_id'])) {
                $explode=explode(",",$aRow['staff_user_id']);
                foreach ($explode as $key=>$ex) {
                    $getstaff = $ci->server_api_staff_model->getWho($ex);
                    if (isset($getstaff['firstname'])) {
                        if($key == "0") {
                            $_data = $getstaff['firstname'] . " " . $getstaff['lastname'];
                        }else{
                            $_data .=", ". $getstaff['firstname'] . " " . $getstaff['lastname'];
                        }
                    } else {
                        if($key == "0") {
                            $_data = $ex;
                        }else{
                            $_data .=", ". $ex;

                        }
                    }
                }
            }
        }

        $row[] = $_data;
    }
    $actionbos = 1;
    $actions = '
            <div class="dropdown text-right" style="display:inline-block;margin-right:2rem;">
                <button class="btn btn-sm btn-info dropdown-toggle" title="' . _l('actions') . '" type="button" id="' . $tabName . 'dropdownMenu-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-cog"></i>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="' . $tabName . 'dropdownMenu-' . $aRow['id'] . '" style="bottom:auto!important;">
                ';
    
    if (has_permission('server_api', '', 'server_api_commands_per_edit')) {
        $actions .= '<li><a href="javascript:detailsModal' . $itemForm . '(' . $aRow['id'] . ')">' . _l('record_details') . '</a></li>';
        $actionbos = 0;
    }
    if (has_permission('server_api', '', 'server_api_commands_per_copy')) {
        $actions .= '<li><a href="javascript:copyModal' . $itemForm . '(' . $aRow['id'] . ')">' . _l('record_copy') . '</a></li>';
        $actionbos = 0;
    }
    if ($actionbos == 0) {
        $actions .= '<li role="separator" class="divider"></li>';
    }
    if (has_permission('server_api', '', 'server_api_commands_per_delete')) {
        $actions .= '<li><a style="color:red;" href="javascript:delete' . $itemForm . '(' . $aRow['id'] . ')">' . _l('record_delete') . '</a></li>';
        $actionbos = 0;
    }
    if ($actionbos == 1) {
        $actions .= '<li><a href="#">' . _l('sgyy') . '</a></li>';
    }
    $actions .= '
                </ul>
            </div>
            ';


    array_push($row, $actions);

    ob_start();
    $progress = ob_get_contents();
    ob_end_clean();
    $row[]              = $progress;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
