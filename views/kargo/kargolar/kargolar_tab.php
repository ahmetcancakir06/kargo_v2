<?php

//    ['name' => _l('id')],
$fields = array(
    ["name" => _l('id'), "th_attrs" => ["title" => ""]],
    ["name" => _l('musteri_ismi'), "th_attrs" => ["title" => "Hangi komut"]],
    ["name" => _l('adres'), "th_attrs" => ["title" => "Kim"]],
    ["name" => _l('mahalle'), "th_attrs" => ["title" => "Tarih"]],
    ["name" => _l('eyalet'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('posta_kodu'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('urun_ismi'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('musteri_telefon'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('tarih'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('gonderim'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('fatura_no'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('kargo_notu'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('kim_ekledi'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('durum'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('gonderilen_tarih'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('gonderen_kisi'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('teslim_edilen_tarih'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('teslim_eden_kisi'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('takip_numarasi'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('kargo_resmi'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('iade'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => '', "th_attrs" => ["class" => "all"]],

);
$tabName = 'kargo_kargolar';
$columnsDiv = 'tableColumnsDiv_' . $tabName;
$itemForm = 'item' . $tabName;
$ci = &get_instance();
$ci->load->model('kargo_kargolar_model');

if (has_permission('kargo', '', $tabName)) {
?>
    <div role="tabpanel" class="tab-pane" id="<?php echo $tabName; ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons">
                    <button type="button" class="btn btn-info pull-left display-block mr-1 mr-2" onclick="addModal<?php echo $itemForm; ?>();">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;

                        <?php echo _l('new_' . $tabName); ?>

                    </button>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4" style="display: block; padding-top: 2rem;padding-bottom: 2rem;">
                    <label for="stokfilter"><?php echo _l('stok_filter');?></label>
                    <select id="stokfilter" class="form-control">
                        <option value=""><?php echo _l('all');?></option>
                            <option value="1"><?php echo _l('stok_var');?></option>
                            <option value="-1"><?php echo _l('stok_yok');?></option>
                    </select>
                </div>
                <div class="col-md-4" style="display: block; padding-top: 2rem;padding-bottom: 2rem;">
                    <label for="kargobayifilter"><?php echo _l('kargobayifilter');?></label>
                    <select id="kargobayifilter" class="form-control">
                        <option value=""><?php echo _l('all');?></option>
                        <?php
                        $kargobayis=$ci->kargo_kargolar_model->getKargoBayi();
                        foreach ($kargobayis as $kargobayi) {
                        ?>
                        <option value="<?=$kargobayi['id']?>"><?= $kargobayi['bayi']?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div id="<?php echo $columnsDiv; ?>" style="position: relative; margin: 1rem;border: 1px solid lightgray;border-radius: 0.5rem; padding-top: 1rem;display:none;">
                </div>
                <hr class="hr-panel-heading" />
                <?php render_datatable($fields, $tabName, ['scroll-responsive'], ["style" => "margin-bottom:18rem !important;"]); ?>
            </div>
        </div>
        <!-- <div id="rightMenu" style="display:none;" class="row right-drawer">
        <div class="col-md-12">
            <button type="button" class="btn btn-info btn-xs display-block mb-2" onclick="showHideEl('rightMenu');">
                <?php echo _l('hide'); ?>
            </button>
        </div>
    </div> -->
        <div class="modal fade" id="mdl<?php echo $itemForm; ?>" tabindex="-1" role="dialog" aria-labelledby="mlabel<?php echo $itemForm; ?>" aria-hidden="true">
            <form id="frm<?php echo $itemForm; ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h5 class="modal-title" id="mlabel<?php echo $itemForm; ?>">
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <!--div class="form-group">
                                                        <label class="control-label">Müşteri İsmi</label>
                                                        <select class="form-control" onchange="musteribilgiler(this);"
                                                            id="musteri_ismi" name="musteri_ismi">
                                                            <option>Seçiniz</option>
                                                            <?php
                                    /*
                                        $musteriget=$db->prepare("SELECT * FROM tblclients");
                                        $musteriget->execute();
                                        $musteriresult=$musteriget->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($musteriresult as $ff){
                                            echo "<option value='".$ff['userid']."'>".$ff['company']."</option>";
                                        }
                                        */
                                    ?>
                                                        </select>
                                                    </div-->

                                    <label for="clientid" class="control-label"> <small class="req text-danger">* </small>Müşteri</label>
                                    <div class="dropdown bootstrap-select ajax-search bs3 open" style="width: 100%;"><select onchange="musteribilgiler(this);" id="clientid" name="musteri_ismi" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="Seçim yapılmadı" tabindex="-98" title="Seçin ve yazmaya başlayın" required>
                                            <option class="bs-title-option" value=""></option>
                                        </select>
                                    </div>


                                    <a style="display:none;" id="fatura_adresi" target="_blank">Faturayı Gör</a>
                                </div><!-- Col -->
                                <div class="col-sm-3">
                                    <div class="form-group">

                                        <label class="control-label">Eyalet</label>
                                        <input type="text" class="form-control" autocomplete="off" name="eyaletmodal1" id="eyaletmodal1" disabled>
                                        <input type="hidden" name="eyaletmodal" id="eyaletmodal">
                                    </div>


                                </div><!-- Col -->
                                <div class="col-sm-3">

                                    <div class="form-group">

                                        <label class="control-label">Mahalle</label>
                                        <input type="text" class="form-control" autocomplete="off" name="mahallemodal1" id="mahallemodal1" disabled>
                                        <input type="hidden" name="mahallemodal" id="mahallemodal">
                                    </div>

                                </div><!-- Col -->
                                <div class="col-sm-2">
                                    <div class="form-group">

                                        <label class="control-label">Posta Kodu</label>
                                        <input type="text" class="form-control" autocomplete="off" name="zipmodal1" id="zipmodal1" disabled>
                                        <input type="hidden" name="zipmodal" id="zipmodal">
                                    </div>

                                </div><!-- Col -->
                                <div class="col-sm-12">
                                    <div class="form-group">

                                        <label class="control-label">Adres</label>
                                        <textarea class="form-control" autocomplete="off" name="adresmodal1" id="adresmodal1" disabled></textarea>
                                        <input type="hidden" name="adresmodal" id="adresmodal">
                                    </div>

                                </div><!-- Col -->
                            </div><!-- Row -->
                            <br>
                            <hr>
                            <br>
                            <div class="row" id="kargourundiv">

                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-4">
                                </div>
                                <div class="col-sm-4" style="text-align: center;">
                                    <input type="button" value="Ürün Ekle" onclick="urunekle()" class="btn btn-primary" style="text-align: center;">
                                </div>
                                <div class="col-sm-4">
                                </div>
                            </div>
                            <hr>
                            <div class="row">

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input required class="form-check-input gonderim" type="radio" name="gonderim" id="ucretligonderim" value="Ücretli Gönderim">
                                        <label class="control-label" for="ucretligonderim">Ücretli
                                            Gönderim</label>

                                    </div>


                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input class="form-check-input gonderim" type="radio" name="gonderim" id="ucretsizgonderim" value="Ücretsiz Gönderim">
                                        <label class="control-label" for="ucretsizgonderim">Ücretsiz
                                            Gönderim</label>

                                    </div>


                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input class="form-check-input gonderim" type="radio" name="gonderim" id="degisim" value="Değişim">
                                        <label class="control-label" for="degisim">Ücretsiz
                                            Değişim</label>

                                    </div>


                                </div>


                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input required class="form-check-input durum" type="radio" name="durum" id="gonderimehazir" value="Göndermeye Hazır">
                                        <label class="control-label" for="gonderimehazir">Göndermeye
                                            Hazır</label>

                                    </div>


                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input class="form-check-input durum" type="radio" name="durum" id="gonderildi" value="Gönderildi">
                                        <label class="control-label" for="gonderildi">Gönderildi</label>

                                    </div>


                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input class="form-check-input durum" type="radio" name="durum" id="teslimedildi" value="Teslim Edildi">
                                        <label class="control-label" for="teslimedildi">Teslim
                                            Edildi</label>

                                    </div>


                                </div>
                                <div id="iadegizle" style="display:none;">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input class="form-check-input iadedurum" type="radio" name="iadedurum" id="cihaziadesibekliyoruz" value="Cihaz İadesi Bekliyoruz">
                                            <label class="control-label" for="cihaziadesibekliyoruz">Cihaz İadesi
                                                Bekliyoruz</label>

                                        </div>


                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input class="form-check-input iadedurum" type="radio" name="iadedurum" id="cihaziadesibeklemiyoruz" value="Cihaz İadesi Beklemiyoruz">
                                            <label class="control-label" for="cihaziadesibeklemiyoruz">Cihaz İadesi
                                                Beklemiyoruz</label>

                                        </div>


                                    </div>
                                    <div id="iadeaciklamagizle" style="display:none;">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label" for="iadeaciklama">Açıklama</label>
                                                <textarea class="form-control" autocomplete="off" name="iadeaciklama" id="iadeaciklama" rows="2" cols="50"></textarea>

                                            </div>


                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label" for="iademac">Mac</label>
                                                <input type="text" class="form-control" autocomplete="off" name="iademac" id="iademac">

                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8" style="display:none" id="takipdiv">
                                        <div class="form-group">
                                            <label class="control-label">Takip Numarası</label>
                                            <input type="text" class="form-control" autocomplete="off" name="takipnumarasi" id="takipnumarasi">

                                        </div>

                                    </div>
                                    <div class="col-sm-4" style="display:none" id="fotodiv">
                                        <div class="form-group">
                                            <label class="control-label">Kargo Fotoğraf Yükle</label>
                                            <input type="file" class="form-control" autocomplete="off" name="kargoresim" id="kargoresim">

                                        </div>

                                    </div>
                                    <div class="col-sm-12" style="display:none" id="faturanumara">
                                        <div class="form-group">
                                            <label class="control-label">Fatura Numarası</label>
                                            <!--input type="text" class="form-control" autocomplete="off" name="faturanumarasi" id="faturanumarasi"-->
                                            <select class="form-control" name="faturanumarasi" id="faturanumarasi">

                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">Kargo Notu - Ödeme ile alakali
                                                notunuz </label>
                                            <input required type="text" class="form-control" autocomplete="off" name="faturanot" id="faturanot">

                                        </div>


                                    </div>
                                </div>



                            </div>

                        </div>
                        <div class="modal-footer">
                            <div id="formMsg<?php echo $itemForm; ?>" class="alert alert-dismissible text-left" role="alert" style="display: none;">
                                <button type="button" class="close" aria-label="Close" onclick="resetFormState('<?php echo $itemForm; ?>');"><span aria-hidden="true">&times;</span></button>
                                <span id="formMsgText<?php echo $itemForm; ?>"></span>
                            </div>
                            <!-- <button type="button" class="btn btn-warning" onclick="itemTestConn<?php echo $itemForm; ?>();">
                        <?php echo _l('test_connection'); ?>
                    </button> -->
                            <button type="button" class="btn btn-light" data-dismiss="modal">
                                <?php echo _l('close'); ?>
                            </button>
                            <button type="button" id="btnItemSaveformMsg<?php echo $itemForm; ?>" name="btnItemSave" class="btn btn-primary submit" data-loading-text="<?php echo _l('saving'); ?>" onclick="<?php echo $itemForm; ?>Save();">
                                <?php echo _l('save'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script>
            tabKargolar = '<?php echo $tabName; ?>';
            frmKargolar = '<?php echo $itemForm; ?>';
            var stok_filter=0;
            var bayi_filter=0;
            var urunsayac = 0;

            // Onload function
            document.addEventListener('DOMContentLoaded', function() {

                sysUrls[tabKargolar] = {
                    itemGet: '<?php echo admin_url('kargo/kargo_kargolar/get'); ?>',
                    itemSave: '<?php echo admin_url('kargo/kargo_kargolar/add_update'); ?>',
                    itemDelete: '<?php echo admin_url('kargo/kargo_kargolar/delete_multiple'); ?>',
                    urunEkle:'<?php echo admin_url('kargo/kargo_kargolar/urun_ekle'); ?>',
                    getUrun:'<?php echo admin_url('kargo/kargo_kargolar/get_urun'); ?>',
                    getMusteriBilgi:'<?php echo admin_url('kargo/kargo_kargolar/get_musteri_bilgi'); ?>',
                };

                <?php
                echo "defaultValues[frmKargolar] = {\r\n";
                foreach ($ci->kargo_kargolar_model->fields as $field) {
                    echo '  ' . $field[0] . ':' . $field[1] . ",\r\n";
                };
                echo "};\r\n";
                ?>

                sysTables[tabKargolar] = {
                    name: tabKargolar,
                    columnsDiv: '<?php echo $columnsDiv; ?>',
                    table: initDataTableSpecial('.table-' + tabKargolar, '<?php echo admin_url('kargo/kargo_kargolar/'); ?>', [], [], null, [0, 'desc']),
                    urlTableUpdade: '<?php echo admin_url('kargo/kargo_kargolar/'); ?>',
                    urlRecCount: '<?php echo admin_url('kargo/kargo_kargolar/get_last_update'); ?>',
                    last_update: 1,
                    rec_count: 1,
                    timer: null, //reloadData && setInterval(() => reloadData(tabKargolar), 3000),
                    gridBatch: [],
                };
                initAutoRefreshControl(tabKargolar);
                initColumns(tabKargolar, sysTables[tabKargolar].columnsDiv);
                addStandartButtonsToGrid(tabKargolar);

                sysTables[tabKargolar].table.on('draw', function() {
                    fillCheckBoxes(tabKargolar);
                });
                var optionsay = 5;
                <?php if (has_permission('kargo', '', 'kargo_kargolar_deleteAll')) { ?>
                    sysTables[tabKargolar].table.button().add(optionsay, {
                        action: function(e, dt, button, config) {
                            <?php echo $tabName; ?>deleteAll();
                            //sysTables[tabKargolar].table.ajax.url(sysTables[tabKargolar].urlTableUpdate).load();
                            dt.ajax.reload();
                        },
                        text: '<?php echo _l('record_delete_multiple') ?>'
                    });
                    optionsay += 1;
                <?php } ?>
                sysTables[tabKargolar].table.button().add(optionsay, {
                    action: function(e, dt, button, config) {
                        showHideEl('<?php echo $columnsDiv; ?>');
                    },
                    text: '<?php echo _l('columns') ?>'
                });

                $("#stokfilter").change(function(){
                    value = $("#stokfilter option:selected" ).val();
                    stok_filter=value;
                    if(bayi_filter == 0) {
                        sysTables[tabKargolar].table.ajax.url('<?php echo admin_url('kargo/kargo_kargolar') ?>/?stok=' + value).load();
                    }else{
                        sysTables[tabKargolar].table.ajax.url('<?php echo admin_url('kargo/kargo_kargolar') ?>/?stok=' + value+'&kargobayifilter='+bayi_filter).load();
                    }
                });

                $("#kargobayifilter").change(function(){
                    value = $("#kargobayifilter option:selected" ).val();
                    bayi_filter=value;
                    if(stok_filter == 0) {
                        sysTables[tabKargolar].table.ajax.url('<?php echo admin_url('kargo/kargo_kargolar') ?>/?kargobayifilter=' + value).load();
                    }else{
                        sysTables[tabKargolar].table.ajax.url('<?php echo admin_url('kargo/kargo_kargolar') ?>/?stok='+stok_filter+'&kargobayifilter=' + value).load();
                    }
                });
                fillSelectByElement(sysUrls[tabKargolar].getCategories, 'selCategoryId', ['id', 'category_name'], false);


                $('.gonderimgnc').change(function() {
                    console.log('testss');
                    if (this.value == "Ücretli Gönderim") {
                        $('#faturanumaragnc').css("display", "block");
                    }
                    if (this.value == "Ücretsiz Gönderim") {
                        $('#faturanumaragnc').css("display", "none");
                        $('#faturanumarasignc').val("");
                    }
                    if (this.value == "Değişim") {
                        $('#faturanumaragnc').css("display", "block");
                    }
                });
                $('.durumgnc').change(function() {
                    if (this.value == "Gönderildi") {
                        $('#takipdivgnc').css("display", "block");
                        $('#fotodivgnc').css("display", "block");
                        $('#kargokonrol').show();
                    }
                    if (this.value == "Teslim Edildi") {
                        $('#takipdivgnc').css("display", "block");
                        $('#fotodivgnc').css("display", "block");
                        $('#kargokonrol').show();

                    }
                    if (this.value == "Göndermeye Hazır") {
                        $('#takipdivgnc').css("display", "none");
                        $('#fotodivgnc').css("display", "none");
                        $('#kargokonrol').hide();

                        $('#takipnumarasignc').val("");
                        $('#eskikargoresim').val("");
                    }

                });
                $('.durumgncdurum').change(function() {
                    if (this.value == "Gönderildi") {
                        $('#takipdivgncdurum').css("display", "block");
                        $('#fotodivgncdurum').css("display", "block");
                        $('#kargokonroldurum').show();
                    }
                    if (this.value == "Teslim Edildi") {
                        $('#takipdivgncdurum').css("display", "block");
                        $('#fotodivgncdurum').css("display", "block");
                        $('#kargokonroldurum').show();

                    }
                    if (this.value == "Göndermeye Hazır") {
                        $('#takipnumarasigncdurum').val("");
                        $('#kargoresimsrcgncdurum').attr("src", "");
                        $('#eskikargoresimdurum').val("");
                        $('#takipdivgncdurum').css("display", "none");
                        $('#fotodivgncdurum').css("display", "none");
                        $('#kargokonroldurum').hide();

                        $('#takipnumarasignc').val("");
                        $('#eskikargoresim').val("");
                    }

                });
                $('.iadedurumgnc').change(function() {
                    if (this.value == "Cihaz İadesi Bekliyoruz") {
                        $('#iadeaciklamagizlegnc').css("display", "block");
                        $('#iadeaciklamagnc').prop("required", true);
                    } else {
                        $('#iadeaciklamagizlegnc').css("display", "none");
                        $('#iadeaciklamagnc').prop("required", false);
                    }
                });
                $('.iadedurum').change(function() {
                    if (this.value == "Cihaz İadesi Bekliyoruz") {
                        $('#iadeaciklamagizle').css("display", "block");
                        $('#iadeaciklama').prop("required", true);
                        $('#iademac').prop("required", true);
                    } else {
                        $('#iadeaciklamagizle').css("display", "none");
                        $('#iadeaciklama').prop("required", false);
                        $('#iademac').prop("required", false);
                    }
                });
                $('.gonderim').change(function() {
                    console.log('testssadfasfs');
                    if (this.value == "Ücretli Gönderim") {
                        $('#faturanumara').css("display", "block");
                        $('#iadegizle').css("display", "block");
                        $('.iadedurum').prop("required", true);
                    }
                    if (this.value == "Ücretsiz Gönderim") {
                        $('#faturanumara').css("display", "none");
                        $('#iadegizle').css("display", "none");
                        $('.iadedurum').prop("required", false);

                    }
                    if (this.value == "Değişim") {
                        $('#faturanumara').css("display", "block");
                        $('#iadegizle').css("display", "block");
                        $('.iadedurum').prop("required", true);

                    }
                });
                $('.durum').change(function() {
                    if (this.value == "Gönderildi") {
                        $('#takipdiv').css("display", "block");
                        $('#fotodiv').css("display", "block");

                    }
                    if (this.value == "Teslim Edildi") {
                        $('#takipdiv').css("display", "block");
                        $('#fotodiv').css("display", "block");

                    }
                    if (this.value == "Göndermeye Hazır") {
                        $('#takipdiv').css("display", "none");
                        $('#fotodiv').css("display", "none");

                    }

                });
            });
            function dosyaOnizleme(input) {
                if (input.files && input.files[0]) {
                    var dosyaOkuyucu = new FileReader();
                    dosyaOkuyucu.onload = function (e) {
                        document.getElementById('onizleme').setAttribute('src', e.target.result);
                        document.getElementById('onizlemeinput').setAttribute('value', e.target.result);

                    };
                    dosyaOkuyucu.readAsDataURL(input.files[0]);
                }
            }

            function musteribilgiler(item) {
                var id = item.value;
                $.ajax({
                    url: sysUrls[tabKargolar].getMusteriBilgi,
                    method: 'post',
                    dataType: "json",

                    data: {
                        'id': id

                    },
                    success: function(results) {
                        console.log(results.shipping_state)
                        fatura_getAll(id);
                        if(results.shipping_state == ""){
                            alert_float("danger", "Fatura adresi bulunamadı normal adresi ile dolduruluyor");

                            $('#mahallemodal').val(results.state);
                            $('#eyaletmodal').val(results.city);
                            $('#zipmodal').val(results.zip);
                            $('#adresmodal').val(results.street);
                            $('#fatura_adresi').css("display", "block");
                            $('#fatura_adresi').attr("href", "../admin/clients/client/" + id + "?group=invoices");
                            $('#mahallemodal1').val(results.state);
                            $('#eyaletmodal1').val(results.city);
                            $('#zipmodal1').val(results.zip);
                            $('#adresmodal1').val(results.street);
                        }else {
                            $('#mahallemodal').val(results.shipping_state);
                            $('#eyaletmodal').val(results.shipping_city);
                            $('#zipmodal').val(results.shipping_zip);
                            $('#adresmodal').val(results.shipping_street);
                            $('#fatura_adresi').css("display", "block");
                            $('#fatura_adresi').attr("href", "../admin/clients/client/" + id + "?group=invoices");
                            $('#mahallemodal1').val(results.shipping_state);
                            $('#eyaletmodal1').val(results.shipping_city);
                            $('#zipmodal1').val(results.shipping_zip);
                            $('#adresmodal1').val(results.shipping_street);
                        }

                    }
                });
            }
            function fatura_getAll(musteri_id) {
                $('#faturanumarasi').empty();
                $('#faturanumarasi').append("<option value='"+musteri_id+"'>test</option>");
            }
            function addModal<?php echo $itemForm; ?>() {
                formName = frmKargolar;
                resetFormState(formName);
                $('#resim').val("");
                $('#onizlemeinput').val("");
                $('#onizleme').attr("src","");
                $("#mlabel" + formName).text("<?php echo _l('record_new'); ?>");
                jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                    //console.log(`i: ${i}, j: ${getDataId(j)}`);
                    setInputValue(j, defaultValues[formName][getDataId(j)]);
                });
                $.post(sysUrls[tabKargolar].getDiziler, {
                        id: 'test'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        for (let i = 0; i < data.length; i++) {
                            $("#diziler" + formName).append("<option value='" + data[i].id + "'>" + data[i].title + "</option>");
                            console.log(data[i].id);
                        }

                    });
                $('#mdl' + formName).modal({
                    backdrop: true
                })
            };
            function urun_aciklama_yazi(thiss) {
                var aciklama=thiss.value;
                $.ajax({
                    url: "https://api.mymemory.translated.net/get?q="+aciklama+"&langpair=tr|en", // JSON verisini döndüren PHP dosyasının yolunu buraya yazın
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#productexplanation'+frmKargolar).val(data.responseData.translatedText);
                    },
                    error: function() {
                        // AJAX isteği başarısız olduğunda çalışacak işlev
                        console.log("AJAX isteği başarısız oldu.");
                    }
                });
            }
            function urunekle() {
                alert('test');
                urunsayac += 1;
                $.ajax({
                    url: sysUrls[tabKargolar].urunEkle,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        'urunsayac': urunsayac

                    },
                    success: function(results) {
                        $('#kargourundiv').append(results.html);


                    }
                });
            }
            function urun_baslik_yazi(thiss) {
                var aciklama=thiss.value;
                $.ajax({
                    url: "https://api.mymemory.translated.net/get?q="+aciklama+"&langpair=tr|en", // JSON verisini döndüren PHP dosyasının yolunu buraya yazın
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#urun_ismi_en'+frmKargolar).val(data.responseData.translatedText);
                    },
                    error: function() {
                        // AJAX isteği başarısız olduğunda çalışacak işlev
                        console.log("AJAX isteği başarısız oldu.");
                    }
                });
            }
            function urunbilgiler(item) {
                var id = item.value;
                var rowid = item.id;
                console.log(rowid);
                var dataid = $('#' + rowid).data("id");
                console.log(dataid);
                $.ajax({
                    url: sysUrls[tabKargolar].getUrun,
                    method: 'post',
                    dataType: "json",

                    data: {
                        'id': id

                    },
                    success: function(results) {
                        console.log(results);
                        if (parseInt(results.urun_stok) > 0) {
                            $('#urun_stokmodal' + dataid).val(results.urun_stok);
                            $('#urun_fiyatmodal' + dataid).val(results.urun_fiyat);
                            $('#urun_aciklamamodal' + dataid).val(results.urun_aciklama);
                            $('#urun_stokmodal1' + dataid).val(results.urun_stok);
                            $('#urun_fiyatmodal1' + dataid).val(results.urun_fiyat);
                            $('#urunresimsrc' + dataid).attr("src", results.urun_resim);

                            $('#urun_aciklamamodal1' + dataid).val(results.urun_aciklama);
                        } else {
                            alert_float("danger", "ÜRÜN STOKTA YOK");
                        }
                    }
                });
            }


            function detailsModal<?php echo $itemForm; ?>(id) {
                formName = frmKargolar;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabKargolar].itemGet, {
                        id: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                            //console.log(`i: ${i}, j: ${getDataId(j)}`);
                            setInputValue(j, data[getDataId(j)]);
                        });
                        $('#mdl' + formName).modal({
                            backdrop: true
                        })
                    });
            };

            function copyModal<?php echo $itemForm; ?>(id) {
                formName = frmKargolar;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabKargolar].itemGet, {
                        id: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                            //console.log(`i: ${i}, j: ${getDataId(j)}`);
                            if (getDataId(j) == 'id') {
                                setInputValue(j, null);
                            } else {
                                setInputValue(j, data[getDataId(j)]);
                            }
                        });
                        $('#mdl' + formName).modal({
                            backdrop: true
                        })
                    });
            };



            function delete<?php echo $itemForm; ?>(id) {

                $.post(sysUrls[tabKargolar].itemDelete, {
                        ids: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            alert_float('success', 'Kayıt silindi', 5000);
                            reloadData(tabKargolar, true);
                        }
                    });
            };

            function deleteAll<?php echo $itemForm; ?>() {

                $.post(sysUrls[tabKargolar].itemDelete, {
                        ids: sysTables[tabKargolar].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            reloadData(tabKargolar, true);
                            alert_float('success', 'Kayıtlar silindi', 5000);
                        }
                    });
            };


            async function <?php echo $itemForm; ?>Save() {
                let formName = frmKargolar
                let btnItemSave = $('#btnItemSaveformMsg' + formName);
                console.log($('#onizlemeinput').val());

                try {
                    resetFormState(formName);
                    btnItemSave.button('loading');
                    showFormMessage(formName, '<?php echo _l('saving'); ?>', msgType.info);
                    //await sleep(3000);
                    let formData = {};
                    jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                        value = getInputValue(j) == '' ? null : getInputValue(j);
                        dataId = getDataId(j);
                        console.log(`dataId: ${dataId}, value: ${value}`);
                        if (dataId != undefined) {
                            //console.log(`dataId: ${dataId}, value: ${value}`);
                            if (typeof(value) == 'string' && value.trim() == '') {
                                value = null;
                            }
                            //console.log(`dataId: ${dataId} - Value: ${value}`);
                            formData[dataId] = value;
                        }
                    });
                    $.post(sysUrls[tabKargolar].itemSave, formData)
                        .done(function(data) {
                            btnItemSave.button('reset');
                            data = JSON.parse(data);
                            if (data["error"]) {
                                error = data["error"];
                                showFormMessage(formName, `<?php echo _l('error'); ?>`, msgType.error);
                                return false;
                            } else {
                                jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                                    //console.log(data);
                                    setInputValue(j, data[getDataId(j)]);
                                });
                                reloadData(tabKargolar, true);
                                showFormMessage(formName, '<?php echo _l('saved'); ?>', msgType.success, 3000);
                            }
                        }).error((jqXHR, textStatus, errorThrown) => {
                            if (errorThrown == 'Not Found') {
                                showFormMessage(formName, 'Url hatalı: ' + sysUrls[tabKargolar].itemSave, msgType.error);
                            } else {
                                showFormMessage(formName, textStatus + ': ' + errorThrown, msgType.error);
                            }
                            btnItemSave.button('reset');
                        });
                } catch (error) {
                    btnItemSave.button('reset');
                    console.log(`error: ${error}`);
                    showFormMessage(formName, `<?php echo _l('error'); ?>`, msgType.error);
                    return false;
                } finally {
                    btnItemSave.button('reset');
                    return false;
                }
            };



            function <?php echo $tabName; ?>deleteAll() {

                $.post(sysUrls[tabKargolar].itemDelete, {
                        ids: sysTables[tabKargolar].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            sysTables[tabKargolar].gridBatch = [];
                            reloadData(tabKargolar, true, true);
                            alert_float('success', 'Kayıtlar silindi', 5000);
                        }
                    });
            };
        </script>
    </div>
<?php } else { ?>
    <div role="tabpanel" class="tab-pane active" id="<?php echo $tabName; ?>">
        <?php echo _l('access_denied'); ?>
    </div>
<?php } ?>