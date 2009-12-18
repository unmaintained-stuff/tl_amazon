-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- --------------------------------------------------------
-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `amazonlistid` varchar(255) NOT NULL default '',
  `amazonwishlisttemplate` varchar(255) NOT NULL default '',
  `amazonperpage` int(3) NOT NULL default '0',
  `amazonshowpurchased` int(1) NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tl_amazon_cache` (
  `id` int(10) unsigned NOT NULL auto_increment,  
  `hashkey` varchar(255) NOT NULL default '',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `data` text NULL
  PRIMARY KEY  (`id`), 
  KEY `tstamp` (`tstamp`),
  KEY `hashkey` (`hashkey`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;