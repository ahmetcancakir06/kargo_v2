<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'kargo.id',
    'CONCAT(tblcontacts.firstname," ",tblcontacts.lastname) as musteri',
    'adres',
    'mahalle',
    'eyalet',
    'postakodu',
    'GROUP_CONCAT(CONCAT(tblkargolist.urun_ismi, ":", tblkargolist.id) SEPARATOR ",") as urunler',
    'CONCAT(tblcontacts.phonenumber," ",tblcontacts.secondphone," ",tblcontacts.thirdphone) as telefon',
    'tblkargo.tarih',
    'gonderim',
    'fatura_no',
    'fatura_not',
    'CONCAT(tblstaff.firstname," ",tblstaff.lastname) as staff',
    'tblkargo.durum',
    'gonderilenkargotarih',
    'CONCAT(tblstaff_gonderenkisi.firstname," ",tblstaff_gonderenkisi.lastname) as gonderen_kisi',
    'teslimtarih',
    'CONCAT(tblstaff_teslimkisi.firstname," ",tblstaff_teslimkisi.lastname) as teslim_kisi',
    'takip_numarası',
    'kargo_foto',
    'tblkargo_iade.durum'

];

$tabName = 'kargo_settings';
$itemForm = 'item' . $tabName;

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'kargo';

$ci = &get_instance();
$stok = $ci->input->get('stok', true);
$kargobayi = $ci->input->get('kargobayifilter', true);

//$ci->load->model('server_api_logs_model');
$ci->load->model('server_api_staff_model');


$where = [];
if(isset($stok) && !empty($stok)){
    if($stok == '1') {
        array_push($where, 'AND tblkargo.urun_stok > 0');
    }else{
        array_push($where,'AND tblkargo.urun_stok < 1');
    }
}
if(isset($kargobayi) && !empty($kargobayi)){
    array_push($where, 'AND tblkargo.bayi_id = \''.$kargobayi.'\'');
}

$join = [
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'kargo.staff_user_id',
    'LEFT JOIN ' . db_prefix() . 'contacts ON ' . db_prefix() . 'contacts.userid = ' . db_prefix() . 'kargo.musteri_id',
    'LEFT JOIN ' . db_prefix() . 'kargolist ON FIND_IN_SET(' . db_prefix() . 'kargolist.id,' . db_prefix() . 'kargo.urun_id) > 0',
    'LEFT JOIN ' . db_prefix() . 'kargo_iade ON ' . db_prefix() . 'kargo_iade.kargo_id = ' . db_prefix() . 'kargo.id',
    'LEFT JOIN tblstaff AS tblstaff_gonderenkisi ON tblstaff_gonderenkisi.staffid = tblkargo.gonderenkisi',
    'LEFT JOIN tblstaff AS tblstaff_teslimkisi ON tblstaff_teslimkisi.staffid = tblkargo.teslimkisi',


];

$ci->load->library(KARGO_MODULE_NAME . '/' . 'custom_data_table');

// WHERE (convert((SELECT CONCAT(tblcontacts.firstname," ",tblcontacts.lastname) USING utf8) LIKE '%tes%' ESCAPE '!')
// WHERE (CONVERT(CONCAT(tblcontacts.firstname, ' ', tblcontacts.lastname) USING utf8) LIKE '%tes%' ESCAPE '!')
$result = $ci->custom_data_table->data_tables_init_custom($aColumns, $sIndexColumn, $sTable, $join, $where, ["tblkargo.id"],"GROUP BY tblkargo.id,
  tblcontacts.firstname,
  tblcontacts.lastname,
  tblcontacts.phonenumber,
  tblcontacts.secondphone,
  tblcontacts.thirdphone,
  tblkargo_iade.durum");
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $aColumns[$i]=str_replace('CONCAT(tblcontacts.firstname," ",tblcontacts.lastname) as musteri',"musteri",$aColumns[$i]);
        $aColumns[$i]=str_replace('GROUP_CONCAT(CONCAT(tblkargolist.urun_ismi, ":", tblkargolist.id) SEPARATOR ",") as urunler',"urunler",$aColumns[$i]);
        $aColumns[$i]=str_replace('CONCAT(tblcontacts.phonenumber," ",tblcontacts.secondphone," ",tblcontacts.thirdphone) as telefon',"telefon",$aColumns[$i]);
        $aColumns[$i]=str_replace('CONCAT(tblstaff_gonderenkisi.firstname," ",tblstaff_gonderenkisi.lastname) as gonderen_kisi',"gonderen_kisi",$aColumns[$i]);
        $aColumns[$i]=str_replace('CONCAT(tblstaff_teslimkisi.firstname," ",tblstaff_teslimkisi.lastname) as teslim_kisi',"teslim_kisi",$aColumns[$i]);

        $aColumns[$i]=str_replace('CONCAT(tblstaff.firstname," ",tblstaff.lastname) as staff',"staff",$aColumns[$i]);
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'id') {
            $_data = '<div style="white-space: nowrap;"><input type="checkbox" id="' . $tabName . '-cbox-' . $aRow['id'] . '" data-value="' . $aRow['id'] . '" style="float: left" onchange="addRemoveToBatch(\'' . $tabName . '\',\'' . trim($aRow['id']) . '\');" />&nbsp;&nbsp;';
            $_data .= '&nbsp;' . $aRow['id'] . '&nbsp;&nbsp;&nbsp;</div>';
        }else if($aColumns[$i] == 'kargo_foto'){
            if(strlen($aRow['kargo_foto']) > 100){
                $_data="<img width='200px' src='".$aRow['kargo_foto']."'>";
            }else{
                $_data="<img width='200px' src='".admin_url('media').$aRow['kargo_foto']."'>";
            }
        }else if($aColumns[$i] == 'urunler'){
            $uruns=str_replace(",","<br>",$aRow['urunler']);
            $_data=str_replace(":"," Adet: ",$uruns);
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
