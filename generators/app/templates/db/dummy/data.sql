SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `menu_type` (`code`, `name`) VALUES
('heading',	'Nadpis'),
('html',	'HTML kód'),
('page',	'Statická stránka'),
('presenter',	'Presenter'),
('section',	'Sekce webu'),
('subject',	'Krajský orgán'),
('submodule',	'Submodul'),
('symlink',	'Odkaz na jinou položku menu'),
('url',	'URL odkaz');

INSERT INTO `module` (`code`, `name`) VALUES
('Front',	'Front');


INSERT INTO `role` (`code`, `name`, `super`) VALUES
('admin',	'Admin',	1);

INSERT INTO `role_action` (`code`, `name`) VALUES
('',	''),
('create',	'create'),
('delete',	'delete'),
('edit',	'edit'),
('view',	'view');


INSERT INTO `section` (`id`, `module`, `domain`, `name`, `priority`) VALUES
(1,	'Front',	'sandbox.loc',	'Front',	1);

INSERT INTO `section_has_menu_type` (`section_id`, `menu_type_code`) VALUES
(1,	'heading'),
(1,	'page');


INSERT INTO `user` (`id`, `username`, `password`, `role`, `name`, `surname`, `email`, `salt`) VALUES
(1,	'esportsadmin',	'$2y$10$HigXS5uzCK5TMdT2MR6Z0Ob2hkarqghB7IcvP9q8R1htDSFWdKvL6',	'admin',	'Admin',	'Esports',	NULL,	NULL);

INSERT INTO `user_has_role` (`user_id`, `role`) VALUES
(1,	'admin');

INSERT INTO `user_has_section` (`user_id`, `section_id`) VALUES
(1,	1);