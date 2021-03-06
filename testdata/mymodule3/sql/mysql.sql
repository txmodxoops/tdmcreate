# SQL Dump for my module 3 module
# PhpMyAdmin Version: 4.0.4
# http://www.phpmyadmin.net
#
# Host: localhost
# Generated on: Tue Apr 28, 2020 to 21:04:20
# Server version: 5.5.5-10.4.10-MariaDB
# PHP Version: 7.3.12

#
# Structure table for `mymodule3_categories` 5
#

CREATE TABLE `mymodule3_categories` (
  `cat_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_name` VARCHAR(200) NOT NULL DEFAULT '',
  `cat_logo` VARCHAR(200) NOT NULL DEFAULT '',
  `cat_created` INT(10) NOT NULL DEFAULT '0',
  `cat_submitter` INT(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB;

#
# Structure table for `mymodule3_articles` 9
#

CREATE TABLE `mymodule3_articles` (
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
# Structure table for `mymodule3_testfields` 22
#

CREATE TABLE `mymodule3_testfields` (
  `tf_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tf_text` VARCHAR(255) NOT NULL DEFAULT '',
  `tf_textarea` TEXT NOT NULL ,
  `tf_dhtml` TEXT NOT NULL ,
  `tf_checkbox` INT(10) NOT NULL DEFAULT '0',
  `tf_yesno` INT(1) NOT NULL DEFAULT '0',
  `tf_selectbox` INT(10) NOT NULL DEFAULT '0',
  `tf_user` INT(10) NOT NULL DEFAULT '0',
  `tf_color` VARCHAR(7) NOT NULL DEFAULT '',
  `tf_imagelist` VARCHAR(255) NOT NULL DEFAULT '',
  `tf_urlfile` VARCHAR(255) NOT NULL DEFAULT '',
  `tf_uplimage` VARCHAR(255) NOT NULL DEFAULT '',
  `tf_uplfile` VARCHAR(255) NOT NULL DEFAULT '',
  `tf_textdateselect` INT(10) NOT NULL DEFAULT '0',
  `tf_selectfile` VARCHAR(255) NOT NULL DEFAULT '',
  `tf_password` VARCHAR(255) NOT NULL DEFAULT '',
  `tf_country_list` VARCHAR(3) NOT NULL DEFAULT '',
  `tf_language` VARCHAR(100) NOT NULL DEFAULT '',
  `tf_radio` INT(10) NOT NULL DEFAULT '0',
  `tf_status` INT(1) NOT NULL DEFAULT '0',
  `tf_datetime` INT(10) NOT NULL DEFAULT '0',
  `tf_combobox` INT(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tf_id`)
) ENGINE=InnoDB;

