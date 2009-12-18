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
 * @copyright	CyberSpectrum 2007-2009
 * @author		Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @package		Amazon
 * @license		LGPL 
 * @filesource
 */

$GLOBALS['TL_LANG']['tl_settings']['amazon_legend'] = 'Amazon webservices configuration';
$GLOBALS['TL_LANG']['tl_settings']['amazoncaching'] = array('Caching of data', 'Please specify how long (in seconds) retrieved data shall be cached. A value of 0 disables the cache altogether.');
$GLOBALS['TL_LANG']['tl_settings']['amazonregion'] = array('Amazon server for your location', 'Please select the Amazon WS server you want to use.');
$GLOBALS['TL_LANG']['tl_settings']['amazonsecretkey'] = array('Your Secret Access Key for signing requests', 'Please enter your Secret Access Key. This is needed to sign the requests.');
$GLOBALS['TL_LANG']['tl_settings']['amazonapikey'] = array('Access Key ID', 'Please enter your Amazon Access Key ID.');

$GLOBALS['TL_LANG']['tl_settings']['region'] = array('com' => 'USA', 'co.uk' => 'United Kingdom', 'jp' => 'Japan', 'fr' => 'France', 'de' => 'Germany', 'ca' => 'Canada');

?>
