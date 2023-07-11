<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    // 'perfex_module_name',
    'settings_key',
    'settings_value',
    'description',
    'ip',
    'username',
    'password',
    'updated_at',

];

$tabName = 'server_api_module_settings';
$itemForm = 'item'.$tabName;

$ci = & get_instance();
$ci->load->model('server_api_module_settings_model');
$ci->load->model('server_api_system_care_servers_model');

$module_id = $ci->input->get('module_id', true);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'server_api_module_settings';

$where = [];
if ($module_id != null and $module_id != ''){
    $where[]= "AND perfex_module_name = '".$module_id."'";
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where,["id"]);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'id') {
            $_data = '<div style="white-space: nowrap;"><input type="checkbox" id="'.$tabName.'-cbox-' . $aRow['id'] . '" data-value="'. $aRow['id'] .'" style="float: left" onchange="addRemoveToBatch(\''.$tabName.'\',\''. trim($aRow['id']) .'\');" />&nbsp;&nbsp;';
            $_data .= '&nbsp;' . $aRow['id'] . '&nbsp;&nbsp;&nbsp;</div>';
        } elseif ($aColumns[$i] == 'perfex_module_name'){
            $_data = _l($_data);
        }else if($aColumns[$i] == 'server_id'){
            $getserver_title = $ci->server_api_system_care_servers_model->getServertitle($aRow['server_id']);
            if(isset($getserver_title['title'])){
                $_data = $getserver_title['title'];
            }else{
                $_data = $aRow['server_id'];
            }
        }
        $row[] = $_data;
    }

    $actions = '
    <div class="dropdown text-right">
        <button class="btn btn-sm btn-info dropdown-toggle" title="'. _l('actions') .'" type="button" id="'.$tabName.'dropdownMenu-'. $aRow['id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-cog"></i>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="'.$tabName.'dropdownMenu-'.$aRow['id'].'" style="bottom:auto!important;">';
        $actionbos=1;
        if (has_permission('server_api', '', 'server_api_module_settings_edit')) {
            $actions.='<li><a href="javascript:detailsModal'.$itemForm.'('.$aRow['id'].')">'. _l('record_details').'</a></li>';
            $actionbos=0;
        }
        if($actionbos == 1){
            $actions.='<li><a href="#">' . _l('sgyy') . '</a></li>';
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
