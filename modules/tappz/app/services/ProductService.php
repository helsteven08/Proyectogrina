<?php
/**
 * NOTICE OF LICENSE.
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    Tappz Team
 *  @copyright 2009-2016 Tmob
 *  @license   LICENSE.txt
 */

class ProductService extends BaseService
{
    public $product;
    private $item = array();
    private $tax;

    public function getProductById($productId)
    {
        $this->loadProductById($productId);
        $this->setProductInfo();
        $this->setCreditCartInstallments();
        $this->setShipmentDetails();
        $this->setCodes();
        $this->setPrices();
        $this->getVariant($productId);
        $this->setImages($productId);

        return $this->item;
    }

    public function getProductByBarcode($barcode)
    {
        $q = 'SELECT '._DB_PREFIX_.'product.id_product
                FROM '._DB_PREFIX_.'product
              WHERE '._DB_PREFIX_."product.upc = '".pSQL($barcode)."'
                OR
              "._DB_PREFIX_."product.ean13='".pSQL($barcode)."'";

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($q);
    }

    public function getProductBySearch()
    {
        $sql = 'SELECT p.id_product
             FROM '._DB_PREFIX_.'product p
        LEFT JOIN '._DB_PREFIX_.'category_product cp ON p.id_product = cp.id_product
		LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON sa.`id_product` = p.`id_product`
        LEFT JOIN '._DB_PREFIX_."category_lang cl ON (p.id_category_default = cl.id_category
            AND cl.id_lang = '".$this->getLangId()."')
        LEFT JOIN "._DB_PREFIX_."product_lang pl ON (p.id_product = pl.id_product
             AND pl.id_lang = '".$this->getLangId()."')
        WHERE p.active = '1'";
        if ((Tools::getValue('phrase')) && !empty(Tools::getValue('phrase'))) {
            $sql = $sql." AND pl.name LIKE '%".Tools::getValue('phrase')."%'";
        }
        $filter_data = array();
        if ((Tools::getValue('filters')) && !empty(Tools::getValue('filters'))) {
            $filters = explode(';', Tools::getValue('filters'));
            foreach ($filters as $fl) {
                $arr = explode(':', $fl);
                $filter_data[$arr[0]] = $arr[1];
            }
        }
        if (isset($filter_data['category']) && !empty($filter_data['category'])) {
            $sql = $sql." AND cp.id_category ='".pSQL($filter_data['category'])."'";
        } elseif (isset($_REQUEST['category']) && !empty($_REQUEST['category'])) {
            $sql = $sql." AND cp.id_category ='".pSQL($_REQUEST['category'])."'";
        }
        if (isset($filter_data['brand']) && !empty($filter_data['brand'])) {
            $sql = $sql." AND p.id_manufacturer ='".pSQL($filter_data['brand'])."'";
        }
        if (isset($filter_data['price']) && !empty($filter_data['price'])) {
            $price_arr = explode('-', $filter_data['price']);
            $sql = $sql." AND p.price >= '".pSQL($price_arr[0])."' AND p.price <= '".pSQL($price_arr[1])."'";
        }
        $sql = $sql.' GROUP BY p.id_product ';
        if ((Tools::getValue('sort')) && !empty(Tools::getValue('sort'))) {
            $sql = $sql.' ORDER BY  '.pSQL(Tools::getValue('sort'));
        }
        if (!empty(Tools::getValue('page')) && !empty(Tools::getValue('pageSize'))) {
            $page = Tools::getValue('page');
            $num_rec_per_page = Tools::getValue('pageSize');
            if ($page == 1) {
                $start_from = $page;
            } else {
                $start_from = (($page) * $num_rec_per_page) - $num_rec_per_page;
            }
            $end_from = ($page) * $num_rec_per_page;
            $sql = $sql." LIMIT '".pSQL($start_from)."','".pSQL($end_from)."'";
        }

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $counter = sizeof($result);
        $data = array();
        $filter_data = array();
        if ($counter > 0) {
            $data['totalResultCount'] = $counter;
            $data['pageNumber'] = (Tools::getValue('page')) ? Tools::getValue('page') : 1;
            $data['pageSize'] = ((Tools::getValue('pageSize'))) ? Tools::getValue('pageSize')  : 1;
            foreach ($result as $row) {
                $data['products'][] = $this->getProductById($row['id_product']);
            }
        }
        if (sizeof($data['products'])) {
            $data['filters'] = array();
            if ((Tools::getValue('phrase')) && !empty(Tools::getValue('phrase'))) {
                if (Tools::getIsset($filter_data['category']) && !empty($filter_data['category'])) {
                    $cat_sql = 'SELECT id_category,name
                     FROM '._DB_PREFIX_."category_lang WHERE  id_category='".pSQL($filter_data['category'])."'";
                    $cat_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($cat_sql);
                    $cat_data = $cat_results[0];
                }
                $cat_sql1 = 'SELECT cl.id_category,cl.name
                     FROM '._DB_PREFIX_.'category_lang AS cl LEFT JOIN '._DB_PREFIX_."category AS c
                      ON c.id_category=cl.id_category WHERE cl.id_lang='1' AND c.id_parent > 1 GROUP BY cl.id_category";
                $c_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($cat_sql1);
                $filter = array();
                $cat_id= $cat_data['id_category'];
                $filter['id'] = 'category';
                $filter['displayName'] = 'category';
                $filter['selectedItemId'] =  (Tools::getIsset($cat_id) && $cat_id != '') ? $cat_id : '';
                $filter['rangeStart'] = 0;
                $filter['rangeEnd'] = 0;
                $filter['FilterType'] = 0;
                $filter['items'] = array();
                foreach ($c_results as $cat) {
                    $item = array();
                    $item['id'] = $cat['id_category'];
                    $item['displayName'] = $cat['name'];
                    $item['productCount'] = $this->countProductInCat($cat['id_category']);
                    $filter['items'][] = $item;
                }
                array_push($data['filters'], $filter);
            }
            if (Tools::getIsset($filter_data['brand']) && !empty($filter_data['brand'])) {
                $man_sql = 'SELECT id_manufacturer,name
                     FROM '._DB_PREFIX_."manufacturer WHERE  id_manufacturer='".pSQL($filter_data['brand'])."'";
                $man_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($man_sql);
                $man_data = $man_results[0];
            }
            $man_sql1 = 'SELECT id_manufacturer,name
                     FROM '._DB_PREFIX_."manufacturer  WHERE active='1'";
            $m_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($man_sql1);
            $filter = array();
            $idManifacturer = $man_data['id_manufacturer'];
            $filter['id'] = 'brand';
            $filter['displayName'] = 'brand';
            $filter['selectedItemId'] = (isset($idManifacturer) && $idManifacturer != '') ? $idManifacturer: '';
            $filter['rangeStart'] = 0;
            $filter['rangeEnd'] = 0;
            $filter['FilterType'] = 0;
            $filter['items'] = array();
            foreach ($m_results as $cat) {
                $item = array();
                $item['id'] = $cat['id_manufacturer'];
                $item['displayName'] = $cat['name'];
                $item['productCount'] = $this->countProductInManu($cat['id_manufacturer']);
                $filter['items'][] = $item;
            }
            array_push($data['filters'], $filter);
            $filterPrice = $filter_data['price'];
            $filter = array();
            $filter['id'] = 'price';
            $filter['displayName'] = 'price';
            $filter['selectedItemId'] = (isset($filterPrice) && $filterPrice != '') ? $filterPrice : '';
            $filter['rangeStart'] = 0;
            $filter['rangeEnd'] = 10000;
            $filter['FilterType'] = 0;
            $filter['items'] = array();
            $item = array();
            $item['id'] = '0-100';
            $item['displayName'] = '0 to 100';
            $item['productCount'] = $this->countProductInPrice(0, 100);
            $filter['items'][] = $item;
            $item = array();
            $item['id'] = '100-500';
            $item['displayName'] = '100 to 500';
            $item['productCount'] = $this->countProductInPrice(100, 500);
            $filter['items'][] = $item;
            $item = array();
            $item['id'] = '500-5000';
            $item['displayName'] = '500 to 5000';
            $item['productCount'] = $this->countProductInPrice(500, 5000);
            $filter['items'][] = $item;
            $item = array();
            $item['id'] = '5000-10000';
            $item['displayName'] = '5000 to 10000';
            $item['productCount'] = $this->countProductInPrice(5000, 10000);
            $filter['items'][] = $item;

            $data['sortList'][0]['displayName'] = 'Product Name: A to Z';
            $data['sortList'][0]['value'] = 'pl.name ASC';
            $data['sortList'][1]['displayName'] = 'Product Name: Z to A';
            $data['sortList'][1]['value'] = 'pl.name DESC';
            $data['sortList'][2]['displayName'] = 'Price: Lowest first';
            $data['sortList'][2]['value'] = 'p.price ASC';
            $data['sortList'][3]['displayName'] = 'Price: Highest first';
            $data['sortList'][3]['value'] = 'p.price DESC';
            $data['categories'][0]['id'] = '';
            $data['categories'][0]['name'] = '';
            $data['categories'][0]['isRoot'] = false;
            $data['categories'][0]['isLeaf'] = false;
            $data['categories'][0]['parentCategoryId'] = '';
            $data['categories'][0]['children'] = array();
            $data['categories'][0]['description'] = '';
            $data['categories'][0]['ErrorCode'] = '';
            $data['categories'][0]['Message'] = '';
            $data['categories'][0]['UserFriendly'] = false;
            $data['ErrorCode'] = '';
            $data['Message'] = '';
            $data['UserFriendly'] = false;
        } else {
            $data['totalResultCount'] = 0;
            $data['pageNumber'] = Tools::getValue('page');
            $data['pageSize'] = Tools::getValue('pageSize');
            $data['products'] = null;
            if (isset($filter_data['category']) && !empty($filter_data['category'])) {
                $cat_sql = 'SELECT id_category,name
                     FROM '._DB_PREFIX_."category_lang WHERE  id_category='".pSQL($filter_data['category'])."'";

                $cat_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($cat_sql);
                $cat_data = $cat_results[0];
            }
            $cat_sql1 = 'SELECT cl.id_category,cl.name
                     FROM '._DB_PREFIX_.'category_lang AS cl LEFT JOIN '._DB_PREFIX_."category AS c
                      ON c.id_category=cl.id_category
                     WHERE cl.id_lang='1' AND c.id_parent > 1 GROUP BY cl.id_category";
            $c_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($cat_sql1);
            $data['filters'][0]['id'] = 'category';
            $data['filters'][0]['displayName'] = 'category';
            $categoryId = $cat_data['id_category'];
            $data['filters'][0]['selectedItemId'] = (isset($categoryId) && $categoryId != '') ? $categoryId : '';
            $data['filters'][0]['rangeStart'] = 0;
            $data['filters'][0]['rangeEnd'] = 0;
            $data['filters'][0]['FilterType'] = 0;
            $k = 0;
            foreach ($c_results as $cat) {
                $data['filters'][0]['items'][$k]['id'] = $cat['id_category'];
                $data['filters'][0]['items'][$k]['displayName'] = $cat['name'];
                $data['filters'][0]['items'][$k]['productCount'] = $this->countProductInCat($cat['id_category']);
                ++$k;
            }
            if (isset($filter_data['brand']) && !empty($filter_data['brand'])) {
                $man_sql = 'SELECT id_manufacturer,name
                     FROM '._DB_PREFIX_."manufacturer WHERE  id_manufacturer='".pSQL($filter_data['brand'])."'";
                $man_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($man_sql);
                $man_data = $man_results[0];
            }
            $man_sql1 = 'SELECT id_manufacturer,name
                     FROM '._DB_PREFIX_."manufacturer  WHERE active='1'";
            $m_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($man_sql1);
            $data['filters'][1]['id'] = 'brand';
            $data['filters'][1]['displayName'] = 'brand';
            $idManifacturer = $man_data['id_manufacturer'];
            $filterSelectedItem =(isset($idManifacturer) && $idManifacturer != '') ?$idManifacturer : '';
            $data['filters'][1]['selectedItemId'] = $filterSelectedItem ;
            $data['filters'][1]['rangeStart'] = 0;
            $data['filters'][1]['rangeEnd'] = 0;
            $data['filters'][1]['FilterType'] = 0;
            $k = 0;
            foreach ($m_results as $cat) {
                $data['filters'][1]['items'][$k]['id'] = $cat['id_manufacturer'];
                $data['filters'][1]['items'][$k]['displayName'] = $cat['name'];
                $data['filters'][1]['items'][$k]['productCount'] = $this->countProductInManu($cat['id_manufacturer']);
                ++$k;
            }

            $data['filters'][2]['id'] = 'price';

            $data['filters'][2]['displayName'] = 'price';
            $filterPrice = $filter_data['price'];
            $data['filters'][2]['selectedItemId'] = (isset($filterPrice) && $filterPrice != '') ? $filterPrice : '';

            $data['filters'][2]['rangeStart'] = 0;

            $data['filters'][2]['rangeEnd'] = 10000;

            $data['filters'][2]['FilterType'] = 0;

            $data['filters'][2]['items'][0]['id'] = '0-100';
            $data['filters'][2]['items'][0]['displayName'] = '0 to 100';
            $data['filters'][2]['items'][0]['productCount'] = $this->countProductInPrice(0, 100);

            $data['filters'][2]['items'][1]['id'] = '100-500';
            $data['filters'][2]['items'][1]['displayName'] = '100 to 500';
            $data['filters'][2]['items'][1]['productCount'] = $this->countProductInPrice(100, 500);

            $data['filters'][2]['items'][2]['id'] = '500-5000';
            $data['filters'][2]['items'][2]['displayName'] = '500 to 5000';
            $data['filters'][2]['items'][2]['productCount'] = $this->countProductInPrice(500, 5000);

            $data['filters'][2]['items'][3]['id'] = '5000-10000';
            $data['filters'][2]['items'][3]['displayName'] = '5000 to 10000';
            $data['filters'][2]['items'][3]['productCount'] = $this->countProductInPrice(5000, 10000);
            $data['sortList'][0]['displayName'] = 'Name ASC';

            $data['sortList'][0]['value'] = 'cl.name ASC';

            $data['sortList'][1]['displayName'] = 'Name DESC';

            $data['sortList'][1]['value'] = 'cl.name DESC';

            $data['sortList'][2]['displayName'] = 'Price ASC';

            $data['sortList'][2]['value'] = 'p.price ASC';

            $data['sortList'][3]['displayName'] = 'Price DESC';

            $data['sortList'][3]['value'] = 'p.price DESC';

            $data['categories'][0]['id'] = '';

            $data['categories'][0]['name'] = '';

            $data['categories'][0]['isRoot'] = false;

            $data['categories'][0]['isLeaf'] = false;

            $data['categories'][0]['parentCategoryId'] = '';

            $data['categories'][0]['children'] = array();

            $data['categories'][0]['description'] = '';

            $data['categories'][0]['ErrorCode'] = '';

            $data['categories'][0]['Message'] = '';

            $data['categories'][0]['UserFriendly'] = false;

            $data['ErrorCode'] = '';

            $data['Message'] = '';

            $data['UserFriendly'] = false;
        }

