<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'kargolist.id',
    'urun_ismi',
    'productname',
    'urun_aciklama',
    'productexplanation',
    'urun_resim',
    'urun_stok',
    'urun_fiyat',
    'kargobayi.bayi as bayi',
    'tarih',
    'CONCAT(tblstaff.firstname," ",tblstaff.lastname,",",tblstaff.staffid) as staff',
];

$tabName = 'kargo_settings';
$itemForm = 'item' . $tabName;

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'kargolist';

$ci = &get_instance();
$stok = $ci->input->get('stok', true);
$kargobayi = $ci->input->get('kargobayifilter', true);

//$ci->load->model('server_api_logs_model');
$ci->load->model('server_api_staff_model');


$where = [];
if(isset($stok) && !empty($stok)){
    if($stok == '1') {
        array_push($where, 'AND tblkargolist.urun_stok > 0');
    }else{
        array_push($where,'AND tblkargolist.urun_stok < 1');
    }
}
if(isset($kargobayi) && !empty($kargobayi)){
    array_push($where, 'AND tblkargolist.bayi_id = \''.$kargobayi.'\'');
}

$join = [
    'LEFT JOIN kargobayi ON kargobayi.id = ' . db_prefix() . 'kargolist.bayi_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'kargolist.staffid',
];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ["tblkargolist.id"]);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $aColumns[$i]=str_replace('kargobayi.bayi as bayi',"bayi",$aColumns[$i]);

        $aColumns[$i]=str_replace('CONCAT(tblstaff.firstname," ",tblstaff.lastname,",",tblstaff.staffid) as staff',"staff",$aColumns[$i]);
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'id') {
            $_data = '<div style="white-space: nowrap;"><input type="checkbox" id="' . $tabName . '-cbox-' . $aRow['id'] . '" data-value="' . $aRow['id'] . '" style="float: left" onchange="addRemoveToBatch(\'' . $tabName . '\',\'' . trim($aRow['id']) . '\');" />&nbsp;&nbsp;';
            $_data .= '&nbsp;' . $aRow['id'] . '&nbsp;&nbsp;&nbsp;</div>';
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
    if (has_permission('server_api', '', 'server_api_logs_delete')) {
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
