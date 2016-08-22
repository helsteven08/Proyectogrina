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

class BasketService extends BaseService
{
    private $cart;

    public function updateBasket($input_xml, $basketId)
    {
        $data = Tools::jsonDecode($input_xml, true);
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $cart = new Cart($basketId);
        $currency_sign = $currency->sign;
        $cart->id_currency = (int) $currency->id;
        $cart->id_lang = (int) $this->getLangId();
        if (!Tools::getIsset($cart->id) || !$cart->id) {
            $cart->id = $basketId;
        }
        foreach ($data['product'] as $value) {
            $product_id = (int) $value['productId'];
            $quantity = (int) $value['quantity'];
            $id_product_attribute = null;
            $availableQuantity = StockAvailable::getQuantityAvailableByProduct(
                $product_id,
                $id_product_attribute,
                $this->getShopId()
            );
            if ($quantity > $availableQuantity) {
                return $this->registerCart(
                    $cart->id,
                    $currency = null,
                    array(),
                    array(),
                    array(),
                    array(),
                    null,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    array(),
                    array(),
                    0,
                    array(),
                    0,
                    0,
                    0,
                    0,
                    0,
                    null,
                    false,
                    null,
                    406,
                    "There isn't enough product in stock.",
                    true
                );
            }
            $idAttr = $value['features']['value'];
            $cart->updateQty(0, $product_id, $idAttr);
            if ($quantity > 0) {
                $cart->updateQty($quantity, $product_id, $idAttr);
            }
        }

        $products = $cart->getProducts();
        $lines = array();
        $priceProduct = 0;
        foreach ($products as $row) {
            $product_id = $row['id_product'];
            $quantity = $row['quantity'];
            $placedPrice = $row['total_wt'];
            $placedPriceTotal = $row['total_wt'];
            $extendedPrice = $row['total_wt'];
            $extendedPriceValue = $row['total_wt'];
            $extendedPriceTotal = $row['total_wt'];
            $extendedPriceTotalValue = $row['total_wt'];
            $priceProduct  += $row['total_wt'];
            $lines[] = $this->registerLines(
                $product_id,
                $quantity,
                $placedPrice,
                $placedPriceTotal,
                $extendedPrice,
                $extendedPriceValue,
                $extendedPriceTotal,
                $extendedPriceTotalValue
            );
        }
        $beforeTaxTotal = $cart->getOrderTotal(false);
        $total = $cart->getOrderTotal(true);
        $subTotal = $total;
        $taxTotal = $total - $beforeTaxTotal;
        $shippingMethods = $this->getShipMethod();
        $delivery = array();
        $delivery['shippingAddress'] = null;
        $delivery['billingAddress'] = null;
        $delivery['shippingMethod'] = array();
        $delivery['useSameAddressForBilling'] = false;
        $paymentOptions = $this->getPaymentMethod();
        $payment = null;

        return $this->registerCart(
            $basketId,
            $currency_sign,
            $lines,
            $shippingMethods,
            $delivery,
            $paymentOptions,
            $payment,
            $priceProduct,
            $subTotal,
            $beforeTaxTotal,
            $taxTotal,
            0,
            $total
        );
    }

    public function mergeBasket($userid, $json)
    {
   
        $data = Tools::jsonDecode($json, true);
        $cart_id = $data ['basketId'];
        if ($userid != null || $userid != 0) {
       
            $cart_id = $this->getCartIdCustomerId($userid);
         
        }
        if ($cart_id == null || $cart_id == 0) {
        
            $cart_id = $this->create($userid)->id;
        }

        return $this->getUserBasket($cart_id, $userid);
    }

    public function productPrice($proId)
    {
        $productId = $proId;
        $sql = 'SELECT p.*, pl.description, pl.description_short, pl.available_now,
                pl.available_later, pl.link_rewrite, pl.meta_description, pl.meta_keywords,
                pl.meta_title, pl.name, cl.name AS category_default
        FROM '._DB_PREFIX_.'category_product cp
        LEFT JOIN '._DB_PREFIX_.'product p ON p.id_product = cp.id_product
        LEFT JOIN '._DB_PREFIX_."category_lang cl ON (p.id_category_default = cl.id_category
         AND cl.id_lang = '".$this->getLangId()."')
        LEFT JOIN "._DB_PREFIX_."product_lang pl ON (p.id_product = pl.id_product
         AND pl.id_lang = '".$this->getLangId()."')
        WHERE p.active = '1' AND p.id_product = '".pSQL($productId)."'";
        $sql = $sql.'  GROUP BY p.id_product ';
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(($sql));
        $price = '';
        if (Db::getInstance()->NumRows() > 0) {
            $price = number_format($results[0]['price'], 2);
        }

