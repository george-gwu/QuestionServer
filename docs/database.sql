
--
-- Create schema question
--

CREATE DATABASE IF NOT EXISTS question;
USE question;

--
-- Definition of table `question`.`answers`
--

DROP TABLE IF EXISTS `question`.`answers`;
CREATE TABLE  `question`.`answers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `questionID` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `isValid` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question`.`answers`
--

/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
LOCK TABLES `answers` WRITE;
INSERT INTO `question`.`answers` VALUES  (1,1,'3',0),
 (2,1,'4',0),
 (3,1,'5',0),
 (4,1,'6',0),
 (5,1,'7',0),
 (6,2,'True',0),
 (7,2,'False',0),
 (16,6,'H2O',1),
 (17,6,'H2O2',0),
 (18,6,'HO',0),
 (19,7,'USA has 55 states',1),
 (20,7,'China is in Mid-East',0),
 (21,7,'Turkey crosses two conticents',1),
 (22,8,'Yes',1),
 (23,8,'No',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;


--
-- Definition of table `question`.`exam`
--

DROP TABLE IF EXISTS `question`.`exam`;
CREATE TABLE  `question`.`exam` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `timeLimit` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `owner` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question`.`exam`
--

/*!40000 ALTER TABLE `exam` DISABLE KEYS */;
LOCK TABLES `exam` WRITE;
INSERT INTO `question`.`exam` VALUES  (1,'Test Exam','',60,1,'test'),
 (2,'Other Test Exam','password',NULL,2,'test'),
 (4,'Test Exam','123',60,2,'test');
UNLOCK TABLES;
/*!40000 ALTER TABLE `exam` ENABLE KEYS */;


--
-- Definition of table `question`.`questions`
--

DROP TABLE IF EXISTS `question`.`questions`;
CREATE TABLE  `question`.`questions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `examID` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `examindex` (`examID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question`.`questions`
--

/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
LOCK TABLES `questions` WRITE;
INSERT INTO `question`.`questions` VALUES  (1,1,'What is 2+2?'),
 (2,1,'The sky is blue.'),
 (6,4,'What is water?'),
 (7,4,'Which is correct?'),
 (8,4,'Is acceleration of gravity 9.8 m/s^2?');
UNLOCK TABLES;
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;


--
-- Definition of table `question`.`scores`
--

DROP TABLE IF EXISTS `question`.`scores`;
CREATE TABLE  `question`.`scores` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `examID` int(11) NOT NULL,
  `score` int(3) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `userexam` (`user`,`examID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question`.`scores`
--

/*!40000 ALTER TABLE `scores` DISABLE KEYS */;
LOCK TABLES `scores` WRITE;
INSERT INTO `question`.`scores` VALUES  (1,'test',1,100);
UNLOCK TABLES;
/*!40000 ALTER TABLE `scores` ENABLE KEYS */;


--
-- Definition of table `question`.`user`
--

DROP TABLE IF EXISTS `question`.`user`;
CREATE TABLE  `question`.`user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `role` int(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question`.`user`
--

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
LOCK TABLES `user` WRITE;
INSERT INTO `question`.`user` VALUES  (1,'test','b8ffd16722f742ef29e9e8f0174379d83ef5b5c9',1),
 (2,'test2','89c4db0ea3ed2b6446208398bfa41a6ff0b9692f',2),
 (3,'Mehmet','58693f65b77c39967e79a0da6b3b22f2b3568f26',2),
 (4,'Eamin','301a1fc76473efc573a4b6a22a1b078f995c1e30',1),
 (5,'EaminZ','3407a1911e66797214a5ab3e47c3a8b40d61203d',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

