CREATE TABLE `users` (
  `id` int(199) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(199) NOT NULL,
  `password` varchar(199) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);
