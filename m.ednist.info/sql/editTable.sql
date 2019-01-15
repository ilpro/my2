-- Vova, 27.12.2014 00.22
-- `ctbl_autosavenews`
-- add:
       `newsHeader` varchar(255), 
       `newsSubheader` varchar(255)

-----------------------------------------------------------------------
-- Vova, 08.01.2015 16.00
--
-- Table structure for table `ctbl_themes`
--

CREATE TABLE IF NOT EXISTS `ctbl_themes` (
  `newsId` int(11) NOT NULL,
  `themesId` int(11) NOT NULL,
  `themesSmile` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-нейтрально,1-негатив,2-позитив'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `ctbl_themesimg`
--

CREATE TABLE IF NOT EXISTS `ctbl_themesimg` (
  `imgId` int(11) NOT NULL,
  `themesId` int(11) NOT NULL,
  `imgName` varchar(255) NOT NULL,
  `imgDesc` mediumtext NOT NULL,
  `imgMain` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=789 DEFAULT CHARSET=utf8;


--
-- Table structure for table `tbl_themes`
--

CREATE TABLE IF NOT EXISTS `tbl_themes` (
  `themesId` int(11) NOT NULL,
  `themesStatus` tinyint(4) NOT NULL DEFAULT '0',
  `themesName` varchar(255) NOT NULL,
  `themesImg` varchar(255) NOT NULL,
  `themesDesc` text NOT NULL,
  `themesSearch` varchar(255) NOT NULL,
  `themesTimeUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `themesSortName` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `themesType` int(11) DEFAULT NULL,
  `themesActive` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

--
-- Stand-in structure for view `vtbl_themes`
--

CREATE TABLE IF NOT EXISTS `vtbl_themes` (
`themesId` int(11)
,`themesScore` bigint(21)
,`themesName` varchar(255)
,`imgMain` varchar(255)
,`imgMainDesc` mediumtext
,`imgNames` varchar(341)
,`imgDescs` varchar(341)
,`themesActive` tinyint(1)
,`themesType` int(11)
,`themesSortName` varchar(200)
,`themesImg` varchar(255)
,`themesStatus` tinyint(4)
,`themesDesc` text
,`themesTimeUpdate` bigint(10)
,`newsIds` varchar(256)
,`themesSearch` varchar(255)
);


--
-- Structure for view `vtbl_themes`
--
DROP TABLE IF EXISTS `vtbl_themes`;

CREATE VIEW `vtbl_themes` AS select `tbl_themes`.`themesId` AS `themesId`,count(0) AS `themesScore`,`tbl_themes`.`themesName` AS `themesName`,(select `ctbl_themesimg`.`imgName` from `ctbl_themesimg` where ((`tbl_themes`.`themesId` = `ctbl_themesimg`.`themesId`) and (`ctbl_themesimg`.`imgMain` = 1))) AS `imgMain`,(select `ctbl_themesimg`.`imgDesc` from `ctbl_themesimg` where ((`tbl_themes`.`themesId` = `ctbl_themesimg`.`themesId`) and (`ctbl_themesimg`.`imgMain` = 1))) AS `imgMainDesc`,(select group_concat(`ctbl_themesimg`.`imgName` separator '|') AS `imgName` from `ctbl_themesimg` where (`tbl_themes`.`themesId` = `ctbl_themesimg`.`themesId`)) AS `imgNames`,(select group_concat(`ctbl_themesimg`.`imgDesc` separator '|') AS `imgDesc` from `ctbl_themesimg` where (`tbl_themes`.`themesId` = `ctbl_themesimg`.`themesId`)) AS `imgDescs`,`tbl_themes`.`themesActive` AS `themesActive`,`tbl_themes`.`themesType` AS `themesType`,`tbl_themes`.`themesSortName` AS `themesSortName`,`tbl_themes`.`themesImg` AS `themesImg`,`tbl_themes`.`themesStatus` AS `themesStatus`,`tbl_themes`.`themesDesc` AS `themesDesc`,unix_timestamp(`tbl_themes`.`themesTimeUpdate`) AS `themesTimeUpdate`,(select group_concat(`ctbl_themes`.`newsId` separator '|') AS `newsIds` from `ctbl_themes` where (`ctbl_themes`.`themesId` = `tbl_themes`.`themesId`)) AS `newsIds`,`tbl_themes`.`themesSearch` AS `themesSearch` from (`tbl_themes` left join `ctbl_themes` on((`ctbl_themes`.`themesId` = `tbl_themes`.`themesId`))) group by `tbl_themes`.`themesId`;

--
-- Indexes for table `ctbl_themes`
--
ALTER TABLE `ctbl_themes`
  ADD KEY `newsId` (`newsId`,`themesId`), ADD KEY `themesId` (`themesId`);

--
-- Indexes for table `ctbl_themesimg`
--
ALTER TABLE `ctbl_themesimg`
  ADD UNIQUE KEY `imgId` (`imgId`), ADD KEY `themesId` (`themesId`);

--
-- Indexes for table `tbl_themes`
--
ALTER TABLE `tbl_themes`
  ADD PRIMARY KEY (`themesId`);

--
-- AUTO_INCREMENT for table `ctbl_themesimg`
--
ALTER TABLE `ctbl_themesimg`
  MODIFY `imgId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=789;

--
-- AUTO_INCREMENT for table `tbl_themes`
--
ALTER TABLE `tbl_themes`
  MODIFY `themesId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=105;

-- `vtbl_news`
-- add:
,`themesIds` varchar(256)
,(select group_concat(`ctbl_themes`.`themesId` separator '|') AS `themesId` from `ctbl_themes` where (`tbl_news`.`newsId` = `ctbl_themes`.`newsId`)) AS `themesIds`


--Vova 10.01.2015 14.48
--add table
CREATE TABLE IF NOT EXISTS `ctbl_usereditnews` (
  `newsId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `inTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