        return number_format($price, _PS_PRICE_COMPUTE_PRECISION_);
    }

    public function getProductDetail($proId = null)
    {
        $productId = $proId;
        $sql = 'SELECT p.*, pl.description, pl.description_short,sa.quantity AS stock_qty, pl.available_now,
                      pl.available_later, pl.link_rewrite, pl.meta_description, pl.meta_keywords, pl.meta_title,
                      pl.name, cl.name AS category_default
        FROM '._DB_PREFIX_.'category_product cp
        LEFT JOIN '._DB_PREFIX_.'product p ON p.id_product = cp.id_product
		LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON sa.`id_product` = p.`id_product`
        LEFT JOIN '._DB_PREFIX_."category_lang cl
        ON (p.id_category_default = cl.id_category AND cl.id_lang = '".$this->getLangId()."')
        LEFT JOIN "._DB_PREFIX_."product_lang pl
        ON (p.id_product = pl.id_product AND pl.id_lang = '".$this->getLangId()."')
        WHERE p.active = '1' AND p.id_product = '".pSQL($productId)."'";
        $sql = $sql.' GROUP BY p.id_product ';
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(($sql));
        if (Db::getInstance()->NumRows() > 0) {
            $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
            $currency = new Currency($id_currency);
            $currency_sign = $currency->sign;
            $arr = array();
            foreach ($results as $k1 => $v1) {
                $arr[$k1] = $v1;
                $arr[$k1]['currency_sign'] = $currency_sign;
                $sql2 = "SELECT pa.*, ag.id_attribute_group, ag.is_color_group, agl.name AS group_name,
                                al.name AS attribute_name, a.id_attribute, pa.unit_price_impact
					FROM '._DB_PREFIX_.'product_attribute pa
					LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac
					ON pac.id_product_attribute = pa.id_product_attribute
					LEFT JOIN '._DB_PREFIX_.'attribute a ON a.id_attribute = pac.id_attribute
					LEFT JOIN '._DB_PREFIX_.'attribute_group ag ON ag.id_attribute_group = a.id_attribute_group
					LEFT JOIN '._DB_PREFIX_.'attribute_lang al ON (a.id_attribute = al.id_attribute AND al.id_lang = 1)
					LEFT JOIN '._DB_PREFIX_.'attribute_group_lang agl ON
					(ag.id_attribute_group = agl.id_attribute_group AND agl.id_lang = 1)
					WHERE pa.id_product = '".pSQL($v1['id_product'])."' ORDER BY group_name";
                $attr_res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(($sql2));
                $attr_results = $this->groupBy($attr_res, 'group_name');
                $arr[$k1]['Attributes'] = $attr_results;
                $sql3 = "SELECT * FROM '._DB_PREFIX_.'image i
					LEFT JOIN "._DB_PREFIX_."image_lang il ON (i.id_image = il.id_image)
					WHERE i.id_product = '".pSQL($v1['id_product'])."' AND il.id_lang = ".$this->getLangId()."
					ORDER BY i.position ASC";
                $img_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(($sql3));
                $arr[$k1]['Image'] = $img_results;
            }
        }
        $data = array();
        if (!empty($arr)) {
            $i = 0;
            foreach ($arr as $row) {
                $context = Context::getContext();
                $data['id'] = $row['id_product'];
                $data['productName'] = $row['name'];
                $discounted_price = $this->getPriceDiscounted($row['id_product']);
                if ($discounted_price > 0) {
                    if (Tools::getIsset($row['price']) && !empty($row['price'])) {
                        $data['listPrice']['amount'] = number_format($this->getPriceDiscounted($row['id_product']), 2);
                    } else {
                        $data['listPrice']['amount'] = '0.00';
                    }
                    $data['listPrice']['currency'] = $row['currency_sign'];
                    $data['listPrice']['amountDefaultCurrency'] = '';
                    $data['IsCampaign'] = true;
                    $amount = (isset($row['price']) && $row['price'] != '') ? number_format($row['price'], 2) : 0;
                    $data['strikeoutPrice']['amount'] =$amount;
                    $data['strikeoutPrice']['currency'] = $row['currency_sign'];
                    $data['strikeoutPrice']['amountDefaultCurrency'] = '';
                } else {
                    if (Tools::getIsset($row['price']) && !empty($row['price'])) {
                        $amount = (isset($row['price']) && $row['price'] != '') ? number_format($row['price'], 2) : 0;
                        $data['listPrice']['amount'] = $amount;
                    } else {
                        $data['listPrice']['amount'] = '0.00';
                    }
                    $data['listPrice']['currency'] = $row['currency_sign'];
                    $data['listPrice']['amountDefaultCurrency'] = '';
                    $data['IsCampaign'] = false;
                    $data['strikeoutPrice'] = null;
                }
                $data['noImageUrl'] = '';
                $data['headline'] = '';
                $data['creditCardInstallments'][0]['image'] = '';
                $data['creditCardInstallments'][0]['type'] = '';
                $data['creditCardInstallments'][0]['threeDStatus'] = 0;
                $data['creditCardInstallments'][0]['displayName'] = '';
                $data['creditCardInstallments'][0]['installmentNumber'] = 0;
                $data['creditCardInstallments'][0]['installments'][0]['installmentNumber'] = 0;
                $data['creditCardInstallments'][0]['installments'][0]['installmentPayment'] = 0;
                $data['creditCardInstallments'][0]['installments'][0]['total'] = 0;
                $data['inStock'] = false;
                if (Tools::getIsset($row['stock_qty']) && $row['stock_qty'] > 0) {
                    $data['inStock'] = true;
                }
                $data['shipmentInformation'] = '';
                $data['isShipmentFree'] = false;
                $data['features'] = null;
                if (Tools::getIsset($row['Attributes']) && !empty($row['Attributes'])) {
                    $ii = 0;
                    $data['variants'][$ii]['groupName'] = '';
                    $data['variants'][$ii]['groupId'] = '';
                    $j = 0;
                    foreach ($row['Attributes'] as $k2 => $att_data) {
                        $attName = $att_data['attribute_name'];
                        $displayeName = Tools::getIsset($attName) ? $attName : null;
                        $idAttr = Tools::getIsset($att_data['id_attribute']) ? $att_data['id_attribute'] : null;
                        $data['variants'][$ii]['features'][$j]['displayName'] = $displayeName ;
                        $data['variants'][$ii]['features'][$j]['value'] = $idAttr ;
                        ++$j;
                        //$ii++;
                    }
                } else {
                    $data['variants'] = null;
                }
                if (Tools::getIsset($row['Attributes']) && count($row['Attributes']) > 0) {
                    $ii = 0;
                    foreach ($row['Attributes'] as $k2 => $v2) {
                        $data['variants'][$ii]['groupName'] = $k2;
                        $idAttrGroup = $v2[0]['id_attribute_group'];
                        $groupId = (isset($idAttrGroup) && $idAttrGroup != '') ? $idAttrGroup : '';
                        $data['variants'][$ii]['groupId'] = $groupId ;
                        $j = 0;
                        $chkArr = array();
                        foreach ($v2 as $att_data) {
                            if (!in_array($att_data['attribute_name'], $chkArr)) {
                                $data['variants'][$ii]['features'][$j]['displayName'] = $att_data['attribute_name'];
                                $data['variants'][$ii]['features'][$j]['value'] = $att_data['id_attribute'];
                                $chkArr[] = $att_data['attribute_name'];
                                ++$j;
                            }
                        }
                        ++$ii;
                    }
                } else {
                    $data['variants'] = null;
                }
                $data['shoutOutTexts'] = array();
                $data['actions'][0]['type'] = '';
                $data['actions'][0]['image'] = '';
                $data['actions'][0]['text'] = '';
                $data['actions'][0]['productId'] = '';
                $data['actions'][0]['href'] = '';
                $data['actions'][0]['categoryId'] = '';
                if (isset($row['Image']) && !empty($row['Image'])) {
                    foreach ($row['Image'] as $v3) {
                        $imgLink = $context->link->getImageLink($row['link_rewrite'], $v3['id_image']);
                        if ($v3['cover'] == 1) {
                            $data['picture'] = $imgLink;
                        }
                    }
                } else {
                    $data['picture'] = '';
                }
                if (isset($row['Image']) && !empty($row['Image'])) {
                    $k = 0;
                    foreach ($row['Image'] as $v4) {
                        $imgLink = $context->link->getImageLink($row['link_rewrite'], $v4['id_image']);
                        if ($v4['cover'] != 1) {
                            $data['pictures'][$k]['url'] = $imgLink;
                            ++$k;
                        }
                    }
                } else {
                    $data['pictures'][0]['url'] = '';
                }
                $data['productDetailUrl'] = $row['description_short'];
                $url = $context->link->getProductLink($row);
                $data['productUrl'] = $url;
                $data['points'] = 0;
                $data['unit'] = '';
                $data['ErrorCode'] = '';
                $data['Message'] = '';
                $data['UserFriendly'] = false;
                ++$i;
            }
        }

        return $data;
    }
    private function fillShippingMethod($id, $displayname, $trackingAddress, $price, $priceForYou, $imageUrl)
    {
        $data = array();
        $data[0]['id'] = $id;
        $data[0]['displayName'] = $displayname;
        $data[0]['trackingAddress'] = "$trackingAddress";
        $data[0]['price'] = "$price";
        $data[0]['priceForYou'] = "$priceForYou";
        $data[0]['imageUrl'] = "$imageUrl";

        return  $data;
    }

    private function getShipMethod($id_address = null)
    {
        $results = Carrier::getCarriers($this->getLangId(), true);
        $data = array();
        if (Db::getInstance()->NumRows() > 0) {
            $i = 0;
            if (!empty($id_address)) {
                $id_zone = Address::getZoneById($id_address);
            } else {
                $ps_country_default = Configuration::get('PS_COUNTRY_DEFAULT');
                $country = new Country($ps_country_default);
                $id_zone = $country->id_zone;
            }
            foreach ($results as $v1) {
                $ps_method = (int) Configuration::get('PS_SHIPPING_METHOD');
                if ($ps_method) {
                    $w = Carrier::SHIPPING_METHOD_WEIGHT;
                    $qr = 'd.id_range_weight = '.$w;
                } else {
                    $p = Carrier::SHIPPING_METHOD_PRICE;
                    $qr = 'd.id_range_price = '.$p;
                }
                $sql2 = 'SELECT d.* FROM '._DB_PREFIX_."delivery d
                WHERE id_zone = '".pSQL($id_zone)."' AND id_carrier = '".pSQL($v1['id_carrier'])."' AND ".pSQL($qr);
                $results2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(($sql2));
                if (isset($results2[0]['price'])) {
                    $price = $results2[0]['price'];
                } else {
                    $price = '';
                }
                $data[$i]['id'] = $v1['id_carrier'];
                $data[$i]['displayName'] = $v1['name'];
                $data[$i]['trackingAddress'] = '';
                $data[$i]['price'] = $price;
                $data[$i]['priceForYou'] = '';
                $data[$i]['imageUrl'] = '';
                ++$i;
            }

            return $data;
        } else {
            return array();
        }
    }
    private function getPaypalParameters()
    {
        $query = 'SELECT *
			FROM `'._DB_PREFIX_.'tappz_paypal`
			order by id_tappz_paypal  desc limit 1';
        $result = Db::getInstance()->executeS(($query));

        return $result[0];
    }
    public function getPaymentMethod()
    {
        $payment_modules = array();
        $shop_id = Context::getContext()->shop->id;
        $modules = Module::getModulesOnDisk(true);
        $paypal = $this->getPaypalParameters();
        foreach ($modules as $module) {
            if ($module->tab == 'payments_gateways') {
                if ($module->id) {
                    if (!get_class($module) == 'SimpleXMLElement') {
                        $module->country = array();
                    }
                    $queryCountries = "
						SELECT id_country
						FROM "._DB_PREFIX_."module_country
						WHERE id_module = '".pSQL($module->id)."' AND `id_shop`='".pSQL($shop_id)."'";
                    $countries = DB::getInstance()->executeS(($queryCountries));
                    foreach ($countries as $country) {
                        $module->country[] = $country['id_country'];
                    }
                    if (!get_class($module) == 'SimpleXMLElement') {
                        $module->currency = array();
                    }
                    $queryCurrency = "
						SELECT id_currency
						FROM "._DB_PREFIX_."module_currency
						WHERE id_module = '".(int) $module->id."' AND `id_shop`='".pSQL($shop_id)."'";
                    $currencies = DB::getInstance()->executeS(($queryCurrency));
                    foreach ($currencies as $currency) {
                        $module->currency[] = $currency['id_currency'];
                    }
                    if (!get_class($module) == 'SimpleXMLElement') {
                        $module->group = array();
                    }
                    $queryGroup = "	SELECT id_group
						FROM "._DB_PREFIX_."module_group
						WHERE id_module = '".(int) $module->id."' AND `id_shop`='".pSQL($shop_id)."'";
                    $groups = DB::getInstance()->executeS(($queryGroup));
                    foreach ($groups as $group) {
                        $module->group[] = $group['id_group'];
                    }
                } else {
                    $module->country = null;
                    $module->currency = null;
                    $module->group = null;
                }
                $payment_modules[] = $module;
            }
        }
        $data = array();
        $i = 0;

        foreach ($payment_modules as $v1) {
            if ($v1->id != 0) {
                $name = $v1->name;
                if (Tools::strtolower($name) == 'paypal') {
                    $data['paypal'] = array(
                        'clientId' => $paypal['paypal_client_id'],
                        'displayName' => 'PayPal',
                        'isSandbox' => $paypal['sandbox'] > 0 ? true : false,
                    );
                }
                if (Tools::strtolower($name) == 'hipay') {
                    $data['creditCard'][0] = array(
                            'image' => "",
                             'type' =>"creditCard",
                             'threeDStatus' => 0,
                             'displayName' =>  "Default Credit Card",
                             'installmentNumber' => 0,
                             'installments' => null,
                    );
                }
                if (Tools::strtolower($name) == 'cashondelivery') {
                    $data['cashOnDelivery'][0]['type'] = 'cashOnDelivery';
                    $data['cashOnDelivery'][0]['displayName'] = $v1->displayName;
                    $data['cashOnDelivery'][0]['additionalFee'] = '0';
                    $data['cashOnDelivery'][0]['description'] = $v1->displayName;
                    $data['cashOnDelivery'][0]['isSmsVerification'] = false;
                    $data['cashOnDelivery'][0]['phoneNumber'] = '';
                    $data['cashOnDelivery'][0]['smsCode'] = '';
                }
            }
            ++$i;
        }
        if (count($data) > 0) {
            return $data;
        } else {
            $data['cashOnDelivery'][0]['type'] = "Couldn't find any payment method";
            $data['cashOnDelivery'][0]['displayName'] = "Couldn't find any payment method";
            $data['cashOnDelivery'][0]['additionalFee'] = '0';
            $data['cashOnDelivery'][0]['description'] = "Couldn't find any payment method";
            $data['cashOnDelivery'][0]['isSmsVerification'] = false;
            $data['cashOnDelivery'][0]['phoneNumber'] = '';
            $data['cashOnDelivery'][0]['smsCode'] = '';

            return $data;
        }
    }

    public function updateBasketAddress($input_xml, $basketId)
    {
        $data = Tools::jsonDecode($input_xml, true);
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $cart = new Cart($basketId);
        $currency_sign = $currency->sign;
        $products = $cart->getProducts();
        $lines = array();
        $priceProduct = 0;
        foreach ($products as $row) {
            $product_id = $row['id_product'];
            $quantity = $row['quantity'];
            $placedPrice = $row['total_wt'];
            $placedPriceTotal = $row['total_wt'];
            $extendedPrice = $row['total_wt'];
            $extendedPriceValue = $row['total_wt'];
            $extendedPriceTotal = $row['total_wt'];
            $extendedPriceTotalValue = $row['total_wt'];
            $lines[] = $this->registerLines(
                $product_id,
                $quantity,
                $placedPrice,
                $placedPriceTotal,
                $extendedPrice,
                $extendedPriceValue,
                $extendedPriceTotal,
                $extendedPriceTotalValue
            );
            $priceProduct +=  $row['total_wt'];
        }
        $beforeTaxTotal = $cart->getOrderTotal(false);
        $total = $cart->getOrderTotal(true);
        $taxTotal = $total - $beforeTaxTotal;
        $taxTotal > 0 ? $taxTotalShipmentPrice = true : $taxTotalShipmentPrice = false;
        $shipmentId = $data['shippingMethod'][0]['id'];
        (int) $shipmentId > 0 ? $id_carrier = $shipmentId :  $id_carrier = null;
        $shipmentPrice = $cart->getPackageShippingCost($id_carrier, $taxTotalShipmentPrice);
        $subTotal = $priceProduct + $shipmentPrice;

        $useSameAddress = false;
        $billingId = $data['billingAddress']['id'] ;
        $shippingMethods = $this->getShipMethod();
        if ($data['shippingAddress']['id'] != null && (int) $data['shippingAddress']['id'] > 0) {
            $cart->id_address_delivery = $data['shippingAddress']['id'];
            $cart->id_address_invoice = $data['shippingAddress']['id'];
            $cart->update();
        }
        if ($billingId != null && (int) $billingId > 0 && $useSameAddress == false) {
            $cart->id_address_invoice = $data['billingAddress']['id'];
            $cart->update();
        }

        $data['delivery'] ['shippingAddress'] = '';
        $data['delivery'] ['billingAddress'] = '';
        $data['delivery'] ['shippingMethod'] = array();

        $data['delivery'] ['useSameAddressForBilling'] = $useSameAddress;
        $address_id = ($data['shippingAddress']['id'] != '') ? $data['shippingAddress']['id'] : '';
        $billing_address_id = ($data['billingAddress']['id'] != '') ? $data['billingAddress']['id'] : '';
        $paymentOptions = $this->getPaymentMethod();
        if (count($data['shippingMethod']) > 0) {
            $data['delivery'] = $this->getDeliveryAddressWithShipping($cart->id_customer, $data['shippingMethod']);
        } else {
            if ($address_id != '') {
                $data['delivery'] = $this->getDeliveryAddressById($address_id, $billing_address_id);
            } else {
                $data['delivery'] = $this->getDeliveryAddress($cart->id_customer);
            }
        }

        if ($data['shippingMethod'][0]['id'] != null && (int) $data['shippingMethod'][0]['id']  > 0) {
            $id = $data['shippingMethod'][0]['id'];
            $shippingMethods = $this->getShipMethod($id);
            $name = $data['shippingMethod'][0]['name'] != '' ? $data['shippingMethod'][0]['name'] : '';
            $data['delivery']['shippingMethod'] = $this->fillShippingMethod($id, $name, '', $shipmentPrice);
            $cart->id_carrier = (int) $data['shippingMethod'][0][' id'];
            $cart->update();
        }

        $payment = array();
        $payment['methodType'] = 'CashOnDelivery';
        $payment['type'] = 'cashOnDelivery';
        $payment['displayName'] = 'Cash On Delivery';
        $payment['bankCode'] = '';
        $payment['installment'] = 0;
        $payment['accountNumber'] = '';
        $payment['branch'] = '';
        $payment['iban'] = '';
        $payment_modules = array();
        $modules = Module::getModulesOnDisk(true);
        $paypal = $this->getPaypalParameters();
        foreach ($modules as $module) {
            if ($module->tab == 'payments_gateways') {
                $payment_modules[] = $module;
            }
        }
        foreach ($payment_modules as $v1) {
            if ($v1->id != 0) {
                $name = $v1->name;
                if (Tools::strtolower($name) == 'paypal') {
                    $data['paypal'] = array(
                        'clientId' => $paypal['paypal_client_id'],
                        'displayName' => 'PayPal',
                        'isSandbox' => $paypal['sandbox'] > 0 ? true : false,
                    );
                }
                if (Tools::strtolower($name) == 'hipay') {
                    $data['creditCard'][0] = array(
                        'image' => "",
                        'type' =>"creditCard",
                        'threeDStatus' => 0,
                        'displayName' => "Default Credit Card",
                        'installmentNumber' => 0,
                        'installments' => null,
                    );
                }
                if (Tools::strtolower($name) == 'cashondelivery') {
                    $data['cashOnDelivery'][0]['type'] = 'cashOnDelivery';
                    $data['cashOnDelivery'][0]['displayName'] = $v1->displayName;
                    $data['cashOnDelivery'][0]['additionalFee'] = '0';
                    $data['cashOnDelivery'][0]['description'] = $v1->displayName;
                    $data['cashOnDelivery'][0]['isSmsVerification'] = false;
                    $data['cashOnDelivery'][0]['phoneNumber'] = '';
                    $data['cashOnDelivery'][0]['smsCode'] = '';
                }
            }
        }
        
        $response = $this->registerCart(
            $basketId,
            $currency_sign,
            $lines,
            $shippingMethods,
            $data['delivery'],
            $paymentOptions,
            $payment,
            $priceProduct,
            $subTotal,
            $beforeTaxTotal,
            $taxTotal,
            $shipmentPrice,
            $total
        );

        return $response;
    }

    public function getDeliveryAddressById($address_id = null, $billing_address_id = null)
    {
        $sql = 'SELECT a.*, s.id_state,s.name AS state_name,cl.id_lang,cl.id_country,cl.name AS country_name
                FROM '._DB_PREFIX_.'address a
                LEFT JOIN '._DB_PREFIX_.'country_lang cl ON a.id_country = cl.id_country
                LEFT JOIN  '._DB_PREFIX_."state s ON s.id_state = a.id_state
                 WHERE a.id_address='".pSQL($address_id)."' AND a.deleted = '0'";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $data = array();
        if (Db::getInstance()->NumRows() > 0) {
            $countryName = $results[0]['country_name'];
            $idCountry = $results[0]['id_country'];
            $resultStateName = $results[0]['state_name'];
            $idState = $results[0]['id_state'];
            $shipping_id = $results[0]['id_address'];
            $addressName = $results[0]['alias'];
            $name = $results[0]['firstname'];
            $surname = $results[0]['lastname'];
            $email = '';
            $addressLine = $results[0]['address1'].' '.$results[0]['address2'];
            $country = (Tools::getIsset($countryName) && $countryName != '') ? $countryName : '';
            $countryCode = (Tools::getIsset($idCountry) && $idCountry != '') ? $idCountry : '';
            $state = (Tools::getIsset($resultStateName) && $resultStateName != '') ? $resultStateName : '';
            $stateCode = (Tools::getIsset($idState) && $idState != '') ? $idState : '';
            $city = $results[0]['city'];
            $cityCode = '';
            $district = '';
            $districtCode = '';
            $town = '';
            $townCode = '';
            $corporate = false;
            $companyTitle = $results[0]['company'];
            $taxDepartment = '';
            $taxNo = '';
            $phoneNumber = $results[0]['phone'];
            $identityNo = '';
            $zipCode = '';
            $usCheckoutCity = '';
            $ErrorCode = '';
            $Message = '';
            $UserFriendly = false;
            $data['shippingAddress'] = $this->registerShippingAddress(
                $shipping_id,
                $addressName,
                $name,
                $surname,
                $email,
                $addressLine,
                $country,
                $countryCode,
                $state,
                $stateCode,
                $city,
                $cityCode,
                $district,
                $districtCode,
                $town,
                $townCode,
                $corporate,
                $companyTitle,
                $taxDepartment,
                $taxNo,
                $phoneNumber,
                $identityNo,
                $zipCode,
                $usCheckoutCity,
                $ErrorCode,
                $Message,
                $UserFriendly
            );
            $sql = 'SELECT a.*, s.id_state,s.name AS state_name,cl.id_lang,cl.id_country,cl.name AS country_name
                    FROM '._DB_PREFIX_.'address a
                     LEFT JOIN '._DB_PREFIX_.'country_lang cl ON a.id_country = cl.id_country
                     LEFT JOIN  '._DB_PREFIX_."state s ON s.id_state = a.id_state
                     WHERE a.id_address='".pSQL($billing_address_id)."' AND a.deleted = '0'";
            $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            $id = $results[0]['id_address'];
            $addressName = $results[0]['alias'];
            $name = $results[0]['firstname'];
            $surname = $results[0]['lastname'];
            $email = '';
            $addressLine = $results[0]['address1'].' '.$results[0]['address2'];
            $country = $results[0]['country_name'];
            $countryCode = $results[0]['id_country'];
            $state = $results[0]['state_name'];
            $stateCode = $results[0]['id_state'];
            $city = $results[0]['city'];
            $cityCode = '';
            $district = '';
            $districtCode = '';
            $town = '';
            $townCode = '';
            $corporate = false;
            $companyTitle = $results[0]['company'];
            $taxDepartment = '';
            $taxNo = '';
            $phoneNumber = $results[0]['phone'];
            $identityNo = '';
            $zipCode = '';
            $usCheckoutCity = '';
            $ErrorCode = '';
            $Message = '';
            $UserFriendly = false;
            $data['shippingMethod'] = array();
            $data['useSameAddressForBilling'] = ($id == $shipping_id ? true : false);
            $data['billingAddress'] = $this->registerBillingAdress(
                $id,
                $name,
                $addressName,
                $email,
                $surname,
                $addressLine,
                $country,
                $countryCode,
                $state,
                $stateCode,
                $city,
                $cityCode,
                $district,
                $districtCode,
                $town,
                $townCode,
                $corporate,
                $companyTitle,
                $taxDepartment,
                $taxNo,
                $phoneNumber,
                $identityNo,
                $zipCode,
                $usCheckoutCity,
                $ErrorCode,
                $Message,
                $UserFriendly
            );
        } else {
            $data['shippingAddress'] = $this->registerShippingAddress();
            $data['billingAddress'] = $this->registerBillingAdress();
            $data['shippingMethod'] = array();
            $data['useSameAddressForBilling'] = false;
        }

        return $data;
    }

    public function addBasketDiscount($input_xml, $basketId)
    {
        $data_arr = (Tools::getIsset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $currency_sign = $currency->sign;
        $data = array();
        if (
            Tools::getIsset($basketId)
            && !empty($basketId)
            && Tools::getIsset($data_arr['displayName'])
            && !empty($data_arr['displayName'])
            && Tools::getIsset($data_arr['promoCode'])
            && !empty($data_arr['promoCode'])
        ) {
            $sql = 'SELECT cr.*, crl.* FROM '._DB_PREFIX_.'cart_rule cr

					LEFT JOIN '._DB_PREFIX_."cart_rule_lang crl ON crl.id_cart_rule = cr.id_cart_rule

					WHERE cr.code = '".pSQL($data_arr['promoCode'])."'";
            $dis_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if (count($dis_results) > 0) {
                $discountPrice = $dis_results[0]['reduction_amount'];
                $discountPercent = $dis_results[0]['reduction_percent'];
                $insert_sql = 'INSERT INTO '._DB_PREFIX_."cart_cart_rule
                 SET id_cart = '".pSQL($basketId)."',
                  id_cart_rule = '".pSQL($dis_results[0]['id_cart_rule'])."'";
                Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($insert_sql);
                $query = "SELECT c.*,p.id_product, p.quantity, p.id_product_attribute FROM "._DB_PREFIX_."cart c
                            LEFT JOIN "._DB_PREFIX_."cart_product p ON (p.id_cart = c.id_cart)
                          WHERE c.id_cart = '".pSQL($basketId) ."' ORDER BY p.date_add ASC";
                $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                $i = 0;
                if (Db::getInstance()->NumRows() > 0) {
                    $data['id'] = $basketId;
                    $total = 0;
                    foreach ($results as $v1) {
                        $price = $this->productPrice($v1['id_product']);
                        $total = $total + ($price * $v1['quantity']);
                        if (Tools::getIsset($v1['id_customer']) && !empty($v1['id_customer'])) {
                            $data['lines'][$i]['productId'] = $v1['id_product'];
                            $data['lines'][$i]['product'] = $this->getProductDetail($v1['id_product']);
                            $data['lines'][$i]['quantity'] = $v1['quantity'];
                            $data['lines'][$i]['placedPrice'] = 0;
                            $data['lines'][$i]['placedPriceTotal'] = 0;
                            $data['lines'][$i]['extendedPrice'] = (string) $price;
                            $data['lines'][$i]['extendedPriceValue'] = (float) $price;
                            $data['lines'][$i]['extendedPriceTotal'] = (string) ($price * $v1['quantity']);
                            $data['lines'][$i]['extendedPriceTotalValue'] = (float) $price * $v1['quantity'];
                            $data['lines'][$i]['strikeoutPrice']['amount'] = 0;
                            $data['lines'][$i]['strikeoutPrice']['currency'] = '';
                            $data['lines'][$i]['strikeoutPrice']['amountDefaultCurrency'] = '';
                            $data['lines'][$i]['status'] = '';
                            $data['lines'][$i]['averageDeliveryDays'] = '';
                            $data['lines'][$i]['variants'][0]['groupName'] = '';
                            $data['lines'][$i]['variants'][0]['groupId'] = '';
                            $data['lines'][$i]['variants'][0]['features'][0]['displayName'] = '';
                            $data['lines'][$i]['variants'][0]['features'][0]['value'] = '';
                            $data['shippingMethods'] = $this->getShipMethod($v1['id_address_delivery']);
                            $data['delivery'] = $this->getDeliveryAddress($v1['id_customer']);
                            $data['paymentOptions'] = $this->getPaymentMethod();
                            $data['payment']['methodType'] = 'cashOnDelivery';
                            $data['payment']['type'] = 'cashOnDelivery';
                            $data['payment']['displayName'] = '';
                            $data['payment']['bankCode'] = '';
                            $data['payment']['installment'] = 0;
                            $data['payment']['accountNumber'] = '';
                            $data['payment']['branch'] = '';
                            $data['payment']['iban'] = '';
                            $data['payment']['cashOnDelivery']['type'] = 'cashOnDelivery';
                            $data['payment']['cashOnDelivery']['displayName'] = '';
                            $data['payment']['cashOnDelivery']['additionalFee'] = '0';
                            $data['payment']['cashOnDelivery']['description'] = 'Cash On Delivery';
                            $data['payment']['cashOnDelivery']['isSmsVerification'] = false;
                            $data['payment']['cashOnDelivery']['phoneNumber'] = '';
                            $data['payment']['cashOnDelivery']['smsCode'] = '';
                            $data['payment']['creditCard']['owner'] = '';
                            $data['payment']['creditCard']['number'] = '';
                            $data['payment']['creditCard']['cvv'] = '';
                            $data['payment']['creditCard']['month'] = 0;
                            $data['payment']['creditCard']['year'] = 0;
                            $data['payment']['creditCard']['type'] = '0';
                            $data['payment']['creditCard']['installment'] = 0;
                            $data['payment']['threeDUrl']['pendingUrl'] = '';
                            $data['payment']['threeDUrl']['successUrl'] = '';
                            $data['payment']['threeDUrl']['failUrl'] = '';
                            $data['payment']['threeDUrl']['cancelUrl'] = '';
                            $data['currency'] = $currency_sign;
                            $data['itemsPriceTotal'] = $total;
                            $data['subTotal'] = $total;
                            $data['beforeTaxTotal'] = $total;
                            $data['taxTotal'] = 0;
                            $data['shippingTotal'] = 0;
                            $data['total'] = $total;
                            $data['errors'] = array();
                            $data['giftCheques'][0]['chequeNumber'] = '';
                            $data['giftCheques'][0]['activationDate'] = '';
                            $data['giftCheques'][0]['expireDate'] = '';
                            $data['giftCheques'][0]['amount'] = 0;
                            $data['giftCheques'][0]['code'] = '';
                            $data['giftCheques'][0]['status'] = '';
                            $data['giftCheques'][0]['type'] = '';
                            $data['giftCheques'][0]['legalText'] = '';
                            $data['spentGiftChequeTotal'] = '0';
                            $data['itemsPriceTotal'] = $total;
                            $data['subTotal'] = $total;
                            $data['beforeTaxTotal'] = $total;
                            $data['taxTotal'] = 0;
                            $data['shippingTotal'] = 0;
                            $data['total'] = $total;
                            $data['errors'] = array();
                            $disPrice = '';
                            if (!empty($discountPrice) && $discountPrice > 0) {
                                $disPrice = $data['total'] - $discountPrice;
                            } else {
                                if (!empty($discountPercent) && $discountPercent > 0) {
                                    $p_price = ($discountPercent / 100) * $data['total'];
                                    $disPrice = $data['total'] - $p_price;
                                }
                            }
                            $data['discounts'][0]['displayName'] = (Tools::getIsset($dis_results[0]['name'])
                                && $dis_results[0]['name'] != '')
                                ?
                                $dis_results[0]['name']
                                :
                                'discount1';
                            $data['discounts'][0]['discountTotal'] = (Tools::getIsset($disPrice) && $disPrice != '')
                                ?
                                $disPrice
                                :
                                0;
                            $data['discounts'][0]['promoCode'] = $data_arr['promoCode'];
                            $data['discounts'][0]['ErrorCode'] = '';
                            $data['discounts'][0]['Message'] = '';
                            $data['discounts'][0]['UserFriendly'] = false;
                            $data['discountTotal'] = 0;
                            $data['usedPoints'] = 0;
                            $data['usedPointsAmount'] = 0;
                            $data['rewardPoints'] = 0;
                            $data['paymentFee'] = 0;
                            $data['estimatedSupplyDate'] = '';
                            $data['isGiftWrappingEnabled'] = false;
                            $data['giftWrapping']['isSelected'] = false;
                            $data['giftWrapping']['giftWrappingFee'] = '0';
                            $data['giftWrapping']['maxCharackter'] = '0';
                            $data['giftWrapping']['message'] = '';
                            $data['ErrorCode'] = '';
                            $data['Message'] = '';
                            $data['UserFriendly'] = false;
                            ++$i;
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function deleteBasketDiscount($input_xml)
    {
        $data_arr = (Tools::getIsset(($input_xml)) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
        $request = (Tools::getIsset((Tools::getValue('url')))
            && Tools::getValue('url')  != '') ? Tools::getValue('url') : '';
        $catId = explode('/', $request);
        $basketId = (Tools::getIsset(($catId[1])) && $catId[1] != '') ? $catId[1] : '';
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $currency_sign = $currency->sign;
        $data = array();
        if (
            Tools::getIsset(($basketId))
            && !empty($basketId)
            && Tools::getIsset(($data_arr['promoCode']))
            && !empty($data_arr['promoCode'])) {
            $sql = 'SELECT cr.*, crl.* FROM '._DB_PREFIX_.'cart_rule cr
					LEFT JOIN '._DB_PREFIX_."cart_rule_lang crl ON crl.id_cart_rule = cr.id_cart_rule
					WHERE cr.code = '".pSQL($data_arr['promoCode'])."'";
            $dis_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if (count($dis_results) > 0) {
                $del_sql = "DELETE FROM "._DB_PREFIX_."cart_cart_rule
                 WHERE id_cart = '".pSQL($basketId)."' AND id_cart_rule = '".pSQL($dis_results[0]['id_cart_rule'])."'";
                Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($del_sql);
                $query = "SELECT c.*,p.id_product, p.quantity, p.id_product_attribute FROM "._DB_PREFIX_."cart c
LEFT JOIN "._DB_PREFIX_."cart_product p ON (p.id_cart = c.id_cart)
WHERE c.id_cart = '".pSQL($basketId)."' ORDER BY p.date_add ASC";
                $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                $i = 0;
                if (Db::getInstance()->NumRows() > 0) {
                    $data['id'] = $basketId;
                    $total = '';
                    foreach ($results as $v1) {
                        $price = $this->productPrice($v1['id_product']);
                        $total = $total + ($price * $v1['quantity']);
                        if (Tools::getIsset(($v1['id_customer'])) && !empty($v1['id_customer'])) {
                            $data['lines'][$i]['productId'] = $v1['id_product'];
                            $data['lines'][$i]['product'] = $this->getProductDetail($v1['id_product']);
                            $data['lines'][$i]['quantity'] = $v1['quantity'];
                            $data['lines'][$i]['placedPrice'] = 0;
                            $data['lines'][$i]['placedPriceTotal'] = 0;
                            $data['lines'][$i]['extendedPrice'] = (string) $price;
                            $data['lines'][$i]['extendedPriceValue'] = (float) $price;
                            $data['lines'][$i]['extendedPriceTotal'] = (string) ($price * $v1['quantity']);
                            $data['lines'][$i]['extendedPriceTotalValue'] = (float) $price * $v1['quantity'];
                            $data['lines'][$i]['strikeoutPrice']['amount'] = 0;
                            $data['lines'][$i]['strikeoutPrice']['currency'] = '';
                            $data['lines'][$i]['strikeoutPrice']['amountDefaultCurrency'] = '';
                            $data['lines'][$i]['status'] = '';
                            $data['lines'][$i]['averageDeliveryDays'] = '';
                            $data['lines'][$i]['variants'][0]['groupName'] = '';
                            $data['lines'][$i]['variants'][0]['groupId'] = '';
                            $data['lines'][$i]['variants'][0]['features'][0]['displayName'] = '';
                            $data['lines'][$i]['variants'][0]['features'][0]['value'] = '';
                            $data['shippingMethods'] = $this->getShipMethod($v1['id_address_delivery']);
                            $data['delivery'] = $this->getDeliveryAddress($v1['id_customer']);
                            $data['paymentOptions'] = $this->getPaymentMethod();
                            $data['payment']['methodType'] = 'cashOnDelivery';
                            $data['payment']['type'] = 'cashOnDelivery';
                            $data['payment']['displayName'] = '';
                            $data['payment']['bankCode'] = '';
                            $data['payment']['installment'] = 0;
                            $data['payment']['accountNumber'] = '';
                            $data['payment']['branch'] = '';
                            $data['payment']['iban'] = '';
                            $data['payment']['cashOnDelivery']['type'] = 'cashOnDelivery';
                            $data['payment']['cashOnDelivery']['displayName'] = '';
                            $data['payment']['cashOnDelivery']['additionalFee'] = '0';
                            $data['payment']['cashOnDelivery']['description'] = "'Cash On Delivery";
                            $data['payment']['cashOnDelivery']['isSmsVerification'] = false;
                            $data['payment']['cashOnDelivery']['phoneNumber'] = '';
                            $data['payment']['cashOnDelivery']['smsCode'] = '';
                            $data['payment']['creditCard']['owner'] = '';
                            $data['payment']['creditCard']['number'] = '';
                            $data['payment']['creditCard']['cvv'] = '';
                            $data['payment']['creditCard']['month'] = 0;
                            $data['payment']['creditCard']['year'] = 0;
                            $data['payment']['creditCard']['type'] = '0';
                            $data['payment']['creditCard']['installment'] = 0;
                            $data['payment']['threeDUrl']['pendingUrl'] = '';
                            $data['payment']['threeDUrl']['successUrl'] = '';
                            $data['payment']['threeDUrl']['failUrl'] = '';
                            $data['payment']['threeDUrl']['cancelUrl'] = '';
                            $data['currency'] = $currency_sign;
                            $data['itemsPriceTotal'] = $total;
                            $data['subTotal'] = $total;
                            $data['beforeTaxTotal'] = $total;
                            $data['taxTotal'] = 0;
                            $data['shippingTotal'] = 0;
                            $data['total'] = $total;
                            $data['errors'] = array();
                            $data['giftCheques'][0]['chequeNumber'] = '';
                            $data['giftCheques'][0]['activationDate'] = '';
                            $data['giftCheques'][0]['expireDate'] = '';
                            $data['giftCheques'][0]['amount'] = 0;
                            $data['giftCheques'][0]['code'] = '';
                            $data['giftCheques'][0]['status'] = '';
                            $data['giftCheques'][0]['type'] = '';
                            $data['giftCheques'][0]['legalText'] = '';
                            $data['spentGiftChequeTotal'] = '0';
                            $data['itemsPriceTotal'] = $total;
                            $data['subTotal'] = $total;
                            $data['beforeTaxTotal'] = $total;
                            $data['taxTotal'] = 0;
                            $data['shippingTotal'] = 0;
                            $data['total'] = $total;
                            $data['errors'] = array();
                            $data['discounts'][0]['displayName'] = 'discount1';
                            $data['discounts'][0]['discountTotal'] = 0;
                            $data['discounts'][0]['promoCode'] = '';
                            $data['discounts'][0]['ErrorCode'] = '';
                            $data['discounts'][0]['Message'] = '';
                            $data['discounts'][0]['UserFriendly'] = false;
                            $data['discountTotal'] = 0;
                            $data['usedPoints'] = 0;
                            $data['usedPointsAmount'] = 0;
                            $data['rewardPoints'] = 0;
                            $data['paymentFee'] = 0;
                            $data['estimatedSupplyDate'] = '';
                            $data['isGiftWrappingEnabled'] = false;
                            $data['giftWrapping']['isSelected'] = false;
                            $data['giftWrapping']['giftWrappingFee'] = '0';
                            $data['giftWrapping']['maxCharackter'] = '0';
                            $data['giftWrapping']['message'] = '';
                            $data['ErrorCode'] = '';
                            $data['Message'] = '';
                            $data['UserFriendly'] = false;
                            ++$i;
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function getContractInfo()
    {
        $data = array();
        $data['contract'] = '<p>Put your conditions of use information here. You can edit this in the admin site.</p>';
        $data['shippingContract'] = '';
        $data['ErrorCode'] = '';
        $data['Message'] = '';
        $data['UserFriendly'] = true;

        return $data;
    }

    private function getPriceDiscounted($prod_id)
    {
        $sql1 = "SELECT reduction
                    FROM "._DB_PREFIX_."specific_price
                 WHERE id_product = '".pSQL($prod_id)."'";
        $discount = Db::getInstance()->getValue($sql1);

        return $discount;
    }

    private function groupBy($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            $return[$val[$key]][] = $val;
        }

        return $return;
    }

    public function getDeliveryAddress($customerId = null)
    {
        $sql = 'SELECT a.*, s.id_state,s.name AS state_name,cl.id_lang,cl.id_country,cl.name AS country_name
 FROM '._DB_PREFIX_.'address a  LEFT JOIN '._DB_PREFIX_.'country_lang cl ON a.id_country = cl.id_country
 LEFT JOIN  '._DB_PREFIX_."state s ON s.id_state = a.id_state
 WHERE a.id_customer='".pSQL($customerId)."' AND a.deleted = '0'";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $data = array();
        $i = 0;
        if (Db::getInstance()->NumRows() > 0) {
            $id = $results[0]['id_address'];
            $addressName = $results[0]['alias'];
            $name = $results[0]['firstname'];
            $surname = $results[0]['lastname'];
            $email = '';
            $addressLine = $results[0]['address1'].' '.$results[0]['address2'];
            $country = (Tools::getIsset($results[0]['country_name'])
                && $results[0]['country_name'] != '') ? $results[0]['country_name'] : '';
            $countryCode = (Tools::getIsset($results[0]['id_country'])
                && $results[0]['id_country'] != '') ? $results[0]['id_country'] : '';
            $state = (Tools::getIsset($results[0]['state_name'])
                && $results[0]['state_name'] != '') ? $results[0]['state_name'] : '';
            $stateCode = (Tools::getIsset($results[0]['id_state'])
                && $results[0]['id_state'] != '') ? $results[0]['id_state'] : '';
            $city = $results[0]['city'];
            $cityCode = '';
            $district = '';
            $districtCode = '';
            $town = '';
            $townCode = '';
            $corporate = false;
            $companyTitle = $results[0]['company'];
            $taxDepartment = '';
            $taxNo = '';
            $phoneNumber = $results[0]['phone'];
            $identityNo = '';
            $zipCode = '';
            $usCheckoutCity = '';
            $ErrorCode = '';
            $Message = '';
            $UserFriendly = false;
            $data['shippingAddress'] = $this->registerShippingAddress(
                $id,
                $addressName,
                $name,
                $surname,
                $email,
                $addressLine,
                $country,
                $countryCode,
                $state,
                $stateCode,
                $city,
                $cityCode,
                $district,
                $districtCode,
                $town,
                $townCode,
                $corporate,
                $companyTitle,
                $taxDepartment,
                $taxNo,
                $phoneNumber,
                $identityNo,
                $zipCode,
                $usCheckoutCity,
                $ErrorCode,
                $Message,
                $UserFriendly
            );
            $data['billingAddress'] = $this->registerBillingAdress(
                $id,
                $name,
                $addressName,
                $email,
                $surname,
                $addressLine,
                $country,
                $countryCode,
                $state,
                $stateCode,
                $city,
                $cityCode,
                $district,
                $districtCode,
                $town,
                $townCode,
                $corporate,
                $companyTitle,
                $taxDepartment,
                $taxNo,
                $phoneNumber,
                $identityNo,
                $zipCode,
                $usCheckoutCity,
                $ErrorCode,
                $Message,
                $UserFriendly
            );
            $car_sql = 'SELECT s.*, sl.*, z.* FROM '._DB_PREFIX_.'carrier_zone z
            LEFT JOIN '._DB_PREFIX_.'carrier_lang sl ON sl.id_carrier = z.id_carrier
            LEFT JOIN '._DB_PREFIX_."carrier s ON s.id_carrier = sl.id_carrier
            WHERE s.name NOT LIKE '0' GROUP BY s.id_carrier";
            $car_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($car_sql);
            if (Db::getInstance()->NumRows() > 0) {
                $i = 0;
                if ($data['billingAddress']['id'] != '') {
                    $id_zone = Address::getZoneById($data['billingAddress']['id']);
                } else {
                    $ps_country_default = Configuration::get('PS_COUNTRY_DEFAULT');
                    $country = new Country($ps_country_default);
                    $id_zone = $country->id_zone;
                }
                foreach ($car_results as $v1) {
                    $ps_method = (int) Configuration::get('PS_SHIPPING_METHOD');
                    if ($ps_method) {
                        $w = Carrier::SHIPPING_METHOD_WEIGHT;
                        $qr = 'd.id_range_weight = '.pSQL($w);
                    } else {
                        $p = Carrier::SHIPPING_METHOD_PRICE;
                        $qr = 'd.id_range_price = '.pSQL($p);
                    }
                    $sql2 = 'SELECT d.* FROM '._DB_PREFIX_."delivery d
                    WHERE id_zone = '".pSQL($id_zone)."' AND id_carrier = '".pSQL($v1['id_carrier'])."' AND ".$qr;
                    $results2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                    $data['shippingMethod'][$i]['id'] = $v1['id_carrier'];
                    $data['shippingMethod'][$i]['displayName'] = $v1['name'];
                    $data['shippingMethod'][$i]['trackingAddress'] = '';
                    $data['shippingMethod'][$i]['price'] = (Tools::getIsset($results2[1]['price'])
                        && $results2[1]['price'] != '')
                        ? $results2[1]['price'] : '0';
                    $data['shippingMethod'][$i]['priceForYou'] = '';
                    $data['shippingMethod'][$i]['imageUrl'] = '';
                }
            } else {
                $data['shippingMethod'] = array();
            }
            $data['useSameAddressForBilling'] = false;
        } else {
            $data['shippingAddress'] = $this->registerShippingAddress();
            $data['billingAddress'] = $this->registerBillingAdress();
        }

        return $data;
    }

    private function getDeliveryAddressWithShipping($customerId, $shipping_methods)
    {
        $sql = 'SELECT a.*, s.id_state,s.name AS state_name,cl.id_lang,cl.id_country,cl.name AS country_name
                FROM '._DB_PREFIX_.'address a
                LEFT JOIN '._DB_PREFIX_.'country_lang cl ON a.id_country = cl.id_country
                LEFT JOIN  '._DB_PREFIX_."state s ON s.id_state = a.id_state
                WHERE a.id_customer='".pSQL($customerId)."' AND a.deleted = '0'";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        $data = array();
        if (Db::getInstance()->NumRows() > 0) {
            $id = $results[0]['id_address'];
            $addressName = $results[0]['alias'];
            $name = $results[0]['firstname'];
            $surname = $results[0]['lastname'];
            $email = '';
            $addressLine = $results[0]['address1'].' '.$results[0]['address2'];
            $country = (Tools::getIsset($results[0]['country_name']) && $results[0]['country_name'] != '')
                ? $results[0]['country_name'] : '';
            $countryCode = (Tools::getIsset($results[0]['id_country']) && $results[0]['id_country'] != '')
                ? $results[0]['id_country'] : '';
            $state = (Tools::getIsset($results[0]['state_name']) && $results[0]['state_name'] != '')
                ? $results[0]['state_name'] : '';
            $stateCode = (Tools::getIsset($results[0]['id_state']) && $results[0]['id_state'] != '')
                ? $results[0]['id_state'] : '';
            $city = $results[0]['city'];
            $cityCode = '';
            $district = '';
            $districtCode = '';
            $town = '';
            $townCode = '';
            $corporate = false;
            $companyTitle = $results[0]['company'];
            $taxDepartment = '';
            $taxNo = '';
            $phoneNumber = $results[0]['phone'];
            $identityNo = '';
            $zipCode = '';
            $usCheckoutCity = '';
            $ErrorCode = '';
            $Message = '';
            $UserFriendly = false;
            $data['shippingAddress'] = $this->registerShippingAddress(
                $id,
                $addressName,
                $name,
                $surname,
                $email,
                $addressLine,
                $country,
                $countryCode,
                $state,
                $stateCode,
                $city,
                $cityCode,
                $district,
                $districtCode,
                $town,
                $townCode,
                $corporate,
                $companyTitle,
                $taxDepartment,
                $taxNo,
                $phoneNumber,
                $identityNo,
                $zipCode,
                $usCheckoutCity,
                $ErrorCode,
                $Message,
                $UserFriendly
            );
            $data['billingAddress'] = $this->registerBillingAdress(
                $id,
                $name,
                $addressName,
                $email,
                $surname,
                $addressLine,
                $country,
                $countryCode,
                $state,
                $stateCode,
                $city,
                $cityCode,
                $district,
                $districtCode,
                $town,
                $townCode,
                $corporate,
                $companyTitle,
                $taxDepartment,
                $taxNo,
                $phoneNumber,
                $identityNo,
                $zipCode,
                $usCheckoutCity,
                $ErrorCode,
                $Message,
                $UserFriendly
            );
            if (count($shipping_methods) > 0) {
                $kk = 0;
                foreach ($shipping_methods as $shipping_data) {
                    $sql_car = 'SELECT s.*, sl.*, z.* FROM '._DB_PREFIX_.'carrier_zone z
                    LEFT JOIN '._DB_PREFIX_.'carrier_lang sl ON sl.id_carrier = z.id_carrier
                    LEFT JOIN '._DB_PREFIX_."carrier s ON s.id_carrier = sl.id_carrier
                     WHERE s.id_carrier='".pSQL($shipping_data['id'])."' AND s.name NOT LIKE '0' GROUP BY s.id_carrier";
                    $carrier_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql_car);
                    if (Tools::getIsset($carrier_results[0]['id_carrier']) && $carrier_results[0]['id_carrier'] != '') {
                        if ($data['billingAddress']['id'] != '') {
                            $id_zone = Address::getZoneById($data['billingAddress']['id']);
                        } else {
                            $ps_country_default = Configuration::get('PS_COUNTRY_DEFAULT');
                            $country = new Country($ps_country_default);
                            $id_zone = $country->id_zone;
                        }
                        $ps_method = (int) Configuration::get('PS_SHIPPING_METHOD');
                        if ($ps_method) {
                            $w = Carrier::SHIPPING_METHOD_WEIGHT;
                            $qr = 'd.id_range_weight = '.$w;
                        } else {
                            $p = Carrier::SHIPPING_METHOD_PRICE;
                            $qr = 'd.id_range_price = '.$p;
                        }
                        $sql2 = 'SELECT d.* FROM '._DB_PREFIX_."delivery d WHERE id_zone = '".pSQL($id_zone)."'
                        AND id_carrier = '".pSQL($carrier_results[0]['id_carrier'])."' AND ".$qr;
                        $results2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                        $price = (Tools::getIsset($results2[1]['price']) && $results2[1]['price'] != '')
                            ? $results2[1]['price'] : '0';
                    } else {
                        $price = 0;
                    }
                    $data['shippingMethod'][$kk]['id'] = (Tools::getIsset($carrier_results[0]['id_carrier'])
                        && $carrier_results[0]['id_carrier'] != '') ? $carrier_results[0]['id_carrier'] : '';
                    $data['shippingMethod'][$kk]['displayName'] = (Tools::getIsset($carrier_results[0]['name'])
                        && $carrier_results[0]['name'] != '') ? $carrier_results[0]['name'] : '';
                    $data['shippingMethod'][$kk]['trackingAddress'] = '';
                    $data['shippingMethod'][$kk]['price'] = $price;
                    $data['shippingMethod'][$kk]['priceForYou'] = '';
                    $data['shippingMethod'][$kk]['imageUrl'] = '';
                    ++$kk;
                }
            } else {
                $data['shippingMethod'] = array();
            }
            $data['useSameAddressForBilling'] = false;
        } else {
            $data['shippingAddress'] = $this->registerShippingAddress();
            $data['billingAddress'] = $this->registerBillingAdress();
            if (count($shipping_methods) > 0) {
                $kk = 0;
                foreach ($shipping_methods as $shipping_data) {
                    $sql_car = 'SELECT s.*, sl.*, z.* FROM '._DB_PREFIX_.'carrier_zone z
                     LEFT JOIN '._DB_PREFIX_.'carrier_lang sl ON sl.id_carrier = z.id_carrier
                     LEFT JOIN '._DB_PREFIX_."carrier s ON s.id_carrier = sl.id_carrier
                     WHERE s.id_carrier='".pSQL($shipping_data['id'])."' AND s.name NOT LIKE '0' GROUP BY s.id_carrier";
                    $carrier_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql_car);
                    if (Tools::getIsset($carrier_results[0]['id_carrier']) && $carrier_results[0]['id_carrier'] != '') {
                        if ($data['billingAddress']['id'] != '') {
                            $id_zone = Address::getZoneById($data['billingAddress']['id']);
                        } else {
                            $ps_country_default = Configuration::get('PS_COUNTRY_DEFAULT');
                            $country = new Country($ps_country_default);
                            $id_zone = $country->id_zone;
                        }
                        $ps_method = (int) Configuration::get('PS_SHIPPING_METHOD');
                        if ($ps_method) {
                            $w = Carrier::SHIPPING_METHOD_WEIGHT;
                            $qr = 'd.id_range_weight = '.$w;
                        } else {
                            $p = Carrier::SHIPPING_METHOD_PRICE;
                            $qr = 'd.id_range_price = '.$p;
                        }
                        $sql2 = 'SELECT d.* FROM '._DB_PREFIX_."delivery d
                         WHERE id_zone = '".pSQL($id_zone)."' 
                         AND id_carrier = '".pSQL($carrier_results[0]['id_carrier'])."'
                         AND ".$qr;
                        $results2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                        $price = (Tools::getIsset($results2[1]['price']) && $results2[1]['price'] != '')
                            ? $results2[1]['price'] : '0';
                    } else {
                        $price = 0;
                    }
                    $data['shippingMethod'][$kk]['id'] = (Tools::getIsset($carrier_results[0]['id_carrier']) &&
                        $carrier_results[0]['id_carrier'] != '') ? $carrier_results[0]['id_carrier'] : '';
                    $data['shippingMethod'][$kk]['displayName'] = (Tools::getIsset($carrier_results[0]['name'])
                        && $carrier_results[0]['name'] != '') ? $carrier_results[0]['name'] : '';
                    $data['shippingMethod'][$kk]['trackingAddress'] = '';
                    $data['shippingMethod'][$kk]['price'] = $price;
                    $data['shippingMethod'][$kk]['priceForYou'] = '';
                    $data['shippingMethod'][$kk]['imageUrl'] = '';

                    ++$kk;
                }
            } else {
                $data['shippingMethod'] = array();
            }
            $data['useSameAddressForBilling'] = false;
        }

        return $data;
    }

    private function getCartIdCustomerId($customer_id)
    {
        $q = 'SELECT id_cart  FROM '._DB_PREFIX_."cart
        WHERE id_carrier = 0 AND  id_customer = '".(int) $customer_id."'";
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($q);

        return (int) $result['id_cart'];
    }

    public function getUserFirstBasket()
    {
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $currency_sign = $currency->sign;
        $lines = array();
        $customer_id = 0;
        $cart_id = $this->create($customer_id)->id;
        $total = 0;
        $taxTotal = 0;
        $beforeTaxTotal = 0;
        $subTotal = 0;
        $paymentOptions = $this->getPaymentMethod();
        $payment = null;
        $shippingMethods = $this->getShipMethod();

        return $this->registerCart(
            $cart_id,
            $currency_sign,
            $lines,
            $shippingMethods,
            array(),
            $paymentOptions,
            $payment,
            $subTotal,
            $subTotal,
            $beforeTaxTotal,
            $taxTotal,
            0,
            $total
        );
    }

    private function create($customer_id)
    {
        $cart = new Cart();
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $cart->id_currency = $currency->id;
        $cart->id_lang = $this->getLangId();
        $cart->id_customer = $customer_id;
        $cart->add();

        return $cart;
    }

    private function update($customer_id, $quantity, $product_id, $attribute = null)
    {
        $cart = new Cart();
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $cart->id_currency = $currency->id;
        $cart->id_lang = $this->getLangId();
        $cart->id_customer = $customer_id;
        $cart->updateQty($quantity, $product_id, $attribute);

        return $cart;
    }

    public function getUserBasket($cart_id)
    {
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $currency_sign = $currency->sign;
        $cart = new Cart($cart_id);
        $products = $cart->getProducts();
        $lines = array();
        $priceProduct = 0;
        foreach ($products as $row) {
            $product_id = $row['id_product'];
            $quantity = $row['quantity'];
            $placedPrice = $row['total_wt'];
            $placedPriceTotal = $row['total_wt'];
            $extendedPrice = $row['total_wt'];
            $extendedPriceValue = $row['total_wt'];
            $extendedPriceTotal = $row['total_wt'];
            $extendedPriceTotalValue = $row['total_wt'];
            $priceProduct +=  $row['total_wt'];
            $lines[] = $this->registerLines(
                $product_id,
                $quantity,
                $placedPrice,
                $placedPriceTotal,
                $extendedPrice,
                $extendedPriceValue,
                $extendedPriceTotal,
                $extendedPriceTotalValue
            );
        }
        $beforeTaxTotal = $cart->getOrderTotal(false);
        $total = $cart->getOrderTotal(true);
        $subTotal = $total;
        $taxTotal = $total - $beforeTaxTotal;
        $shippingMethods = $this->getShipMethod();
        $delivery = array();
        $delivery['shippingAddress'] = null;
        $delivery['billingAddress'] = null;
        $delivery['shippingMethod'] = array();
        $delivery['useSameAddressForBilling'] = false;
        $paymentOptions = $this->getPaymentMethod();
        $payment = null;

        return $this->registerCart(
            $cart_id,
            $currency_sign,
            $lines,
            $shippingMethods,
            $delivery,
            $paymentOptions,
            $payment,
            $priceProduct,
            $subTotal,
            $beforeTaxTotal,
            $taxTotal,
            0,
            $total
        );
    }

    private function fillPrice($amount, $currency, $amountDefaultCurrency)
    {
        return array(
            'amount ' => $amount,
            'currency' => $currency,
            'amountDefaultCurrency' => $amountDefaultCurrency,
        );
    }

    private function fillVariants()
    {
        $data = array();
        $data['lines']['variants'][0]['groupId'] = '';
        $data['lines']['variants'][0]['features'][0]['displayName'] = '';
        $data['lines']['variants'][0]['features'][0]['value'] = '';
    }

    private function registerBillingAdress(
        $id = '',
        $name = '',
        $addressName = '',
        $email = '',
        $surname = '',
        $addressLine = '',
        $country = '',
        $countryCode = '',
        $state = '',
        $stateCode = '',
        $city = '',
        $cityCode = '',
        $district = '',
        $districtCode = '',
        $town = '',
        $townCode = '',
        $corporate = false,
        $companyTitle = '',
        $taxDepartment = '',
        $taxNo = '',
        $phoneNumber = '',
        $identityNo = '',
        $zipCode = '',
        $usCheckoutCity = '',
        $ErrorCode = '',
        $Message = '',
        $UserFriendly = false
    ) {
        $data = array();
        $data['id'] = $id;
        $data['name'] = $name;
        $data['addressName'] = $addressName;
        $data['email'] = $email;
        $data['surname'] = $surname;
        $data['addressLine'] = $addressLine;
        $data['country'] = $country;
        $data['countryCode'] = $countryCode;
        $data['state'] = $state;
        $data['stateCode'] = $stateCode;
        $data['city'] = $city;
        $data['cityCode'] = $cityCode;
        $data['district'] = $district;
        $data['districtCode'] = $districtCode;
        $data['town'] = $town;
        $data['townCode'] = $townCode;
        $data['corporate'] = $corporate;
        $data['companyTitle'] = $companyTitle;
        $data['taxDepartment'] = $taxDepartment;
        $data['taxNo'] = $taxNo;
        $data['phoneNumber'] = $phoneNumber;
        $data['identityNo'] = $identityNo;
        $data['zipCode'] = $zipCode;
        $data['usCheckoutCity'] = $usCheckoutCity;
        $data['ErrorCode'] = $ErrorCode;
        $data['Message'] = $Message;
        $data['UserFriendly'] = $UserFriendly;

        return $data;
    }

    private function registerShippingAddress(
        $id = '',
        $addressName = '',
        $name = '',
        $surname = '',
        $email = '',
        $addressLine = '',
        $country = '',
        $countryCode = '',
        $state = '',
        $stateCode = '',
        $city = '',
        $cityCode = '',
        $district = '',
        $districtCode = '',
        $town = '',
        $townCode = '',
        $corporate = false,
        $companyTitle = '',
        $taxDepartment = '',
        $taxNo = '',
        $phoneNumber = '',
        $identityNo = '',
        $zipCode = '',
        $usCheckoutCity = '',
        $ErrorCode = '',
        $Message = '',
        $UserFriendly = false
    ) {
        $data = array();
        $data['id'] = $id;
        $data['addressName'] = $addressName;
        $data['name'] = $name;
        $data['surname'] = $surname;
        $data['email'] = $email;
        $data['addressLine'] = $addressLine;
        $data['country'] = $country;
        $data['countryCode'] = $countryCode;
        $data['state'] = $state;
        $data['stateCode'] = $stateCode;
        $data['city'] = $city;
        $data['cityCode'] = $cityCode;
        $data['district'] = $district;
        $data['districtCode'] = $districtCode;
        $data['town'] = $town;
        $data['townCode'] = $townCode;
        $data['corporate'] = $corporate;
        $data['companyTitle'] = $companyTitle;
        $data['taxDepartment'] = $taxDepartment;
        $data['taxNo'] = $taxNo;
        $data['phoneNumber'] = $phoneNumber;
        $data['identityNo'] = $identityNo;
        $data['zipCode'] = $zipCode;
        $data['usCheckoutCity'] = $usCheckoutCity;
        $data['ErrorCode'] = $ErrorCode;
        $data['Message'] = $Message;
        $data['UserFriendly'] = $UserFriendly;

        return $data;
    }

    private function registerLines(
        $product_id,
        $quantity = 0,
        $placedPrice = 0,
        $placedPriceTotal = 0,
        $extendedPrice = 0,
        $extendedPriceValue = 0,
        $extendedPriceTotal = 0,
        $extendedPriceTotalValue = 0,
        $strikeoutPrice = null,
        $status = '',
        $averageDeliveryDays = '',
        $variants = array()
    ) {
        $data = array();
        $data['productId'] = $product_id;
        $data['product'] = $this->getProductDetail($product_id);
        $data['quantity'] = $quantity;
        $data['placedPrice'] = $placedPrice;
        $data['placedPriceTotal'] = $placedPriceTotal;
        $data['extendedPrice'] = $extendedPrice;
        $data['extendedPriceValue'] = $extendedPriceValue;
        $data['extendedPriceTotal'] = $extendedPriceTotal;
        $data['extendedPriceTotalValue'] = $extendedPriceTotalValue;
        $data['strikeoutPrice'] = $strikeoutPrice;
        $data['status'] = $status;
        $data['averageDeliveryDays'] = $averageDeliveryDays;
        $data['variants'] = $variants;

        return $data;
    }

    private function registerCart(
        $id,
        $currency = null,
        $lines = array(),
        $shippingMethod = array(),
        $delivery = array(),
        $paymentOptions = array(),
        $payment = null,
        $itemsPriceTotal = 0,
        $subTotal = 0,
        $beforeTaxTotal = 0,
        $taxTotal = 0,
        $shippingTotal = 0,
        $total = 0,
        $errors = array(),
        $giftCheques = array(),
        $spentGiftChequeTotal = 0,
        $discounts = array(),
        $discountTotal = 0,
        $usedPoints = 0,
        $usedPointsAmount = 0,
        $rewardPoints = 0,
        $paymentFee = 0,
        $estimatedSupplyDate = null,
        $isGiftWrappingEnabled = false,
        $giftWrapping = null,
        $ErrorCode = null,
        $Message = null,
        $UserFriendly = false
    ) {
        $data = array();
        $data['id'] = $id;
        $data['lines'] = $lines;
        $data['shippingMethods'] = $shippingMethod;
        $data['delivery'] = $delivery;
        $data['paymentOptions'] = $paymentOptions;
        $data['payment'] = $payment;
        $data['currency'] = $currency;
        $data['itemsPriceTotal'] = $itemsPriceTotal;
        $data['subTotal'] = $subTotal;
        $data['beforeTaxTotal'] = $beforeTaxTotal;
        $data['taxTotal'] = $taxTotal;
        $data['shippingTotal'] = $shippingTotal;
        $data['total'] = $total;
        $data['errors'] = $errors;
        $data['giftCheques'] = $giftCheques;
        $data['spentGiftChequeTotal'] = $spentGiftChequeTotal;
        $data['discounts'] = $discounts;
        $data['discountTotal'] = $discountTotal;
        $data['usedPoints'] = $usedPoints;
        $data['usedPointsAmount'] = $usedPointsAmount;
        $data['rewardPoints'] = $rewardPoints;
        $data['paymentFee'] = $paymentFee;
        $data['estimatedSupplyDate'] = $estimatedSupplyDate;
        $data['isGiftWrappingEnabled'] = $isGiftWrappingEnabled;
        $data['giftWrapping'] = $giftWrapping;
        $data['ErrorCode'] = $ErrorCode;
        $data['Message'] = $Message;
        $data['UserFriendly'] = $UserFriendly;

        return $data;
    }

    private function registerPayment(
        $methodType = '',
        $type = '',
        $displayName = '',
        $bankCode = '',
        $installment = 0,
        $accountNumber = '',
        $branch = '',
        $iban = '',
        $cashOnDelivery = array(),
        $creditCard = array(),
        $threeDUrl = array()
    ) {
        return array(
            'methodType' => $methodType,
            'type' => $type,
            'displayName' => $displayName,
            'bankCode' => $bankCode,
            'installment' => $installment,
            'accountNumber' => $accountNumber,
            'branch' => $branch,
            'iban' => $iban,
            'cashOnDelivery' => $cashOnDelivery,
            'creditCard' => $creditCard,
            'threeDUrl' => $threeDUrl,
        );
    }

    private function registerCashOnDelivery(
        $type = '',
        $displayName = '',
        $additionalFee = '',
        $description = 'Cash On Delivery',
        $isSmsVerification = false,
        $phoneNumber = '',
        $smsCode = ''
    ) {
        return array(
            'type' => $type,
            'displayName' => $displayName,
            'additionalFee' => $additionalFee,
            'description' => $description,
            'isSmsVerification' => $isSmsVerification,
            'phoneNumber' => $phoneNumber,
            'smsCode' => $smsCode,
        );
    }

    private function registerCreditCard(
        $owner = '',
        $number = '',
        $cvv = '',
        $month = 0,
        $year = 0,
        $type = 0,
        $installment = 0
    ) {
        return array(
            'owner' => $owner,
            'number' => $number,
            'cvv' => $cvv,
            'month' => $month,
            'year' => $year,
            'type' => $type,
            'installment' => $installment,
        );
    }

    private function registerThreeDUrl($pendingUrl = '', $successUrl = '', $failUrl = '', $cancelUrl = '')
    {
        return array(
            'pendingUrl' => $pendingUrl,
            'successUrl' => $successUrl,
            'failUrl' => $failUrl,
            'cancelUrl' => $cancelUrl,
        );
    }
}
