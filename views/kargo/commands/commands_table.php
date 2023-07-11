<?php

use function Clue\StreamFilter\append;

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'title',
    'commands',
    'in_system',
    'active',
];

$tabName = 'server_api_commands';
$itemForm = 'item' . $tabName;

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'server_api_commands';

$ci = &get_instance();
// $only_id_number = $ci->input->get('only_id_number', true);
$ci->load->model('server_api_commands_model');


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
        } else if ($aColumns[$i] == 'in_system') {
            if (has_permission('server_api', '', 'server_api_commands_status')) {
                $_data = "<select onchange='insystemchange".$itemForm."(this,".$aRow['id'].")'>";
                for ($sayac = 0; $sayac < 3; $sayac++) {
                    $yazar = [];
                    array_push($yazar,"Sistem Dışı");
                    array_push($yazar,"Sistem İçi");
                    array_push($yazar,"Her İkiside");

                    if ($sayac == $aRow['in_system']) {
                        $_data .= "<option value='" . $sayac . "' selected>".$yazar[$sayac]."</option>";
                    } else {
                        $_data .= "<option value='" . $sayac . "'>".$yazar[$sayac]."</option>";
                    }
                }
                $_data.="</select>";
            } else {
                $yaz = "";
                if ($aRow['in_system'] == "0") {
                    $yaz = "Sistem Dışı";
                } else if ($aRow['in_system'] == "1") {
                    $yaz = "Sistem İçi";
                } else if ($aRow['in_system'] == "2") {
                    $yaz = "Her ikiside";
                }
                $_data = $yaz;
            }
        } else if ($aColumns[$i] == 'active') {
            $activetext = "";
            if ($aRow['active'] == "1") {
                $activetext = "checked";
            }
            $_data = '<div class="onoffswitch"><input type="checkbox" onclick="changestatus' . $tabName . '(\'' . $aRow['id'] . '\')" name="onoffswitch" class="onoffswitch-checkbox change-status" id="status_' . $aRow['id'] . '" data-target="' . $aRow['id'] . '" ' . $activetext . '><label class="onoffswitch-label" for="status_' . $aRow['id'] . '"></label> </div>';
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
    if (has_permission('server_api', '', 'server_api_commands_edit')) {
        $actions .= '<li><a href="javascript:detailsModal' . $itemForm . '(' . $aRow['id'] . ')">' . _l('record_details') . '</a></li>';
        $actionbos = 0;
    }
    if (has_permission('server_api', '', 'server_api_commands_copy')) {
        $actions .= '<li><a href="javascript:copyModal' . $itemForm . '(' . $aRow['id'] . ')">' . _l('record_copy') . '</a></li>';
        $actionbos = 0;
    }
    if ($actionbos == 0) {
        $actions .= '<li role="separator" class="divider"></li>';
    }

    if (has_permission('server_api', '', 'server_api_commands_delete')) {
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
