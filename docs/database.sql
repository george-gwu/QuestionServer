
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
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question`.`answers`
--

/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
LOCK TABLES `answers` WRITE;
INSERT INTO `question`.`answers` VALUES  (1,1,'3'),
 (2,1,'4'),
 (3,1,'5'),
 (4,1,'6'),
 (5,1,'7'),
 (6,2,'True'),
 (7,2,'False');
UNLOCK TABLES;
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;


--
-- Definition of table `question`.`exam`
--

DROP TABLE IF EXISTS `question`.`exam`;
CREATE TABLE  `question`.`exam` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `timeLimit` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `owner` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question`.`exam`
--

/*!40000 ALTER TABLE `exam` DISABLE KEYS */;
LOCK TABLES `exam` WRITE;
INSERT INTO `question`.`exam` VALUES  (1,'Test Exam',NULL,60,1,'test'),
 (2,'Other Test Exam','password',NULL,1,'test');
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
  `validAnswerID` int(2) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `examindex` (`examID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question`.`questions`
--

/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
LOCK TABLES `questions` WRITE;
INSERT INTO `question`.`questions` VALUES  (1,1,'What is 2+2?',2),
 (2,1,'The sky is blue.',6);
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question`.`scores`
--

/*!40000 ALTER TABLE `scores` DISABLE KEYS */;
LOCK TABLES `scores` WRITE;
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question`.`user`
--

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
LOCK TABLES `user` WRITE;
INSERT INTO `question`.`user` VALUES  (1,'test','b8ffd16722f742ef29e9e8f0174379d83ef5b5c9',1),
 (2,'test2','89c4db0ea3ed2b6446208398bfa41a6ff0b9692f',2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;


