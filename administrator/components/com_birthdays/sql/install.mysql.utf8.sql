CREATE TABLE IF NOT EXISTS `#__birthdays` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`birthday` DATETIME,
	`name` VARCHAR(255) NOT NULL,
	`created_by` INT(11) NOT NULL,
	`state` INT(11) NOT NULL DEFAULT 1,
	`ordering` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='' DEFAULT COLLATE=utf8_general_ci;
