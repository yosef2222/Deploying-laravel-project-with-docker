DROP TABLE IF EXISTS `places1`;

CREATE TABLE `places` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visited` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `places` (name, visited) VALUES ('Tokyo',0),('Budapest',0),('Verkhnyaa Sinyachikhai',1),('Strassbourg',0),('Penza',1),('Serov',1),('Nevyansk',1),('Nairobi',0),('Edinburg',1),('Kirovgrad',1),('Alapayevsk',0);
