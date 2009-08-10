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
 * Class AmazonRequest
 *
 * @copyright	CyberSpectrum 2009
 * @author		Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @package		Controller
 *
 */
class AmazonRequest extends Request
{
	
		/*
		Parameters:
		$region - the Amazon(r) region (ca,com,co.uk,de,fr,jp)
		$params - an array of parameters, eg. array("Operation"=>"ItemLookup", "ItemId"=>"B000X9FLKM", "ResponseGroup"=>"Small")
		$public_key - your "Access Key ID"
		$private_key - your "Secret Access Key"
		*/
	function aws_signed($region, $params, $public_key, $private_key)
	{
		// based upon code copyright (c) 2009 Ulrich Mierendorff
		// some paramters
		$method = "GET";
		$host = "ecs.amazonaws.".$region;
		$uri = "/onca/xml";
		// additional parameters
		$params["Service"] = "AWSECommerceService";
		$params["AWSAccessKeyId"] = $public_key;
		// GMT timestamp
		$params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
		// API version
		$params["Version"] = $GLOBALS['TL_CONFIG']['amazon']['AMAZON_ECS_SCHEMA'];
		// sort the parameters
		ksort($params);
		// create the canonicalized query
		$canonicalized_query = array();
		foreach ($params as $param=>$value)
		{
			$param = str_replace("%7E", "~", rawurlencode($param));
			$value = str_replace("%7E", "~", rawurlencode($value));
			$canonicalized_query[] = $param."=".$value;
		}
		$canonicalized_query = implode("&", $canonicalized_query);
		// create the string to sign
		$string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
		// calculate HMAC with SHA256 and base64-encoding
		$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));
		// encode the signature for the request
		$signature = str_replace("%7E", "~", rawurlencode($signature));
		// create request
		return "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;
	}

	public function prepareURL($operation, $params=array()) {
		if(!is_array($params))
			return '';
		$params += array('Operation' => $operation);
		// TODO: add associat ID support here.
		// $parameters += array('AssociateTag' => $associate_id,);
		$region = $GLOBALS['TL_CONFIG']['amazonregion'] ? $GLOBALS['TL_CONFIG']['amazonregion'] : $GLOBALS['TL_CONFIG']['amazon']['default'];
		return $this->aws_signed($region, $params, $GLOBALS['TL_CONFIG']['apikey'],  $GLOBALS['TL_CONFIG']['secretkey']);
	}

	private function decode_chunked($string=null) {
		$lines = explode("\r\n",$string);
		$i=0;
		$length = 999;
		$content = '';
		foreach($lines as $line) {
			$i++;
			if ($i%2 == 1) {
				$length = hexdec($line);
			} elseif ($length == strlen($line)) {
			$content .= $line;
		}
			if ($length == 0)
				break;
		}
		return $content;
	}

	public function execute($operation, $params)
	{
		// fetch data from amazon.
		$url=$this->prepareURL($operation, $params);
		$objRequest = new Request();
		$objRequest->send($url);
		// content is most likely encoded as chunked, fix this.
		if(array_key_exists('Transfer-Encoding', $objRequest->headers) && $objRequest->headers['Transfer-Encoding'] == 'chunked')
			$xml = $this->decode_chunked($objRequest->response);
		else
			$xml=$objRequest->response;
		if (!$objRequest->hasError())
		{
			try {
				$xml = new SimpleXMLElement($xml);
				return $xml;
			} catch (Exception $e) {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function loadList($params)
	{
		return $this->execute('ListLookup', $params);
	}
	
	public function addItemToCart($params, $cartID=NULL)
	{
		if(!$cartID)
			
		return $this->execute('ListLookup', $params);
	}

	
};
?>