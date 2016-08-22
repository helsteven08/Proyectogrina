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

class PaymentService extends BaseService
{
    public function payBankWire($input_xml, $id, $userid)
    {
        $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
        $basketId = (int)$id;
        $cart = new Cart($basketId);
        $beforeTaxTotal = $cart->getOrderTotal(false);
        $cartTotal = $cart->getOrderTotal(true);
        $taxTotal = $cartTotal - $beforeTaxTotal;
        $taxTotal > 0 ? $taxTotalShipmentPrice = true : $taxTotalShipmentPrice = false;
        $shipmentMethodId = $data_arr['shippingMethod'][0][' id'];
        (int) $shipmentMethodId > 0 ? $id_carrier = $shipmentMethodId :  $id_carrier = null;
        $shipmentPrice = $cart->getPackageShippingCost($id_carrier, $taxTotalShipmentPrice);
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $currency_sign = $currency->sign;
        $query = 'SELECT c.*,p.id_product, p.quantity, p.id_product_attribute FROM '._DB_PREFIX_.'cart c
LEFT JOIN '._DB_PREFIX_.'cart_product p ON (p.id_cart = c.id_cart)
WHERE c.id_cart = '.(int) $basketId.' ORDER BY p.date_add ASC';
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        $dis_sql = 'SELECT cr.*, crl.*, crll.* FROM '._DB_PREFIX_.'cart_cart_rule cr

					LEFT JOIN '._DB_PREFIX_.'cart_rule crl ON crl.id_cart_rule = cr.id_cart_rule
					LEFT JOIN '._DB_PREFIX_."cart_rule_lang crll ON crll.id_cart_rule = cr.id_cart_rule
					WHERE cr.id_cart = '".pSQL($basketId)."'";
        $dis_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dis_sql);
        $discountPrice = '';
        $discountPercent = '';
        $promoCode = '';
        if (count($dis_results) > 0) {
            $discountPrice = $dis_results[0]['reduction_amount'];
            $discountPercent = $dis_results[0]['reduction_percent'];
            $promoCode = $dis_results[0]['code'];
        }
        $data = array();
        $i = 0;
        if (count($results) > 0) {
            $data['id'] = $basketId;
            $total = 0;
            $price = '';
            foreach ($results as $v1) {
                if (empty($v1['id_customer']) && !empty($id)) {
                    $insert_sql = 'UPDATE  '._DB_PREFIX_."cart  SET id_customer = '".pSQL($userid)."'  
                    where id_cart= '".pSQL($id)."''";
                    Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($insert_sql);
                    $v1['id_customer'] = $userid;
                }
                $price = $this->productPrice($v1['id_product']);
                $total = $total + ($price * $v1['quantity']);
                $subTotal = $total + $shipmentPrice;
                if (isset($v1['id_customer']) && !empty($v1['id_customer'])) {
                    $data['lines'][$i]['productId'] = $v1['id_product'];
                    $data['lines'][$i]['product'] = $this->getProductDetail($v1['id_product']);
                    $data['lines'][$i]['quantity'] = $v1['quantity'];
                    $data['lines'][$i]['placedPrice'] = 0;
                    $data['lines'][$i]['placedPriceTotal'] = 0;
                    $data['lines'][$i]['extendedPrice'] = (string) $price;
                    $data['lines'][$i]['extendedPriceValue'] = (float) $price;
                    $data['lines'][$i]['extendedPriceTotal'] = (string) ($price * $v1['quantity']);
                    $data['lines'][$i]['extendedPriceTotalValue'] = (float) $price * $v1['quantity'];
                    $data['lines'][$i]['strikeoutPrice'] = null;
                    $data['lines'][$i]['status'] = '';
                    $data['lines'][$i]['averageDeliveryDays'] = '';
                    $sql2 = 'SELECT pa.*,
                                    ag.id_attribute_group,
                                    ag.is_color_group,
                                    agl.name AS group_name,
                                    al.name AS attribute_name,
                                    a.id_attribute,
                                    pa.unit_price_impact
					FROM '._DB_PREFIX_.'product_attribute pa
					LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac
					ON pac.id_product_attribute = pa.id_product_attribute
					LEFT JOIN '._DB_PREFIX_.'attribute a ON a.id_attribute = pac.id_attribute
					LEFT JOIN '._DB_PREFIX_.'attribute_group ag ON ag.id_attribute_group = a.id_attribute_group
					LEFT JOIN '._DB_PREFIX_.'attribute_lang al ON (a.id_attribute = al.id_attribute AND al.id_lang = 1)
					LEFT JOIN '._DB_PREFIX_."attribute_group_lang agl ON (ag.id_attribute_group = agl.id_attribute_group
					AND agl.id_lang = 1)
					WHERE pa.id_product = '".pSQL($v1['id_product'])."' ORDER BY group_name";
                    $attr_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                    if (isset($attr_results) && !empty($attr_results)) {
                        $iii = 0;
                        $data['lines'][$i]['variants'][$iii]['groupName'] = '';
                        $data['lines'][$i]['variants'][$iii]['groupId'] = '';
                        $j = 0;
                        foreach ($attr_results as $att_data) {
                            $attrName = $att_data['attribute_name'];
                            $data['lines'][$i]['variants'][$iii]['features'][$j]['displayName'] =$attrName;
                            $data['lines'][$i]['variants'][$iii]['features'][$j]['value'] = $att_data['id_attribute'];
                            ++$j;
                        }
                    } else {
                        $data['lines'][$i]['variants'] = null;
                    }
                    $data['shippingMethods'] = $this->getShipMethod($v1['id_address_delivery']);
                    $data['delivery'] = $this->getDeliveryAddressWithShippingForPayment($v1['id_customer']);
                    $data['paymentOptions'] = $this->getPaymentMethod();
                    $data['payment']['methodType'] = $data_arr['methodType'];
                    $data['payment']['type'] = '';
                    $data['payment']['displayName'] = $data_arr['methodType'];
                    $data['payment']['bankCode'] = '';
                    $data['payment']['installment'] = 0;
                    $data['payment']['accountNumber'] = '';
                    $data['payment']['branch'] = '';
                    $data['payment']['iban'] = '';
                    $data['payment']['cashOnDelivery']['type'] = 'CashOnDelivery';
                    $data['payment']['cashOnDelivery']['displayName'] = 'CashOnDelivery';
                    $data['payment']['cashOnDelivery']['additionalFee'] = '0';
                    $data['payment']['cashOnDelivery']['description'] = 'Cash On Delivery';
                    $data['payment']['cashOnDelivery']['isSmsVerification'] = false;
                    $data['payment']['cashOnDelivery']['phoneNumber'] = '';
                    $data['payment']['cashOnDelivery']['smsCode'] = '';
         
                    $data['payment']['threeDUrl']['pendingUrl'] = '';
                    $data['payment']['threeDUrl']['successUrl'] = '';
                    $data['payment']['threeDUrl']['failUrl'] = '';
                    $data['payment']['threeDUrl']['cancelUrl'] = '';
                    $data['currency'] = $currency_sign;
                    $data['itemsPriceTotal'] = $total;
                    $data['subTotal'] = $subTotal;
                    $data['beforeTaxTotal'] = $beforeTaxTotal;
                    $data['taxTotal'] = $taxTotal;
                    $data['shippingTotal'] = $shipmentPrice;
                    $data['total'] = $cartTotal;
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
                    $disPrice = '';
                    if (!empty($discountPrice) && $discountPrice > 0) {
                        $disPrice = $data['total'] - $discountPrice;
                    } else {
                        if (!empty($discountPercent) && $discountPercent > 0) {
                            $p_price = ($discountPercent / 100) * $data['total'];
                            $disPrice = $data['total'] - $p_price;
                        }
                    }
                    if (isset($dis_results[0]['name']) && $dis_results[0]['name'] != '') {
                        $data['discounts'][0]['displayName'] = $dis_results[0]['name'];
                        $data['discounts'][0]['discountTotal'] = (isset($disPrice) && $disPrice != '') ? $disPrice : 0;
                        $data['discounts'][0]['promoCode'] = $promoCode;
                        $data['discounts'][0]['ErrorCode'] = '';
                        $data['discounts'][0]['Message'] = '';
                        $data['discounts'][0]['UserFriendly'] = false;
                    } else {
                        $data['discounts'] = null;
                    }
                    $data['discountTotal'] = (isset($disPrice) && $disPrice != '') ? $disPrice : 0;
                    $data['usedPoints'] = 0;
                    $data['usedPointsAmount'] = 0;
                    $data['rewardPoints'] = 0;
                    $data['paymentFee'] = 0;
                    $data['estimatedSupplyDate'] = '';
                    $data['isGiftWrappingEnabled'] = false;
                    $data['giftWrapping']['isSelected'] = false;
                    $data['giftWrapping']['giftWrappingFee'] = 0;
                    $data['giftWrapping']['maxCharackter'] = 0;
                    $data['giftWrapping']['message'] = '';
                    $data['ErrorCode'] = '';
                    $data['Message'] = '';
                    $data['UserFriendly'] = false;
                    ++$i;
                }
            }
        } else {
          
            $data['id'] = $basketId;
            $data['lines'] = array();
            $data['shippingMethods'] = null;
            $data['delivery']['shippingAddress'] = null;
            $data['delivery']['billingAddress'] = null;
            $data['delivery']['shippingMethod'] = null;
            $data['delivery']['useSameAddressForBilling'] = false;
            $data['paymentOptions']['moneyTransfer'] = array();
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
                        $data['paymentOptions']['paypal'] = array(
                            'clientId' => $paypal['paypal_client_id'],
                            'displayName' => 'PayPal',
                            'isSandbox' => $paypal['sandbox'] > 0 ? true : false,
                        );
                    }
                    if (Tools::strtolower($name) == 'hipay') {
                        $data['paymentOptions']['creditCard'][0] = array(
                            'image' => "",
                            'type' => "creditCard",
                            'threeDStatus' => false,
                            'displayName' => "Default Credit Card'",
                            'installmentNumber' => false,
                            'installments' => null,
                        );
                    }
                    if (Tools::strtolower($name) == 'cashondelivery') {
                        $data['paymentOptions']['cashOnDelivery'][0]['type'] = 'cashOnDelivery';
                        $data['paymentOptions']['cashOnDelivery'][0]['displayName'] = $v1->displayName;
                        $data['paymentOptions']['cashOnDelivery'][0]['additionalFee'] = '0';
                        $data['paymentOptions']['cashOnDelivery'][0]['description'] = $v1->displayName;
                        $data['paymentOptions']['cashOnDelivery'][0]['isSmsVerification'] = false;
                        $data['paymentOptions']['cashOnDelivery'][0]['phoneNumber'] = '';
                        $data['paymentOptions']['cashOnDelivery'][0]['smsCode'] = '';
                    }
                }
            }
            $data['paymentOptions']['threeDUrl'] = null;
            $data['payment'] = null;
            $data['currency'] = $currency_sign;
            $data['itemsPriceTotal'] = 0;
            $data['subTotal'] = 0;
            $data['beforeTaxTotal'] = 0;
            $data['taxTotal'] = 0;
            $data['shippingTotal'] = 0;
            $data['total'] = 0;
            $data['errors'] = array();
            $data['giftCheques'] = array();
            $data['spentGiftChequeTotal'] = 0;
            $data['discounts'] = array();
            $data['discountTotal'] = 0;
            $data['usedPoints'] = 0;
            $data['usedPointsAmount'] = 0;
            $data['rewardPoints'] = 0;
            $data['paymentFee'] = 0;
            $data['estimatedSupplyDate'] = null;
            $data['isGiftWrappingEnabled'] = false;
            $data['giftWrapping'] = null;
            $data['ErrorCode'] = null;
            $data['Message'] = null;
            $data['UserFriendly'] = false;
        }

        return $data;
    }

    private function productPrice($productId)
    {
        $sql = 'SELECT p.*,
                pl.description,
                pl.description_short,
                pl.available_now,
                pl.available_later,
                pl.link_rewrite,
                pl.meta_description,
                pl.meta_keywords,
                pl.meta_title,
                pl.name,
                cl.name AS category_default
        FROM '._DB_PREFIX_.'category_product cp
        LEFT JOIN '._DB_PREFIX_.'product p ON p.id_product = cp.id_product
        LEFT JOIN '._DB_PREFIX_."category_lang cl ON (p.id_category_default = cl.id_category
            AND cl.id_lang = '".$this->getLangId()."')
        LEFT JOIN "._DB_PREFIX_."product_lang pl ON (p.id_product = pl.id_product
            AND pl.id_lang = '".$this->getLangId()."')
        WHERE p.active = '1' AND p.id_product = '".pSQL($productId)."' GROUP BY p.id_product";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $price = 0;
        if (Db::getInstance()->NumRows() > 0) {
            $price = number_format($results[0]['price'], 2);
        }

        return $price;
    }

    private function getProductDetail($proId = null)
    {
        $productId = $proId;
        $sql = 'SELECT p.*,
                pl.description,
                pl.description_short,sa.quantity AS stock_qty,
                pl.available_now, pl.available_later,
                pl.link_rewrite, pl.meta_description,
                pl.meta_keywords, pl.meta_title, pl.name, cl.name AS category_default
        FROM '._DB_PREFIX_.'category_product cp
        LEFT JOIN '._DB_PREFIX_.'product p ON p.id_product = cp.id_product
		LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON sa.`id_product` = p.`id_product`
        LEFT JOIN '._DB_PREFIX_."category_lang cl ON (p.id_category_default = cl.id_category
            AND cl.id_lang = '".$this->getLangId()."')
        LEFT JOIN "._DB_PREFIX_."product_lang pl ON (p.id_product = pl.id_product
            AND pl.id_lang = '".$this->getLangId()."')
        WHERE p.active = '1' AND p.id_product = '".pSQL($productId)."'";
        $sql = $sql.' GROUP BY p.id_product ';
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (Db::getInstance()->NumRows() > 0) {
            $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
            $currency = new Currency($id_currency);
            $currency_sign = $currency->sign;
            $arr = array();
            foreach ($results as $k1 => $v1) {
                $arr[$k1] = $v1;
                $arr[$k1]['currency_sign'] = $currency_sign;
                $sql2 = 'SELECT pa.*,
                                ag.id_attribute_group,
                                ag.is_color_group,
                                agl.name AS group_name,
                                al.name AS attribute_name,
                                a.id_attribute, pa.unit_price_impact
					FROM '._DB_PREFIX_.'product_attribute pa
					LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac
					    ON pac.id_product_attribute = pa.id_product_attribute
					LEFT JOIN '._DB_PREFIX_.'attribute a ON a.id_attribute = pac.id_attribute
					LEFT JOIN '._DB_PREFIX_.'attribute_group ag ON ag.id_attribute_group = a.id_attribute_group
					LEFT JOIN '._DB_PREFIX_.'attribute_lang al ON (a.id_attribute = al.id_attribute AND al.id_lang = 1)
					LEFT JOIN '._DB_PREFIX_."attribute_group_lang agl ON (ag.id_attribute_group = agl.id_attribute_group
					 AND agl.id_lang = 1)
					WHERE pa.id_product = '".pSQL($v1['id_product'])."' ORDER BY group_name";
                $attr_res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                $attr_results = $this->groupBy($attr_res, 'group_name');
                $arr[$k1]['Attributes'] = $attr_results;
                $sql3 = "SELECT * FROM "._DB_PREFIX_."image i
					LEFT JOIN "._DB_PREFIX_."image_lang il ON (i.id_image = il.id_image)
					WHERE i.id_product = '".pSQL($v1['id_product'])."' AND il.id_lang = ".$this->getLangId()."
					ORDER BY i.position ASC";
                $img_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql3);
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
                    if (isset($row['price']) && !empty($row['price'])) {
                        $data['listPrice']['amount'] = number_format($this->getDiscountedPrice($row['id_product']), 2);
                    } else {
                        $data['listPrice']['amount'] = '0.00';
                    }
                    $data['listPrice']['currency'] = $row['currency_sign'];
                    $data['listPrice']['amountDefaultCurrency'] = '';
                    $data['IsCampaign'] = true;
                    $data['strikeoutPrice'] = null;
                } else {
                    if (isset($row['price']) && !empty($row['price'])) {
                        $amount = (isset($row['price']) && $row['price']!= '') ? number_format($row['price'], 2) : 0;
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
                if (isset($row['stock_qty']) && $row['stock_qty'] > 0) {
                    $data['inStock'] = true;
                }
                $data['shipmentInformation'] = '';
                $data['isShipmentFree'] = false;
                $data['features'] = null;
                if (isset($row['Attributes']) && !empty($row['Attributes'])) {
                    $ii = 0;
                    $data['variants'][$ii]['groupName'] = '';
                    $data['variants'][$ii]['groupId'] = '';
                    $j = 0;
                    foreach ($row['Attributes'] as $k2 => $att_data) {
                        $data['variants'][$ii]['features'][$j]['displayName'] = $att_data['attribute_name'];
                        $data['variants'][$ii]['features'][$j]['value'] = $att_data['id_attribute'];
                        ++$j;
                    }
                } else {
                    $data['variants'] = null;
                }
                if (isset($row['Attributes']) && count($row['Attributes']) > 0) {
                    $ii = 0;
                    foreach ($row['Attributes'] as $k2 => $v2) {
                        $idAttrGroupId = $v2[0]['id_attribute_group'];
                        $groupId = (isset($idAttrGroupId) && $idAttrGroupId!= '') ? $idAttrGroupId: '';
                        $data['variants'][$ii]['groupName'] = $k2;
                        $data['variants'][$ii]['groupId'] = $groupId;
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
                    $data['picture'] = null;
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

    private function getShipMethod($id_address = null)
    {
        $sql = 'SELECT s.*, sl.*, z.*
                FROM '._DB_PREFIX_.'carrier_zone z
                LEFT JOIN '._DB_PREFIX_.'carrier_lang sl ON sl.id_carrier = z.id_carrier
                 LEFT JOIN '._DB_PREFIX_."carrier s ON s.id_carrier = sl.id_carrier
                 WHERE s.name NOT LIKE '0' GROUP BY s.id_carrier";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
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
                WHERE id_zone = '".$id_zone."' AND id_carrier = '".$v1['id_carrier']."' AND ".$qr;
                $results2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
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

    private function getDeliveryAddressWithShippingForPayment($customerId = null)
    {
        $sql = 'SELECT a.*, s.id_state,s.name AS state_name,cl.id_lang,cl.id_country,cl.name AS country_name
                FROM '._DB_PREFIX_.'address a  LEFT JOIN '._DB_PREFIX_.'country_lang cl ON a.id_country = cl.id_country
                LEFT JOIN  '._DB_PREFIX_."state s ON s.id_state = a.id_state WHERE a.id_customer='".pSQL($customerId)."'
                AND a.deleted = '0'";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $data = array();
        $i = 0;
        if (Db::getInstance()->NumRows() > 0) {
            $data['shippingAddress']['id'] = $results[0]['id_address'];
            $data['shippingAddress']['addressName'] = $results[0]['alias'];
            $data['shippingAddress']['name'] = $results[0]['firstname'];
            $data['shippingAddress']['surname'] = $results[0]['lastname'];
            $data['shippingAddress']['email'] = '';
            $countryName = $results[0]['country_name'];
            $idCountry = $results[0]['id_country'];
            $resultStateName = $results[0]['state_name'];
            $idState = $results[0]['id_state'];
            $country = (isset($countryName) && $countryName != '') ? $countryName : '';
            $countryCode  =(isset($idCountry) && $idCountry != '') ? $idCountry : '';
            $stateName = (isset($resultStateName) && $resultStateName != '') ? $resultStateName : '';
            $stateCode = (isset($idState) && $idState != '') ? $idState : '';

            $data['shippingAddress']['addressLine'] = $results[0]['address1'].' '.$results[0]['address2'];
            $data['shippingAddress']['country'] = $country;
            $data['shippingAddress']['countryCode'] = $countryCode;
            $data['shippingAddress']['state'] = $stateName;
            $data['shippingAddress']['stateCode'] = $stateCode;
            $data['shippingAddress']['city'] = $results[0]['city'];
            $data['shippingAddress']['cityCode'] = '';
            $data['shippingAddress']['district'] = '';
            $data['shippingAddress']['districtCode'] = '';
            $data['shippingAddress']['town'] = '';
            $data['shippingAddress']['townCode'] = '';
            $data['shippingAddress']['corporate'] = false;
            $data['shippingAddress']['companyTitle'] = $results[0]['company'];
            $data['shippingAddress']['taxDepartment'] = '';
            $data['shippingAddress']['taxNo'] = '';
            $data['shippingAddress']['phoneNumber'] = $results[0]['phone'];
            $data['shippingAddress']['identityNo'] = $results[0]['phone'];
            $data['shippingAddress']['zipCode'] = '';
            $data['shippingAddress']['usCheckoutCity'] = '';
            $data['shippingAddress']['ErrorCode'] = '';
            $data['shippingAddress']['Message'] = '';
            $data['shippingAddress']['UserFriendly'] = false;
            $data['billingAddress']['id'] = $results[0]['id_address'];
            $data['billingAddress']['name'] = $results[0]['firstname'];
            $data['billingAddress']['surname'] = $results[0]['lastname'];
            $data['billingAddress']['addressLine'] = $results[0]['address1'].' '.$results[0]['address2'];
            $data['billingAddress']['addressName'] = $results[0]['alias'];
            $data['billingAddress']['email'] = '';
            $data['billingAddress']['country'] = $results[0]['country_name'];
            $data['billingAddress']['countryCode'] = $results[0]['id_country'];
            $data['billingAddress']['state'] = $results[0]['state_name'];
            $data['billingAddress']['stateCode'] = $results[0]['id_state'];
            $data['billingAddress']['city'] = $results[0]['city'];
            $data['billingAddress']['cityCode'] = '';
            $data['billingAddress']['district'] = '';
            $data['billingAddress']['districtCode'] = '';
            $data['billingAddress']['town'] = '';
            $data['billingAddress']['townCode'] = '';
            $data['billingAddress']['corporate'] = false;
            $data['billingAddress']['companyTitle'] = $results[0]['company'];
            $data['billingAddress']['taxDepartment'] = '';
            $data['billingAddress']['taxNo'] = '';
            $data['billingAddress']['phoneNumber'] = $results[0]['phone'];
            $data['billingAddress']['identityNo'] = $results[0]['phone'];
            $data['billingAddress']['zipCode'] = '';
            $data['billingAddress']['usCheckoutCity'] = '';
            $data['billingAddress']['ErrorCode'] = '';
            $data['billingAddress']['Message'] = '';
            $data['billingAddress']['UserFriendly'] = false;
            $car_sql = 'SELECT s.*, sl.*, z.*
                          FROM '._DB_PREFIX_.'carrier_zone z
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
                        $qr = 'd.id_range_weight = '.$w;
                    } else {
                        $p = Carrier::SHIPPING_METHOD_PRICE;
                        $qr = 'd.id_range_price = '.$p;
                    }
                    $sql2 = 'SELECT d.* FROM '._DB_PREFIX_."delivery d
                    WHERE id_zone = '".$id_zone."' AND id_carrier = '".$v1['id_carrier']."' AND ".$qr;
                    $results2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                    $resultPrice = $results2[1]['price'];
                    $price = (isset($resultPrice) && $resultPrice != '') ? $resultPrice : '0';
                    $data['shippingMethod'][$i]['id'] = $v1['id_carrier'];
                    $data['shippingMethod'][$i]['displayName'] = $v1['name'];
                    $data['shippingMethod'][$i]['trackingAddress'] = '';
                    $data['shippingMethod'][$i]['price'] = $price;
                    $data['shippingMethod'][$i]['priceForYou'] = '';
                    $data['shippingMethod'][$i]['imageUrl'] = '';
                }
            } else {
                $data['shippingMethod'] = array();
            }
            $data['useSameAddressForBilling'] = false;
        } else {
            $data['shippingAddress']['id'] = '';
            $data['shippingAddress']['addressName'] = '';
            $data['shippingAddress']['name'] = '';
            $data['shippingAddress']['surname'] = '';
            $data['shippingAddress']['email'] = '';
            $data['shippingAddress']['addressLine'] = '';
            $data['shippingAddress']['country'] = '';
            $data['shippingAddress']['countryCode'] = '';
            $data['shippingAddress']['state'] = '';
            $data['shippingAddress']['stateCode'] = '';
            $data['shippingAddress']['city'] = '';
            $data['shippingAddress']['cityCode'] = '';
            $data['shippingAddress']['district'] = '';
            $data['shippingAddress']['districtCode'] = '';
            $data['shippingAddress']['town'] = '';
            $data['shippingAddress']['townCode'] = '';
            $data['shippingAddress']['corporate'] = false;
            $data['shippingAddress']['companyTitle'] = '';
            $data['shippingAddress']['taxDepartment'] = '';
            $data['shippingAddress']['taxNo'] = '';
            $data['shippingAddress']['phoneNumber'] = '';
            $data['shippingAddress']['identityNo'] = '';
            $data['shippingAddress']['zipCode'] = '';
            $data['shippingAddress']['usCheckoutCity'] = '';
            $data['shippingAddress']['ErrorCode'] = '';
            $data['shippingAddress']['Message'] = '';
            $data['shippingAddress']['UserFriendly'] = false;
            $data['billingAddress']['id'] = '';
            $data['billingAddress']['name'] = '';
            $data['billingAddress']['addressName'] = '';
            $data['billingAddress']['email'] = '';
            $data['billingAddress']['surname'] = '';
            $data['billingAddress']['addressLine'] = '';
            $data['billingAddress']['country'] = '';
            $data['billingAddress']['countryCode'] = '';
            $data['billingAddress']['state'] = '';
            $data['billingAddress']['stateCode'] = '';
            $data['billingAddress']['city'] = '';
            $data['billingAddress']['cityCode'] = '';
            $data['billingAddress']['district'] = '';
            $data['billingAddress']['districtCode'] = '';
            $data['billingAddress']['town'] = '';
            $data['billingAddress']['townCode'] = '';
            $data['billingAddress']['corporate'] = false;
            $data['billingAddress']['companyTitle'] = '';
            $data['billingAddress']['taxDepartment'] = '';
            $data['billingAddress']['taxNo'] = '';
            $data['billingAddress']['phoneNumber'] = '';
            $data['billingAddress']['identityNo'] = '';
            $data['billingAddress']['zipCode'] = '';
            $data['billingAddress']['usCheckoutCity'] = '';
            $data['billingAddress']['ErrorCode'] = '';
            $data['billingAddress']['Message'] = '';
            $data['billingAddress']['UserFriendly'] = false;
            $car_sql = 'SELECT s.*, sl.*, z.*
                        FROM '._DB_PREFIX_.'carrier_zone z
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
                        $qr = 'd.id_range_weight = '.$w;
                    } else {
                        $p = Carrier::SHIPPING_METHOD_PRICE;
                        $qr = 'd.id_range_price = '.$p;
                    }
                    $sql2 = 'SELECT d.* FROM '._DB_PREFIX_."delivery d
                    WHERE id_zone = '".$id_zone."' AND id_carrier = '".$v1['id_carrier']."' AND ".$qr;
                    $results2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                    $shipPrice = $results2[1]['price'];
                    $sPrice = (isset($shipPrice) && $shipPrice!= '') ? $shipPrice : '0';
                    $data['shippingMethod'][$i]['id'] = $v1['id_carrier'];
                    $data['shippingMethod'][$i]['displayName'] = $v1['name'];
                    $data['shippingMethod'][$i]['trackingAddress'] = '';
                    $data['shippingMethod'][$i]['price'] = $sPrice;
                    $data['shippingMethod'][$i]['priceForYou'] = '';
                    $data['shippingMethod'][$i]['imageUrl'] = '';
                }
            } else {
                $data['shippingMethod'] = array();
            }
            $data['useSameAddressForBilling'] = false;
        }

        return $data;
    }

    private function getPaymentMethod()
    {
        $payment_modules = array();
        $shop_id = Context::getContext()->shop->id;
        $modules = Module::getModulesOnDisk(true);
        foreach ($modules as $module) {
            if ($module->tab == 'payments_gateways') {
                if ($module->id) {
                    if (!get_class($module) == 'SimpleXMLElement') {
                        $module->country = array();
                    }
                    $queryCountry = '
						SELECT id_country
						FROM '._DB_PREFIX_.'module_country
						WHERE id_module = '.(int) $module->id.' AND `id_shop`='.(int) $shop_id;
                    $countries = DB::getInstance()->executeS($queryCountry);
                    foreach ($countries as $country) {
                        $module->country[] = $country['id_country'];
                    }
                    if (!get_class($module) == 'SimpleXMLElement') {
                        $module->currency = array();
                    }
                    $queryCurrency = 'SELECT id_currency
						FROM '._DB_PREFIX_.'module_currency
						WHERE id_module = '.(int) $module->id.' AND `id_shop`='.(int) $shop_id;
                    $currencies = DB::getInstance()->executeS($queryCurrency);
                    foreach ($currencies as $currency) {
                        $module->currency[] = $currency['id_currency'];
                    }
                    if (!get_class($module) == 'SimpleXMLElement') {
                        $module->group = array();
                    }
                    $queryGroup = 'SELECT id_group
						FROM '._DB_PREFIX_.'module_group
						WHERE id_module = '.(int) $module->id.' AND `id_shop`='.(int) $shop_id;
                    $groups = DB::getInstance()->executeS($queryGroup);
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

        $paypal = $this->getPaypalParameters();
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
                if (Tools::strtolower($name) == 'cashondelivery') {
                    $data['cashOnDelivery'][0]['type'] = 'cashOnDelivery';
                    $data['cashOnDelivery'][0]['displayName'] = $v1->displayName;
                    $data['cashOnDelivery'][0]['additionalFee'] = '0';
                    $data['cashOnDelivery'][0]['description'] = 'Cash On Delivery';
                    $data['cashOnDelivery'][0]['isSmsVerification'] = false;
                    $data['cashOnDelivery'][0]['phoneNumber'] = '';
                    $data['cashOnDelivery'][0]['smsCode'] = '';
                }
            }
        }

        if (count($data) > 0) {
            return $data;
        } else {
            $data['cashOnDelivery'][0]['type'] = 'cashOnDelivery';
            $data['cashOnDelivery'][0]['displayName'] = $v1->displayName;
            $data['cashOnDelivery'][0]['additionalFee'] = '0';
            $data['cashOnDelivery'][0]['description'] = 'Cash On Delivery';
            $data['cashOnDelivery'][0]['isSmsVerification'] = false;
            $data['cashOnDelivery'][0]['phoneNumber'] = '';
            $data['cashOnDelivery'][0]['smsCode'] = '';

            return $data;
        }
    }
    public function purchaseWithCreditCard($input_xml, $basketId, $userid)
    {
        $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
        $id_cart = (int)$basketId;
        $cart = new Cart($basketId);
        $beforeTaxTotal = $cart->getOrderTotal(false);
        $cartTotal = $cart->getOrderTotal(true);
        $taxTotal = $cartTotal - $beforeTaxTotal;
        $products = $cart->getProducts();
        $priceProduct = 0;
        foreach ($products as $row) {
            $priceProduct +=  $row['total_wt'];
        }
        $shipmentId = $data_arr['shippingMethod'][0][' id'];
        (int) $shipmentId > 0 ?  $id_carrier = $shipmentId :  $id_carrier = null;
        $shipmentPrice = $cart->getPackageShippingCost($id_carrier);
        $id_order_state = Configuration::get('PS_OS_PREPARATION');
        $payment_method = $data_arr['displayName'];
        $this->context = Context::getContext();
        $id_lang = $this->context->language->id;
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $reference = Order::generateReference();
        $this->context->customer = new Customer($userid);
        $query = 'SELECT c.*,p.* FROM '._DB_PREFIX_.'cart c
LEFT JOIN '._DB_PREFIX_.'cart_product p ON (p.id_cart = c.id_cart)
    WHERE c.id_cart = '.pSQL($id_cart).' ORDER BY p.date_add ASC';
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        $dis_sql = 'SELECT cr.*, crl.*, crll.* FROM '._DB_PREFIX_.'cart_cart_rule cr
         LEFT JOIN '._DB_PREFIX_.'cart_rule crl ON crl.id_cart_rule = cr.id_cart_rule
         LEFT JOIN '._DB_PREFIX_."cart_rule_lang crll ON crll.id_cart_rule = cr.id_cart_rule
         WHERE cr.id_cart = '".pSQL($basketId)."'";
        $dis_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dis_sql);
        $discountPrice = '';
        $discountPercent = '';
        if (count($dis_results) > 0) {
            $discountPrice = $dis_results[0]['reduction_amount'];
            $discountPercent = $dis_results[0]['reduction_percent'];
        }
        $total = 0;
        foreach ($results as $v1) {
            $price = $this->productPrice($v1['id_product']);
            $total = $total + ($price * $v1['quantity']);
        }
        if (!empty($discountPrice) && $discountPrice > 0) {
            $disPrice = $total - $discountPrice;
        } else {
            if (!empty($discountPercent) && $discountPercent > 0) {
                $p_price = ($discountPercent / 100) * $total;
                $disPrice = $total - $p_price;
            }
        }
        $ad = $this->getDeliveryAddress($userid);
        $idD_currency = isset($this->context->currency->id) ? $this->context->currency->id : $id_currency;
        $id_shop_group = 1;
        $total_discounts = (isset($disPrice) && $disPrice != '') ? $disPrice : 0;
        $id_shop = (int) $this->context->shop->id;
        $conversion_rate = '';
        $secure_key = $this->context->customer->secure_key;
        $id_address_invoice = isset($ad['shippingAddress']['id']) ? $ad['shippingAddress']['id'] : '';
        $order_qry = 'INSERT INTO '._DB_PREFIX_."orders SET id_carrier = '0', id_customer = '".pSQL($userid)."
        ',id_lang = '".(int) $id_lang."', id_cart = '".(int) $id_cart."', current_state = '".(int) $id_order_state."',
             reference = '". pSQL($reference)."', id_shop = '".pSQL($id_shop)."',
              id_currency = '".pSQL($idD_currency)."',
            id_shop_group = '".pSQL($id_shop_group)."', total_paid = '".pSQL($cartTotal)."',
            total_paid_tax_incl = '".$cartTotal."',
            total_paid_tax_excl = '".pSQL($beforeTaxTotal)."',   total_paid_real = '".pSQL($cartTotal)."',
            total_products = '".pSQL($priceProduct)."',  total_products_wt = '".pSQL($priceProduct)."',
            total_shipping_tax_incl = '".pSQL($shipmentPrice)."', total_shipping_tax_excl = '".pSQL($shipmentPrice)."',
            total_shipping = '".pSQL($shipmentPrice)."', total_discounts = '".pSQL($total_discounts)."',
            secure_key = '".pSQL($secure_key)."', payment = '".pSQL($payment_method)."',
            module = 'cashondelivery', recyclable = '0', gift = '0', gift_message = '',
            mobile_theme = '0', conversion_rate = '".pSQL($conversion_rate)."' ,
            round_mode = '".Configuration::get('PS_PRICE_ROUND_MODE')."',
            round_type = '".Configuration::get('PS_ROUND_TYPE')."',
            invoice_date = '0000-00-00 00:00:00', delivery_date = '0000-00-00 00:00:00',
            id_address_invoice = '".pSQL($id_address_invoice)."',
            id_address_delivery = '".pSQL($id_address_invoice)."',
            date_add='".date('Y-m-d H:i:s')."',date_upd='".date('Y-m-d H:i:s')."'";
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($order_qry);
        $orderId = Db::getInstance()->Insert_ID();
        $order_state = 'INSERT INTO '._DB_PREFIX_."order_history SET
                id_order = '".$orderId."', id_order_state = '14', date_add = '".date('Y-m-d H:i:s')."'";
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($order_state);
        foreach ($results as $v1) {
            $pro = $this->getProductDetail($v1['id_product']);
            $o_detail = 'INSERT INTO '._DB_PREFIX_."order_detail SET id_order = '".pSQL($orderId)."',
            id_order_invoice = '', id_warehouse = '".pSQL($orderId)."', 
            id_shop = '".pSQL($id_shop)."',
            product_id = '".pSQL($v1['id_product'])."',
            product_attribute_id = '".pSQL($v1['id_product_attribute'])."',
            product_name = '".pSQL($pro['productName'])."',
            product_quantity = '".pSQL($v1['quantity'])."',
            product_quantity_in_stock = '".pSQL($v1['quantity'])."', 
            product_price = '".pSQL($pro['listPrice']['amount'])."',
            unit_price_tax_excl = '".pSQL($pro['listPrice']['amount'])."',
            unit_price_tax_incl = '".pSQL($pro['listPrice']['amount'])."',
            original_product_price = '".pSQL($pro['listPrice']['amount'])."'";
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($o_detail);
        }
        $query_cart = 'SELECT c.*,p.* FROM '._DB_PREFIX_.'cart c
LEFT JOIN '._DB_PREFIX_.'cart_product p ON (p.id_cart = c.id_cart)
    WHERE c.id_customer = '.pSQL($userid).' ORDER BY p.date_add ASC';
        $cart_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
        if (count($cart_results) > 0) {
            foreach ($cart_results as $cprod) {
                $del_q = 'DELETE FROM '._DB_PREFIX_."cart_product WHERE id_cart='".$cprod['id_cart']."'";
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($del_q);
            }
        } else {
            $query_cart = 'SELECT c.*,p.* FROM '._DB_PREFIX_.'cart c
                LEFT JOIN '._DB_PREFIX_."cart_product p ON (p.id_cart = c.id_cart)
                WHERE c.id_cart = '".pSQL($id_cart)."' ORDER BY p.date_add ASC";
            $acart_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
            foreach ($acart_results as $cprod) {
                $del_q = 'DELETE FROM '._DB_PREFIX_."cart_product WHERE id_cart='".$cprod['id_cart']."'";
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($del_q);
            }
        }
        $query_cart = 'DELETE FROM '._DB_PREFIX_."cart WHERE id_cart='".pSQL($id_cart)."'";
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
        if ($userid != '') {
            $query_cart = 'DELETE FROM '._DB_PREFIX_."cart WHERE id_customer='".pSQL($userid)."'";
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
        }
        $ov1 = (array) new Order($orderId);
        $dataArr = array();
        $ii = 0;
        if (!empty($ov1)) {
            $status_query = 'SELECT * FROM '._DB_PREFIX_.'order_history AS oh
            LEFT JOIN '._DB_PREFIX_."order_state_lang AS osl ON osl.id_order_state=oh.id_order_state
            WHERE id_order = '".pSQL($orderId)."'";
            $status_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($status_query);
            $dataArr['id'] = $orderId;
            $dataArr['trackingNumber'] = $ov1['reference'];
            $dataArr['orderDate'] = $ov1['date_add'];
            $resultName= $status_results[0]['name'];
            $statusName = (isset($resultName) && $resultName != '') ? $resultName : '';
            $dataArr['shippingStatus'] = $statusName;
            $dataArr['paymentStatus'] = $statusName;
            $dataArr['ipAddress'] = '';
            $query = 'SELECT * FROM '._DB_PREFIX_."order_detail WHERE id_order = '".pSQL($orderId)."'";
            $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
            $currency = new Currency($id_currency);
            $currency_sign = $currency->sign;
            $i = 0;
            if (Db::getInstance()->NumRows() > 0) {
                $data['id'] = $ov1['id_cart'];
                $total = '';
                foreach ($results as $v1) {
                    $price = $this->productPrice($v1['product_id']);
                    $total = $total + ($price * $v1['product_quantity']);
                    $subTotal = $total + $shipmentPrice;
                    if (isset($ov1['id_customer']) && !empty($ov1['id_customer'])) {
                        $dataArr['lines'][$i]['productId'] = $v1['product_id'];
                        $dataArr['lines'][$i]['product'] = $this->getProductDetail($v1['product_id']);
                        $dataArr['lines'][$i]['quantity'] = $v1['product_quantity'];
                        $dataArr['lines'][$i]['placedPrice'] = 0;
                        $dataArr['lines'][$i]['placedPriceTotal'] = 0;
                        $dataArr['lines'][$i]['extendedPrice'] = (string) $price;
                        $dataArr['lines'][$i]['extendedPriceValue'] = (float) $price;
                        $dataArr['lines'][$i]['extendedPriceTotal'] = (string) ($price * $v1['product_quantity']);
                        $dataArr['lines'][$i]['extendedPriceTotalValue'] = (float) $price * $v1['product_quantity'];
                        $dataArr['lines'][$i]['strikeoutPrice'] = null;
                        $dataArr['lines'][$i]['status'] = '';
                        $dataArr['lines'][$i]['averageDeliveryDays'] = '';
                        $dataArr['lines'][$i]['variants'][0]['groupName'] = '';
                        $dataArr['lines'][$i]['variants'][0]['groupId'] = '';
                        $dataArr['lines'][$i]['variants'][0]['features'][0]['displayName'] = '';
                        $dataArr['lines'][$i]['variants'][0]['features'][0]['value'] = '';
                        $dataArr['delivery'] = $this->getDeliveryAddress($ov1['id_customer']);
                        ++$i;
                    }
                }
                $dataArr['payment']['methodType'] = 'CashOnDelivery';
                $dataArr['payment']['type'] = 'CashOnDelivery';
                $dataArr['payment']['displayName'] = 'CashOnDelivery';
                $dataArr['payment']['bankCode'] = '';
                $dataArr['payment']['installment'] = 0;
                $dataArr['payment']['accountNumber'] = '';
                $dataArr['payment']['branch'] = '';
                $dataArr['payment']['iban'] = '';
                $dataArr['payment']['cashOnDelivery']['type'] = 'CashOnDelivery';
                $dataArr['payment']['cashOnDelivery']['displayName'] = 'CashOnDelivery';
                $dataArr['payment']['cashOnDelivery']['additionalFee'] = 0;
                $dataArr['payment']['cashOnDelivery']['description'] = 'CashOnDelivery';
                $dataArr['payment']['cashOnDelivery']['isSmsVerification'] = false;
                $dataArr['payment']['cashOnDelivery']['phoneNumber'] = '';
                $dataArr['payment']['cashOnDelivery']['phoneNumber'] = '';
                $dataArr['payment']['cashOnDelivery']['smsCode'] = '';
      
                $dataArr['payment']['threeDUrl'] = null;
                $dataArr['currency'] = $currency_sign;
                $dataArr['itemsPriceTotal'] = (string) $total;
                $dataArr['discountTotal'] = '0';
                $dataArr['subTotal'] = $subTotal;
                $dataArr['taxTotal'] = $taxTotal;
                $dataArr['shippingTotal'] = $shipmentPrice;
                $dataArr['total'] = $cartTotal;
                $dataArr['taxTotalValue'] = $taxTotal;
                $dataArr['shippingTotalValue'] = '0.00';
                $dataArr['totalValue'] = '0.00';
                $dataArr['usedPoints'] = 0;
                $dataArr['usedPointsAmount'] = 0;
                $dataArr['rewardPoints'] = 0;
                $dataArr['ErrorCode'] = '';
                $dataArr['Message'] = '';
                $dataArr['UserFriendly'] = false;
            } else {
                $dataArr['id'] = $orderId;
                $dataArr['lines'] = array();
                $dataArr['shippingMethods'] = null;
                $dataArr['delivery']['shippingAddress'] = null;
                $dataArr['delivery']['billingAddress'] = null;
                $dataArr['delivery']['shippingMethod'] = null;
                $dataArr['delivery']['useSameAddressForBilling'] = false;
                $dataArr['paymentOptions']['moneyTransfer'] = array();
                $dataArr['paymentOptions']['cashOnDelivery'][0]['type'] = '';
                $dataArr['paymentOptions']['cashOnDelivery'][0]['displayName'] = '';
                $dataArr['paymentOptions']['cashOnDelivery'][0]['additionalFee'] = '0';
                $dataArr['paymentOptions']['cashOnDelivery'][0]['description'] = 'Cash On Delivery';
                $dataArr['paymentOptions']['cashOnDelivery'][0]['isSmsVerification'] = false;
                $dataArr['paymentOptions']['cashOnDelivery'][0]['phoneNumber'] = '';
                $dataArr['paymentOptions']['cashOnDelivery'][0]['smsCode'] = '';
                $dataArr['payment'] = null;
                $dataArr['currency'] = 'TL';
                $dataArr['itemsPriceTotal'] = 0;
                $dataArr['subTotal'] = 0;
                $dataArr['beforeTaxTotal'] = 0;
                $dataArr['taxTotal'] = 0;
                $dataArr['shippingTotal'] = 0;
                $dataArr['total'] = 0;
                $dataArr['errors'] = array();
                $dataArr['giftCheques'] = array();
                $dataArr['spentGiftChequeTotal'] = 0;
                $dataArr['discounts'] = array();
                $dataArr['discountTotal'] = 0;
                $dataArr['usedPoints'] = 0;
                $dataArr['usedPointsAmount'] = 0;
                $dataArr['rewardPoints'] = 0;
                $dataArr['paymentFee'] = 0;
                $dataArr['estimatedSupplyDate'] = null;
                $dataArr['isGiftWrappingEnabled'] = false;
                $dataArr['giftWrapping'] = null;
                $dataArr['ErrorCode'] = null;
                $dataArr['Message'] = null;
                $dataArr['UserFriendly'] = false;
            }
            ++$ii;
        } else {
            $dataArr['id'] = '';
            $dataArr['lines'] = array();
            $dataArr['shippingMethods'] = array();
            $dataArr['delivery']['shippingAddress'] = null;
            $dataArr['delivery']['billingAddress'] = null;
            $dataArr['delivery']['shippingMethod'] = null;
            $dataArr['delivery']['useSameAddressForBilling'] = false;
            $dataArr['paymentOptions']['moneyTransfer'] = array();
            $dataArr['paymentOptions']['cashOnDelivery'][0]['type'] = '';
            $dataArr['paymentOptions']['cashOnDelivery'][0]['displayName'] = '';
            $dataArr['paymentOptions']['cashOnDelivery'][0]['additionalFee'] = '0';
            $dataArr['paymentOptions']['cashOnDelivery'][0]['description'] = 'Cash On Delivery';
            $dataArr['paymentOptions']['cashOnDelivery'][0]['isSmsVerification'] = false;
            $dataArr['paymentOptions']['cashOnDelivery'][0]['phoneNumber'] = '';
            $dataArr['paymentOptions']['cashOnDelivery'][0]['smsCode'] = '';
            $dataArr['payment'] = null;
            $dataArr['currency'] = 'TL';
            $dataArr['itemsPriceTotal'] = 0;
            $dataArr['subTotal'] = 0;
            $dataArr['beforeTaxTotal'] = 0;
            $dataArr['taxTotal'] = 0;
            $dataArr['shippingTotal'] = 0;
            $dataArr['total'] = 0;
            $dataArr['errors'] = array();
            $dataArr['giftCheques'] = array();
            $dataArr['spentGiftChequeTotal'] = 0;
            $dataArr['discounts'] = array();
            $dataArr['discountTotal'] = 0;
            $dataArr['usedPoints'] = 0;
            $dataArr['usedPointsAmount'] = 0;
            $dataArr['rewardPoints'] = 0;
            $dataArr['paymentFee'] = 0;
            $dataArr['estimatedSupplyDate'] = null;
            $dataArr['isGiftWrappingEnabled'] = false;
            $dataArr['giftWrapping'] = null;
            $dataArr['ErrorCode'] = null;
            $dataArr['Message'] = null;
            $dataArr['UserFriendly'] = false;
        }

        return $dataArr;
    }
    public function payCashOnDelivery($input_xml, $basketId, $userid)
    {
        $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
        $id_cart = $basketId;
        $cart = new Cart($basketId);
        $beforeTaxTotal = $cart->getOrderTotal(false);
        $cartTotal = $cart->getOrderTotal(true);
        $taxTotal = $cartTotal - $beforeTaxTotal;
        $products = $cart->getProducts();
        $priceProduct = 0;
        foreach ($products as $row) {
            $priceProduct +=  $row['total_wt'];
        }
        
        $shipmentId = $data_arr['shippingMethod'][0][' id'];
        (int) $shipmentId > 0 ?  $id_carrier = $shipmentId :  $id_carrier = null;
        $shipmentPrice = $cart->getPackageShippingCost($id_carrier);
        $id_order_state = Configuration::get('PS_OS_PREPARATION');
        $payment_method = $data_arr['displayName'];
        $this->context = Context::getContext();
        $id_lang = $this->context->language->id;
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $reference = Order::generateReference();
        $this->context->customer = new Customer($userid);
        $query = 'SELECT c.*,p.* FROM '._DB_PREFIX_.'cart c
LEFT JOIN '._DB_PREFIX_."cart_product p ON (p.id_cart = c.id_cart)
    WHERE c.id_cart ='".pSQL($id_cart)."' ORDER BY p.date_add ASC";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        $dis_sql = 'SELECT cr.*, crl.*, crll.* FROM '._DB_PREFIX_.'cart_cart_rule cr
         LEFT JOIN '._DB_PREFIX_.'cart_rule crl ON crl.id_cart_rule = cr.id_cart_rule
         LEFT JOIN '._DB_PREFIX_."cart_rule_lang crll ON crll.id_cart_rule = cr.id_cart_rule
         WHERE cr.id_cart = '".pSQL($basketId)."'";
        $dis_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dis_sql);
        $discountPrice = '';
        $discountPercent = '';
        if (count($dis_results) > 0) {
            $discountPrice = $dis_results[0]['reduction_amount'];
            $discountPercent = $dis_results[0]['reduction_percent'];
        }
        $total = 0;
        foreach ($results as $v1) {
            $price = $this->productPrice($v1['id_product']);
            $total = $total + ($price * $v1['quantity']);
        }
        if (!empty($discountPrice) && $discountPrice > 0) {
            $disPrice = $total - $discountPrice;
        } else {
            if (!empty($discountPercent) && $discountPercent > 0) {
                $p_price = ($discountPercent / 100) * $total;
                $disPrice = $total - $p_price;
            }
        }
        $ad = $this->getDeliveryAddress($userid);
        $idD_currency = isset($this->context->currency->id) ? $this->context->currency->id : $id_currency;
        $id_shop_group = 1;
        $total_discounts = (isset($disPrice) && $disPrice != '') ? $disPrice : 0;
        $id_shop = (int) $this->context->shop->id;
        $conversion_rate = '';
        $secure_key = $this->context->customer->secure_key;
        $id_address_invoice = isset($ad['shippingAddress']['id']) ? $ad['shippingAddress']['id'] : '';
        $order_qry = 'INSERT INTO '._DB_PREFIX_."orders SET id_carrier = '0', id_customer = '".
            pSQL($userid)."', id_lang = '".pSQL($id_lang)."', id_cart = '".
            pSQL($id_cart)."', current_state = '".pSQL($id_order_state)."',
             reference = '". pSQL($reference)."', id_shop = '".pSQL($id_shop)."',
              id_currency = '".pSQL($idD_currency)."',
            id_shop_group = '".pSQL($id_shop_group)."', total_paid = '".pSQL($cartTotal)."',
            total_paid_tax_incl = '".pSQL($cartTotal)."',
            total_paid_tax_excl = '".pSQL($beforeTaxTotal)."',   total_paid_real = '".pSQL($cartTotal)."',
            total_products = '".pSQL($priceProduct)."',  total_products_wt = '".pSQL($priceProduct)."',
            total_shipping_tax_incl = '".pSQL($shipmentPrice)."', total_shipping_tax_excl = '".pSQL($shipmentPrice)."',
            total_shipping = '".pSQL($shipmentPrice)."', total_discounts = '".pSQL($total_discounts)."',
            secure_key = '".pSQL($secure_key)."', payment = '".pSQL($payment_method)."',
            module = 'cashondelivery', recyclable = '0', gift = '0', gift_message = '',
            mobile_theme = '0', conversion_rate = '".$conversion_rate."' ,
            round_mode = '".Configuration::get('PS_PRICE_ROUND_MODE')."',
            round_type = '".Configuration::get('PS_ROUND_TYPE')."',
            invoice_date = '0000-00-00 00:00:00', delivery_date = '0000-00-00 00:00:00',
            id_address_invoice = '".pSQL($id_address_invoice)."',
            id_address_delivery = '".pSQL($id_address_invoice)."',
            date_add='".date('Y-m-d H:i:s')."',date_upd='".date('Y-m-d H:i:s')."'";
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($order_qry);
        $orderId = Db::getInstance()->Insert_ID();
        $order_state = 'INSERT INTO '._DB_PREFIX_."order_history SET
                id_order = '".pSQL($orderId)."', id_order_state = '14', date_add = '".date('Y-m-d H:i:s')."'";
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($order_state);
        foreach ($results as $v1) {
            $pro = $this->getProductDetail($v1['id_product']);
            $o_detail = 'INSERT INTO '._DB_PREFIX_."order_detail SET id_order = '".pSQL($orderId)."',
            id_order_invoice = '', id_warehouse = '".pSQL($orderId)."', id_shop = '".pSQL($id_shop)."',
            product_id = '".pSQL($v1['id_product'])."', product_attribute_id = '".pSQL($v1['id_product_attribute'])."',
            product_name = '".pSQL($pro['productName'])."', product_quantity = '".pSQL($v1['quantity'])."',
            product_quantity_in_stock = '".$v1['quantity']."', product_price = '".pSQL($pro['listPrice']['amount'])."',
            unit_price_tax_excl = '".pSQL($pro['listPrice']['amount'])."',
            unit_price_tax_incl = '".pSQL($pro['listPrice']['amount'])."',
            original_product_price = '".pSQL($pro['listPrice']['amount'])."'";
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($o_detail);
        }
        $query_cart = 'SELECT c.*,p.* FROM '._DB_PREFIX_.'cart c
