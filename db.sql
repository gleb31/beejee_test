CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_beejee` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `db_beejee`;

CREATE TABLE `tasks` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(64) NOT NULL DEFAULT '',
  `email` VARCHAR(64) NOT NULL DEFAULT '',
  `tasktext` TEXT,
  `created_date` DATETIME DEFAULT '0000-00-00 00:00:00',
  `updated_date` DATETIME DEFAULT '0000-00-00 00:00:00',
  `status` ENUM('new','updated_by_admin') DEFAULT 'new',
  `task_completed` ENUM('no','yes') DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `task_completed` (`task_completed`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE USER 'db_beejee_user'@'localhost' IDENTIFIED BY 'db_beejee_pass';
GRANT SELECT, INSERT, UPDATE ON `db_beejee`.`tasks` TO 'db_beejee_user'@'localhost';
FLUSH PRIVILEGES;



