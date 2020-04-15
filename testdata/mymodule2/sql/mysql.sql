# SQL Dump for my module 2 module
# PhpMyAdmin Version: 4.0.4
# http://www.phpmyadmin.net
#
# Host: localhost
# Generated on: Wed Apr 15, 2020 to 08:21:46
# Server version: 5.5.5-10.4.10-MariaDB
# PHP Version: 7.3.12

#
# Structure table for `mymodule2_categories` 5
#

CREATE TABLE `mymodule2_categories` (
  `cat_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_name` VARCHAR(200) NOT NULL DEFAULT '',
  `cat_logo` VARCHAR(200) NOT NULL DEFAULT '',
  `cat_created` INT(10) NOT NULL DEFAULT '0',
  `cat_submitter` INT(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB;

#
# Structure table for `mymodule2_articles` 9
#

CREATE TABLE `mymodule2_articles` (
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

#
# Structure table for `mymodule2_testfields` 15
#

CREATE TABLE `mymodule2_testfields` (
  `tf_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tf_text` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_textarea` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_dhtml` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_checkbox` INT(10) NOT NULL DEFAULT '0',
  `tf_yesno` INT(1) NOT NULL DEFAULT '0',
  `tf_selectbox` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_user` INT(10) NOT NULL DEFAULT '0',
  `tf_color` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_imagelist` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_urlfile` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_uplimage` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_uplfile` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_textdateselect` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_selectfile` VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`tf_id`)
) ENGINE=InnoDB;

