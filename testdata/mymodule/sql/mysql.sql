# SQL Dump for my module module
# PhpMyAdmin Version: 4.0.4
# http://www.phpmyadmin.net
#
# Host: localhost
# Generated on: Tue Apr 07, 2020 to 18:14:42
# Server version: 5.5.5-10.4.10-MariaDB
# PHP Version: 7.3.12

#
# Structure table for `mymodule_categories` 5
#

CREATE TABLE `mymodule_categories` (
  `cat_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_name` VARCHAR(200) NOT NULL DEFAULT '',
  `cat_logo` VARCHAR(200) NOT NULL DEFAULT '',
  `cat_created` INT(10) NOT NULL DEFAULT '0',
  `cat_submitter` INT(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB;

#
# Structure table for `mymodule_articles` 9
#

CREATE TABLE `mymodule_articles` (
  `art_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `art_cat` INT(8) NOT NULL DEFAULT '0',
  `art_title` VARCHAR(200) NOT NULL DEFAULT '',
  `art_descr` MEDIUMTEXT NOT NULL ,
  `art_img` VARCHAR(200) NULL DEFAULT '&#039;&#039;',
  `art_online` TINYINT(1) NOT NULL DEFAULT '0',
  `art_file` VARCHAR(200) NOT NULL DEFAULT '',
  `art_created` INT(10) NOT NULL DEFAULT '0',
  `art_submitter` INT(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`art_id`)
) ENGINE=InnoDB;

