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
 * Class ModuleAmazonWishlist
 *
 * @copyright	CyberSpectrum 2009
 * @author		Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @package		Controller
 *
 */
abstract class ModuleAmazon extends Module
{
	// request obj.
	var $request = NULL;
	
	var $priomap = array('lowest' => '-2', 'low' => '-1', 'medium' => '0', 'high' => '1', 'highest' => '2',);
	
	protected function ConvertDateToTimestamp($date)
	{
		// convert YYYY-mm-dd to timestamp.
		return mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));
	}

	protected function ConvertDate($date)
	{
		// convert YYYY-mm-dd to timestamp and then to tl compatible format.
		return $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $this->ConvertDateToTimestamp($date));
		
	}

	/**
	 * Set default values
	 */
	public function __construct(Database_Result $dc)
	{
		parent::__construct($dc);
		$this->request = new AmazonRequest();
	}
	
	protected function decodeTransportURL($fieldname)
	{
		return urldecode($this->Input->get($fieldname));
	}
	
	protected function generateTransportURL($localcall, $params, $objPage=NULL)
	{
		$url='';
		foreach($params as $key=>$value)
			if(strlen($key))
				$url .= $key . '=' . urlencode($value) . '&amp;';
		$url = substr($url, 0, -5);
		if($objPage)
		{
			$Page = $objPage->row();
			return $this->generateFrontendUrl($Page, '&amp;amareq=' . urlencode($localcall) . '&amp;' . $url );
		} else {
			return $this->addToUrl('amareq=' . urlencode($localcall) . '&amp;' . $url );
		}
	}
 
	protected function executeRequest($operation, $parameters)
	{
		return $this->request->execute($operation, $parameters);
	}
	
	protected function getRequestURL($operation, $parameters)
	{
		return $this->request->prepareURL($operation, $parameters);
	}
	
	
	protected function compile()
	{
		// we need to inject our css.
		$GLOBALS['TL_CSS'][] = 'system/modules/amazon/html/amazon.css';
	}

};
?>