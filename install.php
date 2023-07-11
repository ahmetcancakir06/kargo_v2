<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'server_api_commands')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "server_api_commands` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `commands` longtext NOT NULL,
      `in_system` int(11) NOT NULL DEFAULT '1',
      `active` tinyint(1) NOT NULL DEFAULT '1',
      `updated_at` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
      PRIMARY KEY (`id`),
      KEY `updated_at` (`updated_at`)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;");

  $CI->db->query("ALTER TABLE `" . db_prefix() . "server_api_commands` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1");
  $CI->db->query("INSERT INTO `".db_prefix()."server_api_commands` ( `title`, `commands`, `in_system`, `active`) VALUES ('Test','wall #degisken#','1','1');");

}
if (!$CI->db->table_exists(db_prefix() . 'server_api_commands_per')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "server_api_commands_per` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `commands_id` int(11) NOT NULL,
    `which_server` int(11) NOT NULL,
    `module_name` varchar(250) COLLATE utf8_turkish_ci DEFAULT NULL,
    `staff_user_id` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
    `ip_address` varchar(250) COLLATE utf8_turkish_ci DEFAULT NULL,
    `api_key` varchar(250) COLLATE utf8_turkish_ci DEFAULT NULL,
    `allowed` tinyint(1) NOT NULL DEFAULT '1',
    `updated_at` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    KEY `updated_at` (`updated_at`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;");

  $CI->db->query("ALTER TABLE `" . db_prefix() . "server_api_commands_per` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1");
  $CI->db->query("INSERT INTO `".db_prefix()."server_api_commands_per` (`id`, `commands_id`, `which_server`, `module_name`, `staff_user_id`, `ip_address`, `api_key`, `allowed`) VALUES (NULL, '1', '1', '1', '1', '*', '232ae0c86314d0555432caa2ef904a94', '1');");

}

if (!$CI->db->table_exists(db_prefix() . 'server_api_logs')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "server_api_logs` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `commands_id` int(11) DEFAULT NULL,
    `who` varchar(250) COLLATE utf8_turkish_ci DEFAULT NULL,
    `date` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    `details` mediumtext COLLATE utf8_turkish_ci,
    `updated_at` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    KEY `updated_at` (`updated_at`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;");

  $CI->db->query("ALTER TABLE `" . db_prefix() . "server_api_logs` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1");
  
}
if (!$CI->db->table_exists(db_prefix() . 'server_api_module_settings')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "server_api_module_settings` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `perfex_module_name` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
    `settings_key` varchar(200) COLLATE utf8_turkish_ci NOT NULL,
    `settings_value` mediumtext COLLATE utf8_turkish_ci,
    `description` mediumtext COLLATE utf8_turkish_ci,
    `ip` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
    `username` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
    `password` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
    `updated_at` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    KEY `updated_at` (`updated_at`),
    KEY `module_name_key` (`perfex_module_name`,`settings_key`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;");

  $CI->db->query("ALTER TABLE `" . db_prefix() . "server_api_module_settings` MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1");
  $CI->db->query("INSERT INTO `".db_prefix()."server_api_module_settings` ( `perfex_module_name`, `settings_key`, `settings_value`, `description`) VALUES ( 'server_api', 'REMOTE_SQL_SERVER', '1', 'Modülün Ana sisteme ulaşması için kullanılan SQL bağlantı ID si dir. SQL Bağlantıları sekmesinden gelir.');");
}

// Module setting initiator
$CI->load->library(SERVER_API_MODULE_NAME . '/' . 'module_settings');
$CI->module_settings->installSettings();
