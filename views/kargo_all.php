<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .data-hide {
        display: none;
    }

    .data-hide:hover {
        display: block;
    }

    .myDIV:hover+.data-hide {
        display: block;
    }

    .mb-2 {
        margin-bottom: .5rem;
    }

    .mr-2 {
        margin-right: 2rem;
    }

    .mr-1 {
        margin-right: 1rem;
    }

    div.undo {
        position: fixed;
        top: 1rem;
        left: 35%;
    }

    .bottom-auto {
        bottom: auto !important;
    }

    .right-drawer {
        position: fixed;
        left: auto;
        right: 0;
        top: 0;
        bottom: 0;
        width: 32rem;
        height: 100%;
        z-index: 110;
        background-color: white;
        box-shadow: 0px 0px 8px -1px #000000;
        padding: 2rem;
        overflow-x: hidden;
        overflow-y: scroll;
        transition: all 2s;
    }
    #onizleme {
        max-width: 100%;
        max-height: 300px;
    }
</style>
<script>
    defaultValues = [];
    sysTables = [];
    sysUrls = [];
    hiddenColumns = [];
    pageForms = [];

    class msgType {
        static success = 'success';
        static info = 'info';
        static warning = 'warning';
        static error = 'danger';
    };
</script>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <ul id="myTabs" class="nav nav-tabs" role="tablist">
                            <?php
                            $gor = 0;
                            if (has_permission('kargo', '', 'kargo_commands')) {
                            ?>
                                <li role="presentation" class="active">
                                    <a href="#kargo_settings" aria-controls="kargo_settings" role="tab" data-toggle="tab"><?php echo _l('kargo_settings'); ?></a>
                                </li>
                            <?php
                                $gor = 1;
                            }
                            if (has_permission('kargo', '', 'kargo_commands_per')) {
                            ?>
                                <li role="presentation">
                                    <a href="#kargo_kargolar" aria-controls="kargo_kargolar" role="tab" data-toggle="tab"><?php echo _l('kargo_kargolar'); ?></a>
                                </li>
                            <?php
                                $gor = 1;
                            }
                            if (has_permission('kargo', '', 'kargo_logs')) {
                            ?>
                                <li role="presentation">
                                    <a href="#kargo_logs" aria-controls="kargo_logs" role="tab" data-toggle="tab"><?php echo _l('kargo_logs'); ?></a>
                                </li>
                            <?php
                                $gor = 1;
                            }
                        
                            if (has_permission('kargo', '', 'kargo_module_settings')) {/*
                            ?>
                                <li role="presentation">
                                    <a href="#kargo_module_settings" aria-controls="kargo_module_settings" role="tab" data-toggle="tab"><?php echo _l('kargo_module_settings'); ?></a>
                                </li>
                            <?php
                                $gor = 1;
                                */
                            }
                            if ($gor == 0) {
                                echo _l('sgyy');
                            }
                            ?>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <?php
                            if (has_permission('kargo', '', 'kargo_settings')) {
                                get_instance()->load->view('kargo/settings/settings_tab');
                            }
                            ?>
                            <?php
                            if (has_permission('kargo', '', 'kargo_commands_per')) {
                                get_instance()->load->view('kargo/kargolar/kargolar_tab');
                            }/*
                            ?>
                            <?php
                            if (has_permission('kargo', '', 'kargo_logs')) {
                                get_instance()->load->view('kargo/logs/logs_tab');
                            }
                            ?>
                            
                            <?php
                            if (has_permission('kargo', '', 'kargo_module_settings')) {
                                get_instance()->load->view('kargo/module_settings/module_settings_tab');
                            }*/
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    // Onload function
    document.addEventListener('DOMContentLoaded', function() {
        $(window).off('beforeunload');
    });

    function reloadData(tab_name, force = false, reload_grid = false) {
        if (force) {
            window.sysTables[tab_name].table.ajax.reload(null, reload_grid);
            return true;
        }
        $.getJSON(window.sysTables[tab_name].urlRecCount)
            .done((data) => {
                if (window.sysTables[tab_name].last_update == null || window.sysTables[tab_name].rec_count == null) {
                    window.sysTables[tab_name].last_update = data["last_udpate"];
                    window.sysTables[tab_name].rec_count = data["recCount"];
                    window.sysTables[tab_name].table.ajax.reload(null, false);
                } else if (window.sysTables[tab_name].last_update != data["last_udpate"] || window.sysTables[tab_name].rec_count != data["recCount"]) {
                    window.sysTables[tab_name].table.ajax.reload(null, false);
                    window.sysTables[tab_name].last_update = data["last_udpate"];
                    window.sysTables[tab_name].rec_count = data["recCount"];
                }
            });
    };

    function showHideEl(el) {
        el = $('#' + el);
        if (el.css('display').toLowerCase() == 'none') {
            el.css('display', 'block');
        } else {
            el.css('display', 'none');
        }
    }

    function getTableColumns(tabName) {
        let html = '<ul>\r';
        sysTables[tabName].table.columns().every(function() {
            if (!(this.header().textContent == "" || this.header().textContent == "Actions" || this.header().textContent == "İşlemler")) {
                //console.log('id:' + this.index() + ' , ' + this.header().textContent);
                let column = sysTables[tabName].table.column(this.index());
                html += '<li style="margin:2rem;display: inline;white-space: nowrap;"><input type="checkbox" data-id="' + this.index() + '" id="' + tabName + '-cboxColmn-' + this.index() + '" ' + (column.visible() ? 'checked' : '') + ' onchange="javascript:changeTableColumn(this,\'' + tabName + '\');" /><label for="' + tabName + '-cboxColmn-' + this.index() + '">&nbsp;&nbsp;' + this.header().textContent + '</label></li>\r';
            }
        })
        html += '</ul>\r';
        return html;
    }

    function initColumns(tabName, tblDiv) {
        window.hiddenColumns[tabName] = window.localStorage.getItem(tabName + '.hiddenColumns');
        if (window.hiddenColumns[tabName] == null || window.hiddenColumns[tabName] == '') {
            window.hiddenColumns[tabName] = [];
            window.localStorage.setItem(tabName + '.hiddenColumns', '');
        } else {
            window.hiddenColumns[tabName] = window.hiddenColumns[tabName].split(',');
        }
        if (window.hiddenColumns[tabName] != []) {
            sysTables[tabName].table.columns().every(function() {
                if (window.hiddenColumns[tabName].indexOf(this.index().toString()) > -1) {
                    this.visible(false);
                }
            });
        }
        $('#' + tblDiv).html(getTableColumns(tabName));
    }

    function changeTableColumn(el, tabName) {
        let dataid = $(el).attr('data-id');
        const index = window.hiddenColumns[tabName].indexOf(dataid);
        if ($(el).prop('checked')) {
            if (index > -1) {
                window.hiddenColumns[tabName].splice(index, 1);
                window.localStorage.setItem(tabName + '.hiddenColumns', window.hiddenColumns[tabName].toString());
                window.sysTables[tabName].table.column(dataid).visible(true);
            }
        } else {
            if (index < 0) {
                window.hiddenColumns[tabName].push(dataid);
                window.localStorage.setItem(tabName + '.hiddenColumns', window.hiddenColumns[tabName].toString());
                window.sysTables[tabName].table.column(dataid).visible(false);
            }
        }
    }

    function getRewriteBase() {
        return window.location.pathname.split('/admin/')[0];
    }

    function fillCheckBoxes(tabName) {
        if (sysTables[tabName].gridBatch && sysTables[tabName].gridBatch.length > 0) {
            jQuery.each($("input[id^='" + tabName + "-cbox-']"), function(i, j) {
                if (sysTables[tabName].gridBatch.includes($(j).attr("data-value"))) {
                    j.checked = true;
                }
            });
        } else if (sysTables[tabName].gridBatch && sysTables[tabName].gridBatch.length == 0) {
            jQuery.each($("input[id^='" + tabName + "-cbox-']"), function(i, j) {
                j.checked = false;
            });
        }
    };

    function addRemoveToBatch(tabName, id) {
        if (sysTables[tabName].gridBatch && sysTables[tabName].gridBatch.includes(id)) {
            sysTables[tabName].gridBatch.splice(sysTables[tabName].gridBatch.indexOf(id), 1)
        } else {
            sysTables[tabName].gridBatch.push(id);
        }
    };

    function getInputValue(input) {
        switch (input.type) {
            case 'text':
            case 'hidden':
            case 'select-one':
            case 'textarea':
                return input.value
                break;
            case 'checkbox':
                return input.checked == true ? '1' : '0';
                break;
        }
    };

    function getDataId(input) {
        return $(input).attr("data-id")
    };

    function setInputValue(input, value) {
        switch (input.type) {
            case 'text':
            case 'hidden':
            case 'select-one':
            case 'textarea':
                input.value = value
                break;
            case 'checkbox':
                $(input).prop('checked', value == '1');
                break;
        }
    };

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    };

    function resetFormState(formName) {
        let formMsg = $('#formMsg' + formName);
        let formMsgText = $('#formMsgText' + formName);
        formMsgText.text('');
        formMsg.css('display', 'none');
        formMsg.removeClass(function(index, classNames) {
            // turn the array back into a string
            return "alert-success alert-info alert-warning alert-danger";
        });
        let btnItemSave = $('#btnItemSaveformMsg' + formName);
        btnItemSave.button('reset');
    };

    async function showFormMessage(formName, msg, mType = msgType.info, timeout = 0) {
        let formMsg = $('#formMsg' + formName);
        let formMsgText = $('#formMsgText' + formName);
        formMsgText.text(msg);
        formMsg.addClass('alert-' + mType);
        formMsg.css('display', 'block');
        if (timeout > 0) {
            await sleep(timeout);
            formMsgText.text('');
            formMsg.removeClass('alert-' + mType);
            formMsg.css('display', 'none');
        }
    };

    //fillSelect('url','deneme',['id','title']);
    function fillSelectByElement(url, el_id, fields, addempty = true) {
        $.getJSON(url)
            .done(function(data) {
                let el = document.getElementById(el_id);
                let currentValue = el.value;
                el.innerText = null;
                select = $('#' + el_id);
                if (addempty) select.append("<option value=''></option>");
                data.forEach((item) => {
                    select.append("<option value='" + item[fields[0]] + "'>" + item[fields[1]] + "</option>");
                });
                el.value = currentValue;
            });
    }

    function fillSelectByClass(url, cssClass, fields, addempty = true) {
        $.getJSON(url)
            .done(function(data) {
                elements = document.getElementsByClassName(cssClass);
                let selects = Array.prototype.filter.call(elements, function(item) {
                    //alert('item.nodeName: ' + item.nodeName);
                    return item.nodeName === 'SELECT';
                });
                for (var i = 0; i < selects.length; i = i + 1) {
                    let currentValue = selects[i].value;
                    selects[i].innerText = null;
                    let select = $('#' + selects[i].id);
                    if (addempty) select.append("<option value=''></option>");
                    data.forEach((item) => {
                        select.append("<option value='" + item[fields[0]] + "'>" + item[fields[1]] + "</option>");
                    });
                    selects[i].value = currentValue;
                }
            });
    }

    function addStandartButtonsToGrid(tabName, startButtonNr = 3) {
        sysTables[tabName].table.button().add(startButtonNr, {
            action: function(e, dt, button, config) {
                jQuery.each($("input[id^='" + sysTables[tabName].name + "-cbox-']"), function(i, j) {
                    if (!sysTables[tabName].gridBatch.includes($(j).attr("data-value"))) {
                        j.checked = true;
                        sysTables[tabName].gridBatch.push($(j).attr("data-value"));
                    } else {
                        j.checked = false;
                        sysTables[tabName].gridBatch.splice(sysTables[tabName].gridBatch.indexOf($(j).attr("data-value")), 1)
                    }
                });
            },
            text: '<?php echo _l('record_select_all') ?>'
        });
        sysTables[tabName].table.button().add(startButtonNr + 1, {
            action: function(e, dt, button, config) {
                sysTables[tabName].gridBatch = [];
                fillCheckBoxes(tabName);
            },
            text: '<?php echo _l('record_deselect_all') ?>'
        });
    }

    function initAutoRefreshControl(tabName) {
        let buttonId = 2;
        sysTables[tabName].autoRefresh = window.localStorage.getItem(tabName + '.autorefresh');
        if (sysTables[tabName].autoRefresh == null) {
            sysTables[tabName].autoRefresh = true;
            window.localStorage.setItem(tabName + '.autorefresh', sysTables[tabName].autoRefresh);
        } else {
            sysTables[tabName].autoRefresh = sysTables[tabName].autoRefresh == 'true';
        }
        sysTables[tabName].table.button().add(buttonId, {
            action: function(e, dt, button, config) {
                if (sysTables[tabName].autoRefresh == true) {
                    sysTables[tabName].table.button(buttonId).text('<?php echo _l('start_autorefresh') ?>');
                    sysTables[tabName].autoRefresh = false;
                    window.localStorage.setItem(tabName + '.autorefresh', sysTables[tabName].autoRefresh);
                    clearInterval(sysTables[tabName].timer);
                } else {
                    sysTables[tabName].table.button(buttonId).text('<?php echo _l('stop_autorefresh') ?>');
                    sysTables[tabName].autoRefresh = true;
                    window.localStorage.setItem(tabName + '.autorefresh', sysTables[tabName].autoRefresh);
                    reloadData(tabName, true);
                    sysTables[tabName].timer = setInterval(() => reloadData(tabName), 3000);
                }
            },
            text: sysTables[tabName].autoRefresh == true ? '<?php echo _l('stop_autorefresh') ?>' : '<?php echo _l('start_autorefresh') ?>'
        });
        if (sysTables[tabName].autoRefresh == true) {
            sysTables[tabName].table.button(buttonId).text('<?php echo _l('stop_autorefresh') ?>');
            sysTables[tabName].autoRefresh = true;
            reloadData(tabName, true);
            sysTables[tabName].timer = setInterval(() => reloadData(tabName), 3000);
        }
    }

    // General function for all datatables serverside
    function initDataTableSpecial(selector, url, notsearchable, notsortable, fnserverparams, defaultorder) {
        var table = typeof(selector) == 'string' ? $("body").find('table' + selector) : selector;

        if (table.length === 0) {
            return false;
        }

        fnserverparams = (fnserverparams == 'undefined' || typeof(fnserverparams) == 'undefined') ? [] : fnserverparams;

        // If not order is passed order by the first column
        if (typeof(defaultorder) == 'undefined') {
            defaultorder = [
                [0, 'asc']
            ];
        } else {
            if (defaultorder.length === 1) {
                defaultorder = [defaultorder];
            }
        }

        var user_table_default_order = table.attr('data-default-order');

        if (!empty(user_table_default_order)) {
            var tmp_new_default_order = JSON.parse(user_table_default_order);
            var new_defaultorder = [];
            for (var i in tmp_new_default_order) {
                // If the order index do not exists will throw errors
                if (table.find('thead th:eq(' + tmp_new_default_order[i][0] + ')').length > 0) {
                    new_defaultorder.push(tmp_new_default_order[i]);
                }
            }
            if (new_defaultorder.length > 0) {
                defaultorder = new_defaultorder;
            }
        }

        var length_options = [10, 25, 50, 100];
        var length_options_names = [10, 25, 50, 100];

        app.options.tables_pagination_limit = parseFloat(app.options.tables_pagination_limit);

        if ($.inArray(app.options.tables_pagination_limit, length_options) == -1) {
            length_options.push(app.options.tables_pagination_limit);
            length_options_names.push(app.options.tables_pagination_limit);
        }

        length_options.sort(function(a, b) {
            return a - b;
        });
        length_options_names.sort(function(a, b) {
            return a - b;
        });

        length_options.push(-1);
        length_options_names.push(app.lang.dt_length_menu_all);

        var dtSettings = {

            "language": app.lang.datatables,
            "processing": true,
            "retrieve": true,
            "serverSide": true,
            'paginate': true,
            'searchDelay': 750,
            "bDeferRender": true,
            "responsive": {
                details: false
            },
            "autoWidth": false,
            dom: "<'row'><'row'<'col-md-7'Bl><'col-md-5'f>>rt<'row'<'col-md-4'i>><'row'<'#colvis'><'.dt-page-jump'>p>",
            "pageLength": app.options.tables_pagination_limit,
            "lengthMenu": [length_options, length_options_names],
            "columnDefs": [{
                "searchable": false,
                "targets": notsearchable,
            }, {
                "sortable": false,
                "targets": notsortable
            }],
            "fnDrawCallback": function(oSettings) {
                _table_jump_to_page(this, oSettings);
                if (oSettings.aoData.length === 0) {
                    $(oSettings.nTableWrapper).addClass('app_dt_empty');
                } else {
                    $(oSettings.nTableWrapper).removeClass('app_dt_empty');
                }
            },
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                // If tooltips found
                $(nRow).attr('data-title', aData.Data_Title);
                $(nRow).attr('data-toggle', aData.Data_Toggle);
            },
            "initComplete": function(settings, json) {
                var t = this;
                var $btnReload = $('.btn-dt-reload');
                $btnReload.attr('data-toggle', 'tooltip');
                $btnReload.attr('title', app.lang.dt_button_reload);

                var $btnColVis = $('.dt-column-visibility');
                $btnColVis.attr('data-toggle', 'tooltip');
                $btnColVis.attr('title', app.lang.dt_button_column_visibility);

                if (t.hasClass('scroll-responsive') || app.options.scroll_responsive_tables == 1) {
                    t.wrap('<div class="table-responsive"></div>');
                }

                var dtEmpty = t.find('.dataTables_empty');
                if (dtEmpty.length) {
                    dtEmpty.attr('colspan', t.find('thead th').length);
                }

                // Hide mass selection because causing issue on small devices
                if (is_mobile() && $(window).width() < 400 && t.find('tbody td:first-child input[type="checkbox"]').length > 0) {
                    t.DataTable().column(0).visible(false, false).columns.adjust();
                    $("a[data-target*='bulk_actions']").addClass('hide');
                }

                t.parents('.table-loading').removeClass('table-loading');
                t.removeClass('dt-table-loading');
                var th_last_child = t.find('thead th:last-child');
                var th_first_child = t.find('thead th:first-child');
                if (th_last_child.text().trim() == app.lang.options) {
                    th_last_child.addClass('not-export');
                }
                if (th_first_child.find('input[type="checkbox"]').length > 0) {
                    th_first_child.addClass('not-export');
                }
                mainWrapperHeightFix();
            },
            "order": defaultorder,
            "ajax": {
                "url": url,
                "type": "POST",
                "data": function(d) {
                    if (typeof(csrfData) !== 'undefined') {
                        d[csrfData['token_name']] = csrfData['hash'];
                    }
                    for (var key in fnserverparams) {
                        d[key] = $(fnserverparams[key]).val();
                    }
                    if (table.attr('data-last-order-identifier')) {
                        d['last_order_identifier'] = table.attr('data-last-order-identifier');
                    }
                }
            },
            buttons: get_datatable_buttons(table),
        };

        if (table.hasClass('scroll-responsive') || app.options.scroll_responsive_tables == 1) {
            dtSettings.responsive = false;
        }

        table = table.dataTable(dtSettings);
        var tableApi = table.DataTable();

        var hiddenHeadings = table.find('th.not_visible');
        var hiddenIndexes = [];

        $.each(hiddenHeadings, function() {
            hiddenIndexes.push(this.cellIndex);
        });

        setTimeout(function() {
            for (var i in hiddenIndexes) {
                tableApi.columns(hiddenIndexes[i]).visible(false, false).columns.adjust();
            }
        }, 10);

        if (table.hasClass('customizable-table')) {

            var tableToggleAbleHeadings = table.find('th.toggleable');
            var invisible = $('#hidden-columns-' + table.attr('id'));
            try {
                invisible = JSON.parse(invisible.text());
            } catch (err) {
                invisible = [];
            }

            $.each(tableToggleAbleHeadings, function() {
                var cID = $(this).attr('id');
                if ($.inArray(cID, invisible) > -1) {
                    tableApi.column('#' + cID).visible(false);
                }
            });

            // For for not blurring out when clicked on the link
            // Causing issues hidden column still to be shown as not hidden because the link is focused
            /* $('body').on('click', '.buttons-columnVisibility a', function() {
                $(this).blur();
            });*/
            /*
                    table.on('column-visibility.dt', function(e, settings, column, state) {
                        var hidden = [];
                        $.each(tableApi.columns()[0], function() {
                            var visible = tableApi.column($(this)).visible();
                            var columnHeader = $(tableApi.column($(this)).header());
                            if (columnHeader.hasClass('toggleable')) {
                                if (!visible) {
                                    hidden.push(columnHeader.attr('id'))
                                }
                            }
                        });
                        var data = {};
                        data.id = table.attr('id');
                        data.hidden = hidden;
                        if (data.id) {
                            $.post(admin_url + 'staff/save_hidden_table_columns', data).fail(function(data) {
                                // Demo usage, prevent multiple alerts
                                if ($('body').find('.float-alert').length === 0) {
                                    alert_float('danger', data.responseText);
                                }
                            });
                        } else {
                            console.error('Table that have ability to show/hide columns must have an ID');
                        }
                    });*/
        }
        <?php if (!has_permission('kargo', '', 'kargo_all_export')) { ?>
            $('.buttons-collection').css("display", "none");
        <?php } ?>
        // Fix for hidden tables colspan not correct if the table is empty
        if (table.is(':hidden')) {
            table.find('.dataTables_empty').attr('colspan', table.find('thead th').length);
        }

        table.on('preXhr.dt', function(e, settings, data) {
            if (settings.jqXHR) settings.jqXHR.abort();
        });

        return tableApi;
    }
</script>
</body>

</html>