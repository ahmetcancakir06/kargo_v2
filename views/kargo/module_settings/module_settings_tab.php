<?php

//    ['name' => _l('id')],
$fields = array(
    ["name" => _l('id'), "th_attrs" => ["title" => ""]],
    // ["name" =>_l('perfex_module_name'), "th_attrs" => ["title" => ""]],
    ["name" => _l('settings_key'), "th_attrs" => ["title" => ""]],
    ["name" => _l('settings_value'), "th_attrs" => ["title" => ""]],
    ["name" => _l('description'), "th_attrs" => ["title" => ""]],
    ["name" => _l('ip'), "th_attrs" => ["title" => ""]],
    ["name" => _l('username'), "th_attrs" => ["title" => ""]],
    ["name" => _l('password'), "th_attrs" => ["title" => ""]],
    ["name" => _l('updated_at'), "th_attrs" => ["title" => ""]],
    ["name" => '', "th_attrs" => ["class" => "all text-right"]],
);
$tabName = 'server_api_module_settings';
$columnsDiv = 'tableColumnsDiv_' . $tabName;
$itemForm = 'item' . $tabName;

if (has_permission('server_api', '', $tabName)) {
?>
    <div role="tabpanel" class="tab-pane" id="<?php echo $tabName; ?>">
        <div class="row">
            <div class="col-md-12">

                <div class="clearfix"></div>
                <div id="<?php echo $columnsDiv; ?>" style="position: relative; margin: 1rem;border: 1px solid lightgray;border-radius: 0.5rem; padding-top: 1rem;display:none;">
                </div>
                <!-- <div class="col-md-4 col-sm-6 col-12" style="margin-top: 1rem;">
                <select id="selmodule_id<?php echo $tabName; ?>" class="form-control selmodule_id" data-id="selmodule_id">
                </select>
            </div> -->
                <div class="clearfix"></div>
                <hr class="hr-panel-heading" />
                <?php render_datatable($fields, $tabName, ['scroll-responsive'], ["style" => "margin-bottom:18rem !important;"]); ?>
            </div>
        </div>

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
                                <div class="row mb-2">
                                    <div class="col-sm-3">
                                        <input type="hidden" id="item1<?php echo $itemForm; ?>" data-id="id" value="">
                                    </div>

                                </div>

                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <label for="item3<?php echo $itemForm; ?>" class="control-label"><?php echo _l('settings_key'); ?><span style="color:red">&nbsp;*</span></label>
                                        <input class="form-control" id="item3<?php echo $itemForm; ?>" data-id="settings_key" value="">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <label for="item4<?php echo $itemForm; ?>" class="control-label"><?php echo _l('settings_value'); ?><span style="color:red">&nbsp;*</span></label>
                                        <input class="form-control" id="item4<?php echo $itemForm; ?>" data-id="settings_value" value="">
                                    </div>

                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <label for="item6<?php echo $itemForm; ?>" class="control-label"><?php echo _l('updated_at'); ?></label>
                                        <input class="form-control" readonly id="item6<?php echo $itemForm; ?>" data-id="updated_at" value="">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <label for="username<?php echo $itemForm; ?>" class="control-label"><?php echo _l('username'); ?></label>
                                        <select id="username<?php echo $itemForm; ?>" class="form-control" data-id="username">

                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="password<?php echo $itemForm; ?>" class="control-label"><?php echo _l('password'); ?></label>
                                        <input class="form-control" id="password<?php echo $itemForm; ?>" data-id="password" value="">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <label for="ip<?php echo $itemForm; ?>" class="control-label"><?php echo _l('ip'); ?></label>
                                        <input class="form-control" id="ip<?php echo $itemForm; ?>" data-id="ip" value="">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <div id="formMsg<?php echo $itemForm; ?>" class="alert alert-dismissible text-left" role="alert" style="display: none;">
                                <button type="button" class="close" aria-label="Close" onclick="resetFormState('<?php echo $itemForm; ?>');"><span aria-hidden="true">&times;</span></button>
                                <span id="formMsgText<?php echo $itemForm; ?>"></span>
                            </div>
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
            tabModuleSettings = '<?php echo $tabName; ?>';
            frmModuleSettings = '<?php echo $itemForm; ?>';

            // Onload function
            document.addEventListener('DOMContentLoaded', function() {

                sysUrls[tabModuleSettings] = {
                    itemGet: '<?php echo admin_url('server_api/server_api_module_settings/get'); ?>',
                    itemSave: '<?php echo admin_url('server_api/server_api_module_settings/add_update'); ?>',
                    itemDelete: '<?php echo admin_url('server_api/server_api_module_settings/delete_multiple'); ?>',
                    itemCheck: '<?php echo admin_url('server_api/server_api_module_settings/check'); ?>',
                    getServers: '<?php echo admin_url('server_api/server_api_module_settings/getServers'); ?>',
                    getModules: '<?php echo admin_url('server_api/server_api_module_settings/get_modules'); ?>',
                    StaffuserName: '<?php echo admin_url('server_api/server_api_module_settings/staffuserName'); ?>',
                };

                defaultValues[frmModuleSettings] = {
                    id: null,
                    perfex_module_name: null,
                    settings_key: null,
                    settings_value: null,
                    description: null,
                    updated_at: null,
                };

                sysTables[tabModuleSettings] = {
                    name: tabModuleSettings,
                    columnsDiv: '<?php echo $columnsDiv; ?>',
                    table: initDataTableSpecial('.table-' + tabModuleSettings, '<?php echo admin_url('server_api/server_api_module_settings/'); ?>', [], [], null, [0, 'asc']),
                    urlTableUpdade: '<?php echo admin_url('server_api/server_api_module_settings/'); ?>',
                    urlRecCount: '<?php echo admin_url('server_api/server_api_module_settings/get_last_update'); ?>',
                    last_update: 1,
                    rec_count: 1,
                    timer: null, //reloadData && setInterval(() => reloadData(tabModuleSettings), 3000),
                    gridBatch: [],
                };
                initAutoRefreshControl(tabModuleSettings);
                initColumns(tabModuleSettings, sysTables[tabModuleSettings].columnsDiv);
                addStandartButtonsToGrid(tabModuleSettings);

                sysTables[tabModuleSettings].table.on('draw', function() {
                    fillCheckBoxes(tabModuleSettings);
                });
                sysTables[tabModuleSettings].table.button().add(5, {
                    action: function(e, dt, button, config) {
                        showHideEl('<?php echo $columnsDiv; ?>');
                    },
                    text: '<?php echo _l('columns') ?>'
                });

                /*$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    //alert('previous active tab: ' + e.relatedTarget) // previous active tab
                    if (String(e.target).includes(tabModuleSettings)) {
                        fillSelectByElement(sysUrls[tabModuleSettings].getModules, 'selmodule_id' + tabModuleSettings, ['id', 'title'], true);
                    };
                });*/



                $("#selmodule_id" + tabModuleSettings).change(function() {
                    value = $("#selmodule_id" + tabModuleSettings + " option:selected").val();
                    sysTables[tabModuleSettings].urlTableUpdade = '<?php echo admin_url('server_api/server_api_module_settings/'); ?>' + '?module_id=' + value;
                    sysTables[tabModuleSettings].table.ajax.url(sysTables[tabModuleSettings].urlTableUpdade).load();
                });

            });

            function addModal<?php echo $itemForm; ?>() {
                formName = frmModuleSettings;
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

            function detailsModal<?php echo $itemForm; ?>(id) {
                formName = frmModuleSettings;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabModuleSettings].itemGet, {
                        id: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        staff = data.username;

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
                $.post(sysUrls[tabModuleSettings].StaffuserName, {
                        id: 'test'
                    })
                    .done(function(data) {
                        $('#username' + formName).empty();
                        data = JSON.parse(data);
                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        for (let i = 0; i < data.length; i++) {
                            if (data[i].email == staff) {
                                $("#username" + formName).append("<option value='" + data[i].email + "' selected>" + data[i].email+ "</option>");

                            } else {
                                $("#username" + formName).append("<option value='" + data[i].email + "'>" + data[i].email + "</option>");
                            }
                        }

                    });

            };

            function copyModal<?php echo $itemForm; ?>(id) {
                formName = frmModuleSettings;
                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabModuleSettings].itemGet, {
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
                if (!confirm(`<?php echo _l('delete_confirm'); ?>`)) {
                    return;
                }
                $.post(sysUrls[tabModuleSettings].itemDelete, {
                        ids: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            alert_float('success', 'Kayıt silindi', 5000);
                            reloadData(tabModuleSettings, true);
                        }
                    });
            };

            function deleteAll<?php echo $itemForm; ?>() {
                if (sysTables[tabModuleSettings].gridBatch.length <= 0) {
                    alert('<?php echo _l('record_delete_multiple_no_selection'); ?>')
                    return false;
                }
                if (!confirm(`<?php echo _l('record_delete_multiple_confirm'); ?>`)) {
                    return false;
                }
                $.post(sysUrls[tabModuleSettings].itemDelete, {
                        ids: sysTables[tabModuleSettings].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            reloadData(tabModuleSettings, true);
                            alert_float('success', 'Kayıtlar silindi', 5000);
                        }
                    });
            };


            async function <?php echo $itemForm; ?>Save() {
                let formName = frmModuleSettings
                let btnItemSave = $('#btnItemSaveformMsg' + formName);
                try {
                    resetFormState(formName);
                    btnItemSave.button('loading');
                    showFormMessage(formName, '<?php echo _l('saving'); ?>', msgType.info);
                    await sleep(100);
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
                    $.post(sysUrls[tabModuleSettings].itemSave, formData)
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
                                reloadData(tabModuleSettings, true);
                                showFormMessage(formName, '<?php echo _l('saved'); ?>', msgType.success, 3000);
                            }
                        }).error((jqXHR, textStatus, errorThrown) => {
                            if (errorThrown == 'Not Found') {
                                showFormMessage(formName, 'Url hatalı: ' + sysUrls[tabModuleSettings].itemSave, msgType.error);
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

            async function itemTestConn<?php echo $itemForm; ?>() {
                let formName = frmModuleSettings
                let btnItemSave = $('#btnItemSaveformMsg' + formName);
                try {
                    resetFormState();
                    btnItemSave.button('loading');
                    showFormMessage(formName, '<?php echo _l('sql_testing'); ?>', msgType.info);
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
                    $.post(sysUrls[tabModuleSettings].itemCheck, formData)
                        .done(function(data) {
                            btnItemSave.button('reset');
                            data = JSON.parse(data);
                            if (data["error"]) {
                                error = data["error"];
                                showFormMessage(formName, `<?php echo _l('error'); ?>`, msgType.error);
                                return false;
                            } else {
                                showFormMessage(formName, data["ok"], msgType.success, 3000);
                            }
                        }).error((jqXHR, textStatus, errorThrown) => {
                            if (errorThrown == 'Not Found') {
                                showFormMessage(formName, 'Url hatalı: ' + sysUrls[tabModuleSettings].itemCheck, msgType.error);
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

            // async function <?php echo $tabName; ?>resetErrorCount(id){
            //     $.post( sysUrls[tabModuleSettings].servicesSaveErrorCountUrl, { id: id,  error_count: 0})
            //     .done(function( data ) {
            //         data = JSON.parse(data);
            //         if (data["error"]) {
            //             alert_float("danger", "hata: " + data["error"], 3000);
            //         } else {
            //             alert_float("success", '<?php echo _l('saved') ?>', 3000);
            //         }
            //     });
            // }
        </script>
    </div>

<?php } else { ?>
    <div role="tabpanel" class="tab-pane" id="<?php echo $tabName; ?>">
        <?php echo _l('access_denied'); ?>
    </div>
<?php } ?>