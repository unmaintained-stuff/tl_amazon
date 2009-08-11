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

$GLOBALS['TL_LANG']['tl_settings']['amazon_legend'] = 'Amazon Webservices Konfiguration';
$GLOBALS['TL_LANG']['tl_settings']['amazoncaching'] = array('Caching von Daten', 'Bitte geben Sie hier die Anzahl in Sekunden ein, wie lange Daten wie Listen etc. zwischengespeichert werden sollen. Eine Angabe von 0 deaktiviert den Cache.');
$GLOBALS['TL_LANG']['tl_settings']['amazonregion'] = array('Zuständiger Amazon server', 'Bitte wählen Sie den Amazon WS server, welchen Sie verwenden wollen.');
$GLOBALS['TL_LANG']['tl_settings']['amazonsecretkey'] = array('Ihr Secret Access Key', 'Bitte geben Sie ihren Secret Access Key ein. Dieser wird benötigt um die Anfragen an Amazon zu signieren.');
$GLOBALS['TL_LANG']['tl_settings']['amazonapikey'] = array('Access Key ID', 'Bitte geben Sie ihren Access Key ID ein.');

$GLOBALS['TL_LANG']['tl_settings']['region'] = array('com' => 'USA', 'co.uk' => 'England', 'jp' => 'Japan', 'fr' => 'Franreich', 'de' => 'Deutschland', 'ca' => 'Kanada');
?>
