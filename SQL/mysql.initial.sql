-- RoundCube Webmail initial database structure


/*!40014  SET FOREIGN_KEY_CHECKS=0 */;

-- Table structure for table `session`

CREATE TABLE `session` (
 `sess_id` varchar(40) NOT NULL,
 `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
 `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
 `ip` varchar(40) NOT NULL,
 `vars` mediumtext NOT NULL,
 PRIMARY KEY(`sess_id`),
 INDEX `changed_index` (`changed`)
) /*!40000 ENGINE=INNODB */ /*!40101 CHARACTER SET utf8 COLLATE utf8_general_ci */;


-- Table structure for table `users`

CREATE TABLE `users` (
 `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 `username` varchar(128) NOT NULL,
 `mail_host` varchar(128) NOT NULL,
 `alias` varchar(128) NOT NULL,
 `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
 `last_login` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
 `language` varchar(5),
 `preferences` text,
 PRIMARY KEY(`user_id`),
 INDEX `username_index` (`username`),
 INDEX `alias_index` (`alias`)
) /*!40000 ENGINE=INNODB */ /*!40101 CHARACTER SET utf8 COLLATE utf8_general_ci */;


-- Table structure for table `messages`

CREATE TABLE `messages` (
 `message_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
 `del` tinyint(1) NOT NULL DEFAULT '0',
 `cache_key` varchar(128) /*!40101 CHARACTER SET ascii COLLATE ascii_general_ci */ NOT NULL,
 `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
 `idx` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `subject` varchar(255) NOT NULL,
 `from` varchar(255) NOT NULL,
 `to` varchar(255) NOT NULL,
 `cc` varchar(255) NOT NULL,
 `date` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
 `size` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `headers` text NOT NULL,
 `structure` text,
 PRIMARY KEY(`message_id`),
 CONSTRAINT `user_id_fk_messages` FOREIGN KEY (`user_id`)
   REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
 INDEX `created_index` (`created`),
 INDEX `index_index` (`user_id`, `cache_key`, `idx`),
 UNIQUE `uniqueness` (`user_id`, `cache_key`, `uid`)
) /*!40000 ENGINE=INNODB */ /*!40101 CHARACTER SET utf8 COLLATE utf8_general_ci */;


-- Table structure for table `cache`

CREATE TABLE `cache` (
 `cache_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 `cache_key` varchar(128) /*!40101 CHARACTER SET ascii COLLATE ascii_general_ci */ NOT NULL ,
 `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
 `data` longtext NOT NULL,
 `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
 PRIMARY KEY(`cache_id`),
 CONSTRAINT `user_id_fk_cache` FOREIGN KEY (`user_id`)
   REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
 INDEX `created_index` (`created`),
 INDEX `user_cache_index` (`user_id`,`cache_key`)
) /*!40000 ENGINE=INNODB */ /*!40101 CHARACTER SET utf8 COLLATE utf8_general_ci */;


-- Table structure for table `contacts`

CREATE TABLE `contacts` (
 `contact_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
 `del` tinyint(1) NOT NULL DEFAULT '0',
 `name` varchar(128) NOT NULL DEFAULT '',
 `email` varchar(128) NOT NULL,
 `firstname` varchar(128) NOT NULL DEFAULT '',
 `surname` varchar(128) NOT NULL DEFAULT '',
 `vcard` text NULL,
 `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
 PRIMARY KEY(`contact_id`),
 CONSTRAINT `user_id_fk_contacts` FOREIGN KEY (`user_id`)
   REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
 INDEX `user_contacts_index` (`user_id`,`email`)
) /*!40000 ENGINE=INNODB */ /*!40101 CHARACTER SET utf8 COLLATE utf8_general_ci */;

-- Table structure for table `contactgroups`

CREATE TABLE `contactgroups` (
  `contactgroup_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY(`contactgroup_id`),
  CONSTRAINT `user_id_fk_contactgroups` FOREIGN KEY (`user_id`)
    REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `contactgroups_user_index` (`user_id`,`del`)
) /*!40000 ENGINE=INNODB */ /*!40101 CHARACTER SET utf8 COLLATE utf8_general_ci */;

CREATE TABLE `contactgroupmembers` (
  `contactgroup_id` int(10) UNSIGNED NOT NULL,
  `contact_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`contactgroup_id`, `contact_id`),
  CONSTRAINT `contactgroup_id_fk_contactgroups` FOREIGN KEY (`contactgroup_id`)
    REFERENCES `contactgroups`(`contactgroup_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `contact_id_fk_contacts` FOREIGN KEY (`contact_id`)
    REFERENCES `contacts`(`contact_id`) ON DELETE CASCADE ON UPDATE CASCADE
) /*!40000 ENGINE=INNODB */;


-- Table structure for table `identities`

CREATE TABLE `identities` (
 `identity_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
 `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
 `del` tinyint(1) NOT NULL DEFAULT '0',
 `standard` tinyint(1) NOT NULL DEFAULT '0',
 `name` varchar(128) NOT NULL,
 `organization` varchar(128) NOT NULL DEFAULT '',
 `email` varchar(128) NOT NULL,
 `reply-to` varchar(128) NOT NULL DEFAULT '',
 `bcc` varchar(128) NOT NULL DEFAULT '',
 `signature` text,
 `html_signature` tinyint(1) NOT NULL DEFAULT '0',
 PRIMARY KEY(`identity_id`),
 CONSTRAINT `user_id_fk_identities` FOREIGN KEY (`user_id`)
   REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
 INDEX `user_identities_index` (`user_id`, `del`)
) /*!40000 ENGINE=INNODB */ /*!40101 CHARACTER SET utf8 COLLATE utf8_general_ci */;

