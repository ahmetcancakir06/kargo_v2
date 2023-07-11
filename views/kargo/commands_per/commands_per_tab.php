<?php

//    ['name' => _l('id')],
$fields = array(
    ["name" => _l('id'), "th_attrs" => ["title" => ""]],
    ["name" => _l('commands_id'), "th_attrs" => ["title" => "Hangi komut"]],
    ["name" => _l('which_server'), "th_attrs" => ["title" => "Hangi sunucu"]],
    ["name" => _l('module_name'), "th_attrs" => ["title" => "Modül İsmi"]],
    ["name" => _l('staff_user_id'), "th_attrs" => ["title" => "Kime"]],
    ["name" => _l('ip_address'), "th_attrs" => ["title" => "İp adres"]],
    ["name" => _l('api_key'), "th_attrs" => ["class" => "all", "title" => "Api anahtarı"]],
    ["name" => _l('allowed'), "th_attrs" => ["class" => "all", "title" => "İzin Verildimi"]],
    ["name" => '', "th_attrs" => ["class" => "all"]],
);
$tabName = 'server_api_commands_per';
$columnsDiv = 'tableColumnsDiv_' . $tabName;
$itemForm = 'item' . $tabName;
$batchForm = 'batch' . $tabName;
if (has_permission('server_api', '', $tabName)) {
?>
    <div role="tabpanel" class="tab-pane " id="<?php echo $tabName; ?>">
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
                    <?php if (has_permission('server_api', '', $tabName . '_test')) { ?>

                        <button type="button" class="btn btn-info btn-xs pull-left display-block mr-1 mr-2" onclick="testapi<?php echo $itemForm; ?>();">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;

                            <?php echo _l('testapi' . $tabName); ?>

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
                                        <label for="commands_id<?php echo $itemForm; ?>" class="control-label"><?php echo _l('commands_id'); ?><span style="color:red">&nbsp;*</span></label>
                                        <!--input class="form-control" id="commands_id<?php echo $itemForm; ?>" required data-id="commands_id" value=""-->
                                        <select class="form-control" data-id="commands_id" id="commands_id<?php echo $itemForm; ?>" onchange="commands_idschange<?php echo $itemForm; ?>(this)" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <label for="which_server<?php echo $itemForm; ?>" class="control-label"><?php echo _l('which_server'); ?><span style="color:red">&nbsp;*</span></label>
                                        <select id="which_server<?php echo $itemForm; ?>" class="form-control" data-id="which_server" required>

                                        </select>

                                    </div>
                                    <div class="col-sm-6">
                                        <label for="allowed<?php echo $itemForm; ?>" class="control-label"><?php echo _l('allowed'); ?><span style="color:red">&nbsp;*</span></label>
                                        <select id="allowed<?php echo $itemForm; ?>" class="form-control" data-id="allowed">

                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2" id="in_system" style="display:none">
                                    <div class="col-sm-6">
                                        <label for="staff_user_id<?php echo $itemForm; ?>" class="control-label"><?php echo _l('staff_user_id'); ?><span style="color:red">&nbsp;*</span></label>
                                        <select id="staff_user_id<?php echo $itemForm; ?>" class="form-control selectpicker" multiple data-live-search="true"  data-width="100%" data-id="staff_user_id">

                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="module_name<?php echo $itemForm; ?>" class="control-label"><?php echo _l('module_name'); ?><span style="color:red">&nbsp;*</span></label>
                                        <select id="module_name<?php echo $itemForm; ?>" class="form-control selectpicker" multiple data-live-search="true"  data-width="100%" data-id="module_name">

                                        </select>

                                    </div>

                                </div>
                                <div class="row mb-2" id="out_system" style="display:none">
                                    <div class="col-sm-6">
                                        <label for="ip_address<?php echo $itemForm; ?>" class="control-label"><?php echo _l('ip_address'); ?><span style="color:red">&nbsp;*</span></label>
                                        <input class="form-control" id="ip_address<?php echo $itemForm; ?>" data-id="ip_address" value="">
                                    </div>
                                    <div class="col-sm-6" id="api_keyinput">
                                        <label for="api_key<?php echo $itemForm; ?>" class="control-label"><?php echo _l('api_key'); ?><span style="color:red">&nbsp;*</span></label>
                                        <input class="form-control" id="api_key<?php echo $itemForm; ?>" data-id="api_key" value="">
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
            tabCommadsPer = '<?php echo $tabName; ?>';
            frmCommadsPer = '<?php echo $itemForm; ?>';
            frmCommadsPerBatch = '<?php echo $batchForm; ?>';

            // Onload function
            document.addEventListener('DOMContentLoaded', function() {

                sysUrls[tabCommadsPer] = {
                    itemGet: '<?php echo admin_url('server_api/server_api_commands_per/get'); ?>',
                    itemSave: '<?php echo admin_url('server_api/server_api_commands_per/add_update'); ?>',
                    itemDelete: '<?php echo admin_url('server_api/server_api_commands_per/delete_multiple'); ?>',
                    getServers: '<?php echo admin_url('server_api/server_api_commands_per/getServers'); ?>',
                    getCommandsIds: '<?php echo admin_url('server_api/server_api_commands_per/getCommandsIds'); ?>',
                    getCommandsId: '<?php echo admin_url('server_api/server_api_commands_per/getCommandsId'); ?>',
                    getAllModules: '<?php echo admin_url('server_api/server_api_commands_per/getAllModules'); ?>',
                    getModules: '<?php echo admin_url('server_api/server_api_commands_per/getModules'); ?>',
                    testAPI: '<?php echo admin_url('server_api/server_api_commands/requestAPI'); ?>',
                    getServerCommand: '<?php echo admin_url('server_api/server_api_commands/get'); ?>',
                    staffusers: '<?php echo admin_url('server_api/server_api_commands_per/staffusers'); ?>',
                };

                <?php
                $ci = &get_instance();
                $ci->load->model('server_api_commands_per_model');
                echo "defaultValues[frmCommadsPer] = {\r\n";
                foreach ($ci->server_api_commands_per_model->fields as $field) {
                    echo '  ' . $field[0] . ':' . $field[1] . ",\r\n";
                };
                echo "};\r\n";
                ?>

                sysTables[tabCommadsPer] = {
                    name: tabCommadsPer,
                    columnsDiv: '<?php echo $columnsDiv; ?>',
                    table: initDataTableSpecial('.table-' + tabCommadsPer, '<?php echo admin_url('server_api/server_api_commands_per/'); ?>', [], [], null, [0, 'desc']),
                    urlTableUpdade: '<?php echo admin_url('server_api/server_api_commands_per/'); ?>',
                    urlRecCount: '<?php echo admin_url('server_api/server_api_commands_per/get_last_update'); ?>',
                    last_update: 1,
                    rec_count: 1,
                    timer: null, //reloadData && setInterval(() => reloadData(tabCommadsPer), 3000),
                    gridBatch: [],
                };

                initAutoRefreshControl(tabCommadsPer);
                initColumns(tabCommadsPer, sysTables[tabCommadsPer].columnsDiv);
                addStandartButtonsToGrid(tabCommadsPer);

                sysTables[tabCommadsPer].table.on('draw', function() {
                    fillCheckBoxes(tabCommadsPer);
                });
                var optionsay = 5;
                <?php if (has_permission('server_api', '', 'server_api_commands_per_deleteAll')) { ?>

                    sysTables[tabCommadsPer].table.button().add(optionsay, {
                        action: function(e, dt, button, config) {
                            <?php echo $tabName; ?>deleteAll();
                            //sysTables[tabCommadsPer].table.ajax.url(sysTables[tabCommadsPer].urlTableUpdate).load();
                            dt.ajax.reload();
                        },
                        text: '<?php echo _l('record_delete_multiple') ?>'
                    });
                    optionsay += 1;
                <?php } ?>
                sysTables[tabCommadsPer].table.button().add(optionsay, {
                    action: function(e, dt, button, config) {
                        showHideEl('<?php echo $columnsDiv; ?>');
                    },
                    text: '<?php echo _l('columns') ?>'
                });

                $("#selectOnly_id_number").change(function() {
                    value = $("#selectOnly_id_number option:selected").val();
                    sysTables[tabCommadsPer].table.ajax.url('<?php echo admin_url('server_api/server_api_commands_per') ?>/?only_id_number=' + value).load();
                });
                fillSelectByElement(sysUrls[tabCommadsPer].getCategories, 'selCategoryId', ['id', 'category_name'], false);
            });

            function commands_idschange<?php echo $itemForm; ?>(get) {
                $.post(sysUrls[tabCommadsPer].getCommandsId, {
                        id: get.value
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        console.log(data);
                        if (data.in_system == 0) {
                            $('#in_system').css("display", "none");
                            $('#out_system').css("display", "block");
                        } else if (data.in_system == 1) {
                            $('#in_system').css("display", "block");
                            $('#out_system').css("display", "none");
                        } else if (data.in_system == 2) {
                            console.log("loo");
                            $('#in_system').css("display", "block");
                            $('#out_system').css("display", "block");
                        } else {
                            $('#in_system').css("display", "none");
                            $('#out_system').css("display", "none");

                        }


                    });
            }

            function testapi<?php echo $itemForm; ?>() {

                $.post(sysUrls[tabCommadsPer].testAPI, {
                        id: '1',
                        module_id: '1',
                        staff: '1',
                        degisken: 'test',
                    })
                    .done(function(data) {
                        var hataara = JSON.parse(data);
                        if (hataara['error'] != undefined) {
                            alert_float('danger', "hata: " + hataara["error"]);
                        }
                        reloadData(tabCommands, true);

                    });
            };

            function addModal<?php echo $itemForm; ?>() {
                $('#staff_user_id<?php echo $itemForm; ?>').attr("multiple",true);
                $('#module_name<?php echo $itemForm; ?>').attr("multiple",true);
                formName = frmCommadsPer;
                $("#which_server" + formName).empty();

                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_new'); ?>");
                jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                    //console.log(`i: ${i}, j: ${getDataId(j)}`);
                    setInputValue(j, defaultValues[formName][getDataId(j)]);
                });
                $('#allowed' + formName).empty();

                $("#allowed" + formName).append("<option value='0'>Kapalı</option>");
                $("#allowed" + formName).append("<option value='1' selected>Aktif</option>");

                $.post(sysUrls[tabCommadsPer].getServers, {
                        id: 'test'
                    })
                    .done(function(data) {

                        data = JSON.parse(data);
                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        for (let i = 0; i < data.length; i++) {
                            $("#which_server" + formName).append("<option value='" + data[i].id + "'>" + data[i].title + "</option>");
                            console.log(data[i].id);
                        }

                    });

                $.post(sysUrls[tabCommadsPer].getCommandsIds, {
                        id: 'test'
                    })
                    .done(function(data) {
                        $('#commands_id' + formName).empty();
                        data = JSON.parse(data);
                        $('#commands_id' + formName).append("<option value=''></option>");

                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        for (let i = 0; i < data.length; i++) {
                            $("#commands_id" + formName).append("<option value='" + data[i].id + "'>" + data[i].title + "</option>");
                            console.log(data[i].id);
                        }

                    });
                $.post(sysUrls[tabCommadsPer].getAllModules, {
                        id: 'test'
                    })
                    .done(function(data) {
                        $('#module_name' + formName).empty();
                        $('#module_name' + formName).selectpicker('refresh');

                        data = JSON.parse(data);

                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        for (let i = 0; i < data.length; i++) {
                            $("#module_name" + formName).append("<option value='" + data[i].id + "'>" + data[i].module_name + "</option>");
                            $('#module_name' + formName).selectpicker('refresh');

                            console.log(data[i].id);
                        }

                    });
                $.post(sysUrls[tabCommadsPer].staffusers, {
                        id: 'test'
                    })
                    .done(function(data) {
                        console.log("loo");
                        $('#staff_user_id' + formName).empty();
                        $('#staff_user_id'+formName).selectpicker('refresh');

                        data = JSON.parse(data);
                        console.log(data);
                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        for (let i = 0; i < data.length; i++) {
                            $("#staff_user_id" + formName).append("<option value='" + data[i].staffid + "'>" + data[i].firstname + " " + data[i].lastname + "</option>");
                            $('#staff_user_id'+formName).selectpicker('refresh');
                        }

                    });
                $('#api_keyinput').css('display', 'none');
                $('#mdl' + formName).modal({
                    backdrop: true
                })
            };

            function detailsModal<?php echo $itemForm; ?>(id) {


                formName = frmCommadsPer;
                $("#which_server" + formName).empty();
                $('#api_keyinput').css('display', 'block');

                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabCommadsPer].itemGet, {
                        id: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        commandsid = data.commands_id;
                        servers = data.which_server;
                        modulename = data.module_name;
                        modulesAr=modulename.split(",");
                        staff = data.staff_user_id;
                        stafAr=staff.split(",");
                        $('#allowed' + formName).empty();
                        if (data.allowed == "1") {
                            $("#allowed" + formName).append("<option value='0'>Kapalı</option>");
                            $("#allowed" + formName).append("<option value='1' selected>Aktif</option>");
                        } else {
                            $("#allowed" + formName).append("<option value='0' selected>Kapalı</option>");
                            $("#allowed" + formName).append("<option value='1'>Aktif</option>");
                        }
                        $.post(sysUrls[tabCommadsPer].getServerCommand, {
                                id: id
                            })
                            .done(function(data) {
                                data = JSON.parse(data);
                                $('#in_system').css("display", "none");
                                $('#out_system').css("display", "none");
                                if (data.in_system == "1") {
                                    $('#in_system').css("display", "block");
                                    $('#out_system').css("display", "none");
                                } else if (data.in_system == "2") {
                                    $('#in_system').css("display", "none");
                                    $('#out_system').css("display", "block");
                                } else {
                                    $('#in_system').css("display", "block");
                                    $('#out_system').css("display", "block");
                                }


                            });
                        if (data.module_name == null) {
                            $('#in_system').css("display", "none");
                            $('#out_system').css("display", "block");
                        } else {
                            $('#in_system').css("display", "block");
                            $('#out_system').css("display", "none");
                        }

                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                            //console.log(`i: ${i}, j: ${getDataId(j)}`);
                            setInputValue(j, data[getDataId(j)]);
                        });
                        /*    */
                        $.post(sysUrls[tabCommadsPer].getServers, {
                                id: 'test'
                            })
                            .done(function(data) {
                                $('#which_server' + formName).empty();

                                data = JSON.parse(data);
                                if (data["error"] != undefined) {
                                    alert("hata: " + data["error"]);
                                    return false;
                                }
                                for (let i = 0; i < data.length; i++) {

                                    if (data[i].id == servers) {
                                        $("#which_server" + formName).append("<option value='" + data[i].id + "' selected>" + data[i].title + "</option>");

                                    } else {
                                        $("#which_server" + formName).append("<option value='" + data[i].id + "'>" + data[i].title + "</option>");
                                    }
                                }

                            });
                        $.post(sysUrls[tabCommadsPer].getCommandsIds, {
                                id: 'test'
                            })
                            .done(function(data) {
                                $('#commands_id' + formName).empty();
                                data = JSON.parse(data);
                                if (data["error"] != undefined) {
                                    alert("hata: " + data["error"]);
                                    return false;
                                }
                                for (let i = 0; i < data.length; i++) {
                                    if (data[i].id == commandsid) {
                                        $("#commands_id" + formName).append("<option value='" + data[i].id + "' selected> " + data[i].title + "</option>");
                                    } else {
                                        $("#commands_id" + formName).append("<option value='" + data[i].id + "'>" + data[i].title + "</option>");

                                    }
                                }

                            });


                        $.post(sysUrls[tabCommadsPer].getAllModules, {
                                id: 'test'
                            })
                            .done(function(data) {
                                $('#module_name' + formName).empty();

                                $('#module_name' + formName).selectpicker('refresh');

                                data = JSON.parse(data);

                                if (data["error"] != undefined) {
                                    alert("hata: " + data["error"]);
                                    return false;
                                }
                                for (let i = 0; i < data.length; i++) {
                                    if ($.inArray(data[i].id,modulesAr) != -1) {
                                        $("#module_name" + formName).append("<option value='" + data[i].id + "' selected>" + data[i].module_name + "</option>");
                                        $('#module_name' + formName).selectpicker('refresh');

                                    } else {
                                        $("#module_name" + formName).append("<option value='" + data[i].id + "'>" + data[i].module_name + "</option>");
                                        $('#module_name' + formName).selectpicker('refresh');

                                    }
                                }

                            });
                        $.post(sysUrls[tabCommadsPer].staffusers, {
                                id: 'test'
                            })
                            .done(function(data) {
                                $('#staff_user_id' + formName).empty();
                                $('#staff_user_id'+formName).selectpicker('refresh');

                                data = JSON.parse(data);
                                if (data["error"] != undefined) {
                                    alert("hata: " + data["error"]);
                                    return false;
                                }

                                for (let i = 0; i < data.length; i++) {
                                    if ($.inArray(data[i].staffid,stafAr) != -1) {
                                        $("#staff_user_id" + formName).append("<option value='" + data[i].staffid + "' selected>" + data[i].firstname + " " + data[i].lastname + "</option>");
                                        $('#staff_user_id'+formName).selectpicker('refresh');

                                    } else {
                                        $("#staff_user_id" + formName).append("<option value='" + data[i].staffid + "'>" + data[i].firstname + " " + data[i].lastname + "</option>");
                                        $('#staff_user_id'+formName).selectpicker('refresh');

                                    }
                                }
                                $('#staff_user_id'+formName).selectpicker('refresh');

                            });
                        /*    */
                        $('#mdl' + formName).modal({
                            backdrop: true
                        })
                    });

            };

            function copyModal<?php echo $itemForm; ?>(id) {
                formName = frmCommadsPer;
                $("#which_server" + formName).empty();
                $('#api_keyinput').css('display', 'none');

                resetFormState(formName);
                $("#mlabel" + formName).text("<?php echo _l('record_edit'); ?>");
                $.post(sysUrls[tabCommadsPer].itemGet, {
                        id: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        commandsid = data.commands_id;
                        servers = data.which_server;
                        modulename = data.module_name;
                        staff = data.staff_user_id;
                        $('#allowed' + formName).empty();
                        if (data.allowed == "1") {
                            $("#allowed" + formName).append("<option value='0'>Kapalı</option>");
                            $("#allowed" + formName).append("<option value='1' selected>Aktif</option>");
                        } else {
                            $("#allowed" + formName).append("<option value='0' selected>Kapalı</option>");
                            $("#allowed" + formName).append("<option value='1'>Aktif</option>");
                        }
                        $.post(sysUrls[tabCommadsPer].getServerCommand, {
                                id: id
                            })
                            .done(function(data) {
                                data = JSON.parse(data);
                                $('#in_system').css("display", "none");
                                $('#out_system').css("display", "none");
                                if (data.in_system == "1") {
                                    $('#in_system').css("display", "block");
                                    $('#out_system').css("display", "none");
                                } else if (data.in_system == "2") {
                                    $('#in_system').css("display", "none");
                                    $('#out_system').css("display", "block");
                                } else {
                                    $('#in_system').css("display", "block");
                                    $('#out_system').css("display", "block");
                                }


                            });
                        if (data.module_name == null) {
                            $('#in_system').css("display", "none");
                            $('#out_system').css("display", "block");
                        } else {
                            $('#in_system').css("display", "block");
                            $('#out_system').css("display", "none");
                        }

                        if (data["error"] != undefined) {
                            alert("hata: " + data["error"]);
                            return false;
                        }
                        jQuery.each(jQuery('#frm' + formName)[0].elements, function(i, j) {
                            if (getDataId(j) == 'id') {
                                setInputValue(j, null);
                            } else {
                                setInputValue(j, data[getDataId(j)]);
                            }
                        });
                        /*    */
                        $.post(sysUrls[tabCommadsPer].getServers, {
                                id: 'test'
                            })
                            .done(function(data) {
                                $('#which_server' + formName).empty();

                                data = JSON.parse(data);
                                if (data["error"] != undefined) {
                                    alert("hata: " + data["error"]);
                                    return false;
                                }
                                for (let i = 0; i < data.length; i++) {
                                    if (data[i].id == servers) {
                                        $("#which_server" + formName).append("<option value='" + data[i].id + "' selected>" + data[i].title + "</option>");

                                    } else {
                                        $("#which_server" + formName).append("<option value='" + data[i].id + "'>" + data[i].title + "</option>");
                                    }
                                }

                            });
                        $.post(sysUrls[tabCommadsPer].getCommandsIds, {
                                id: 'test'
                            })
                            .done(function(data) {
                                $('#commands_id' + formName).empty();
                                data = JSON.parse(data);
                                if (data["error"] != undefined) {
                                    alert("hata: " + data["error"]);
                                    return false;
                                }
                                for (let i = 0; i < data.length; i++) {
                                    if (data[i].id == commandsid) {
                                        $("#commands_id" + formName).append("<option value='" + data[i].id + "' selected> " + data[i].title + "</option>");
                                    } else {
                                        $("#commands_id" + formName).append("<option value='" + data[i].id + "'>" + data[i].title + "</option>");

                                    }
                                }

                            });


                        $.post(sysUrls[tabCommadsPer].getAllModules, {
                                id: 'test'
                            })
                            .done(function(data) {
                                $('#module_name' + formName).empty();
                                $('#module_name' + formName).append("<option value=''></option>");
                                $('#module_name' + formName).selectpicker('refresh');

                                data = JSON.parse(data);

                                if (data["error"] != undefined) {
                                    alert("hata: " + data["error"]);
                                    return false;
                                }
                                for (let i = 0; i < data.length; i++) {
                                    if (data[i].id == modulename) {
                                        $("#module_name" + formName).append("<option value='" + data[i].id + "' selected>" + data[i].module_name + "</option>");
                                        $('#module_name' + formName).selectpicker('refresh');

                                    } else {
                                        $("#module_name" + formName).append("<option value='" + data[i].id + "'>" + data[i].module_name + "</option>");
                                        $('#module_name' + formName).selectpicker('refresh');

                                    }
                                }

                            });
                        $.post(sysUrls[tabCommadsPer].staffusers, {
                                id: 'test'
                            })
                            .done(function(data) {
                                $('#staff_user_id' + formName).empty();
                                $('#staff_user_id'+formName).selectpicker('refresh');

                                data = JSON.parse(data);
                                if (data["error"] != undefined) {
                                    alert("hata: " + data["error"]);
                                    return false;
                                }
                                for (let i = 0; i < data.length; i++) {
                                    if (data[i].id == staff) {
                                        $("#staff_user_id" + formName).append("<option value='" + data[i].staffid + "' selected>" + data[i].firstname + " " + data[i].lastname + "</option>");
                                        $('#staff_user_id'+formName).selectpicker('refresh');

                                    } else {
                                        $("#staff_user_id" + formName).append("<option value='" + data[i].staffid + "'>" + data[i].firstname + " " + data[i].lastname + "</option>");
                                        $('#staff_user_id'+formName).selectpicker('refresh');

                                    }
                                }

                            });
                        /*    */
                        $('#mdl' + formName).modal({
                            backdrop: true
                        })
                    });
               
            };

            function delete<?php echo $itemForm; ?>(id) {
                if (!confirm(`<?php echo _l('delete_confirm'); ?>`)) {
                    return;
                }
                $.post(sysUrls[tabCommadsPer].itemDelete, {
                        ids: id
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            alert_float('success', 'Kayıt silindi', 5000);
                            reloadData(tabCommadsPer, true);
                        }
                    });
            };

            function deleteAll<?php echo $itemForm; ?>() {
                if (sysTables[tabCommadsPer].gridBatch.length <= 0) {
                    alert('<?php echo _l('record_delete_multiple_no_selection'); ?>')
                    return false;
                }
                if (!confirm(`<?php echo _l('record_delete_multiple_confirm'); ?>`)) {
                    return false;
                }
                $.post(sysUrls[tabCommadsPer].itemDelete, {
                        ids: sysTables[tabCommadsPer].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            reloadData(tabCommadsPer, true);
                            alert_float('success', 'Kayıtlar silindi', 5000);
                        }
                    });
            };


            async function <?php echo $itemForm; ?>Save() {
                console.log("Staff:"+$('#staff_user_id<?php echo $itemForm; ?>').val());
                let formName = frmCommadsPer
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
                        console.log("Data id:"+dataId);
                        console.log("Value:"+value);
                        if (dataId != undefined) {
                            //console.log(`dataId: ${dataId}, value: ${value}`);
                            if (typeof(value) == 'string' && value.trim() == '') {
                                value = null;
                            }
                            if(dataId == "staff_user_id"){
                                value=$('#staff_user_id<?php echo $itemForm; ?>').val();
                            }
                            if(dataId == "module_name"){
                                value=$('#module_name<?php echo $itemForm; ?>').val();
                            }
                            //console.log(`dataId: ${dataId} - Value: ${value}`);
                            formData[dataId] = value;
                        }
                    });
                    console.log("Form:"+formData);
                    $.post(sysUrls[tabCommadsPer].itemSave, formData)
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
                                reloadData(tabCommadsPer, true);
                                showFormMessage(formName, '<?php echo _l('saved'); ?>', msgType.success, 3000);
                            }
                        }).error((jqXHR, textStatus, errorThrown) => {
                            if (errorThrown == 'Not Found') {
                                showFormMessage(formName, 'Url hatalı: ' + sysUrls[tabCommadsPer].itemSave, msgType.error);
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

            <?php if (has_permission($tabName, '', $tabName . '_batch')) { ?>

                function batchAddModal<?php echo $batchForm; ?>() {
                    let formName = frmCommadsPerBatch;
                    resetFormState(formName);
                    $('#btnItemSaveformMsg' + formName).text("<?php echo _l('new_item_batch_process'); ?>");
                    document.getElementById('itemb1' + formName).innerText = null;
                    for (let i = 0; i < Object.keys(defaultValues[frmCommadsPer]).length; i++) {
                        if (Object.keys(defaultValues[frmCommadsPer])[i] != 'id') {
                            $("#itemb1" + formName).append("<option value='" + Object.keys(defaultValues[frmCommadsPer])[i] + "'>" + Object.keys(defaultValues[frmCommadsPer])[i] + "</option>");
                        }
                    }
                    $('#mdl' + formName).modal({
                        backdrop: true
                    })
                };

                function batchUpdate<?php echo $batchForm; ?>() {
                    let formName = frmCommadsPerBatch;
                    let itemb1 = document.getElementById('itemb1' + formName);
                    let itemb2 = document.getElementById('itemb2' + formName);
                    let itembSearch = document.getElementById('itembSearch' + formName);
                    let cboxBatchAll = document.getElementById('cboxBatchAll' + formName);
                    let btnItemSave = $('#btnItemSaveformMsg' + formName);
                    btnItemSave.prop('disabled', true);
                    try {
                        ids = '';
                        if (cboxBatchAll.checked) {
                            if (sysTables[tabCommadsPer].gridBatch.length <= 0) {
                                //btnItemSave.button('reset');
                                showFormMessage(formName, `<?php echo _l('batch_update_no_selection'); ?>`, msgType.error, 3000);
                                btnItemSave.prop('disabled', false);
                                btnItemSave.button('reset');
                                return false;
                            }
                            ids = sysTables[tabCommadsPer].gridBatch.join(',')
                        }
                        //console.log({ ids: ids,  field: itemb1.value, value: itemb2.value, search: itembSearch.value});
                        $.post(sysUrls[tabCommadsPer].batchUpdate, {
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
                                    reloadData(tabCommadsPer, true);
                                }
                            }).error((jqXHR, textStatus, errorThrown) => {
                                if (errorThrown == 'Not Found') {
                                    showFormMessage(formName, 'Url hatalı: ' + sysUrls[tabCommadsPer].batchUpdate, msgType.error);
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
                if (sysTables[tabCommadsPer].gridBatch.length <= 0) {
                    alert('<?php echo _l('record_delete_multiple_no_selection'); ?>')
                    return false;
                }
                if (!confirm(`<?php echo _l('record_delete_multiple_confirm'); ?>`)) {
                    return false;
                }
                $.post(sysUrls[tabCommadsPer].itemDelete, {
                        ids: sysTables[tabCommadsPer].gridBatch.join(',')
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data["error"]) {
                            alert_float('danger', "hata: " + data["error"]);
                        } else {
                            sysTables[tabCommadsPer].gridBatch = [];
                            reloadData(tabCommadsPer, true, true);
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