CREATE TABLE IF NOT EXISTS `{table_prefix}content` (
  `id` int(11) NOT NULL auto_increment,
  `version` int(11) default NULL,
  `title` varchar(200) default NULL,
  `uri` varchar(200) default NULL,
  `intro` text,
  `body` text,
  `publish_on` datetime default NULL,
  `created_on` datetime default NULL,
  `created_by` int(11) default NULL,
  `modified_on` datetime default NULL,
  `modified_by` int(11) default NULL,
  `tags` text,
  `status` enum('published','draft','secured') default 'published',
  `comment_status` enum('open','closed') default 'open',
  `password` varchar(200) default NULL,
  `view` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `{table_prefix}content` (`id`,`version`,`title`,`uri`,`intro`,`body`,`publish_on`,`created_on`,`created_by`,`modified_on`,`modified_by`,`tags`,`status`,`comment_status`,`password`,`view`) VALUES ('1','1','Home','home','<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>\n<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>\n<p>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>','<p>&nbsp;</p>','2007-11-25 13:36:10','2007-11-25 13:36:10','1','2007-11-25 13:36:10','1',NULL,'published','closed',NULL,NULL);
INSERT INTO `{table_prefix}content` (`id`,`version`,`title`,`uri`,`intro`,`body`,`publish_on`,`created_on`,`created_by`,`modified_on`,`modified_by`,`tags`,`status`,`comment_status`,`password`,`view`) VALUES ('2','1','Products','products','<h2>Product A</h2>\n<p>Beschreibung zum Produkt A</p>\n<h2>Produkt B</h2>\n<p>Beschreibung zum Produkt B</p>','<p>&nbsp;</p>','2007-11-25 13:36:39','2007-11-25 13:36:39','1','2007-11-25 13:36:39','1',NULL,'published','closed',NULL,NULL);
INSERT INTO `{table_prefix}content` (`id`,`version`,`title`,`uri`,`intro`,`body`,`publish_on`,`created_on`,`created_by`,`modified_on`,`modified_by`,`tags`,`status`,`comment_status`,`password`,`view`) VALUES ('3','1','About Me','about-me','<p>Hello, my Name is Max Mustermann. I live in Sydney.</p>','<p>&nbsp;</p>','2007-11-25 13:37:13','2007-11-25 13:37:13','1','2007-11-25 13:37:13','1',NULL,'published','closed',NULL,NULL);
INSERT INTO `{table_prefix}content` (`id`,`version`,`title`,`uri`,`intro`,`body`,`publish_on`,`created_on`,`created_by`,`modified_on`,`modified_by`,`tags`,`status`,`comment_status`,`password`,`view`) VALUES ('4','1','Contact Me','contact-me','<p>E-Mail: max.mustermann@example.com<br />ICQ: 123456789</p>\n<p>Max Mustermann<br />Muster Avenue 12<br />12345 City</p>','<p>&nbsp;</p>','2007-11-25 13:37:46','2007-11-25 13:37:46','1','2007-11-25 13:37:46','1',NULL,'published','closed',NULL,NULL);

CREATE TABLE IF NOT EXISTS `{table_prefix}pages` (
  `id` int(11) NOT NULL auto_increment,
  `content_id` int(11) default NULL,
  `sidebar_content` text,
  `meta_keywords` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `{table_prefix}pages` (`id`,`content_id`,`sidebar_content`,`meta_keywords`) VALUES ('1','1','<p>&nbsp;</p>','');
INSERT INTO `{table_prefix}pages` (`id`,`content_id`,`sidebar_content`,`meta_keywords`) VALUES ('2','2','<p>&nbsp;</p>','');
INSERT INTO `{table_prefix}pages` (`id`,`content_id`,`sidebar_content`,`meta_keywords`) VALUES ('3','3','<p>&nbsp;</p>','');
INSERT INTO `{table_prefix}pages` (`id`,`content_id`,`sidebar_content`,`meta_keywords`) VALUES ('4','4','<p>&nbsp;</p>','');

CREATE TABLE IF NOT EXISTS `{table_prefix}roles` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `{table_prefix}roles` (`id`,`name`,`description`) VALUES ('1','login','Login privileges, granted after account confirmation');
INSERT INTO `{table_prefix}roles` (`id`,`name`,`description`) VALUES ('2','admin','Administrative user, has access to everything.');

CREATE TABLE IF NOT EXISTS `{table_prefix}settings` (
  `id` int(11) NOT NULL auto_increment,
  `context` varchar(200) NOT NULL,
  `key` varchar(200) NOT NULL,
  `value` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `{table_prefix}settings` (`id`,`context`,`key`,`value`) VALUES ('1','s7n','default_uri','home');
INSERT INTO `{table_prefix}settings` (`id`,`context`,`key`,`value`) VALUES ('2','page','views','default, extended');


CREATE TABLE IF NOT EXISTS `{table_prefix}users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `email` varchar(200) NOT NULL default '',
  `username` varchar(200) NOT NULL default '',
  `password` char(50) NOT NULL,
  `logins` int(10) unsigned NOT NULL default '0',
  `homepage` varchar(200) default NULL,
  `first_name` varchar(200) default NULL,
  `last_name` varchar(200) default NULL,
  `registered_on` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{table_prefix}users_roles` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`user_id`,`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
