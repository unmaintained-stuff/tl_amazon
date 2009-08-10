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

	protected function loadList($amazonlistid)
	{
		return $this->executeRequest('ListLookup', array(
													'ListType' => 'WishList', 
													'ListId' => $amazonlistid, 
													'ResponseGroup' => 'ListFull,Offers', 
													//'IsOmitPurchasedItems' => 1
													));
	}

	protected function buyWishListItemAndRedirect()
	{
		// first, send request to amazon
		// This is based upon information form amazon forum:
		// http://developer.amazonwebservices.com/connect/thread.jspa?messageID=34988&#34988
		// I really hope this works out for all items.
		$xml=$this->request->addItemToCart(array(
													'Item.1.OfferListingId'	=> $this->decodeTransportURL('offerlistingid'), 
													'Item.1.Quantity'		=> '1', 
													'Item.1.ListItemId'		=> $this->decodeTransportURL('listitemid'),
													'ListId'				=> $this->decodeTransportURL('amazonlistid')
											));
		if($xml instanceof SimpleXMLElement)
		{
			// second, check if the item was added to a cart
			if((string)$xml->Cart->Request->IsValid == 'True')
			{
				// third, redirect user to amazon checkout.
				$this->redirect((string)$xml->Cart->PurchaseURL);
			} else { die('cart is invalid.'); }
		} else { die('xml is invalid.'); }
	}
	
	protected function convertList($list)
	{
		return array
			(
				'type' => array(
								'label' => $GLOBALS['TL_LANG']['amazon']['listtype'], 
								'value' => (string)$list->ListType
							),
				'name' => array(
								'label' => $GLOBALS['TL_LANG']['amazon']['listname'], 
								'value' => (string)$list->ListName,
								'url'	=> (string)$list->ListURL,
								'link'	=> sprintf('<a href="%s" title="%s">%s</a>',
											(string)$list->ListURL,
											$GLOBALS['TL_LANG']['amazon']['showlist'],
											$GLOBALS['TL_LANG']['amazon']['showlist']
											), 
							),
				'id' => array(
								'label' => $GLOBALS['TL_LANG']['amazon']['listid'], 
								'value' => (string)$list->ListId,
							),
				'totalitems' => array(
								'label' => $GLOBALS['TL_LANG']['amazon']['totalitems'], 
								'value' => (string)$list->TotalItems
							),
				'totalpages' => array(
								'label' => $GLOBALS['TL_LANG']['amazon']['totalpages'], 
								'value' => (string)$list->TotalPages
							),
				'datecreated' => array(
								'label' => $GLOBALS['TL_LANG']['amazon']['datecreated'], 
								'value' => (string)$list->DateCreated
							),
				'lastmodified' => array(
								'label' => $GLOBALS['TL_LANG']['amazon']['lastmodified'], 
								'value' => (string)$list->LastModified
							),
				'customername' => array(
								'label' => $GLOBALS['TL_LANG']['amazon']['customername'], 
								'value' => (string)$list->CustomerName
							),
				'xml'			=> $list->asXML()
			);
	}

	protected function convertItem($node)
	{
		// mktime(0, 0, 0, dd, mm, yyyy)
		$title = (string)$node->Item->ItemAttributes->Title;
	
		$asin = ((string)$node->Item->ASIN);
		// 'srcthumb'	=> 'http://images.amazon.com/images/P/' . ((string)$node->Item->ASIN) . '.01.jpg',
		// 'srclarge'	=> 'http://images.amazon.com/images/P/' . ((string)$node->Item->ASIN) . '.01.LZZZZZZZ.jpg',
		// http://ecx.images-amazon.com/images/I/51CANS0ypRL._SL500_AA240_.jpg
		$thumb = 'http://ecx.images-amazon.com/images/P/' . $asin . '.01.jpg';
		$large = 'http://ecx.images-amazon.com/images/P/' . $asin . '.01.LZZZZZZZ.jpg';
		$img = '<img src="'.$large.'" alt="'.$title.'" width="60" />';
		
		$listitemid=(string)$node->ListItemId;
		
		$offers=$node->Item->Offers;
		// scan for the first new item.
		foreach($offers->Offer as $offer)
		{
			if(((string)$offer->OfferAttributes->Condition)=='New')
			{
				$offerlistingid= (string)$offer->OfferListing->OfferListingId;
				$price = (string)$offer->OfferListing->Price->FormattedPrice;
				$buyurl = $this->generateTransportURL('buyWishlistItem', array(
																			   'listitemid' => $listitemid, 
																			   'amazonlistid' => $this->amazonlistid,
																			   'offerlistingid' => $offerlistingid, 
																			)
													 );
				break;
			}
		}
	
		return array
				(
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
											'value' => $GLOBALS['TL_LANG']['amazon']['wishlistprio'][$this->priomap[(string)$node->Priority]]
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
		
		// we have to check if we want to forward first.
		if($this->Input->get('amareq'))
		{
			// 
			switch($this->Input->get('amareq'))
			{
				case 'buyWishlistItem':	// we want to purchase someting from the wishlist.
					$this->buyWishListItemAndRedirect();
					break;
				default:;
			}
		}
		
		// Template variables
		if($this->amazonwishlisttemplate)
		{
			$this->strTemplate = $this->amazonwishlisttemplate;
			$this->Template = new FrontendTemplate($this->strTemplate);
		}
		// initialize template.
		$this->Template->list=array();
		$this->Template->items=array();

		$xml=$this->loadList($this->amazonlistid);
		$this->Template->xml=$xml->asXML();

		if($xml instanceof SimpleXMLElement)
		{
			$this->Template->isValid=$xml->Lists->Request->IsValid=='True';
			$this->Template->listName = (string)$xml->Lists->List->ListName;
			if($this->Template->isValid)
			{
				$list=$xml->Lists->List;
				$this->Template->list=$this->convertList($list);
					if((int)$list->TotalItems)
					{
						$even=true;
						$items=array();
						$tmpRequest=new AmazonRequest();
						// we got items.
						foreach ($xml->Lists->List->ListItem as $node)
						{
							$item=$this->convertItem($node);
							$item['class'] = ($even ? 'even' : 'odd') . ((count($items)==0) ? ' first' : '');
							$items[]=$item;
							$even=!$even;
						}
						$items[count($items)-1]['class'] .= ' last';
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