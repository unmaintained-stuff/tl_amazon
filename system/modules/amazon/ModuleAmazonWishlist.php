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

	protected function loadListInfo($amazonlistid)
	{
		$req=array(
													'ListType' => 'WishList', 
													'ListId' => $amazonlistid, 
													'ResponseGroup' => 'ListInfo', 
													'Sort' => $this->amazonsortby, 
													);
		if(!$this->amazonshowpurchased)
			$req['IsOmitPurchasedItems'] = 1;
		return $this->request->loadList($req);
	}

	protected function loadList($amazonlistid, $page=0)
	{
		$req=array(
													'ListType' => 'WishList', 
													'ListId' => $amazonlistid, 
													'ResponseGroup' => 'ListFull,Offers', 
													'Sort' => $this->amazonsortby, 
													);
		if(!$this->amazonshowpurchased)
			$req['IsOmitPurchasedItems'] = 1;
		if($page>0)
			$req['ProductPage'] = $page;
		return $this->request->loadList($req);
	}

	protected function buyWishListItemAndRedirect()
	{
		// NOTE: I do not know if we can use the OfferListingId from a previous call, maybe we should refresh it here.
		
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
			} else { return 'cart is invalid.'; }
		} else { return 'xml is invalid.' . $xml; }
	}
	
	protected function convertList($list)
	{
		$objPagination = new Pagination((int)$list->TotalItems, $this->amazonperpage);
		$this->Template->pagination = $objPagination->generate("\n  ");
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
								'value' => $this->ConvertDate((string)$list->DateCreated)
							),
				'lastmodified' => array(
								'label' => $GLOBALS['TL_LANG']['amazon']['lastmodified'], 
								'value' => $this->ConvertDate((string)$list->LastModified)
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
		$title = (string)$node->Item->ItemAttributes->Title;
	
		$asin = ((string)$node->Item->ASIN);
		// 'srcthumb'	=> 'http://images.amazon.com/images/P/' . ((string)$node->Item->ASIN) . '.01.jpg',
		// 'srclarge'	=> 'http://images.amazon.com/images/P/' . ((string)$node->Item->ASIN) . '.01.LZZZZZZZ.jpg',
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
											'value' => $this->ConvertDate((string)$node->DateAdded)
											),
					'quantitydesired'	=> array(
											'label' => $GLOBALS['TL_LANG']['amazon']['quantitydesired'], 
											'value' => (int)$node->QuantityDesired
											),
					'quantityreceived'	=> array(
											'label' => $GLOBALS['TL_LANG']['amazon']['quantityreceived'], 
											'value' => (int)$node->QuantityReceived
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
					'buynow'			=> array(
											'label' => $GLOBALS['TL_LANG']['amazon']['buynow'],
											'url' => $buyurl,
											'value' => (strlen($buyurl) ? '<a class="amazonbuy link" rel="nofollow" href="' . $buyurl . '">' . $GLOBALS['TL_LANG']['amazon']['buynow'] . '</a>'
																		: '<span class="amazonbuy">' . $GLOBALS['TL_LANG']['amazon']['buynow'] . '</span>'),
											),
					'xml'				=> array(
											'value'	=> $node->asXML(),
											),
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

		// load listinfo
		$xml=$this->loadListInfo($this->amazonlistid);
		if(!($xml instanceof SimpleXMLElement))
		{
			$this->Template->listName = 'ERROR: xml is a ' . get_class($xml);
			return;
		}
		
		$this->Template->isValid=$xml->Lists->Request->IsValid=='True';
		$this->Template->listName = (string)$xml->Lists->List->ListName;
		
		if(!$this->Template->isValid)
		{
			$this->Template->listName = 'ERROR: xml is invalid ' . $xml->Lists->Request->IsValid;
			return;
		}

		$list=$xml->Lists->List;
		$this->Template->list=$this->convertList($list);
		$this->amazonperpage = (int)($this->amazonperpage);

		// amazon enforces a limit of 300 items per list.
		$wantedamount = (($this->amazonperpage < 300) && ($this->amazonperpage > 0) ? $this->amazonperpage : 300);
		// check if the list is shorter than what we want., if so we need to shrink.
		if(((int)$this->Template->list['totalitems']['value']) < $wantedamount)
			$wantedamount = ((int)$this->Template->list['totalitems']['value']);
		// check if we have pagination.
		if(isset($_GET['page']))
			$startitem = (int)($this->Input->get('page')-1) * $this->amazonperpage;
		else
			$startitem = 0;
		// calculate first page from amazon
		$thisPage = (int)floor($startitem / 10)+1;
		// first item we want to show
		$thisItem = (int)(($thisPage-1) * 10);
		$even=true;
		$items=array();

		// loop until we have the desired amount of items.
		while(count($items) < $wantedamount)
		{
			// now off to amazon.
			$xml=$this->loadList($this->amazonlistid, $thisPage);
			// check if list is valid.
			if(($xml instanceof SimpleXMLElement) && ((int)$xml->Lists->List->TotalItems>0))
			{
				$i=0;
				$list=$xml->Lists->List;
				// we got items.
				foreach ($list->ListItem as $node)
				{
					// only add if start item has been reached yet.
					if($thisItem >= $startitem)
					{
						$item=$this->convertItem($node);
						$item['class'] = ($even ? 'even' : 'odd') . ((count($items)==0) ? ' first' : '');
						$items[] = $item;
						$even = !$even;
						if(count($items) >= $wantedamount)
							break;
					}
					$thisItem++;$i++;
				}
				// if we got less than 10 items, we were on the last page, we have to break then.
				if($i<10)
					break;
				// ready to go for next page.
				$thisPage++;
			} else {
				// no xml or empty list found, exit.
				break;
			}
		}
		if(count($items)>0)
			$items[count($items)-1]['class'] .= ' last';
		$this->Template->items = $items;
		return;
	}

};
?>