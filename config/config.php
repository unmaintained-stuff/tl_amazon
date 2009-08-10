<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 *
 * The TYPOlight webCMS is an accessible web content management system that 
 * specializes in accessibility and generates W3C-compliant HTML code. It 
 * provides a wide range of functionality to develop professional websites 
 * including a built-in search engine, form generator, file and user manager, 
 * CSS engine, multi-language support and many more. For more information and 
 * additional TYPOlight applications like the TYPOlight MVC Framework please 
 * visit the project website http://www.typolight.org.
 * 
 * PHP version 5
 * @copyright	Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @package		Amazon
 * @license		LGPL 
 * @filesource
 */


/**
 * Front-end modules
 */

$GLOBALS['FE_MOD']['amazon'] = array
	(
		'amazonwishlist'		=> 'ModuleAmazonWishlist',
	);

/**
 * CONFIG Parameters
 */

$GLOBALS['TL_CONFIG']['amazon']['default']	= 'com';
$GLOBALS['TL_CONFIG']['amazon']['AMAZON_ECS_SCHEMA']	= '2009-03-31';
$GLOBALS['TL_CONFIG']['amazon']['AMAZON_PARTICIPANT_TYPES']	= 'Author,Artist,Actor,Director,Creator';
$GLOBALS['TL_CONFIG']['amazon']['locales'] = array('com', 'co.uk', 'jp', 'fr', 'de', 'ca'); 

?>