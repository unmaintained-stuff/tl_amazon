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
 * Add palettes to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['amazonwishlist']    = '{title_legend},name,headline,type;{amazon_legend},amazonlistid,amazonwishlisttemplate,amazonperpage,amazonshowpurchased,amazonsortby;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Add fields to tl_module
 */
array_insert($GLOBALS['TL_DCA']['tl_module']['fields'] , 1, array
(
	'amazonlistid' => array
	(
		'label'						=> &$GLOBALS['TL_LANG']['tl_module']['amazonlistid'],
		'exclude'					=> true,
		'inputType'					=> 'text',
		'default'               	=> '',
		'eval'						=> array('tl_class'=>'w50', 'mandatory'=>true, 'maxlength'=>255)
	),
	'amazonperpage' => array
	(
		'label'						=> &$GLOBALS['TL_LANG']['tl_module']['amazonperpage'],
		'exclude'					=> true,
		'inputType'					=> 'text',
		'eval'						=> array('tl_class'=>'w50', 'rgxp'=>'digit', 'nospace'=>true)
	),
	'amazonshowpurchased' => array
	(
		'label'						=> &$GLOBALS['TL_LANG']['tl_module']['amazonshowpurchased'],
		'exclude'					=> true,
		'inputType'					=> 'checkbox',
		'eval'						=> array('tl_class'=>'w50')
	),
	'amazonsortby' => array
	(
		'label'						=> &$GLOBALS['TL_LANG']['tl_module']['amazonsortby'],
		'exclude'					=> true,
		'inputType'					=> 'select',
		'options'					=> array('DateAdded', 'LastUpdated', 'Price', 'Priority'),
		'eval'						=> array('tl_class'=>'w50')
	),
	'amazonwishlisttemplate' => array
	(
		'label'						=> &$GLOBALS['TL_LANG']['tl_module']['amazonwishlisttemplate'],
		'default'					=> 'mod_amazon_wishlist_table',
		'exclude'					=> true,
		'inputType'					=> 'select',
		'options'					=> $this->getTemplateGroup('mod_amazon_wishlist'),
		'eval'						=> array('tl_class'=>'w50')
	),
));

?>