        return $data;
    }

    protected function loadProductById($productId)
    {
        $priceDisplay = Product::getTaxCalculationMethod();
        $this->tax = (!$priceDisplay | $priceDisplay == 2);
        $this->product = new Product($productId, false, $this->getLangId());
        $link = new Link();
        $imageId = Product::getCover($productId);
        $this->product->id_image = $productId.'-'.$imageId['id_image'];
        $this->product->id_product = $productId;
        $this->product->url = $link->getProductLink($this->product);
        $this->tax = (!$priceDisplay || $priceDisplay == 2);
        if (empty($this->quantity)) {
            $this->product->id_product_attribute = Product::getDefaultAttribute($productId);
            $id_product_attribute = $this->product->id_product_attribute;
            $shopId = $this->getShopId();
            $this->quantity = StockAvailable::getQuantityAvailableByProduct($productId, $id_product_attribute, $shopId);
        }
        $this->product->picture = $link->getImageLink($this->product->link_rewrite, $this->product->id_image);
        $this->product->actions = null;
    }

    protected function setProductInfo()
    {
        $this->item['id'] = $this->product->id;
        $this->item['productName'] = $this->product->name;
        $this->item['listPrice'] = null;// list price
        $this->item['noImageUrl'] = null;
        $this->item['headline'] = $this->product->reference;
        $this->item['strikeoutPrice'] = null; // strike out price
        $this->item['IsCampaign'] = false;
        $this->item['productDetailUrl'] = $this->product->description;
        $this->item['productUrl'] = $this->product->url;
        $this->item['picture'] = 'http://'.$this->product->picture;
        $this->item['inStock'] = $this->getStockBoolean();
        $this->item['features'] = null;
        $this->item['shoutOutTexts'] = null;
        $this->item['actions'] = null;
        $this->item['points'] = 0;
        $this->item['unit'] = null;
        $this->item['UserFriendly'] = false;
    }

    protected function getVariant($productId)
    {
        $result = $this->getProductAttributes($productId);
        if (sizeof($result) > 0) {
            $variants = array();
            $source = array();
            foreach ($result as $row) {
                $variants['features'] = $this->buildFeatures($row['id_attribute'], $row['attribute_name']);
                $variants['groupId'] = $row['id_attribute_group'];
                $variants['groupName'] = $row['group_name'];
                $source[] = array_unique($variants);
            }
            $this->item['variants'] = $this->normaliseArray($source);
        } else {
            $this->item['variants'] = null;
        }
    }

    private function buildFeatures($ids, $attributes)
    {
        $array = array();
        $ids = explode(',', $ids);
        $attributes = explode(',', $attributes);
        $i = 0;
        foreach ($ids as $row) {
            $array[] = array('displayName' => trim($attributes[$i]), 'value' => trim($row));
            ++$i;
        }

        return $this->arrayUniqueMultidimensional($array);
    }

    private function normaliseArray($arr, $recurse = true)
    {
        if (!is_array($arr)) {
            return $arr;
        }

        if (count(array_filter(array_keys($arr), 'is_numeric')) == count($arr)) {
            $arr = array_values($arr);
        }

        if ($recurse) {
            foreach ($arr as $k => $a) {
                $arr[$k] = $this->normaliseArray($a, $recurse);
            }
        }

        return $arr;
    }

    private function arrayUniqueMultidimensional($input)
    {
        $serialized = array_map('serialize', $input);
        $unique = array_unique($serialized);

        return array_intersect_key($input, $unique);
    }

    protected function setImages($productId)
    {
        $images = $this->getImages($productId);
        if (sizeof($images) > 0) {
            $context = Context::getContext();
            foreach ($images as $row) {
                $img = $row['id_image'];
                $this->item['pictures'][]['url'] = $context->link->getImageLink($this->product->link_rewrite, $img);
            }
        }
    }

    protected function getStockBoolean()
    {
        return ($this->quantity > 0) ? true : false;
    }

    protected function setCreditCartInstallments()
    {
        $this->item['creditCardInstallments'] = null;
    }

    protected function setShipmentDetails()
    {
        $shipCost = $this->product->additional_shipping_cost;
        $this->item['shipmentInformation'] = ((int) $shipCost == 0) ? '' : $shipCost;
        $this->item['isShipmentFree'] = $this->product->additional_shipping_cost > 0 ? false : true;
    }

    protected function setCodes()
    {
        $this->item['ErrorCode'] = '';
        $this->item['Message'] = 'No error has occurred';
    }

    protected function setPrices()
    {
        $id = $this->product->id_product;
        $tax = $this->tax;
        $productPrice = Product::getPriceStatic($id, $tax, null, 4, null, false, false, 1, false, null, 1);
        $finalPrice = Product::getPriceStatic($id, $tax, null, 4, false, false, true, 1, false, null, 1);
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);

        $this->item['listPrice'] = $this->fillPrices($finalPrice, $currency->sign);
        if ($productPrice > $finalPrice) {
            $this->item['strikeoutPrice'] = $this->fillPrices($productPrice, $currency->sign);
        } else {
            $this->item['strikeoutPrice'] = null;
            $this->item['discount'] = 0;
        }
    }

    private function fillPrices($amount, $currency, $defaultCurreny = null)
    {
        return array(
            'amount' => $amount,
            'currency' => $currency,
            'amountDefaultCurrency' => $defaultCurreny,
        );
    }


    protected function getImages($product_id)
    {
        $q = 'SELECT * FROM '._DB_PREFIX_.'image i
					LEFT JOIN '._DB_PREFIX_."image_lang il ON (i.id_image = il.id_image)
					WHERE i.id_product = '".pSQL($product_id)."' AND il.id_lang = ".$this->getLangId().'
					ORDER BY i.position ASC';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($q);
    }

    public function getProductAttributes($product_id)
    {
        $q = "SELECT pa.*, ag.id_attribute_group,
                     ag.is_color_group,
                     agl.name AS group_name,
                     GROUP_CONCAT(al.name ORDER BY ag.id_attribute_group SEPARATOR ', ') AS attribute_name,
                      GROUP_CONCAT( a.id_attribute ORDER BY ag.id_attribute_group SEPARATOR ', ') AS id_attribute,
                     pa.unit_price_impact
					FROM "._DB_PREFIX_.'product_attribute pa
					LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac
					ON pac.id_product_attribute = pa.id_product_attribute
					LEFT JOIN '._DB_PREFIX_.'attribute a ON a.id_attribute = pac.id_attribute
					LEFT JOIN '._DB_PREFIX_.'attribute_group ag ON ag.id_attribute_group = a.id_attribute_group
					LEFT JOIN '._DB_PREFIX_.'attribute_lang al ON (a.id_attribute = al.id_attribute AND al.id_lang = 1)
					LEFT JOIN '._DB_PREFIX_."attribute_group_lang agl ON (ag.id_attribute_group = agl.id_attribute_group
					AND agl.id_lang = 1)
					WHERE pa.id_product = '".pSQL($product_id)."' GROUP BY ag.id_attribute_group  ORDER BY group_name";

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($q);
    }

    public function getproductRelated($productId)
    {
        $sql = 'SELECT p.*, pl.description, pl.description_short, pl.available_now,sa.quantity AS stock_qty,
pl.available_later, pl.link_rewrite, pl.meta_description, pl.meta_keywords, pl.meta_title, pl.name,
 cl.id_category AS category_default_id,cl.name AS category_default
        FROM '._DB_PREFIX_.'category_product cp
        LEFT JOIN '._DB_PREFIX_.'product p ON p.id_product = cp.id_product
		LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON sa.`id_product` = p.`id_product`
        LEFT JOIN '._DB_PREFIX_."category_lang cl ON (p.id_category_default = cl.id_category
         AND cl.id_lang = '".$this->getLangId()."')
        LEFT JOIN "._DB_PREFIX_."product_lang pl ON (p.id_product = pl.id_product
        AND pl.id_lang = '".$this->getLangId()."')
        WHERE p.active = '1' AND p.id_product = '".pSQL($productId)."' GROUP BY p.id_product ";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
        if (Db::getInstance()->NumRows() > 0) {
            $products = new Product($productId);
            $category_default_id = $products->id_category_default;
            $id_product = $results['id_product'];
            $sql2 = 'SELECT p.*, pl.description, pl.description_short, pl.available_now, pl.available_later,
                      pl.link_rewrite, pl.meta_description, pl.meta_keywords,
                      pl.meta_title, pl.name, cl.name AS category_default
        FROM '._DB_PREFIX_.'category_product cp
        LEFT JOIN '._DB_PREFIX_.'product p ON p.id_product = cp.id_product
        LEFT JOIN '._DB_PREFIX_."category_lang cl ON (p.id_category_default = cl.id_category
                AND cl.id_lang = '".$this->getLangId()."')
        LEFT JOIN "._DB_PREFIX_."product_lang pl ON (p.id_product = pl.id_product
                AND pl.id_lang = '".$this->getLangId()."')
        WHERE p.active = '1' AND cp.id_category ='".pSQL($category_default_id)."'
                AND p.id_product !='".pSQL($id_product)."' GROUP BY p.id_product";
            $results_cat = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
            $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
            $currency = new Currency($id_currency);
            $currency_sign = $currency->sign;
            $arr = array();
            if (Db::getInstance()->NumRows() > 0) {
                foreach ($results_cat as $k1 => $v1) {
                    $arr[$k1] = $v1;
                    $arr[$k1]['currency_sign'] = $currency_sign;

                    $sql2 = 'SELECT pa.*, ag.id_attribute_group, ag.is_color_group, agl.name AS group_name,
                              al.name AS attribute_name, a.id_attribute, pa.unit_price_impact
						FROM '._DB_PREFIX_.'product_attribute pa
						LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac
						  ON pac.id_product_attribute = pa.id_product_attribute
						LEFT JOIN '._DB_PREFIX_.'attribute a ON a.id_attribute = pac.id_attribute
						LEFT JOIN '._DB_PREFIX_.'attribute_group ag ON ag.id_attribute_group = a.id_attribute_group
						LEFT JOIN '._DB_PREFIX_.'attribute_lang al
						ON (a.id_attribute = al.id_attribute AND al.id_lang = 1)
						LEFT JOIN '._DB_PREFIX_.'attribute_group_lang agl
						ON (ag.id_attribute_group = agl.id_attribute_group AND agl.id_lang = 1)
						WHERE pa.id_product = '.$v1['id_product'].' ORDER BY group_name';
                    $attr_res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                    $attr_results = $this->groupBy($attr_res, 'group_name');
                    $arr[$k1]['Attributes'] = $attr_results;
                    $sql3 = 'SELECT * FROM '._DB_PREFIX_.'image i
						LEFT JOIN '._DB_PREFIX_.'image_lang il ON (i.id_image = il.id_image)
						WHERE i.id_product = '.pSQL($v1['id_product']).' AND il.id_lang = '.$this->getLangId().'
						ORDER BY i.position ASC';
                    $img_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql3);
                    $arr[$k1]['Image'] = $img_results;
                }
            }
        }
        $data = array();
        if (!empty($arr)) {
            $i = 0;
            foreach ($arr as $row) {
                $context = Context::getContext();
                $data[$i]['id'] = $row['id_product'];
                $data[$i]['productName'] = $row['name'];
                $data[$i]['noImageUrl'] = '';
                $data[$i]['headline'] = '';
                $discounted_price = $this->getPriceDiscounted($row['id_product']);
                if ($discounted_price > 0) {
                    if (isset($row['price']) && !empty($row['price'])) {
                        $getDiscounted = $this->getPriceDiscounted($row['id_product']);
                        $data[$i]['listPrice']['amount'] = number_format($getDiscounted, 2);
                    } else {
                        $data[$i]['listPrice']['amount'] = '0.00';
                    }
                    $data[$i]['listPrice']['currency'] = $row['currency_sign'];
                    $data[$i]['listPrice']['amountDefaultCurrency'] = '';
                    $data[$i]['IsCampaign'] = true;
                    $amount = (isset($row['price']) && $row['price']!= '') ? number_format($row['price'], 2) : 0;
                    $data[$i]['strikeoutPrice']['amount'] = $amount;
                    $data[$i]['strikeoutPrice']['currency'] = $row['currency_sign'];
                    $data[$i]['strikeoutPrice']['amountDefaultCurrency'] = '';
                } else {
                    if (isset($row['price']) && !empty($row['price'])) {
                        $amount = (isset($row['price']) && $row['price']!= '') ? number_format($row['price'], 2) : 0;
                        $data[$i]['listPrice']['amount'] = $amount;
                    } else {
                        $data[$i]['listPrice']['amount'] = '0.00';
                    }
                    $data[$i]['listPrice']['currency'] = $row['currency_sign'];
                    $data[$i]['listPrice']['amountDefaultCurrency'] = '';
                    $data[$i]['IsCampaign'] = false;
                    $data[$i]['strikeoutPrice'] = null;
                }
                $data[$i]['creditCardInstallments'][0]['image'] = '';
                $data[$i]['creditCardInstallments'][0]['type'] = '';
                $data[$i]['creditCardInstallments'][0]['threeDStatus'] = '0';
                $data[$i]['creditCardInstallments'][0]['displayName'] = '';
                $data[$i]['creditCardInstallments'][0]['installmentNumber'] = '0';
                $data[$i]['creditCardInstallments'][0]['installments'][0]['installmentNumber'] = '0';
                $data[$i]['creditCardInstallments'][0]['installments'][0]['installmentPayment'] = '0';
                $data[$i]['creditCardInstallments'][0]['installments'][0]['total'] = '0';
                $data[$i]['inStock'] = false;
                if (isset($row['stock_qty']) && $row['stock_qty'] > 0) {
                    $data[$i]['inStock'] = true;
                }
                $data[$i]['shipmentInformation'] = '';
                $data[$i]['isShipmentFree'] = false;
                if (isset($row['Attributes']) && !empty($row['Attributes'])) {
                    $ii = 0;
                    foreach ($row['Attributes'] as $k2 => $v2) {
                        $data[$i]['variants'][$ii]['groupName'] = $k2;
                        $data[$i]['variants'][$ii]['groupId'] = '';
                        $j = 0;
                        $chkArr = array();
                        foreach ($v2 as $att_data) {
                            if (!in_array($att_data['attribute_name'], $chkArr)) {
                                $data[$i]['variants'][$ii]['features'][$j]['displayName'] = $att_data['attribute_name'];
                                $data[$i]['variants'][$ii]['features'][$j]['value'] = $att_data['id_attribute'];
                                $chkArr[] = $att_data['attribute_name'];
                                ++$j;
                            }
                        }
                        ++$ii;
                    }
                } else {
                    $data[$i]['variants'] = null;
                }
                $data[$i]['features'] = null;
                $data[$i]['shoutOutTexts'] = array();
                $data[$i]['actions'] = null;
                if (isset($row['Image']) && !empty($row['Image'])) {
                    foreach ($row['Image'] as $v3) {
                        $imgLink = $context->link->getImageLink($row['link_rewrite'], $v3['id_image']);
                        if ($v3['cover'] == 1) {
                            $data[$i]['picture'] = $imgLink;
                        }
                    }
                } else {
                    $data[$i]['picture'] = null;
                }
                if (isset($row['Image']) && !empty($row['Image'])) {
                    $k = 0;
                    foreach ($row['Image'] as $v4) {
                        $imgLink = $context->link->getImageLink($row['link_rewrite'], $v4['id_image']);
                        if ($v4['cover'] != 1) {
                            $data[$i]['pictures'][$k]['url'] = $imgLink;
                            ++$k;
                        }
                    }
                } else {
                    $data[$i]['pictures'][0]['url'] = '';
                }
                $data[$i]['productDetailUrl'] = $row['description_short'];
                $url = $context->link->getProductLink($row);
                $data[$i]['productUrl'] = $url;
                $data[$i]['points'] = 0;
                $data[$i]['unit'] = '';
                $data[$i]['ErrorCode'] = '';
                $data[$i]['Message'] = '';
                $data[$i]['UserFriendly'] = false;
                ++$i;
            }
        }

        return $data;
    }

    private function groupBy($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            $return[$val[$key]][] = $val;
        }

        return $return;
    }

    private function getPriceDiscounted($prod_id)
    {
        $sql1 = "SELECT reduction
                    FROM "._DB_PREFIX_."specific_price
                 WHERE id_product = '".pSQL($prod_id)."'";
        $discount = Db::getInstance()->getValue($sql1);
        return $discount;
    }

    public function countProductInManu($id_manufacturer)
    {
        $sql = 'SELECT COUNT(id_product) AS total FROM '._DB_PREFIX_."product
        WHERE id_manufacturer='".pSQL($id_manufacturer)."' AND active = '1'";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return (isset($results[0]['total']) && $results[0]['total'] != '') ? $results[0]['total'] : 0;
    }

    public function countProductInPrice($sprice, $eprice)
    {
        $sql = 'SELECT COUNT(id_product) AS total FROM '._DB_PREFIX_."product WHERE price >='".pSQL($sprice)."'
         AND price <='".pSQL($eprice)."' AND active = '1'";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return (isset($results[0]['total']) && $results[0]['total'] != '') ? $results[0]['total'] : 0;
    }

    public function countProductInCat($id_category)
    {
        $category = new Category($id_category, 1);
        $productCount = $category->getProducts(1, 1, 10000, null, null, true);
        if ($productCount > 0) {
            $productCount = $productCount;
        } else {
            $productCount = 0;
        }

        return $productCount;
    }
    public function getProductStatic($id_product)
    {
        $priceDisplay = Product::getTaxCalculationMethod();
        $tax = (!$priceDisplay | $priceDisplay == 2);

        return Product::getPriceStatic($id_product, $tax, null, 4, null, false, false, 1, false, null, 1);
    }
    public function getProductFinalPrice($id_product)
    {
        $priceDisplay = Product::getTaxCalculationMethod();
        $tax = (!$priceDisplay | $priceDisplay == 2);

        return Product::getPriceStatic($id_product, $tax, null, 4, false, false, true, 1, false, null, 1);
    }
}
