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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
