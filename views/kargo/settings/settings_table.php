<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'urun_ismi',
    'productname',
    'urun_aciklama',
    'productexplanation',
    'urun_resim',
    'urun_stok',
    'urun_fiyat',
    'bayi_id',
    'tarih',
    'staffid',
];

$tabName = 'kargo_settings';
$itemForm = 'item' . $tabName;

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'kargolist';

$ci = &get_instance();
// $only_id_number = $ci->input->get('only_id_number', true);
//$ci->load->model('server_api_logs_model');
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
        } else if ($aColumns[$i] == 'who') {
            $getwho = $ci->server_api_staff_model->getWho($aRow['who']);
            if (isset($getwho)) {
                $_data = $getwho['firstname'] . " " . $getwho['lastname'];
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
