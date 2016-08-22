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

class IndexService extends BaseService
{
    public function indexPage()
    {
        $id_lang = $this->getLangId(); /* Current Language ID */
        $mainArr = array();
        $context = Context::getContext();
        $days = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        if (empty($days)) {
            $days = 20;
        }
        $blockbestsellers = Module::isEnabled('blockbestsellers');
        if ($blockbestsellers) {
            $sql = '
				SELECT
					p.*, IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute,
					pl.`link_rewrite`, pl.`name`, pl.`description_short`, product_shop.`id_category_default`,sa.quantity as stock_qty,
					image_shop.`id_image` id_image, il.`legend`,
					ps.`quantity` AS sales, p.`ean13`, p.`upc`, cl.`link_rewrite` AS category, p.show_price, p.available_for_order,
					IFNULL(stock.quantity, 0) as quantity, p.customizable,
					IFNULL(pa.minimal_quantity, p.minimal_quantity) as minimal_quantity, stock.out_of_stock,
					product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.$days.' DAY')).'" as new,
					product_shop.`on_sale`, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity
				FROM `'._DB_PREFIX_.'product_sale` ps
				LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON sa.`id_product` = p.`id_product`
				LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
					ON (p.`id_product` = product_attribute_shop.`id_product` AND
			    product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int) $context->shop->id.')
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (product_attribute_shop.id_product_attribute=pa.id_product_attribute)
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int) $id_lang.Shop::addSqlRestrictionOnLang('pl').'
				LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
					ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1
					AND image_shop.id_shop='.(int) $context->shop->id.')
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image`
				AND il.`id_lang` = '.(int) $id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
					ON cl.`id_category` = product_shop.`id_category_default`
					AND cl.`id_lang` = '.(int) $id_lang.Shop::addSqlRestrictionOnLang('cl').Product::sqlStock('p', 0);
            $sql .= '
				WHERE product_shop.`active` = 1
				AND p.`visibility` != \'none\'';
            if (Group::isFeatureActive()) {
                $groups = FrontController::getCurrentCustomerGroups();
                $sql .= ' AND EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
						JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category
						AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').')
						WHERE cp.`id_product` = p.`id_product`)';
            }
            $sql .= ' group by p.id_product';
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            $mainArr['BestSeller'] = $result;
        }
        $blocknewproducts = Module::isEnabled('blocknewproducts');
        if ($blocknewproducts) {
            $front = true;
            $sqlN = new DbQuery();
            $sqlN->select(
                'p.*, product_shop.*,
                sa.quantity as stock_qty,
                stock.out_of_stock,
                IFNULL(stock.quantity, 0) as quantity,
                pl.`description`,
                pl.`description_short`,
                pl.`link_rewrite`,
                pl.`meta_description`,
				pl.`meta_keywords`,
				pl.`meta_title`, pl.`name`,
			    pl.`available_now`, pl.`available_later`,
				image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
                DATEDIFF(
            product_shop.`date_add`,
            DATE_SUB(
                NOW(),
                INTERVAL '.$days.' DAY
            )
        ) > 0 AS new'
            );
            $sqlN->from('product', 'p');
            $sqlN->join(Shop::addSqlAssociation('product', 'p'));
            $sqlN->leftJoin('product_lang', 'pl', 'p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = '.(int) $id_lang.Shop::addSqlRestrictionOnLang('pl'));
            $sqlN->leftJoin('stock_available', 'sa', 'sa.`id_product` = pl.`id_product`');
            $sqlN->leftJoin('image_shop', 'image_shop', 'image_shop.`id_product` = p.`id_product` AND
                             image_shop.cover=1 AND image_shop.id_shop='.(int) $context->shop->id);
            $sqlN->leftJoin('image_lang', 'il', 'image_shop.`id_image` = il.`id_image`
             AND il.`id_lang` = '.(int) $id_lang);
            $sqlN->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');
            $sqlN->where('product_shop.`active` = 1');
            if ($front) {
                $sqlN->where('product_shop.`visibility` IN ("both", "catalog")');
            }
            if (Group::isFeatureActive()) {
                $groups = FrontController::getCurrentCustomerGroups();
                $sqlN->join('JOIN '._DB_PREFIX_.'category_product cp ON (cp.id_product = p.id_product)');
                $sqlN->join('JOIN '._DB_PREFIX_.'category_group cg ON (cg.id_category = cp.id_category)');
                $sqlN->where('cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1'));
            }

            $sqlN->groupBy('product_shop.id_product');
            $shop_id = (int) $context->shop->id;
            if (Combination::isFeatureActive()) {
                $sqlN->select('product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,
                 IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute');
                $sqlN->leftJoin('product_attribute_shop', 'product_attribute_shop', '
            p.`id_product` = product_attribute_shop.`id_product`
            AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.pSQL($shop_id));
            }

            $sqlN->join(Product::sqlStock('p', 0));
            $resultN = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sqlN);
            $mainArr['NewProducts'] = $resultN;
        }
        $blockspecials = Module::isEnabled('blockspecials');
        if ($blockspecials) {
            $current_date = date('Y-m-d H:i:s');
            $taxAddress = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
            $id_address = isset($taxAddress) ? $taxAddress : 0;
            $ids = Address::getCountryAndState($id_address);
            $countryDefault = (int) Configuration::get('PS_COUNTRY_DEFAULT');
            $id_country = $ids['id_country'] ? (int) $ids['id_country'] : $countryDefault;
            $with_combination = false;
            $beginning = $current_date;
            $ending = $current_date;
            $ids_product = SpecificPrice::getProductIdByDate(
                $context->shop->id,
                $context->currency->id,
                $id_country,
                $context->customer->id_default_group,
                $beginning,
                $ending,
                0,
                $with_combination
            );
            $tab_id_product = array();
            foreach ($ids_product as $product) {
                if (is_array($product)) {
                    $tab_id_product[] = (int) $product['id_product'];
                } else {
                    $tab_id_product[] = (int) $product;
                }
            }
            $sql_groups = '';
            if (Group::isFeatureActive()) {
                $groups = FrontController::getCurrentCustomerGroups();
                $sql_groups = ' AND EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
					JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category
					AND cg.`id_group` '.(count(pSQL($groups)) ? 'IN ('.implode(',', pSQL($groups)).')' : '= 1').')
					WHERE cp.`id_product` = p.`id_product`)';
            }
            $newProduct = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
            $Ssql = '
			SELECT
				p.*, product_shop.*,
			    stock.out_of_stock,
			    IFNULL(stock.quantity, 0) as quantity,
			    pl.`description`, pl.`description_short`,
			    sa.quantity as stock_qty,
				IFNULL(product_attribute_shop.id_product_attribute, 0) id_product_attribute,
				pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
				pl.`name`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
				DATEDIFF(
					p.`date_add`,
					DATE_SUB(
						"'.date('Y-m-d').' 00:00:00",
						INTERVAL '.(Validate::isUnsignedInt(pSQL($newProduct)) ? pSQL($newProduct) : 20).' DAY
					)
				) > 0 AS new
			FROM `'._DB_PREFIX_.'product` p
			'.Shop::addSqlAssociation('product', 'p').'
			LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON sa.`id_product` = p.`id_product`
			LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
				ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1
		    AND product_attribute_shop.id_shop='.(int) $context->shop->id.')
			'.Product::sqlStock('p', 0, false, $context->shop).'
			LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
				p.`id_product` = pl.`id_product`
				AND pl.`id_lang` = '.(int) $id_lang.Shop::addSqlRestrictionOnLang('pl').'
			)
			LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
                ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1
            AND image_shop.id_shop='.(int) $context->shop->id.')
			LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image`
			AND il.`id_lang` = '.(int) $id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
			WHERE product_shop.`active` = 1
			AND product_shop.`show_price` = 1
			'.($front ? ' AND p.`visibility` IN ("both", "catalog")' : '').'
			'.(($beginning) ? ' AND p.`id_product` IN
			('.((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0).')' : '').'
			'.$sql_groups.'
			GROUP BY p.id_product';

            $Sresult = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($Ssql);
            $mainArr['SpecialProducts'] = $Sresult;
        }
        $homefeatured = Module::isEnabled('homefeatured');
        if ($homefeatured) {
            $front = in_array($context->controller->controller_type, array('front', 'modulefront'));
            $id_supplier = (int) Tools::getValue('id_supplier');
            $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
            if (!Validate::isUnsignedInt($nb_days_new_product)) {
                $nb_days_new_product = 20;
            }
            $Psql = 'SELECT p.*, product_shop.*,
                     stock.out_of_stock,sa.quantity as stock_qty, IFNULL(stock.quantity, 0) AS quantity' .
                     (Combination::isFeatureActive() ? ',
                       IFNULL(product_attribute_shop.id_product_attribute, 0) AS id_product_attribute,
					    product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity' : '').
                      ', pl.`description`, pl.`description_short`, pl.`available_now`,
						pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`,
						pl.`meta_keywords`, pl.`meta_title`, pl.`name`, image_shop.`id_image` id_image,
						il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
						DATEDIFF(product_shop.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",
						INTERVAL '.(int) $nb_days_new_product.' DAY)) > 0 AS new, product_shop.price AS orderprice
					FROM `'._DB_PREFIX_.'category_product` cp
					LEFT JOIN `'._DB_PREFIX_.'product` p
					ON p.`id_product` = cp.`id_product`
					'.Shop::addSqlAssociation('product', 'p').
                  (Combination::isFeatureActive() ?
                    ' LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
					ON (p.`id_product` = product_attribute_shop.`id_product`
					AND product_attribute_shop.`default_on` = 1 AND
					product_attribute_shop.id_shop='.(int) $context->shop->id.')' : '').'
					'.Product::sqlStock('p', 0).'
					LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON sa.`id_product` = p.`id_product`
					LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
						ON (product_shop.`id_category_default` = cl.`id_category`
						AND cl.`id_lang` = '.(int) $id_lang.Shop::addSqlRestrictionOnLang('cl').')
					LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
						ON (p.`id_product` = pl.`id_product`
						AND pl.`id_lang` = '.(int) $id_lang.Shop::addSqlRestrictionOnLang('pl').')
					LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
						ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1
						AND image_shop.id_shop='.(int) $context->shop->id.')
					LEFT JOIN `'._DB_PREFIX_.'image_lang` il
						ON (image_shop.`id_image` = il.`id_image`
						AND il.`id_lang` = '.(int) $id_lang.')
					LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
						ON m.`id_manufacturer` = p.`id_manufacturer`
					WHERE product_shop.`id_shop` = '.(int) $context->shop->id.''
                .' AND product_shop.`active` = 1'
                .($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
                .($id_supplier ? ' AND p.id_supplier = '.(int) $id_supplier : '')
                .' GROUP BY cp.id_product';

            $Presult = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($Psql);
            $mainArr['PopularProducts'] = $Presult;
        }
        $arr = array();
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $currency_sign = $currency->sign;
        if (isset($mainArr) && !empty($mainArr)) {
            foreach ($mainArr as $pk1 => $pv1) {
                foreach ($pv1 as $k1 => $v1) {
                    $arr[$pk1][$k1] = $v1;
                    $arr[$pk1][$k1]['currency_sign'] = $currency_sign;
                    $myproduct = new Product($v1['id_product']); //// 23 is your product id
                    $attribute = $myproduct->getAttributesGroups($v1['id_product']); /////// 45 is id_product_attribute
                    $attr_results = $this->groupBy($attribute, 'group_name');
                    $arr[$pk1][$k1]['Attributes'] = $attr_results;
                    $sql3 = "SELECT * FROM "._DB_PREFIX_."image i
					LEFT JOIN "._DB_PREFIX_."image_lang il ON (i.id_image = il.id_image)
					WHERE i.id_product = '".pSQL($v1['id_product'])."' AND il.id_lang = '".$this->getLangId()."'
					ORDER BY i.position ASC";
                    $img_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql3);
                    $arr[$pk1][$k1]['Image'] = $img_results;
                }
            }
        }
        $data = array();
        if (!empty($arr)) {
            $aa = 0;
            foreach ($arr as $rk1 => $res) {
                $data['groups'][$aa]['displayName'] = $rk1;
                $data['groups'][$aa]['image'] = '';
                $i = 0;
                foreach ($res as $row) {
                    $context = Context::getContext();
                    $data['groups'][$aa]['items'][$i]['id'] = $row['id_product'];
                    $data['groups'][$aa]['items'][$i]['productName'] = $row['name'];
                    $data['groups'][$aa]['items'][$i]['listPrice']['currency'] = $row['currency_sign'];
                    $data['groups'][$aa]['items'][$i]['listPrice']['amountDefaultCurrency'] = '';
                    $data['groups'][$aa]['items'][$i]['noImageUrl'] = '';
                    $data['groups'][$aa]['items'][$i]['headline'] = '';
                    $data['groups'][$aa]['items'][$i]['strikeoutPrice'] = null;
                    $discounted_price = $this->getPriceDiscounted($row['id_product']);
                    if ($discounted_price > 0) {
                        if (isset($row['price']) && !empty($row['price'])) {
                            $taxGroup = $row['id_tax_rules_group'];
                            $discountPrice = $this->getDiscountedPrice($row['id_product']);
                            $realPrice = $this->getIncludeTaxed($taxGroup, $discountPrice);
                            $data['groups'][$aa]['items'][$i]['listPrice']['amount'] = $realPrice;
                        } else {
                            $realPrice = 0;
                            $data['groups'][$aa]['items'][$i]['listPrice']['amount'] = '0.00';
                        }
                        $priceDisplay = Product::getTaxCalculationMethod(null);
                        $tax = (!$priceDisplay | $priceDisplay == 2);
                        $id = $row['id_product'];
                        $price = Product::getPriceStatic($id, $tax, null, 4, null, false, false, 1, false, null, 1);
                        $amount = ($price > $realPrice) ? $price : 0;
                        $data['groups'][$aa]['items'][$i]['IsCampaign'] = true;
                        $data['groups'][$aa]['items'][$i]['strikeoutPrice']['amount'] =$amount;
                        $data['groups'][$aa]['items'][$i]['strikeoutPrice']['currency'] = $row['currency_sign'];
                        $data['groups'][$aa]['items'][$i]['strikeoutPrice']['amountDefaultCurrency'] = '';
                    } else {
                        if (isset($row['price']) && !empty($row['price'])) {
                            $data['groups'][$aa]['items'][$i]['listPrice']['amount'] =
                                (isset($row['price']) && $row['price'] != '') ?
                                    $this->getIncludeTaxed($row['id_tax_rules_group'], $row['price']) : 0;
                        } else {
                            $data['groups'][$aa]['items'][$i]['listPrice']['amount'] = '0.00';
                        }
                        $data['groups'][$aa]['items'][$i]['IsCampaign'] = false;
                        $data['groups'][$aa]['items'][$i]['strikeoutPrice'] = null;
                    }
                    $scnumber = "installmentNumber";
                    $scpayment = "installmentPayment";
                    $data['groups'][$aa]['items'][$i]['creditCardInstallments'][0]['image'] = '';
                    $data['groups'][$aa]['items'][$i]['creditCardInstallments'][0]['type'] = '';
                    $data['groups'][$aa]['items'][$i]['creditCardInstallments'][0]['threeDStatus'] = '0';
                    $data['groups'][$aa]['items'][$i]['creditCardInstallments'][0]['displayName'] = '';
                    $data['groups'][$aa]['items'][$i]['creditCardInstallments'][0]['installmentNumber'] = '0';
                    $data['groups'][$aa]['items'][$i]['creditCardInstallments'][0]['installments'][0][$scnumber] = '0';
                    $data['groups'][$aa]['items'][$i]['creditCardInstallments'][0]['installments'][0][$scpayment] = '0';
                    $data['groups'][$aa]['items'][$i]['creditCardInstallments'][0]['installments'][0]['total'] = '0';
                    $data['groups'][$aa]['items'][$i]['inStock'] = false;
                    if (isset($row['stock_qty']) && $row['stock_qty'] > 0) {
                        $data['groups'][$aa]['items'][$i]['inStock'] = true;
                    }
                    $data['groups'][$aa]['items'][$i]['shipmentInformation'] = '';
                    $data['groups'][$aa]['items'][$i]['isShipmentFree'] = false;
                    $data['groups'][$aa]['items'][$i]['features'][0]['groupName'] = '';
                    $data['groups'][$aa]['items'][$i]['features'][0]['groupId'] = '0';
                    if (isset($row['Attributes']) && !empty($row['Attributes'])) {
                        $data['groups'][$aa]['items'][$i]['variants'] = array();
                        foreach ($row['Attributes'] as $a1 => $av1) {
                            $variant = array();
                            $variant['groupName'] = $a1;
                            $variant['groupId'] = $a1;
                            $variant['features'] = array();
                            $chkArr = array();
                            foreach ($av1 as $att_data) {
                                if (!in_array($att_data['attribute_name'], $chkArr)) {
                                    $feature = array();
                                    $feature['displayName'] = $att_data['attribute_name'];
                                    $feature['value'] = $att_data['id_attribute'];
                                    $chkArr[] = $att_data['attribute_name'];
                                    $variant['features'][] = $feature;
                                }
                            }
                            $data['groups'][$aa]['items'][$i]['variants'][] = $variant;
                        }
                    } else {
                        $data['groups'][$aa]['items'][$i]['variants'] = null;
                    }
                    $data['groups'][$aa]['items'][$i]['features'] = null;
                    $data['groups'][$aa]['items'][$i]['actions'][0]['type'] = 'product';
                    $data['groups'][$aa]['items'][$i]['actions'][0]['image'] = '';
                    $data['groups'][$aa]['items'][$i]['actions'][0]['text'] = '';
                    $data['groups'][$aa]['items'][$i]['actions'][0]['productId'] = $row['id_product'];
                    $data['groups'][$aa]['items'][$i]['actions'][0]['href'] = '';
                    $data['groups'][$aa]['items'][$i]['actions'][0]['categoryId'] = '';
                    if (isset($row['Image']) && !empty($row['Image'])) {
                        foreach ($row['Image'] as $v3) {
                            $imgLink = $context->link->getImageLink($row['link_rewrite'], $v3['id_image']);
                            if ($v3['cover'] == 1) {
                                $data['groups'][$aa]['items'][$i]['picture'] = $imgLink;
                            }
                        }
                    } else {
                        $data['groups'][$aa]['items'][$i]['picture'] = null;
                    }

                    if (isset($row['Image']) && !empty($row['Image'])) {
                        $k = 0;
                        foreach ($row['Image'] as $v4) {
                            $imgLink = $context->link->getImageLink($row['link_rewrite'], $v4['id_image']);
                            if ($v4['cover'] != 1) {
                                $data['groups'][$aa]['items'][$i]['pictures'][$k]['url'] = $imgLink;
                                ++$k;
                            }
                        }
                    } else {
                        $data['groups'][$aa]['items'][$i]['pictures'][0]['url'] = '';
                    }
                    $data['groups'][$aa]['items'][$i]['productDetailUrl'] = $row['description_short'];
                    $url = $context->link->getProductLink($row);
                    $data['groups'][$aa]['items'][$i]['productUrl'] = $url;
                    $data['groups'][$aa]['items'][$i]['points'] = 0;
                    $data['groups'][$aa]['items'][$i]['unit'] = '';
                    $data['groups'][$aa]['items'][$i]['ErrorCode'] = '';
                    $data['groups'][$aa]['items'][$i]['Message'] = '';
                    $data['groups'][$aa]['items'][$i]['UserFriendly'] = false;
                    ++$i;
                }
                ++$aa;
            }
        }
        $this->context = Context::getContext();
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;
        $query = 'SELECT hs.`id_homeslider_slides` AS id_slide, hss.`position`, hss.`active`, hssl.`title`,
			  hssl.`url`, hssl.`legend`, hssl.`description`, hssl.`image`
			FROM '._DB_PREFIX_.'homeslider hs
			    LEFT JOIN '._DB_PREFIX_.'homeslider_slides hss
			     ON (hs.id_homeslider_slides = hss.id_homeslider_slides)
			    LEFT JOIN '._DB_PREFIX_.'homeslider_slides_lang hssl
			     ON (hss.id_homeslider_slides = hssl.id_homeslider_slides)
			WHERE id_shop = '.(int) $id_shop.'
			    AND hssl.id_lang = '.(int) $id_lang.(' AND hss.`active` = 1').'
			ORDER BY hss.position';
        $banner = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        $bArr = array();
        $bb = 0;
        foreach ($banner as $bv1) {
            $bArr['ads'][$bb]['displayName'] = $bv1['title'];
            $bArr['ads'][$bb]['image'] = $context->link->getMediaLink(_MODULE_DIR_.'homeslider/images/'.$bv1['image']);
            $bArr['ads'][$bb]['action']['type'] = 'webview';
            $bArr['ads'][$bb]['action']['image'] = '';
            $bArr['ads'][$bb]['action']['text'] = '';
            $bArr['ads'][$bb]['action']['productId'] = '';
            $bArr['ads'][$bb]['action']['href'] = (isset($bv1['url']) && $bv1['url'] != '') ? $bv1['url'] : '';
            $bArr['ads'][$bb]['action']['categoryId'] = '';
            ++$bb;
        }

        $bArr['ErrorCode'] = '';
        $bArr['Message'] = '';
        $bArr['UserFriendly'] = false;
        $farr = array_merge($data, $bArr);

        return $farr;
    }

    public function groupBy($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            $return[$val[$key]][] = $val;
        }

        return $return;
    }

    private function getIncludeTaxed($taxGroupId, $finalPrice)
    {
        $q = "SELECT  rate from "._DB_PREFIX_."tax
              where id_tax='".$taxGroupId."'";
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($q);
        $rate = $result['rate'];

        return number_format($finalPrice * (1 + ((isset($rate) ? $rate : $rate) * 0.01)), _PS_PRICE_COMPUTE_PRECISION_);
    }

    public function getPriceDiscounted($prod_id)
    {
        $sql1 = "SELECT reduction
                    FROM "._DB_PREFIX_."specific_price
                 WHERE id_product = '".pSQL($prod_id)."'";
        $discount = Db::getInstance()->getValue($sql1);

        return $discount;
    }

    public function getDiscountedPrice($prod_id)
    {
        $sql1 = "SELECT reduction
    FROM "._DB_PREFIX_."specific_price
    WHERE id_product = '".pSQL($prod_id)."'";
        $discount = Db::getInstance()->getValue($sql1);

        $sql11 = "SELECT reduction_type
    FROM "._DB_PREFIX_."specific_price
    WHERE id_product = '".pSQL($prod_id)."'";
        $discount_type = Db::getInstance()->getValue($sql11);
        $sql2 = "SELECT price
    FROM "._DB_PREFIX_."product
    WHERE id_product = '".pSQL($prod_id)."'";
        $priceOrig = Db::getInstance()->getValue($sql2);
        if ($discount_type == 'amount') {
            return  $priceOrig - $discount;
        } else {
            return  $priceOrig - ($priceOrig * $discount);
        }
    }
}
