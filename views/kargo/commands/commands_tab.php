<?php

//    ['name' => _l('id')],
$fields = array(
    ["name" => _l('id'), "th_attrs" => ["title" => ""]],
    ["name" => _l('title'), "th_attrs" => ["title" => "Açıklama"]],
    ["name" => _l('commands'), "th_attrs" => ["class" => "all", "title" => "Komut"]],
    ["name" => _l('in_system'), "th_attrs" => ["class" => "all", "title" => "Perfex modüllerinde mi kullanılacak"]],
    ["name" => _l('active'), "th_attrs" => ["class" => "all", "title" => "Aktif/Pasif"]],
    ["name" => '', "th_attrs" => ["class" => "all"]],
);
$tabName = 'server_api_commands';
$columnsDiv = 'tableColumnsDiv_' . $tabName;
$itemForm = 'item' . $tabName;
$batchForm = 'batch' . $tabName;

if (has_permission('server_api', '', $tabName)) {
?>
    <div role="tabpanel" class="tab-pane active" id="<?php echo $tabName; ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons">
                    <?php if (has_permission('server_api', '', $tabName . '_new')) { ?>

                        <button type="button" class="btn btn-info btn-xs pull-left display-block mr-1 mr-2" onclick="addModal<?php echo $itemForm; ?>();">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;

                            <?php echo _l('new_' . $tabName); ?>

                        </button>
                    <?php } ?>

                    <?php if (has_permission('server_api', '', $tabName . '_batch')) { ?>
                        <button type="button" class="btn btn-info btn-xs pull-left display-block mr-1 mb-2" onclick="batchAddModal<?php echo $batchForm; ?>();">
                            <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>&nbsp;
                            <?php echo _l('new_item_batch_process'); ?>
                        </button>
                    <?php } ?>
                   
                   
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
                <div class="modal-dialog modal-lg" role="document">
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
                                    <div class="col-sm-12">
                                        <label for="title<?php echo $itemForm; ?>" class="control-label"><?php echo _l('title'); ?><span style="color:red">&nbsp;*</span></label>
                                        <input class="form-control" id="title<?php echo $itemForm; ?>" required data-id="title" value="">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <label for="commands<?php echo $itemForm; ?>" class="control-label"><?php echo _l('commands'); ?><span style="color:red">&nbsp;*</span></label>
                                        <input class="form-control" id="commands<?php echo $itemForm; ?>" data-id="commands" required value="">
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

        <?php if (has_permission($tabName, '', $tabName . '_batch')) { ?>
            <div class="modal fade" id="mdl<?php echo $batchForm; ?>" tabindex="-1" role="dialog" aria-labelledby="mlabel<?php echo $batchForm; ?>" aria-hidden="true">
                <form id="frm<?php echo $batchForm; ?>" method="POST" enctype="multipart/form-data">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h5 class="modal-title" id="mlabel<?php echo $batchForm; ?>">
                                </h5>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4 mb-2">
                                            <label for="itemb1<?php echo $batchForm; ?>" class="control-label"><?php echo _l('batch_fields'); ?></label>
                                            <select id="itemb1<?php echo $batchForm; ?>" class="form-control">
                                            </select>
                                        </div>
                                        <div class="col-sm-8 mb-2">
                                            <label for="itembSearch<?php echo $batchForm; ?>" class="control-label"><?php echo _l('search') . "&nbsp;(" . _l('value_only_msg') . ")"; ?></label>
                                            <input class="form-control" id="itembSearch<?php echo $batchForm; ?>" value="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <label for="itemb2<?php echo $batchForm; ?>" class="control-label"><?php echo _l('value'); ?></label>
                                            <input class="form-control" id="itemb2<?php echo $batchForm; ?>" value="">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-12">
                                            <input type="checkbox" id="cboxBatchAll<?php echo $batchForm; ?>" checked />&nbsp;&nbsp;
                                            <label for="cboxBatchAll<?php echo $batchForm; ?>"><?php echo _l('batch_apply_to_selection'); ?></label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-10">
                                            <div class="panel panel-default">
                                                <!-- Default panel contents -->
                                                <div class="panel-heading"><?php echo _l('batch_predefined_values'); ?></div>
                                                <ul class="list-group">
                                                    <li class="list-group-item"><?php echo _l('null_value_desc'); ?></li>
                                                    <li class="list-group-item"><?php echo _l('yes'); ?>: 1</li>
                                                    <li class="list-group-item"><?php echo _l('no'); ?>: 0</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div id="formMsg<?php echo $batchForm; ?>" class="alert alert-dismissible text-left" role="alert" style="display: none;">
                                    <button type="button" class="close" aria-label="Close" onclick="resetFormState('<?php echo $batchForm; ?>');"><span aria-hidden="true">&times;</span></button>
                                    <span id="formMsgText<?php echo $batchForm; ?>"></span>
                                </div>
                                <button type="button" class="btn btn-light" data-dismiss="modal">
                                    <?php echo _l('close'); ?>
                                </button>
                                <!-- data-loading-text="<?php echo _l('saving'); ?>" -->
                                <button type="button" id="btnItemSaveformMsg<?php echo $batchForm; ?>" name="btnItemSave" class="btn btn-primary submit" onclick="batchUpdate<?php echo $batchForm; ?>();">
                                    <?php echo _l('save'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>
       
        <script>
            tabCommands = '<?php echo $tabName; ?>';
            frmCommands = '<?php echo $itemForm; ?>';
            frmCommandsBatch = '<?php echo $batchForm; ?>';

            // Onload function
            document.addEventListener('DOMContentLoaded', function() {

                sysUrls[tabCommands] = {
                    itemGet: '<?php echo admin_url('server_api/server_api_commands/get'); ?>',
                    itemSave: '<?php echo admin_url('server_api/server_api_commands/add_update'); ?>',
                    itemDelete: '<?php echo admin_url('server_api/server_api_commands/delete_multiple'); ?>',
                    batchUpdate: '<?php echo admin_url('server_api/server_api_commands/batch_update'); ?>',
                    updateActive:'<?php echo admin_url('server_api/server_api_commands/updateActive'); ?>',
                    updateInSystem:'<?php echo admin_url('server_api/server_api_commands/updateInSystem'); ?>',

                };

                <?php
                $ci = &get_instance();
                $ci->load->model('server_api_commands_model');
                echo "defaultValues[frmCommands] = {\r\n";
                foreach ($ci->server_api_commands_model->fields as $field) {
                    echo '  ' . $field[0] . ':' . $field[1] . ",\r\n";
                };
                echo "};\r\n";
                ?>

                sysTables[tabCommands] = {
                    name: tabCommands,
                    columnsDiv: '<?php echo $columnsDiv; ?>',
                    table: initDataTableSpecial('.table-' + tabCommands, '<?php echo admin_url('server_api/server_api_commands/'); ?>', [], [], null, [0, 'asc']),
                    urlTableUpdade: '<?php echo admin_url('server_api/server_api_commands/'); ?>',
                    urlRecCount: '<?php echo admin_url('server_api/server_api_commands/get_last_update'); ?>',
                    last_update: 1,
                    rec_count: 1,
                    timer: null, //reloadData && setInterval(() => reloadData(tabCommands), 3000),
                    gridBatch: [],
                };

                initAutoRefreshControl(tabCommands);
                initColumns(tabCommands, sysTables[tabCommands].columnsDiv);
                addStandartButtonsToGrid(tabCommands);

                sysTables[tabCommands].table.on('draw', function() {
                    fillCheckBoxes(tabCommands);
                });
                var optionsay = 5;
                <?php if (has_permission('server_api', '', 'server_api_commands_deleteAll')) { ?>
                    sysTables[tabCommands].table.button().add(optionsay, {
                        action: function(e, dt, button, config) {
                            <?php echo $tabName; ?>deleteAll();
                            //sysTables[tabCommands].table.ajax.url(sysTables[tabCommands].urlTableUpdate).load();
                            dt.ajax.reload();
                        },
                        text: '<?php echo _l('record_delete_multiple') ?>'
                    });
                    optionsay += 1;
                <?php }
                ?>

                sysTables[tabCommands].table.button().add(optionsay, {
                    action: function(e, dt, button, config) {
                        showHideEl('<?php echo $columnsDiv; ?>');
                    },
                    text: '<?php echo _l('columns') ?>'
                });
                $("#selectOnly_id_number").change(function() {
                    value = $("#selectOnly_id_number option:selected").val();
                    sysTables[tabCommands].table.ajax.url('<?php echo admin_url('server_api/server_api_commands') ?>/?only_id_number=' + value).load();
                });
                fillSelectByElement(sysUrls[tabCommands].getCategories, 'selCategoryId', ['id', 'category_name'], false);
            });

            

            function addModal<?php echo $itemForm; ?>() {
                formName = frmCommands;
                resetFormState(formName);
                
                $("#mlabel" + formName).text("<?php echo _l('record_new'); ?>");
                jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                    //console.log(`i: ${i}, j: ${getDataId(j)}`);
                    setInputValue(j, defaultValues[formName][getDataId(j)]);
                });
                
                $('#mdl' + formName).modal({
                    backdrop: true
                })

            };

            

            function changestatus<?php echo $tabName; ?>(id) {
                var status = $('#status_' + id).prop('checked') ? 1 : 0;
                $.post(sysUrls[tabCommands].updateActive, {
                        id: id,
                        status: status
                    })
                    .done(function(data) {
                        var hataara = JSON.parse(data);
                        if (hataara['error'] != undefined) {
                            alert("hata: " + hataara['error']);
                        }
                        reloadData(tabCommands, true);

                    });
            };

            function insystemchange<?php echo $itemForm;?>(change,id){
                $.post(sysUrls[tabCommands].updateInSystem, {
                        id: id,
                        value: change.value
                    })
                    .done(function(data) {
                        var hataara = JSON.parse(data);
                        if (hataara['error'] != undefined) {
                            alert("hata: " + hataara['error']);
                        }
                        reloadData(tabCommands, true);

                    });
            }

            function detailsModal<?php echo $itemForm; ?>(id) {
                formName = frmCommands;
                resetFormState(formName);
               
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabCommands].itemGet, {
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
                formName = frmCommands;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabCommands].itemGet, {
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

                $.post(sysUrls[tabCommands].itemDelete, {
                        ids: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            alert_float('success', 'Kayıt silindi', 5000);
                            reloadData(tabCommands, true);
                        }
                    });
            };

            function deleteAll<?php echo $itemForm; ?>() {

                $.post(sysUrls[tabCommands].itemDelete, {
                        ids: sysTables[tabCommands].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            reloadData(tabCommands, true);
                            alert_float('success', 'Kayıtlar silindi', 5000);
                        }
                    });
            };
           


            async function <?php echo $itemForm; ?>Save() {
                let formName = frmCommands;
                let btnItemSave = $('#btnItemSaveformMsg' + formName);

                if ($('#frm<?php echo $itemForm ?>').get(0).checkValidity() == false) {
                    console.log(`error: Lütfen * olanları boş bırakmayın`);
                    showFormMessage(formName, 'Lütfen * olanları boş bırakmayın', msgType.error, 3000);
                    setTimeout(() => {
                        btnItemSave.button('reset');
                    }, 1000);
                } else {

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
                        $.post(sysUrls[tabCommands].itemSave, formData)
                            .done(function(data) {
                                btnItemSave.button('reset');
                                data = JSON.parse(data);
                                if (data["error"]) {
                                    var errors=data["error"];
                                    errors=errors.replace("Duplicate entry","Aynı değerde");
                                    errors=errors.replace("for key 'channel_name'","Kanal id eklenmiş");
                                    errors=errors.replace("for key 'tv_series'","Dizi eklenmiş");

                                    
                                    error = errors;
                                    console.log(error);

                                    showFormMessage(formName, `<?php echo _l('error'); ?>`, msgType.error);
                                    return false;
                                } else {
                                    jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                                        //console.log(data);
                                        setInputValue(j, data[getDataId(j)]);
                                    });
                                    reloadData(tabCommands, true);
                                    showFormMessage(formName, '<?php echo _l('saved'); ?>', msgType.success, 3000);
                                }
                            }).error((jqXHR, textStatus, errorThrown) => {
                                if (errorThrown == 'Not Found') {
                                    showFormMessage(formName, 'Url hatalı: ' + sysUrls[tabCommands].itemSave, msgType.error);
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
                }

            };

            <?php if (has_permission($tabName, '', $tabName . '_batch')) { ?>

                function batchAddModal<?php echo $batchForm; ?>() {
                    let formName = frmCommandsBatch;
                    resetFormState(formName);
                    $('#btnItemSaveformMsg' + formName).text("<?php echo _l('new_item_batch_process'); ?>");
                    document.getElementById('itemb1' + formName).innerText = null;
                    for (let i = 0; i < Object.keys(defaultValues[frmCommands]).length; i++) {
                        if (Object.keys(defaultValues[frmCommands])[i] != 'id') {
                            $("#itemb1" + formName).append("<option value='" + Object.keys(defaultValues[frmCommands])[i] + "'>" + Object.keys(defaultValues[frmCommands])[i] + "</option>");
                        }
                    }
                    $('#mdl' + formName).modal({
                        backdrop: true
                    })
                };

                function batchUpdate<?php echo $batchForm; ?>() {
                    let formName = frmCommandsBatch;
                    let itemb1 = document.getElementById('itemb1' + formName);
                    let itemb2 = document.getElementById('itemb2' + formName);
                    let itembSearch = document.getElementById('itembSearch' + formName);
                    let cboxBatchAll = document.getElementById('cboxBatchAll' + formName);
                    let btnItemSave = $('#btnItemSaveformMsg' + formName);
                    btnItemSave.prop('disabled', true);
                    try {
                        ids = '';
                        if (cboxBatchAll.checked) {
                            if (sysTables[tabCommands].gridBatch.length <= 0) {
                                //btnItemSave.button('reset');
                                showFormMessage(formName, `<?php echo _l('batch_update_no_selection'); ?>`, msgType.error, 3000);
                                btnItemSave.prop('disabled', false);
                                btnItemSave.button('reset');
                                return false;
                            }
                            ids = sysTables[tabCommands].gridBatch.join(',')
                        }
                        //console.log({ ids: ids,  field: itemb1.value, value: itemb2.value, search: itembSearch.value});
                        $.post(sysUrls[tabCommands].batchUpdate, {
                                ids: ids,
                                field: itemb1.value,
                                value: itemb2.value,
                                search: itembSearch.value
                            })
                            .done(function(data) {
                                data = JSON.parse(data);
                                if (data["error"]) {
                                    error = data["error"];
                                    showFormMessage(formName, `<?php echo _l('error'); ?>`, msgType.error);
                                    btnItemSave.prop('disabled', false);
                                    return false;
                                } else {
                                    // console.log(data);
                                    showFormMessage(formName, data["ok"], msgType.success, 5000);
                                    reloadData(tabCommands, true);
                                }
                            }).error((jqXHR, textStatus, errorThrown) => {
                                if (errorThrown == 'Not Found') {
                                    showFormMessage(formName, 'Url hatalı: ' + sysUrls[tabCommands].batchUpdate, msgType.error);
                                } else {
                                    showFormMessage(formName, textStatus + ': ' + errorThrown, msgType.error);
                                }
                                btnItemSave.button('reset');
                            });
                    } catch (error) {
                        console.log(`error: ${error}`);
                        showFormMessage(formName, `<?php echo _l('error'); ?>`, msgType.error);
                        btnItemSave.prop('disabled', false);
                        return false;
                    } finally {
                        btnItemSave.prop('disabled', false);
                        return false;
                    }
                }
            <?php } ?>

            function <?php echo $tabName; ?>deleteAll() {
                if (sysTables[tabCommands].gridBatch.length <= 0) {
                    alert('<?php echo _l('record_delete_multiple_no_selection'); ?>')
                    return false;
                }
                if (!confirm(`<?php echo _l('record_delete_multiple_confirm'); ?>`)) {
                    return false;
                }
                $.post(sysUrls[tabCommands].itemDelete, {
                        ids: sysTables[tabCommands].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            sysTables[tabCommands].gridBatch = [];
                            reloadData(tabCommands, true, true);
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