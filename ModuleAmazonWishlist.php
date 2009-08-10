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
class ModuleAmazonWishlist extends ModuleAmazon
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_amazon_wishlist';

	protected function loadList()
	{
		return $this->executeRequest('ListLookup', array(
													'ListType' => 'WishList', 
													'ListId' => $this->amazonlistid, 
													'ResponseGroup' => 'ListFull,Offers', 
													'IsOmitPurchasedItems' => 1));
	}

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### AMAZON WISHLIST ###';

			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		return parent::generate();
	}

	protected function compile()
	{
		parent::compile();
		// Template variables
		$this->strTemplate = $this->amazonwishlisttemplate;
		$this->Template = new FrontendTemplate($this->strTemplate);
		// initialize template.
		$this->Template->list=array();
		$this->Template->items=array();

		$xml=$this->loadList();
		$this->Template->xml=$xml->asXML();

		if($xml instanceof SimpleXMLElement)
		{
			$this->Template->isValid=$xml->Lists->Request->IsValid=='True';
			$this->Template->listName = (string)$xml->Lists->List->ListName;
			if($this->Template->isValid)
			{
				$this->Template->list=array
					(
						'type' => array(
										'label' => $GLOBALS['TL_LANG']['amazon']['listtype'], 
										'value' => (string)$xml->Lists->List->ListType
									),
						'name' => array(
										'label' => $GLOBALS['TL_LANG']['amazon']['listname'], 
										'value' => (string)$xml->Lists->List->ListName,
										// TODO I guess we have to export this link from here to somewhere else.
										'link'	=> sprintf('<a href="%s" title="%s">%s</a>',
													(string)$xml->Lists->List->ListURL,
													$GLOBALS['TL_LANG']['amazon']['showlist'],
													$GLOBALS['TL_LANG']['amazon']['showlist']
													), 
									),
						// wishlist URL
						// http://www.amazon.de/gp/registry/3J3K70FYE3EC
						'id' => array(
										'label' => $GLOBALS['TL_LANG']['amazon']['listid'], 
										'value' => (string)$xml->Lists->List->ListId,
									),
						'totalitems' => array(
										'label' => $GLOBALS['TL_LANG']['amazon']['totalitems'], 
										'value' => (string)$xml->Lists->List->TotalItems
									),
						'totalpages' => array(
										'label' => $GLOBALS['TL_LANG']['amazon']['totalpages'], 
										'value' => (string)$xml->Lists->List->TotalPages
									),
						'datecreated' => array(
										'label' => $GLOBALS['TL_LANG']['amazon']['datecreated'], 
										'value' => (string)$xml->Lists->List->DateCreated
									),
						'lastmodified' => array(
										'label' => $GLOBALS['TL_LANG']['amazon']['lastmodified'], 
										'value' => (string)$xml->Lists->List->LastModified
									),
						'customername' => array(
										'label' => $GLOBALS['TL_LANG']['amazon']['customername'], 
										'value' => (string)$xml->Lists->List->CustomerName
									),
					);
					if((int)$xml->Lists->List->TotalItems)
					{
						$even=true;
						$items=array();
						$tmpRequest=new AmazonRequest();
						
						// we got items.
						foreach ($xml->Lists->List->ListItem as $node)
						{
							// mktime(0, 0, 0, dd, mm, yyyy)
							$title = (string)$node->Item->ItemAttributes->Title;

							$asin = ((string)$node->Item->ASIN);
							$thumb = 'http://ecx.images-amazon.com/images/P/' . $asin . '.01.jpg';
							$large = 'http://ecx.images-amazon.com/images/P/' . $asin . '.01.LZZZZZZZ.jpg';
							$img = '<img src="'.$large.'" alt="'.$title.'" width="60" />';
							
							$listitemid=(string)$node->ListItemId;
							
							$offers=$node->Item->Offers;
							foreach($offers->Offer as $offer)
							{
								if(((string)$offer->OfferAttributes->Condition)=='New')
								{
									$offerlistingid= (string)$offer->OfferListing->OfferListingId;
									$price = (string)$offer->Price->FormattedPrice;
									// This is based upon information form amazon forum:
									// http://developer.amazonwebservices.com/connect/thread.jspa?messageID=34988&#34988
									// I really hope this works out for all items.
									$buyurl=$tmpRequest->prepareURL('CartCreate', array(
																						'Item.1.OfferListingId'	=> $offerlistingid, 
																						'Item.1.Quantity'		=> '1', 
																						'Item.1.ListItemId'		=> $listitemid,
																						'ListId'				=> $this->amazonlistid
																						)
																	);
									break;
								}
							}

							// 'srcthumb'	=> 'http://images.amazon.com/images/P/' . ((string)$node->Item->ASIN) . '.01.jpg',
							// 'srclarge'	=> 'http://images.amazon.com/images/P/' . ((string)$node->Item->ASIN) . '.01.LZZZZZZZ.jpg',
							// http://ecx.images-amazon.com/images/I/51CANS0ypRL._SL500_AA240_.jpg
							$items[]=array
									(
									 	'class' => ($even ? 'even' : 'odd') . ((count($items)==0) ? ' first' : ''),
									 	// http://www.amazon.de/gp/product/B00018GVN6/ref=em-si-ht_tlink0?ie=UTF8&coliid=I60R114ZF0YJ2
										'listitemid' =>		array(	// TODO: Add link for lookup here. We need to link to some viewer then I guess. 
																	// Do we need a jumpTo page?
																'label' => $GLOBALS['TL_LANG']['amazon']['listitemid'], 
																'value' => $listitemid
																),
										'dateadded'			=> array(
																'label' => $GLOBALS['TL_LANG']['amazon']['dateadded'], 
																'value' => (string)$node->DateAdded
																),
										'quantitydesired'	=> array(
																'label' => $GLOBALS['TL_LANG']['amazon']['quantitydesired'], 
																'value' => (string)$node->QuantityDesired
																),
										'quantityreceived'	=> array(
																'label' => $GLOBALS['TL_LANG']['amazon']['quantityreceived'], 
																'value' => (string)$node->QuantityReceived
																),
										'priority'			=> array(
																'label' => $GLOBALS['TL_LANG']['amazon']['priority'], 
																'value' => (string)$node->Priority
																),
										'asin'				=> array(
																'label' => $GLOBALS['TL_LANG']['amazon']['asin'], 
																'value' => $asin
																),
										'title'				=> array(
																'label' => $GLOBALS['TL_LANG']['amazon']['title'],
																'value' => $title
																),
										'image'				=> array(
																'srcthumb'	=> $thumb,
																'srclarge'	=> $large,
																'img' => $img,
																'lightbox' => '<a rel="lightbox[lb' . $asin . ']" href="' . $large . '" title="' . $title . '">' . $img . '</a>',
																),
										'price'				=> array(
																'label' => $GLOBALS['TL_LANG']['amazon']['price'],
																'value' => $price
																),
										'buynow'				=> array(
																'label' => $GLOBALS['TL_LANG']['amazon']['buynow'],
																'url' => $buyurl,
																'link' => '',
																),
										'xml'				=> $node->asXML(),
									);
									
							/*
http://www.amazon.de/gp/legacy-handle-buy-box.html/ref=cm_wl_addtocart?
ie=UTF8&
coliid=I60R114ZF0YJ2&
offering-id.07j2KmtosP0H2w63fdnstLI4Q8CieuhrW7vd52NeAgrLFxUgney3xKWbxBujooVwpij%252FvsaQum3y9CIXJmQa1A%253D%253D=1&
signInToHUC=0&
colid=3J3K70FYE3EC&
session-id=275-1306235-9516730
*/
							$even=!$even;
						}
						$this->Template->items=$items;
					}
			} else {
				$this->Template->listName = 'ERROR: xml is invalid ' . $xml->Lists->Request->IsValid;
			}
		} else {
			$this->Template->listName = 'ERROR: xml is a ' . get_class($xml);
		}
		return;
	}

};
?>