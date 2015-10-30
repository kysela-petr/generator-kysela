SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` char(20) COLLATE utf8_czech_ci NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT '0',
  `show` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `html` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`id`),
  KEY `fk_menu_section1_idx` (`section_id`),
  KEY `fk_menu_menu_type1_idx` (`type`),
  CONSTRAINT `fk_menu_menu_type1` FOREIGN KEY (`type`) REFERENCES `menu_type` (`code`),
  CONSTRAINT `fk_menu_section1` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Menu';


DROP TABLE IF EXISTS `menu_has_menu`;
CREATE TABLE `menu_has_menu` (
  `menu_id` int(10) unsigned NOT NULL COMMENT 'ID polozky',
  `parent_id` int(10) unsigned NOT NULL COMMENT 'ID nadrazene polozky',
  PRIMARY KEY (`menu_id`),
  KEY `fk_menu_has_menu_menu2_idx` (`parent_id`),
  KEY `fk_menu_has_menu_menu1_idx` (`menu_id`),
  CONSTRAINT `fk_menu_has_menu_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_menu_has_menu_menu2` FOREIGN KEY (`parent_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `menu_has_page`;
CREATE TABLE `menu_has_page` (
  `menu_id` int(10) unsigned NOT NULL,
  `page_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `fk_menu_has_page_page1_idx` (`page_id`),
  KEY `fk_menu_has_page_menu1_idx` (`menu_id`),
  CONSTRAINT `fk_menu_has_page_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_menu_has_page_page1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `menu_has_presenter`;
CREATE TABLE `menu_has_presenter` (
  `menu_id` int(10) unsigned NOT NULL,
  `presenter` char(20) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `fk_menu_has_presenter_presenter1_idx` (`presenter`),
  KEY `fk_menu_has_presenter_menu1_idx` (`menu_id`),
  CONSTRAINT `fk_menu_has_presenter_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_menu_has_presenter_presenter1` FOREIGN KEY (`presenter`) REFERENCES `presenter` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `menu_has_section`;
CREATE TABLE `menu_has_section` (
  `menu_id` int(10) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `fk_menu_has_section_section1_idx` (`section_id`),
  KEY `fk_menu_has_section_menu1_idx` (`menu_id`),
  CONSTRAINT `fk_menu_has_section_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_menu_has_section_section1` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `menu_has_submodule`;
CREATE TABLE `menu_has_submodule` (
  `menu_id` int(10) unsigned NOT NULL,
  `submodule` char(20) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `fk_menu_has_submodule_submodule1_idx` (`submodule`),
  KEY `fk_menu_has_submodule_menu1_idx` (`menu_id`),
  CONSTRAINT `fk_menu_has_submodule_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_menu_has_submodule_submodule1` FOREIGN KEY (`submodule`) REFERENCES `submodule` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `menu_has_symlink`;
CREATE TABLE `menu_has_symlink` (
  `menu_id` int(10) unsigned NOT NULL,
  `symlink_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `symlink_id` (`symlink_id`),
  CONSTRAINT `menu_has_symlink_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_has_symlink_ibfk_2` FOREIGN KEY (`symlink_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `menu_type`;
CREATE TABLE `menu_type` (
  `code` char(20) COLLATE utf8_czech_ci NOT NULL COMMENT 'Kod',
  `name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Nazev',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Typ polozky menu';


DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `code` char(30) COLLATE utf8_czech_ci NOT NULL COMMENT 'Kod odpovidajici nazvu fyzickeho modulu',
  `name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Nazev',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Tabulka fyzickych modulu';


DROP TABLE IF EXISTS `module_has_presenter`;
CREATE TABLE `module_has_presenter` (
  `module_code` char(30) COLLATE utf8_czech_ci NOT NULL,
  `presenter_code` char(20) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`module_code`,`presenter_code`),
  KEY `presenter_code` (`presenter_code`),
  CONSTRAINT `module_has_presenter_ibfk_1` FOREIGN KEY (`module_code`) REFERENCES `module` (`code`) ON DELETE CASCADE,
  CONSTRAINT `module_has_presenter_ibfk_2` FOREIGN KEY (`presenter_code`) REFERENCES `presenter` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `module_has_submodule`;
CREATE TABLE `module_has_submodule` (
  `module_code` char(30) COLLATE utf8_czech_ci NOT NULL,
  `submodule_code` char(20) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`module_code`,`submodule_code`),
  KEY `submodule_code` (`submodule_code`),
  CONSTRAINT `module_has_submodule_ibfk_1` FOREIGN KEY (`module_code`) REFERENCES `module` (`code`) ON DELETE CASCADE,
  CONSTRAINT `module_has_submodule_ibfk_2` FOREIGN KEY (`submodule_code`) REFERENCES `submodule` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `content` longtext COLLATE utf8_czech_ci,
  `show` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `page_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Staticka stranka';


DROP TABLE IF EXISTS `page_has_section`;
CREATE TABLE `page_has_section` (
  `page_id` int(10) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  KEY `page_id` (`page_id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `page_has_section_ibfk_3` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `page_has_section_ibfk_4` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `presenter`;
CREATE TABLE `presenter` (
  `code` char(20) COLLATE utf8_czech_ci NOT NULL COMMENT 'Kod',
  `name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Nazev',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Tabulka fyzickych presenteru';


DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `code` char(30) COLLATE utf8_czech_ci NOT NULL COMMENT 'Kod role',
  `name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Nazev role',
  `super` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Neomezeny pristup',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Uzivatelske role';


DROP TABLE IF EXISTS `role_action`;
CREATE TABLE `role_action` (
  `code` char(30) COLLATE utf8_czech_ci NOT NULL COMMENT 'Kod',
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Nazev',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Tabulka akci pro ACL';


DROP TABLE IF EXISTS `role_has_resource`;
CREATE TABLE `role_has_resource` (
  `role` char(30) COLLATE utf8_czech_ci NOT NULL COMMENT 'Role',
  `resource` char(30) COLLATE utf8_czech_ci NOT NULL COMMENT 'Zdroj',
  `action` char(30) COLLATE utf8_czech_ci NOT NULL COMMENT 'Akce',
  KEY `role_has_resource_ibfk_2_idx` (`resource`),
  KEY `role_has_resource_ibfk_3_idx` (`action`),
  KEY `role_has_resource_ibfk_1_idx` (`role`),
  CONSTRAINT `role_has_resource_ibfk_5` FOREIGN KEY (`role`) REFERENCES `role` (`code`) ON DELETE CASCADE,
  CONSTRAINT `role_has_resource_ibfk_6` FOREIGN KEY (`resource`) REFERENCES `role_resource` (`code`) ON DELETE CASCADE,
  CONSTRAINT `role_has_resource_ibfk_7` FOREIGN KEY (`action`) REFERENCES `role_action` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Tabulka urcujici povolene operace pro roli';


DROP TABLE IF EXISTS `role_resource`;
CREATE TABLE `role_resource` (
  `code` char(30) COLLATE utf8_czech_ci NOT NULL COMMENT 'Kod',
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Nazev',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Tabulka zdroju pro ACL';


DROP TABLE IF EXISTS `section`;
CREATE TABLE `section` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` char(30) COLLATE utf8_czech_ci NOT NULL COMMENT 'Modul',
  `domain` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Domena tretiho radu',
  `name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Nazev',
  `priority` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_UNIQUE` (`domain`),
  KEY `fk_section_module1_idx` (`module`),
  CONSTRAINT `section_ibfk_1` FOREIGN KEY (`module`) REFERENCES `module` (`code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Tabulka sekci';


DROP TABLE IF EXISTS `section_has_menu_type`;
CREATE TABLE `section_has_menu_type` (
  `section_id` int(10) unsigned NOT NULL,
  `menu_type_code` char(20) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`section_id`,`menu_type_code`),
  KEY `menu_type_code` (`menu_type_code`),
  CONSTRAINT `section_has_menu_type_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE,
  CONSTRAINT `section_has_menu_type_ibfk_2` FOREIGN KEY (`menu_type_code`) REFERENCES `menu_type` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `valid_to` datetime NOT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Prihlasovaci jmeno',
  `password` char(60) COLLATE utf8_czech_ci NOT NULL COMMENT 'Heslo',
  `role` char(30) COLLATE utf8_czech_ci NOT NULL COMMENT 'Role',
  `name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Jmeno',
  `surname` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Prijmeni',
  `email` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `salt` varchar(31) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_ibfk_1_idx` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Uzivatele administrace';


DROP TABLE IF EXISTS `user_has_role`;
CREATE TABLE `user_has_role` (
  `user_id` int(10) unsigned NOT NULL,
  `role` char(30) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`user_id`,`role`),
  KEY `role` (`role`),
  CONSTRAINT `user_has_role_ibfk_3` FOREIGN KEY (`role`) REFERENCES `role` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `user_has_role_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `user_has_section`;
CREATE TABLE `user_has_section` (
  `user_id` int(10) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`section_id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `user_has_section_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_has_section_ibfk_4` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `version_db`;
CREATE TABLE `version_db` (
  `id` int(11) NOT NULL,
  `file` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `added` datetime DEFAULT NULL,
  `executed` datetime DEFAULT NULL,
  `author` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('new','executing','error','done') COLLATE utf8_czech_ci DEFAULT 'new',
  `executing_query` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;