-- Table structure for table `accounts`

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

-- Table structure for table `events`

CREATE TABLE `events` (
  `event_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `start` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `end` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `summary` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL DEFAULT '',
  `categories` varchar(255) NOT NULL DEFAULT '',
  `all_day` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY(`event_id`),
  CONSTRAINT `user_id_fk_events` FOREIGN KEY (`user_id`)
    REFERENCES `users`(`user_id`)
    /*!40008
      ON DELETE CASCADE
      ON UPDATE CASCADE */
) /*!40000 ENGINE=INNODB */ /*!40101 CHARACTER SET utf8 COLLATE utf8_general_ci */;

-- Table structure for table `google_contacts`

CREATE TABLE google_contacts LIKE contacts;

ALTER TABLE `google_contacts`
  ADD CONSTRAINT `google_contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;


/*!40014 SET FOREIGN_KEY_CHECKS=1 */;


-- Updates from version 0.1-stable to 0.3.1

TRUNCATE TABLE `messages`;

ALTER TABLE `messages`
  DROP INDEX `idx`,
  DROP INDEX `uid`;

ALTER TABLE `cache`
  DROP INDEX `cache_key`,
  DROP INDEX `session_id`,
  ADD INDEX `user_cache_index` (`user_id`,`cache_key`);

ALTER TABLE `users`
    ADD INDEX `username_index` (`username`),
    ADD INDEX `alias_index` (`alias`);

-- Updates from version 0.1.1

ALTER TABLE `identities`
    MODIFY `signature` text,
    MODIFY `bcc` varchar(128) NOT NULL DEFAULT '',
    MODIFY `reply-to` varchar(128) NOT NULL DEFAULT '',
    MODIFY `organization` varchar(128) NOT NULL DEFAULT '',
    MODIFY `name` varchar(128) NOT NULL,
    MODIFY `email` varchar(128) NOT NULL;

-- Updates from version 0.2-alpha

ALTER TABLE `messages`
    ADD INDEX `created_index` (`created`);

-- Updates from version 0.2-beta (InnoDB only)

ALTER TABLE `cache`
    DROP `session_id`;

ALTER TABLE `session`
    ADD INDEX `changed_index` (`changed`);

ALTER TABLE `cache`
    ADD INDEX `created_index` (`created`);

ALTER TABLE `users`
    CHANGE `language` `language` varchar(5);

-- Updates from version 0.3-stable

TRUNCATE `messages`;

ALTER TABLE `messages`
    ADD INDEX `index_index` (`user_id`, `cache_key`, `idx`);

ALTER TABLE `session`
    CHANGE `vars` `vars` MEDIUMTEXT NOT NULL;

ALTER TABLE `contacts`
    ADD INDEX `user_contacts_index` (`user_id`,`email`);

-- Updates from version 0.3.1

/* MySQL bug workaround: http://bugs.mysql.com/bug.php?id=46293 */
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

ALTER TABLE `messages` DROP FOREIGN KEY `user_id_fk_messages`;
ALTER TABLE `cache` DROP FOREIGN KEY `user_id_fk_cache`;
ALTER TABLE `contacts` DROP FOREIGN KEY `user_id_fk_contacts`;
ALTER TABLE `identities` DROP FOREIGN KEY `user_id_fk_identities`;

ALTER TABLE `messages` ADD CONSTRAINT `user_id_fk_messages` FOREIGN KEY (`user_id`)
 REFERENCES `users`(`user_id`);
ALTER TABLE `cache` ADD CONSTRAINT `user_id_fk_cache` FOREIGN KEY (`user_id`)
 REFERENCES `users`(`user_id`);
ALTER TABLE `contacts` ADD CONSTRAINT `user_id_fk_contacts` FOREIGN KEY (`user_id`)
 REFERENCES `users`(`user_id`);
ALTER TABLE `identities` ADD CONSTRAINT `user_id_fk_identities` FOREIGN KEY (`user_id`)
 REFERENCES `users`(`user_id`);

ALTER TABLE `contacts` ALTER `name` SET DEFAULT '';
ALTER TABLE `contacts` ALTER `firstname` SET DEFAULT '';
ALTER TABLE `contacts` ALTER `surname` SET DEFAULT '';

ALTER TABLE `identities` ADD INDEX `user_identities_index` (`user_id`, `del`);
ALTER TABLE `identities` ADD `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00' AFTER `user_id`;

CREATE TABLE `contactgroups` (
  `contactgroup_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY(`contactgroup_id`),
  CONSTRAINT `user_id_fk_contactgroups` FOREIGN KEY (`user_id`)
    REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `contactgroups_user_index` (`user_id`,`del`)
) /*!40000 ENGINE=INNODB */ /*!40101 CHARACTER SET utf8 COLLATE utf8_general_ci */;

CREATE TABLE `contactgroupmembers` (
  `contactgroup_id` int(10) UNSIGNED NOT NULL,
  `contact_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`contactgroup_id`, `contact_id`),
  CONSTRAINT `contactgroup_id_fk_contactgroups` FOREIGN KEY (`contactgroup_id`)
    REFERENCES `contactgroups`(`contactgroup_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `contact_id_fk_contacts` FOREIGN KEY (`contact_id`)
    REFERENCES `contacts`(`contact_id`) ON DELETE CASCADE ON UPDATE CASCADE
) /*!40000 ENGINE=INNODB */;

/*!40014 SET FOREIGN_KEY_CHECKS=1 */;

/* Google Contacts Section */;
CREATE TABLE google_contacts LIKE contacts;

ALTER TABLE `google_contacts`
  ADD CONSTRAINT `google_contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
