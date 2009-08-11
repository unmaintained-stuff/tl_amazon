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


/**
 * Add palettes to tl_settings
 */

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{amazon_legend:hide},secretkey,apikey,amazonregion,amazoncaching';

/**
 * Add fields to tl_settings
 */
array_insert($GLOBALS['TL_DCA']['tl_settings']['fields'] , 1, array
(
	'amazonregion' => array
	(
		'label'						=> &$GLOBALS['TL_LANG']['tl_settings']['amazonregion'],
		'exclude'					=> true,
		'inputType'					=> 'select',
		'options_callback'			=> array('tl_settings_amazon', 'getServers'),
		'default'					=> '',
		'eval'						=> array('mandatory'=>true, 'nospace'=>true, 'tl_class'=>'w50')
	),
	'secretkey' => array
	(
		'label'						=> &$GLOBALS['TL_LANG']['tl_settings']['amazonsecretkey'],
		'exclude'					=> true,
		'inputType'					=> 'text',
		'default'					=> '',
		'eval'						=> array('mandatory'=>true, 'nospace'=>true, 'tl_class'=>'w50')
	),
	'apikey' => array
	(
		'label'						=> &$GLOBALS['TL_LANG']['tl_settings']['amazonapikey'],
		'exclude'					=> true,
		'inputType'					=> 'text',
		'default'					=> '',
		'eval'						=> array('mandatory'=>true, 'nospace'=>true, 'tl_class'=>'w50')
	),
	'amazoncaching' => array
	(
		'label'						=> &$GLOBALS['TL_LANG']['tl_settings']['amazoncaching'],
		'exclude'					=> true,
		'inputType'					=> 'text',
		'default'					=> '3600',
		'eval'						=> array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
	),
));

class tl_settings_amazon extends Backend
{
	public function getServers() {
		$result = array();
		foreach($GLOBALS['TL_CONFIG']['amazon']['locales'] as $key)
		{
			$result[$key] = $GLOBALS['TL_LANG']['tl_settings']['region'][$key];
;
		}
		return $result;
	}
}
?>