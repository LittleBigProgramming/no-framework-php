# no-framework-php (Work in Progress)

This project is aimed to create a personal framework that is robust and fit for reuse. After alot of Development using frameworks I have noticed myself become somewhat reliant on them.

The following statement can be ran to create a table for the users.
```
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `remember_token` varchar(255) DEFAULT '',
  `remember_identifier` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
```
