<?php

//    ['name' => _l('id')],
$fields = array(
    ["name" => _l('id'), "th_attrs" => ["title" => ""]],
    ["name" => _l('urun_ismi'), "th_attrs" => ["title" => "Hangi komut"]],
    ["name" => _l('product_name'), "th_attrs" => ["title" => "Kim"]],
    ["name" => _l('urun_aciklama'), "th_attrs" => ["title" => "Tarih"]],
    ["name" => _l('product_explanation'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('urun_resim'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('urun_stok'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('urun_fiyat'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('bayi_id'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('tarih'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => _l('kim_ekledi'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => '', "th_attrs" => ["class" => "all"]],

);
$tabName = 'kargo_settings';
$columnsDiv = 'tableColumnsDiv_' . $tabName;
$itemForm = 'item' . $tabName;
$ci = &get_instance();
$ci->load->model('kargo_settings_model');

if (has_permission('kargo', '', $tabName)) {
?>
    <div role="tabpanel" class="tab-pane active" id="<?php echo $tabName; ?>">
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
                        $kargobayis=$ci->kargo_settings_model->getKargoBayi();
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
                            <div class="form-group">
                                <input type="hidden" id="id<?php echo $itemForm; ?>" data-id="id" value="">

                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <label for="urun_ismi<?php echo $itemForm; ?>" class="control-label"><?php echo _l('urun_ismi_tr'); ?><span style="color:red">&nbsp;*</span></label>
                                        <!--input class="form-control" id="commands_id<?php echo $itemForm; ?>" required data-id="commands_id" value=""-->
                                        <input class="form-control" onchange="urun_baslik_yazi(this)" id="urun_ismi<?php echo $itemForm; ?>" data-id="urun_ismi" value="">

                                    </div>
                                    <div class="col-sm-6">
                                        <label for="urun_ismi_en<?php echo $itemForm; ?>" class="control-label"><?php echo _l('urun_ismi_en'); ?><span style="color:red">&nbsp;*</span></label>
                                        <!--input class="form-control" id="commands_id<?php echo $itemForm; ?>" required data-id="commands_id" value=""-->
                                        <input class="form-control"  id="urun_ismi_en<?php echo $itemForm; ?>" data-id="productname" value="">

                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <label for="urun_aciklama<?php echo $itemForm; ?>" class="control-label"><?php echo _l('urun_aciklama'); ?><span style="color:red">&nbsp;*</span></label>
                                        <!--input class="form-control" id="commands_id<?php echo $itemForm; ?>" required data-id="commands_id" value=""-->
                                        <textarea class="form-control" onchange="urun_aciklama_yazi(this)" id="urun_aciklama<?php echo $itemForm; ?>" data-id="urun_aciklama"></textarea>

                                    </div>
                                    <div class="col-sm-6">
                                        <label for="productexplanation<?php echo $itemForm; ?>" class="control-label"><?php echo _l('productexplanation'); ?><span style="color:red">&nbsp;*</span></label>
                                        <!--input class="form-control" id="commands_id<?php echo $itemForm; ?>" required data-id="commands_id" value=""-->
                                        <textarea class="form-control" id="productexplanation<?php echo $itemForm; ?>" data-id="productexplanation" ></textarea>

                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-sm-3">
                                        <label for="stok<?php echo $itemForm; ?>" class="control-label"><?php echo _l('stok'); ?><span style="color:red">&nbsp;*</span></label>
                                        <!--input class="form-control" id="commands_id<?php echo $itemForm; ?>" required data-id="commands_id" value=""-->
                                        <input  class="form-control"  id="stok<?php echo $itemForm; ?>" data-id="urun_stok" value="">

                                    </div>
                                    <div class="col-sm-3">
                                        <label for="fiyat<?php echo $itemForm; ?>" class="control-label"><?php echo _l('fiyat'); ?><span style="color:red">&nbsp;*</span></label>
                                        <!--input class="form-control" id="commands_id<?php echo $itemForm; ?>" required data-id="commands_id" value=""-->
                                        <input  class="form-control"  id="fiyat<?php echo $itemForm; ?>" data-id="urun_fiyat" value="">

                                    </div>
                                    <div class="col-sm-6">
                                        <label for="bayi<?php echo $itemForm; ?>" class="control-label"><?php echo _l('bayi'); ?><span style="color:red">&nbsp;*</span></label>
                                        <select id="bayi<?php echo $itemForm; ?>" class="form-control" data-id="bayi_id">
                                            <?php
                                            foreach ($kargobayis as $kargobayi) {
                                                ?>
                                                <option value="<?=$kargobayi['id']?>"><?= $kargobayi['bayi']?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <input type="file" class="form-control" name="resim" data-id="resim" id="resim" accept="image/jpeg, image/png, image/jpg" onchange="dosyaOnizleme(this);" />
                                    </div>
                                    <div class="col-sm-6">
                                        <img id="onizleme" data-id="fakeimage" name="fakeimage" alt="Resim önizleme" />
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
            tabKargo = '<?php echo $tabName; ?>';
            frmKargo = '<?php echo $itemForm; ?>';
            var stok_filter=0;
            var bayi_filter=0;
            // Onload function
            document.addEventListener('DOMContentLoaded', function() {

                sysUrls[tabKargo] = {
                    itemGet: '<?php echo admin_url('kargo/kargo_settings/get'); ?>',
                    itemSave: '<?php echo admin_url('kargo/kargo_settings/add_update'); ?>',
                    itemDelete: '<?php echo admin_url('kargo/kargo_settings/delete_multiple'); ?>'
                };

                <?php
                echo "defaultValues[frmKargo] = {\r\n";
                foreach ($ci->kargo_settings_model->fields as $field) {
                    echo '  ' . $field[0] . ':' . $field[1] . ",\r\n";
                };
                echo "};\r\n";
                ?>

                sysTables[tabKargo] = {
                    name: tabKargo,
                    columnsDiv: '<?php echo $columnsDiv; ?>',
                    table: initDataTableSpecial('.table-' + tabKargo, '<?php echo admin_url('kargo/kargo_settings/'); ?>', [], [], null, [0, 'desc']),
                    urlTableUpdade: '<?php echo admin_url('kargo/kargo_settings/'); ?>',
                    urlRecCount: '<?php echo admin_url('kargo/kargo_settings/get_last_update'); ?>',
                    last_update: 1,
                    rec_count: 1,
                    timer: null, //reloadData && setInterval(() => reloadData(tabKargo), 3000),
                    gridBatch: [],
                };
                initAutoRefreshControl(tabKargo);
                initColumns(tabKargo, sysTables[tabKargo].columnsDiv);
                addStandartButtonsToGrid(tabKargo);

                sysTables[tabKargo].table.on('draw', function() {
                    fillCheckBoxes(tabKargo);
                });
                var optionsay = 5;
                <?php if (has_permission('kargo', '', 'kargo_settings_deleteAll')) { ?>
                    sysTables[tabKargo].table.button().add(optionsay, {
                        action: function(e, dt, button, config) {
                            <?php echo $tabName; ?>deleteAll();
                            //sysTables[tabKargo].table.ajax.url(sysTables[tabKargo].urlTableUpdate).load();
                            dt.ajax.reload();
                        },
                        text: '<?php echo _l('record_delete_multiple') ?>'
                    });
                    optionsay += 1;
                <?php } ?>
                sysTables[tabKargo].table.button().add(optionsay, {
                    action: function(e, dt, button, config) {
                        showHideEl('<?php echo $columnsDiv; ?>');
                    },
                    text: '<?php echo _l('columns') ?>'
                });

                $("#stokfilter").change(function(){
                    value = $("#stokfilter option:selected" ).val();
                    stok_filter=value;
                    if(bayi_filter == 0) {
                        sysTables[tabKargo].table.ajax.url('<?php echo admin_url('kargo/kargo_settings') ?>/?stok=' + value).load();
                    }else{
                        sysTables[tabKargo].table.ajax.url('<?php echo admin_url('kargo/kargo_settings') ?>/?stok=' + value+'&kargobayifilter='+bayi_filter).load();
                    }
                });

                $("#kargobayifilter").change(function(){
                    value = $("#kargobayifilter option:selected" ).val();
                    bayi_filter=value;
                    if(stok_filter == 0) {
                        sysTables[tabKargo].table.ajax.url('<?php echo admin_url('kargo/kargo_settings') ?>/?kargobayifilter=' + value).load();
                    }else{
                        sysTables[tabKargo].table.ajax.url('<?php echo admin_url('kargo/kargo_settings') ?>/?stok='+stok_filter+'&kargobayifilter=' + value).load();
                    }
                });
                fillSelectByElement(sysUrls[tabKargo].getCategories, 'selCategoryId', ['id', 'category_name'], false);
            });
            function dosyaOnizleme(input) {
                if (input.files && input.files[0]) {
                    var dosyaOkuyucu = new FileReader();
                    dosyaOkuyucu.onload = function (e) {
                        document.getElementById('onizleme').setAttribute('src', e.target.result);
                    };
                    dosyaOkuyucu.readAsDataURL(input.files[0]);
                }
            }
            function addModal<?php echo $itemForm; ?>() {
                formName = frmKargo;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_new'); ?>");
                jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                    //console.log(`i: ${i}, j: ${getDataId(j)}`);
                    setInputValue(j, defaultValues[formName][getDataId(j)]);
                });
                $.post(sysUrls[tabKargo].getDiziler, {
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
                        $('#productexplanation'+frmKargo).val(data.responseData.translatedText);
                    },
                    error: function() {
                        // AJAX isteği başarısız olduğunda çalışacak işlev
                        console.log("AJAX isteği başarısız oldu.");
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
                        $('#urun_ismi_en'+frmKargo).val(data.responseData.translatedText);
                    },
                    error: function() {
                        // AJAX isteği başarısız olduğunda çalışacak işlev
                        console.log("AJAX isteği başarısız oldu.");
                    }
                });
            }
            function detailsModal<?php echo $itemForm; ?>(id) {
                formName = frmKargo;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabKargo].itemGet, {
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
                formName = frmKargo;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabKargo].itemGet, {
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

                $.post(sysUrls[tabKargo].itemDelete, {
                        ids: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            alert_float('success', 'Kayıt silindi', 5000);
                            reloadData(tabKargo, true);
                        }
                    });
            };

            function deleteAll<?php echo $itemForm; ?>() {

                $.post(sysUrls[tabKargo].itemDelete, {
                        ids: sysTables[tabKargo].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            reloadData(tabKargo, true);
                            alert_float('success', 'Kayıtlar silindi', 5000);
                        }
                    });
            };


            async function <?php echo $itemForm; ?>Save() {
                let formName = frmKargo
                let btnItemSave = $('#btnItemSaveformMsg' + formName);
                console.log($('#resim').val());

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
                    $.post(sysUrls[tabKargo].itemSave, formData)
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
                                reloadData(tabKargo, true);
                                showFormMessage(formName, '<?php echo _l('saved'); ?>', msgType.success, 3000);
                            }
                        }).error((jqXHR, textStatus, errorThrown) => {
                            if (errorThrown == 'Not Found') {
                                showFormMessage(formName, 'Url hatalı: ' + sysUrls[tabKargo].itemSave, msgType.error);
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

                $.post(sysUrls[tabKargo].itemDelete, {
                        ids: sysTables[tabKargo].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            sysTables[tabKargo].gridBatch = [];
                            reloadData(tabKargo, true, true);
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