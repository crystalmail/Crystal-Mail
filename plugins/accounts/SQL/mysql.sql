CREATE TABLE IF NOT EXISTS `accounts` (
  `aid` int(10) unsigned NOT NULL auto_increment,
  `account_dn` varchar(128) NOT NULL,  
  `account_id` varchar(128) NOT NULL,
  `account_pw` varchar(128) NOT NULL,
  `account_host` varchar(128) NOT NULL,
  `preferences` text,
  `user_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`aid`),
  KEY `user_id_fk_accounts` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
