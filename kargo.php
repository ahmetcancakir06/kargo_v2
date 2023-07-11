<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Kargo
Description: Manage Kargo scripts
Version: 0.1.2
Author: Ahmet Can ÇAKIR
Requires at least: 2.3.*
*/

define('KARGO_MODULE_NAME', 'kargo');
define('KARGO_MODULE_KEY_PASS', 'Lkndhcbajnbaxhas!jjsU88nxxa913');

hooks()->add_action('admin_init', 'kargo_module_init_menu_items');
hooks()->add_action('admin_init', 'kargo_permissions');
hooks()->add_filter('module_kargo_action_links', 'module_kargo_action_links');

/**
* Register activation module hook
*/
register_activation_hook(KARGO_MODULE_NAME, 'kargo_module_activation_hook');

/**
* Add additional settings for this module in the module list area
* @param  array $actions current actions
* @return array
*/
function module_kargo_action_links($actions)
{
    if (has_permission('kargo', '', 'kargo_update')) {
        $actions[] = '<a href="' . admin_url('kargo/kargo_update') . '">' . _l('kargo_update') . '</a>';
    }

    return $actions;
}


function kargo_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(KARGO_MODULE_NAME, [KARGO_MODULE_NAME]);

/**
 * Init kargo module menu items in setup in admin_init hook
 * @return null
 */
 function kargo_module_init_menu_items()
 {
    $CI = &get_instance();

    if (has_permission('kargo', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('kargo', [
            'collapse' => true,
            'name'     => _l('kargo'),
            'href'     => admin_url('kargo'),
            'icon'     => 'fa fa-server',
            'position' => 18,
        ]);
        if (has_permission('kargo', '', 'kargo_all')) {
            $CI->app_menu->add_sidebar_children_item('kargo', [
                    'slug'     => 'kargo-control-all',
                    'name'     => _l('kargo_all'),
                    'href'     => admin_url('kargo/kargo_all'),
                    'position' => 80,
            ]);
        }
    }

}

function kargo_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'kargo_all' => _l('kargo_all'),
            'kargo_all_export' => _l('kargo_all'). '(' . _l('kargo_export_per') . ')',
            
            'kargo_commands' => _l('kargo_commands'),
            'kargo_commands_new' => _l('kargo_commands'). '(' . _l('kargo_new') . ')',
            'kargo_commands_deleteAll' => _l('kargo_commands'). '(' . _l('kargo_deleteAll') . ')',
            'kargo_commands_updateStatus' => _l('kargo_commands'). '(' . _l('kargo_commands_updateStatus') . ')',
            'kargo_commands_status' => _l('kargo_commands'). '(' . _l('kargo_commands_status') . ')',
            'kargo_commands_delete' => _l('kargo_commands'). '(' . _l('kargo_delete') . ')',
            'kargo_commands_edit' => _l('kargo_commands'). '(' . _l('kargo_edit') . ')',
            'kargo_commands_copy' => _l('kargo_commands'). '(' . _l('kargo_copy') . ')',
            'kargo_commands_batch' => _l('kargo_commands'). '(' . _l('kargo_batch') . ')',

            'kargo_commands_per' => _l('kargo_commands_per'),
            'kargo_commands_per_deleteAll' => _l('kargo_commands_per'). '(' . _l('kargo_deleteAll') . ')',
            'kargo_commands_per_delete' => _l('kargo_commands_per'). '(' . _l('kargo_delete') . ')',
            'kargo_commands_per_new' => _l('kargo_commands_per'). '(' . _l('kargo_new') . ')',
            'kargo_commands_per_edit' => _l('kargo_commands_per'). '(' . _l('kargo_edit') . ')',
            'kargo_commands_per_copy' => _l('kargo_commands_per'). '(' . _l('kargo_copy') . ')',
            'kargo_commands_per_batch' => _l('kargo_commands_per'). '(' . _l('kargo_batch') . ')',
            'kargo_commands_per_test' => _l('kargo_commands_per'). '(' . _l('kargo_commands_per_test') . ')',

            'kargo_logs' => _l('kargo_logs'),
            'kargo_logs_deleteAll' => _l('kargo_logs'). '(' . _l('kargo_deleteAll') . ')',
            'kargo_logs_delete' => _l('kargo_logs'). '(' . _l('kargo_delete') . ')',

            'kargo_module_settings' => _l('kargo_module_settings'),
            'kargo_module_settings_edit' => _l('kargo_module_settings'). '(' . _l('kargo_edit') . ')',

            'kargo_update' => _l('kargo_update'),
    ];

    register_staff_capabilities('kargo', $capabilities, _l('kargo'));
}

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}