LEFT JOIN '._DB_PREFIX_."cart_product p ON (p.id_cart = c.id_cart)
    WHERE c.id_customer = '".pSQL($userid)."' ORDER BY p.date_add ASC";
        $cart_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
        if (count($cart_results) > 0) {
            foreach ($cart_results as $cprod) {
                $del_q = 'DELETE FROM '._DB_PREFIX_."cart_product WHERE id_cart='".$cprod['id_cart']."'";
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($del_q);
            }
        } else {
            $query_cart = 'SELECT c.*,p.* FROM '._DB_PREFIX_.'cart c
LEFT JOIN '._DB_PREFIX_."cart_product p ON (p.id_cart = c.id_cart)
WHERE c.id_cart = '". $id_cart."' ORDER BY p.date_add ASC ";
            $acart_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
            foreach ($acart_results as $cprod) {
                $del_q = 'DELETE FROM '._DB_PREFIX_."cart_product WHERE id_cart='".$cprod['id_cart']."'";
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($del_q);
            }
        }
        $query_cart = 'DELETE FROM '._DB_PREFIX_."cart WHERE id_cart='".pSQL($id_cart)."'";
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
        if ($userid != '') {
            $query_cart = 'DELETE FROM '._DB_PREFIX_."cart WHERE id_customer='".pSQL($userid)."'";
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
        }
        $ov1 = (array) new Order($orderId);
        $dataArr = array();
        $ii = 0;
        if (!empty($ov1)) {
            $status_query = 'SELECT * FROM '._DB_PREFIX_.'order_history AS oh
            LEFT JOIN '._DB_PREFIX_."order_state_lang AS osl ON osl.id_order_state=oh.id_order_state
            WHERE id_order = '".pSQL($orderId)."'";
            $status_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($status_query);
            $dataArr['id'] = $orderId;
            $dataArr['trackingNumber'] = $ov1['reference'];
            $dataArr['orderDate'] = $ov1['date_add'];
            $resultName= $status_results[0]['name'];
            $statusName = (isset($resultName) && $resultName != '') ? $resultName : '';
            $dataArr['shippingStatus'] = $statusName;
            $dataArr['paymentStatus'] = $statusName;
            $dataArr['ipAddress'] = '';
            $query = 'SELECT * FROM '._DB_PREFIX_."order_detail WHERE id_order = '".pSQL($orderId)."'";
            $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
            $currency = new Currency($id_currency);
            $currency_sign = $currency->sign;
            $i = 0;
            if (Db::getInstance()->NumRows() > 0) {
                $data['id'] = $ov1['id_cart'];
                $total = '';
                foreach ($results as $v1) {
                    $price = $this->productPrice($v1['product_id']);
                    $total = $total + ($price * $v1['product_quantity']);
                    $subTotal = $total + $shipmentPrice;
                    if (isset($ov1['id_customer']) && !empty($ov1['id_customer'])) {
                        $dataArr['lines'][$i]['productId'] = $v1['product_id'];
                        $dataArr['lines'][$i]['product'] = $this->getProductDetail($v1['product_id']);
                        $dataArr['lines'][$i]['quantity'] = $v1['product_quantity'];
                        $dataArr['lines'][$i]['placedPrice'] = 0;
                        $dataArr['lines'][$i]['placedPriceTotal'] = 0;
                        $dataArr['lines'][$i]['extendedPrice'] = (string) $price;
                        $dataArr['lines'][$i]['extendedPriceValue'] = (float) $price;
                        $dataArr['lines'][$i]['extendedPriceTotal'] = (string) ($price * $v1['product_quantity']);
                        $dataArr['lines'][$i]['extendedPriceTotalValue'] = (float) $price * $v1['product_quantity'];
                        $dataArr['lines'][$i]['strikeoutPrice'] = null;
                        $dataArr['lines'][$i]['status'] = '';
                        $dataArr['lines'][$i]['averageDeliveryDays'] = '';
                        $dataArr['lines'][$i]['variants'][0]['groupName'] = '';
                        $dataArr['lines'][$i]['variants'][0]['groupId'] = '';
                        $dataArr['lines'][$i]['variants'][0]['features'][0]['displayName'] = '';
                        $dataArr['lines'][$i]['variants'][0]['features'][0]['value'] = '';
                        $dataArr['delivery'] = $this->getDeliveryAddress($ov1['id_customer']);
                        ++$i;
                    }
                }
                $dataArr['payment']['methodType'] = 'CashOnDelivery';
                $dataArr['payment']['type'] = 'CashOnDelivery';
                $dataArr['payment']['displayName'] = 'CashOnDelivery';
                $dataArr['payment']['bankCode'] = '';
                $dataArr['payment']['installment'] = 0;
                $dataArr['payment']['accountNumber'] = '';
                $dataArr['payment']['branch'] = '';
                $dataArr['payment']['iban'] = '';
                $dataArr['payment']['cashOnDelivery']['type'] = 'CashOnDelivery';
                $dataArr['payment']['cashOnDelivery']['displayName'] = 'CashOnDelivery';
                $dataArr['payment']['cashOnDelivery']['additionalFee'] = 0;
                $dataArr['payment']['cashOnDelivery']['description'] = 'CashOnDelivery';
                $dataArr['payment']['cashOnDelivery']['isSmsVerification'] = false;
                $dataArr['payment']['cashOnDelivery']['phoneNumber'] = '';
                $dataArr['payment']['cashOnDelivery']['phoneNumber'] = '';
                $dataArr['payment']['cashOnDelivery']['smsCode'] = '';
      
                $dataArr['payment']['threeDUrl'] = null;
                $dataArr['currency'] = $currency_sign;
                $dataArr['itemsPriceTotal'] = (string) $total;
                $dataArr['discountTotal'] = '0';
                $dataArr['subTotal'] = $subTotal;
                $dataArr['taxTotal'] = $taxTotal;
                $dataArr['shippingTotal'] = $shipmentPrice;
                $dataArr['total'] = $cartTotal;
                $dataArr['taxTotalValue'] = $taxTotal;
                $dataArr['shippingTotalValue'] = '0.00';
                $dataArr['totalValue'] = '0.00';
                $dataArr['usedPoints'] = 0;
                $dataArr['usedPointsAmount'] = 0;
                $dataArr['rewardPoints'] = 0;
                $dataArr['ErrorCode'] = '';
                $dataArr['Message'] = '';
                $dataArr['UserFriendly'] = false;
            } else {
                $dataArr['id'] = $orderId;
                $dataArr['lines'] = array();
                $dataArr['shippingMethods'] = null;
                $dataArr['delivery']['shippingAddress'] = null;
                $dataArr['delivery']['billingAddress'] = null;
                $dataArr['delivery']['shippingMethod'] = null;
                $dataArr['delivery']['useSameAddressForBilling'] = false;
                $dataArr['paymentOptions']['moneyTransfer'] = array();
                $dataArr['paymentOptions']['cashOnDelivery'][0]['type'] = '';
                $dataArr['paymentOptions']['cashOnDelivery'][0]['displayName'] = '';
                $dataArr['paymentOptions']['cashOnDelivery'][0]['additionalFee'] = '0';
                $dataArr['paymentOptions']['cashOnDelivery'][0]['description'] = 'Cash On Delivery';
                $dataArr['paymentOptions']['cashOnDelivery'][0]['isSmsVerification'] = false;
                $dataArr['paymentOptions']['cashOnDelivery'][0]['phoneNumber'] = '';
                $dataArr['paymentOptions']['cashOnDelivery'][0]['smsCode'] = '';
                $dataArr['payment'] = null;
                $dataArr['currency'] = 'TL';
                $dataArr['itemsPriceTotal'] = 0;
                $dataArr['subTotal'] = 0;
                $dataArr['beforeTaxTotal'] = 0;
                $dataArr['taxTotal'] = 0;
                $dataArr['shippingTotal'] = 0;
                $dataArr['total'] = 0;
                $dataArr['errors'] = array();
                $dataArr['giftCheques'] = array();
                $dataArr['spentGiftChequeTotal'] = 0;
                $dataArr['discounts'] = array();
                $dataArr['discountTotal'] = 0;
                $dataArr['usedPoints'] = 0;
                $dataArr['usedPointsAmount'] = 0;
                $dataArr['rewardPoints'] = 0;
                $dataArr['paymentFee'] = 0;
                $dataArr['estimatedSupplyDate'] = null;
                $dataArr['isGiftWrappingEnabled'] = false;
                $dataArr['giftWrapping'] = null;
                $dataArr['ErrorCode'] = null;
                $dataArr['Message'] = null;
                $dataArr['UserFriendly'] = false;
            }
            ++$ii;
        } else {
            $dataArr['id'] = '';
            $dataArr['lines'] = array();
            $dataArr['shippingMethods'] = array();
            $dataArr['delivery']['shippingAddress'] = null;
            $dataArr['delivery']['billingAddress'] = null;
            $dataArr['delivery']['shippingMethod'] = null;
            $dataArr['delivery']['useSameAddressForBilling'] = false;
            $dataArr['paymentOptions']['moneyTransfer'] = array();
            $dataArr['paymentOptions']['cashOnDelivery'][0]['type'] = '';
            $dataArr['paymentOptions']['cashOnDelivery'][0]['displayName'] = '';
            $dataArr['paymentOptions']['cashOnDelivery'][0]['additionalFee'] = '0';
            $dataArr['paymentOptions']['cashOnDelivery'][0]['description'] = 'Cash On Delivery';
            $dataArr['paymentOptions']['cashOnDelivery'][0]['isSmsVerification'] = false;
            $dataArr['paymentOptions']['cashOnDelivery'][0]['phoneNumber'] = '';
            $dataArr['paymentOptions']['cashOnDelivery'][0]['smsCode'] = '';
            $dataArr['payment'] = null;
            $dataArr['currency'] = 'TL';
            $dataArr['itemsPriceTotal'] = 0;
            $dataArr['subTotal'] = 0;
            $dataArr['beforeTaxTotal'] = 0;
            $dataArr['taxTotal'] = 0;
            $dataArr['shippingTotal'] = 0;
            $dataArr['total'] = 0;
            $dataArr['errors'] = array();
            $dataArr['giftCheques'] = array();
            $dataArr['spentGiftChequeTotal'] = 0;
            $dataArr['discounts'] = array();
            $dataArr['discountTotal'] = 0;
            $dataArr['usedPoints'] = 0;
            $dataArr['usedPointsAmount'] = 0;
            $dataArr['rewardPoints'] = 0;
            $dataArr['paymentFee'] = 0;
            $dataArr['estimatedSupplyDate'] = null;
            $dataArr['isGiftWrappingEnabled'] = false;
            $dataArr['giftWrapping'] = null;
            $dataArr['ErrorCode'] = null;
            $dataArr['Message'] = null;
            $dataArr['UserFriendly'] = false;
        }

        return $dataArr;
    }
    private function getPaypalParameters()
    {
        $result = Db::getInstance()->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'tappz_paypal`
			order by id_tappz_paypal  desc limit 1');

        return $result[0];
    }
    public function purchaseWithPayPal($input_xml, $basketId, $userid)
    {
        $userid = (int) $userid;
        $basketId = (int) $basketId;
        $paypal = $this->getPaypalParameters();
        $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
        $url = $paypal['sandbox'] ? 'https://api.sandbox.paypal.com/v1/' : 'https://api.paypal.com/v1/';
        $paypalClientId = $paypal['paypal_client_id'];
        $paypalSecret = $paypal['paypal_secret'];
        $transactionId = $data_arr['TransactionId'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'oauth2/token');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Accept-Language: en_US',
            'content-type: application/x-www-form-urlencoded',
        ));
        curl_setopt($ch, CURLOPT_USERPWD, $paypalClientId.':'.$paypalSecret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        $token_result_json = curl_exec($ch);
        curl_close($ch);
        $token_result = Tools::jsonDecode($token_result_json);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'payments/payment/'.$transactionId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Authorization: Bearer '.$token_result->access_token,
            'content-type: application/x-www-form-urlencoded',
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $payment_result_json = curl_exec($ch);
        curl_close($ch);
        $payment_result = Tools::jsonDecode($payment_result_json, true);
        $transactionState = $payment_result['state'];
        $saleState = $payment_result['transactions'][0]['related_resources'][0]['sale']['state'];
        if ($transactionState == 'approved' && $saleState == 'completed') {
            $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
            $id_cart = $basketId;
            $id_order_state = Configuration::get('PS_OS_PREPARATION');
            $payment_method = $data_arr['displayName'];
            $this->context = Context::getContext();
            $id_lang = $this->context->language->id;
            $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
            $reference = Order::generateReference();
            $this->context->customer = new Customer($userid);
            $query = 'SELECT c.*,p.* FROM '._DB_PREFIX_.'cart c
LEFT JOIN '._DB_PREFIX_."cart_product p ON (p.id_cart = c.id_cart)
  WHERE c.id_cart = '". pSQL($id_cart)."' ORDER BY p.date_add ASC";
            $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

            $dis_sql = 'SELECT cr.*, crl.*, crll.* FROM '._DB_PREFIX_.'cart_cart_rule cr
             LEFT JOIN '._DB_PREFIX_.'cart_rule crl ON crl.id_cart_rule = cr.id_cart_rule
             LEFT JOIN '._DB_PREFIX_."cart_rule_lang crll ON crll.id_cart_rule = cr.id_cart_rule
             WHERE cr.id_cart = '".pSQL($basketId)."'";
            $dis_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dis_sql);
            $discountPrice = '';
            $discountPercent = '';
            if (count($dis_results) > 0) {
                $discountPrice = $dis_results[0]['reduction_amount'];
                $discountPercent = $dis_results[0]['reduction_percent'];
            }
            $total = 0;
            foreach ($results as $v1) {
                $price = $this->productPrice($v1['id_product']);
                $total = $total + ($price * $v1['quantity']);
            }
            if (!empty($discountPrice) && $discountPrice > 0) {
                $disPrice = $total - $discountPrice;
            } else {
                if (!empty($discountPercent) && $discountPercent > 0) {
                    $p_price = ($discountPercent / 100) * $total;
                    $disPrice = $total - $p_price;
                }
            }
            $amount_paid = (float) Tools::ps_round((float) $total, 2);
            $ad = $this->getDeliveryAddress($userid);
            $idD_currency = isset($this->context->currency->id) ? $this->context->currency->id : $id_currency;
            $id_shop_group = 1;
            $total_paid = (isset($disPrice) && $disPrice != '') ? $disPrice : $amount_paid;
            $total_discounts = (isset($disPrice) && $disPrice != '') ? $disPrice : 0;
            $id_shop = (int) $this->context->shop->id;
            $conversion_rate = '';
            $secure_key = $this->context->customer->secure_key;
            $id_address_invoice = isset($ad['shippingAddress']['id']) ? $ad['shippingAddress']['id'] : '';
            $order_qry = 'INSERT INTO '._DB_PREFIX_."orders
            SET id_carrier = '0', id_customer = '". pSQL($userid)."',
            id_lang = '".pSQL($id_lang)."', id_cart = '".pSQL($id_cart)."',
            current_state = '".pSQL($id_order_state)."', reference = '".pSQL($reference)."',
             id_shop = '".pSQL($id_shop)."', id_currency = '".pSQL($idD_currency)."',
             id_shop_group = '".pSQL($id_shop_group)."', total_paid = '".pSQL($total_paid)."',
             total_products = '".pSQL($amount_paid)."', total_discounts = '".pSQL($total_discounts)."',
             secure_key = '".pSQL($secure_key)."', payment = '".pSQL($payment_method)."',
             module = 'paypal', recyclable = '0', gift = '0', gift_message = '',
              mobile_theme = '0', conversion_rate = '".pSQL($conversion_rate)."',
             total_paid_real = '0', round_mode = '".Configuration::get('PS_PRICE_ROUND_MODE')."',
             round_type = '".Configuration::get('PS_ROUND_TYPE')."',
             invoice_date = '0000-00-00 00:00:00',
             delivery_date = '0000-00-00 00:00:00',
             id_address_invoice = '".pSQL($id_address_invoice)."',
             id_address_delivery = '".pSQL($id_address_invoice)."',
             date_add='".date('Y-m-d H:i:s')."',date_upd='".date('Y-m-d H:i:s')."'";
            $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($order_qry);
            $orderId = Db::getInstance()->Insert_ID();
            $order_state = 'INSERT INTO '._DB_PREFIX_."order_history SET id_order = '".pSQL($orderId)."',
            id_order_state = '14', date_add = '".date('Y-m-d H:i:s')."'";
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($order_state);
            foreach ($results as $v1) {
                $pro = $this->getProductDetail($v1['id_product']);
                $o_detail = 'INSERT INTO '._DB_PREFIX_."order_detail SET id_order = '".pSQL($orderId)."',
                 id_order_invoice = '', id_warehouse = '".pSQL($orderId)."',
                 id_shop = '".pSQL($id_shop)."', product_id = '".pSQL($v1['id_product'])."',
                 product_attribute_id = '".pSQL($v1['id_product_attribute'])."',
                 product_name = '".pSQL($pro['productName'])."',
                 product_quantity = '".pSQL($v1['quantity'])."',
                 product_quantity_in_stock = '".pSQL($v1['quantity'])."',
                 product_price = '".pSQL($pro['listPrice']['amount'])."',
                  original_product_price = '".pSQL($pro['listPrice']['amount'])."'";
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($o_detail);
            }
            $query_cart = 'SELECT c.*,p.* FROM '._DB_PREFIX_.'cart c
LEFT JOIN '._DB_PREFIX_."cart_product p ON (p.id_cart = c.id_cart)
WHERE c.id_customer = '".pSQL($userid)."' ORDER BY p.date_add ASC";
            $cart_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
            if (count($cart_results) > 0) {
                foreach ($cart_results as $cprod) {
                    $del_q = 'DELETE FROM '._DB_PREFIX_."cart_product WHERE id_cart='".pSQL($cprod['id_cart'])."'";
                    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($del_q);
                }
            } else {
                $query_cart = 'SELECT c.*,p.* FROM '._DB_PREFIX_.'cart c
LEFT JOIN '._DB_PREFIX_."cart_product p ON (p.id_cart = c.id_cart)
 WHERE c.id_cart = '". pSQL($id_cart)."' ORDER BY p.date_add ASC";
                $acart_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
                foreach ($acart_results as $cprod) {
                    $del_q = 'DELETE FROM '._DB_PREFIX_."cart_product WHERE id_cart='".pSQL($cprod['id_cart'])."'";
                    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($del_q);
                }
            }
            $query_cart = 'DELETE FROM '._DB_PREFIX_."cart WHERE id_cart='".pSQL($id_cart)."'";
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
            if ($userid != '') {
                $query_cart = 'DELETE FROM '._DB_PREFIX_."cart WHERE id_customer='".pSQL($userid)."'";
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query_cart);
            }
            $ov1 = (array) new Order($orderId);
            $dataArr = array();
            $ii = 0;
            if (!empty($ov1)) {
                $status_query = 'SELECT * FROM '._DB_PREFIX_.'order_history AS oh
                LEFT JOIN '._DB_PREFIX_."order_state_lang AS osl ON osl.id_order_state=oh.id_order_state
                 WHERE id_order = '".pSQL($orderId)."'";
                $status_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($status_query);
                $dataArr['id'] = $orderId;
                $dataArr['trackingNumber'] = $ov1['reference'];
                $dataArr['orderDate'] = $ov1['date_add'];
                $resultName = $status_results[0]['name'];
                $statusName  = (isset($resultName) && $resultName != '') ? $resultName : '';
                $dataArr['shippingStatus'] = $statusName;
                $dataArr['paymentStatus'] = $statusName ;
                $dataArr['ipAddress'] = '';
                $query = 'SELECT * FROM '._DB_PREFIX_."order_detail WHERE id_order = '".pSQL($orderId)."'";
                $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
                $currency = new Currency($id_currency);
                $currency_sign = $currency->sign;
                $i = 0;
                if (Db::getInstance()->NumRows() > 0) {
                    $data['id'] = $ov1['id_cart'];
                    $total = '';
                    $price = '';

                    foreach ($results as $v1) {
                        $price = $this->productPrice($v1['product_id']);
                        $total = $total + ($price * $v1['product_quantity']);
                        if (isset($ov1['id_customer']) && !empty($ov1['id_customer'])) {
                            $dataArr['lines'][$i]['productId'] = $v1['product_id'];
                            $dataArr['lines'][$i]['product'] = $this->getProductDetail($v1['product_id']);
                            $dataArr['lines'][$i]['quantity'] = $v1['product_quantity'];
                            $dataArr['lines'][$i]['placedPrice'] = 0;
                            $dataArr['lines'][$i]['placedPriceTotal'] = 0;
                            $dataArr['lines'][$i]['extendedPrice'] = (string) $price;
                            $dataArr['lines'][$i]['extendedPriceValue'] = (float) $price;
                            $dataArr['lines'][$i]['extendedPriceTotal'] = (string) ($price * $v1['product_quantity']);
                            $dataArr['lines'][$i]['extendedPriceTotalValue'] = (float) $price * $v1['product_quantity'];
                            $dataArr['lines'][$i]['strikeoutPrice'] = null;
                            $dataArr['lines'][$i]['status'] = '';
                            $dataArr['lines'][$i]['averageDeliveryDays'] = '';
                            $dataArr['lines'][$i]['variants'][0]['groupName'] = '';
                            $dataArr['lines'][$i]['variants'][0]['groupId'] = '';
                            $dataArr['lines'][$i]['variants'][0]['features'][0]['displayName'] = '';
                            $dataArr['lines'][$i]['variants'][0]['features'][0]['value'] = '';
                            $dataArr['delivery'] = $this->getDeliveryAddress($ov1['id_customer']);
                            ++$i;
                        }
                    }
                    $dataArr['payment']['methodType'] = 'PayPal';
                    $dataArr['payment']['type'] = 'PayPal';
                    $dataArr['payment']['displayName'] = 'PayPal';
                    $dataArr['payment']['bankCode'] = '';
                    $dataArr['payment']['installment'] = 0;
                    $dataArr['payment']['accountNumber'] = '';
                    $dataArr['payment']['branch'] = '';
                    $dataArr['payment']['iban'] = '';
                    $dataArr['payment']['cashOnDelivery'] = null;
                
                    $dataArr['payment']['threeDUrl'] = null;
                    $dataArr['currency'] = $currency_sign;
                    $dataArr['itemsPriceTotal'] = (string) $total;
                    $dataArr['discountTotal'] = '0';
                    $dataArr['subTotal'] = (string) $total;
                    $dataArr['taxTotal'] = '0';
                    $dataArr['shippingTotal'] = '0';
                    $dataArr['total'] = (string) $total;
                    $dataArr['taxTotalValue'] = '0.00';
                    $dataArr['shippingTotalValue'] = '0.00';
                    $dataArr['totalValue'] = '0.00';
                    $dataArr['usedPoints'] = 0;
                    $dataArr['usedPointsAmount'] = 0;
                    $dataArr['rewardPoints'] = 0;
                    $dataArr['ErrorCode'] = '';
                    $dataArr['Message'] = '';
                    $dataArr['UserFriendly'] = false;
                } else {
                    $dataArr['id'] = $orderId;
                    $dataArr['lines'] = array();
                    $dataArr['shippingMethods'] = null;
                    $dataArr['delivery']['shippingAddress'] = null;
                    $dataArr['delivery']['billingAddress'] = null;
                    $dataArr['delivery']['shippingMethod'] = null;
                    $dataArr['delivery']['useSameAddressForBilling'] = false;
                    $dataArr['paymentOptions']['moneyTransfer'] = array();
                    $dataArr['paymentOptions']['cashOnDelivery'] = null;
                    $dataArr['payment'] = null;
                    $dataArr['currency'] = 'TL';
                    $dataArr['itemsPriceTotal'] = 0;
                    $dataArr['subTotal'] = 0;
                    $dataArr['beforeTaxTotal'] = 0;
                    $dataArr['taxTotal'] = 0;
                    $dataArr['shippingTotal'] = 0;
                    $dataArr['total'] = 0;
                    $dataArr['errors'] = array();
                    $dataArr['giftCheques'] = array();
                    $dataArr['spentGiftChequeTotal'] = 0;
                    $dataArr['discounts'] = array();
                    $dataArr['discountTotal'] = 0;
                    $dataArr['usedPoints'] = 0;
                    $dataArr['usedPointsAmount'] = 0;
                    $dataArr['rewardPoints'] = 0;
                    $dataArr['paymentFee'] = 0;
                    $dataArr['estimatedSupplyDate'] = null;
                    $dataArr['isGiftWrappingEnabled'] = false;
                    $dataArr['giftWrapping'] = null;
                    $dataArr['ErrorCode'] = null;
                    $dataArr['Message'] = null;
                    $dataArr['UserFriendly'] = false;
                }
                ++$ii;
            } else {
                $dataArr['id'] = '';
                $dataArr['lines'] = array();
                $dataArr['shippingMethods'] = array();
                $dataArr['delivery']['shippingAddress'] = null;
                $dataArr['delivery']['billingAddress'] = null;
                $dataArr['delivery']['shippingMethod'] = null;
                $dataArr['delivery']['useSameAddressForBilling'] = false;
                $dataArr['paymentOptions']['moneyTransfer'] = array();
                $dataArr['paymentOptions']['cashOnDelivery'] = null;
                $dataArr['payment'] = null;
                $dataArr['currency'] = 'TL';
                $dataArr['itemsPriceTotal'] = 0;
                $dataArr['subTotal'] = 0;
                $dataArr['beforeTaxTotal'] = 0;
                $dataArr['taxTotal'] = 0;
                $dataArr['shippingTotal'] = 0;
                $dataArr['total'] = 0;
                $dataArr['errors'] = array();
                $dataArr['giftCheques'] = array();
                $dataArr['spentGiftChequeTotal'] = 0;
                $dataArr['discounts'] = array();
                $dataArr['discountTotal'] = 0;
                $dataArr['usedPoints'] = 0;
                $dataArr['usedPointsAmount'] = 0;
                $dataArr['rewardPoints'] = 0;
                $dataArr['paymentFee'] = 0;
                $dataArr['estimatedSupplyDate'] = null;
                $dataArr['isGiftWrappingEnabled'] = false;
                $dataArr['giftWrapping'] = null;
                $dataArr['ErrorCode'] = null;
                $dataArr['Message'] = null;
                $dataArr['UserFriendly'] = false;
            }
        } else {
            $dataArr['id'] = "";
            $dataArr['lines'] = array();
            $dataArr['shippingMethods'] = null;
            $dataArr['delivery']['shippingAddress'] = null;
            $dataArr['delivery']['billingAddress'] = null;
            $dataArr['delivery']['shippingMethod'] = null;
            $dataArr['delivery']['useSameAddressForBilling'] = false;
            $dataArr['paymentOptions']['moneyTransfer'] = array();
            $dataArr['paymentOptions']['cashOnDelivery'] = null;
            $dataArr['payment'] = null;
            $dataArr['currency'] = 'TL';
            $dataArr['itemsPriceTotal'] = 0;
            $dataArr['subTotal'] = 0;
            $dataArr['beforeTaxTotal'] = 0;
            $dataArr['taxTotal'] = 0;
            $dataArr['shippingTotal'] = 0;
            $dataArr['total'] = 0;
            $dataArr['errors'] = array();
            $dataArr['giftCheques'] = array();
            $dataArr['spentGiftChequeTotal'] = 0;
            $dataArr['discounts'] = array();
            $dataArr['discountTotal'] = 0;
            $dataArr['usedPoints'] = 0;
            $dataArr['usedPointsAmount'] = 0;
            $dataArr['rewardPoints'] = 0;
            $dataArr['paymentFee'] = 0;
            $dataArr['estimatedSupplyDate'] = null;
            $dataArr['isGiftWrappingEnabled'] = false;
            $dataArr['giftWrapping'] = null;
            $dataArr['ErrorCode'] = null;
            $dataArr['Message'] = 'Purchase Basket cant complete';
            $dataArr['UserFriendly'] = false;
        }

        return $dataArr;
    }

    public function getDeliveryAddress($customerId = null)
    {
        $sql = 'SELECT a.*, s.id_state,s.name AS state_name,cl.id_lang,cl.id_country,cl.name AS country_name
                FROM '._DB_PREFIX_.'address a
                 LEFT JOIN '._DB_PREFIX_.'country_lang cl ON a.id_country = cl.id_country
                 LEFT JOIN  '._DB_PREFIX_."state s ON s.id_state = a.id_state
                 WHERE a.id_customer='".pSQL($customerId) ."' AND a.deleted = '0'";

        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $data = array();
        $i = 0;
        if (Db::getInstance()->NumRows() > 0) {
            $data['shippingAddress']['id'] = $results[0]['id_address'];

            $data['shippingAddress']['addressName'] = $results[0]['alias'];

            $data['shippingAddress']['name'] = $results[0]['firstname'];

            $data['shippingAddress']['surname'] = $results[0]['lastname'];

            $data['shippingAddress']['email'] = '';

            $data['shippingAddress']['addressLine'] = $results[0]['address1'].' '.$results[0]['address2'];
            $countryName = $results[0]['country_name'];
            $idCountry = $results[0]['id_country'];
            $resultStateName = $results[0]['state_name'];
            $idState = $results[0]['id_state'];
            $country = (isset($countryName) && $countryName != '') ? $countryName : '';
            $countryCode  =(isset($idCountry) && $idCountry != '') ? $idCountry : '';
            $stateName = (isset($resultStateName) && $resultStateName != '') ? $resultStateName : '';
            $stateCode = (isset($idState) && $idState != '') ? $idState : '';

            $data['shippingAddress']['country'] = $country;
            $data['shippingAddress']['countryCode'] =$countryCode ;
            $data['shippingAddress']['state'] = $stateName ;

            $data['shippingAddress']['stateCode'] = $stateCode;
            $data['shippingAddress']['city'] = $results[0]['city'];

            $data['shippingAddress']['cityCode'] = '';

            $data['shippingAddress']['district'] = '';

            $data['shippingAddress']['districtCode'] = '';

            $data['shippingAddress']['town'] = '';

            $data['shippingAddress']['townCode'] = '';

            $data['shippingAddress']['corporate'] = false;

            $data['shippingAddress']['companyTitle'] = $results[0]['company'];

            $data['shippingAddress']['taxDepartment'] = '';

            $data['shippingAddress']['taxNo'] = '';

            $data['shippingAddress']['phoneNumber'] = $results[0]['phone'];

            $data['shippingAddress']['identityNo'] = $results[0]['phone'];

            $data['shippingAddress']['zipCode'] = '';

            $data['shippingAddress']['usCheckoutCity'] = '';

            $data['shippingAddress']['ErrorCode'] = '';

            $data['shippingAddress']['Message'] = '';

            $data['shippingAddress']['UserFriendly'] = false;

            $data['billingAddress']['id'] = $results[0]['id_address'];
            $data['billingAddress']['name'] = $results[0]['firstname'];
            $data['billingAddress']['surname'] = $results[0]['lastname'];
            $data['billingAddress']['addressLine'] = $results[0]['address1'].' '.$results[0]['address2'];

            $data['billingAddress']['addressName'] = $results[0]['alias'];
            $data['billingAddress']['email'] = '';

            $data['billingAddress']['country'] = $results[0]['country_name'];

            $data['billingAddress']['countryCode'] = $results[0]['id_country'];

            $data['billingAddress']['state'] = $results[0]['state_name'];

            $data['billingAddress']['stateCode'] = $results[0]['id_state'];

            $data['billingAddress']['city'] = $results[0]['city'];

            $data['billingAddress']['cityCode'] = '';

            $data['billingAddress']['district'] = '';

            $data['billingAddress']['districtCode'] = '';

            $data['billingAddress']['town'] = '';

            $data['billingAddress']['townCode'] = '';

            $data['billingAddress']['corporate'] = false;

            $data['billingAddress']['companyTitle'] = $results[0]['company'];

            $data['billingAddress']['taxDepartment'] = '';

            $data['billingAddress']['taxNo'] = '';

            $data['billingAddress']['phoneNumber'] = $results[0]['phone'];

            $data['billingAddress']['identityNo'] = $results[0]['phone'];

            $data['billingAddress']['zipCode'] = '';

            $data['billingAddress']['usCheckoutCity'] = '';

            $data['billingAddress']['ErrorCode'] = '';

            $data['billingAddress']['Message'] = '';

            $data['billingAddress']['UserFriendly'] = false;

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

                        $qr = 'd.id_range_weight = '.$w;
                    } else {
                        $p = Carrier::SHIPPING_METHOD_PRICE;

                        $qr = 'd.id_range_price = '.$p;
                    }

                    $sql2 = 'SELECT d.* FROM '._DB_PREFIX_."delivery d
                     WHERE id_zone = '".pSQL($id_zone)."' AND id_carrier = '".pSQL($v1['id_carrier'])."' AND ".$qr;

                    $results2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                    $price =(isset($results2[1]['price']) && $results2[1]['price'] != '') ? $results2[1]['price'] : '0';

                    $data['shippingMethod'][$i]['id'] = $v1['id_carrier'];

                    $data['shippingMethod'][$i]['displayName'] = $v1['name'];

                    $data['shippingMethod'][$i]['trackingAddress'] = '';

                    $data['shippingMethod'][$i]['price'] = $price;
                    $data['shippingMethod'][$i]['priceForYou'] = '';

                    $data['shippingMethod'][$i]['imageUrl'] = '';
                }
            } else {
                $data['shippingMethod'] = array();
            }

            /*$data['shippingMethod'][]['displayName'] = '';

            $data['shippingMethod'][]['trackingAddress'] = '';

            $data['shippingMethod'][]['price'] = 0;

            $data['shippingMethod'][]['priceForYou'] = '';

            $data['shippingMethod'][]['imageUrl'] = '';
*/

            $data['useSameAddressForBilling'] = false;
        } else {
            $data['shippingAddress']['id'] = '';

            $data['shippingAddress']['addressName'] = '';

            $data['shippingAddress']['name'] = '';

            $data['shippingAddress']['surname'] = '';

            $data['shippingAddress']['email'] = '';

            $data['shippingAddress']['addressLine'] = '';

            $data['shippingAddress']['country'] = '';

            $data['shippingAddress']['countryCode'] = '';

            $data['shippingAddress']['state'] = '';

            $data['shippingAddress']['stateCode'] = '';

            $data['shippingAddress']['city'] = '';

            $data['shippingAddress']['cityCode'] = '';

            $data['shippingAddress']['district'] = '';

            $data['shippingAddress']['districtCode'] = '';

            $data['shippingAddress']['town'] = '';

            $data['shippingAddress']['townCode'] = '';

            $data['shippingAddress']['corporate'] = false;

            $data['shippingAddress']['companyTitle'] = '';

            $data['shippingAddress']['taxDepartment'] = '';

            $data['shippingAddress']['taxNo'] = '';

            $data['shippingAddress']['phoneNumber'] = '';

            $data['shippingAddress']['identityNo'] = '';

            $data['shippingAddress']['zipCode'] = '';

            $data['shippingAddress']['usCheckoutCity'] = '';

            $data['shippingAddress']['ErrorCode'] = '';

            $data['shippingAddress']['Message'] = '';

            $data['shippingAddress']['UserFriendly'] = false;

            $data['billingAddress']['id'] = '';
            $data['billingAddress']['name'] = '';
            $data['billingAddress']['addressName'] = '';
            $data['billingAddress']['email'] = '';
            $data['billingAddress']['surname'] = '';
            $data['billingAddress']['addressLine'] = '';

            $data['billingAddress']['country'] = '';

            $data['billingAddress']['countryCode'] = '';

            $data['billingAddress']['state'] = '';

            $data['billingAddress']['stateCode'] = '';

            $data['billingAddress']['city'] = '';

            $data['billingAddress']['cityCode'] = '';

            $data['billingAddress']['district'] = '';

            $data['billingAddress']['districtCode'] = '';

            $data['billingAddress']['town'] = '';

            $data['billingAddress']['townCode'] = '';

            $data['billingAddress']['corporate'] = false;

            $data['billingAddress']['companyTitle'] = '';

            $data['billingAddress']['taxDepartment'] = '';

            $data['billingAddress']['taxNo'] = '';

            $data['billingAddress']['phoneNumber'] = '';

            $data['billingAddress']['identityNo'] = '';

            $data['billingAddress']['zipCode'] = '';

            $data['billingAddress']['usCheckoutCity'] = '';

            $data['billingAddress']['ErrorCode'] = '';

            $data['billingAddress']['Message'] = '';

            $data['billingAddress']['UserFriendly'] = false;

            $data['shippingMethod'] = array();

            $data['useSameAddressForBilling'] = false;
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


    private function getDiscountedPrice($prod_id)
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
