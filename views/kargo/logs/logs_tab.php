<?php

//    ['name' => _l('id')],
$fields = array(
    ["name" => _l('id'), "th_attrs" => ["title" => ""]],
    ["name" => _l('commands_id'), "th_attrs" => ["title" => "Hangi komut"]],
    ["name" => _l('who'), "th_attrs" => ["title" => "Kim"]],
    ["name" => _l('date'), "th_attrs" => ["title" => "Tarih"]],
    ["name" => _l('details'), "th_attrs" => ["class" => "all", "title" => "Yapılan işlemler"]],
    ["name" => '', "th_attrs" => ["class" => "all"]],

);
$tabName = 'server_api_logs';
$columnsDiv = 'tableColumnsDiv_' . $tabName;
$itemForm = 'item' . $tabName;

if (has_permission('server_api', '', $tabName)) {
?>
    <div role="tabpanel" class="tab-pane " id="<?php echo $tabName; ?>">
        <div class="row">
            <div class="col-md-12">
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


        <script>
            tabLogs = '<?php echo $tabName; ?>';
            frmLogs = '<?php echo $itemForm; ?>';

            // Onload function
            document.addEventListener('DOMContentLoaded', function() {

                sysUrls[tabLogs] = {
                    itemGet: '<?php echo admin_url('server_api/server_api_logs/get'); ?>',
                    itemSave: '<?php echo admin_url('server_api/server_api_logs/add_update'); ?>',
                    itemDelete: '<?php echo admin_url('server_api/server_api_logs/delete_multiple'); ?>'
                };

                <?php
                $ci = &get_instance();
                $ci->load->model('server_api_logs_model');
                echo "defaultValues[frmLogs] = {\r\n";
                foreach ($ci->server_api_logs_model->fields as $field) {
                    echo '  ' . $field[0] . ':' . $field[1] . ",\r\n";
                };
                echo "};\r\n";
                ?>

                sysTables[tabLogs] = {
                    name: tabLogs,
                    columnsDiv: '<?php echo $columnsDiv; ?>',
                    table: initDataTableSpecial('.table-' + tabLogs, '<?php echo admin_url('server_api/server_api_logs/'); ?>', [], [], null, [0, 'desc']),
                    urlTableUpdade: '<?php echo admin_url('server_api/server_api_logs/'); ?>',
                    urlRecCount: '<?php echo admin_url('server_api/server_api_logs/get_last_update'); ?>',
                    last_update: 1,
                    rec_count: 1,
                    timer: null, //reloadData && setInterval(() => reloadData(tabLogs), 3000),
                    gridBatch: [],
                };
                initAutoRefreshControl(tabLogs);
                initColumns(tabLogs, sysTables[tabLogs].columnsDiv);
                addStandartButtonsToGrid(tabLogs);

                sysTables[tabLogs].table.on('draw', function() {
                    fillCheckBoxes(tabLogs);
                });
                var optionsay = 5;
                <?php if (has_permission('server_api', '', 'server_api_logs_deleteAll')) { ?>
                    sysTables[tabLogs].table.button().add(optionsay, {
                        action: function(e, dt, button, config) {
                            <?php echo $tabName; ?>deleteAll();
                            //sysTables[tabLogs].table.ajax.url(sysTables[tabLogs].urlTableUpdate).load();
                            dt.ajax.reload();
                        },
                        text: '<?php echo _l('record_delete_multiple') ?>'
                    });
                    optionsay += 1;
                <?php } ?>
                sysTables[tabLogs].table.button().add(optionsay, {
                    action: function(e, dt, button, config) {
                        showHideEl('<?php echo $columnsDiv; ?>');
                    },
                    text: '<?php echo _l('columns') ?>'
                });

                $("#selectOnly_id_number").change(function() {
                    value = $("#selectOnly_id_number option:selected").val();
                    sysTables[tabLogs].table.ajax.url('<?php echo admin_url('server_api/server_api_logs') ?>/?only_id_number=' + value).load();
                });
                fillSelectByElement(sysUrls[tabLogs].getCategories, 'selCategoryId', ['id', 'category_name'], false);
            });

            function addModal<?php echo $itemForm; ?>() {
                formName = frmLogs;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_new'); ?>");
                jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                    //console.log(`i: ${i}, j: ${getDataId(j)}`);
                    setInputValue(j, defaultValues[formName][getDataId(j)]);
                });
                $.post(sysUrls[tabLogs].getDiziler, {
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

            function detailsModal<?php echo $itemForm; ?>(id) {
                formName = frmLogs;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabLogs].itemGet, {
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
                formName = frmLogs;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabLogs].itemGet, {
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

                $.post(sysUrls[tabLogs].itemDelete, {
                        ids: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            alert_float('success', 'Kayıt silindi', 5000);
                            reloadData(tabLogs, true);
                        }
                    });
            };

            function deleteAll<?php echo $itemForm; ?>() {

                $.post(sysUrls[tabLogs].itemDelete, {
                        ids: sysTables[tabLogs].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            reloadData(tabLogs, true);
                            alert_float('success', 'Kayıtlar silindi', 5000);
                        }
                    });
            };


            async function <?php echo $itemForm; ?>Save() {
                let formName = frmLogs
                let btnItemSave = $('#btnItemSaveformMsg' + formName);
                try {
                    resetFormState(formName);
                    btnItemSave.button('loading');
                    showFormMessage(formName, '<?php echo _l('saving'); ?>', msgType.info);
                    //await sleep(3000);
                    let formData = {};
                    jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                        value = getInputValue(j) == '' ? null : getInputValue(j);
                        dataId = getDataId(j);
                        if (dataId != undefined) {
                            //console.log(`dataId: ${dataId}, value: ${value}`);
                            if (typeof(value) == 'string' && value.trim() == '') {
                                value = null;
                            }
                            //console.log(`dataId: ${dataId} - Value: ${value}`);
                            formData[dataId] = value;
                        }
                    });
                    $.post(sysUrls[tabLogs].itemSave, formData)
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
                                reloadData(tabLogs, true);
                                showFormMessage(formName, '<?php echo _l('saved'); ?>', msgType.success, 3000);
                            }
                        }).error((jqXHR, textStatus, errorThrown) => {
                            if (errorThrown == 'Not Found') {
                                showFormMessage(formName, 'Url hatalı: ' + sysUrls[tabLogs].itemSave, msgType.error);
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

                $.post(sysUrls[tabLogs].itemDelete, {
                        ids: sysTables[tabLogs].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            sysTables[tabLogs].gridBatch = [];
                            reloadData(tabLogs, true, true);
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