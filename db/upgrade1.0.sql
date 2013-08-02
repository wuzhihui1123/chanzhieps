-- add primary field of image.
alter table xr_file add `primary` enum('1','0')  not null default '0';

-- add hidden field to thread and reply.
ALTER TABLE `xr_thread` ADD `hidden` ENUM( '1', '0' ) NOT NULL DEFAULT '0';
ALTER TABLE `xr_reply` ADD `hidden` ENUM( '1', '0' ) NOT NULL DEFAULT '0';

-- rename articleCategory to relation.
ALTER TABLE `xr_articleCategory` CHANGE `article` `id` MEDIUMINT( 9 ) NOT NULL;
ALTER TABLE `xr_articleCategory` ADD `type` CHAR( 20 ) NOT NULL FIRST ;
ALTER TABLE `xr_articleCategory` DROP INDEX `article` ,
ADD UNIQUE `relation` ( `type` , `id` , `category` );
RENAME TABLE `xirang`.`xr_articleCategory` TO `xirang`.`xr_relation` ;

-- change tree to type.
ALTER TABLE `xr_category` CHANGE `tree` `type` CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
