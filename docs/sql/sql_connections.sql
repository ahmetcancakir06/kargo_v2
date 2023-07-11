-- perfex_crm_273.tblsql_connections definition

CREATE TABLE `tblsql_connections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8_turkish_ci NOT NULL,
  `sql_host` varchar(200) COLLATE utf8_turkish_ci NOT NULL,
  `sql_port` varchar(10) COLLATE utf8_turkish_ci NOT NULL,
  `sql_username` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `sql_password` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `sql_database` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `sql_charset` varchar(20) COLLATE utf8_turkish_ci NOT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;