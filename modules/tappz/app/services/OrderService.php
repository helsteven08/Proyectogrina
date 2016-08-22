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

class OrderService extends BaseService
{
    public function getPurchaseOrder($customerId)
    {   $customerId = (int) $customerId;
        $orders = Order::getCustomerOrders($customerId);

        $dataArr = array();
        $ii = 0;
        if (!empty($orders)) {
            foreach ($orders as $ov1) {
                $status_query = 'SELECT * FROM '._DB_PREFIX_.'order_history AS oh
                 LEFT JOIN '._DB_PREFIX_."order_state_lang AS osl ON osl.id_order_state=oh.id_order_state
                  WHERE id_order = '".pSQL($ov1['id_order'])."'";
                $status_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($status_query);
                $query = "SELECT * FROM "._DB_PREFIX_."order_detail WHERE id_order = '".pSQL($ov1['id_order'])."'";
                $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                if (sizeof($results) > 0) {
                    $dataArr[$ii]['id'] = $ov1['id_order'];
                    $dataArr[$ii]['trackingNumber'] = $ov1['reference'];
                    $dataArr[$ii]['orderDate'] = $ov1['date_add'];
                    $resultName= $status_results[0]['name'];
                    $statusName = (isset($resultName) && $resultName != '') ? $resultName : '';
                    $dataArr[$ii]['shippingStatus'] = $statusName;
                    $dataArr[$ii]['paymentStatus'] = $statusName;
                    $dataArr[$ii]['ipAddress'] = '';
                    $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
                    $currency = new Currency($id_currency);
                    $currency_sign = $currency->sign;
                    $total = '';

                    $dataArr[$ii]['delivery'] = $this->getDeliveryAddress($ov1['id_customer']);
                    $dataArr[$ii]['payment']['methodType'] = 'CashOnDelivery';
                    $dataArr[$ii]['payment']['type'] = 'CashOnDelivery';
                    $dataArr[$ii]['payment']['displayName'] = 'CashOnDelivery';
                    $dataArr[$ii]['payment']['bankCode'] = '';
                    $dataArr[$ii]['payment']['installment'] = 0;
                    $dataArr[$ii]['payment']['accountNumber'] = '';
                    $dataArr[$ii]['payment']['branch'] = '';
                    $dataArr[$ii]['payment']['iban'] = '';
                    $dataArr[$ii]['payment']['cashOnDelivery']['type'] = 'CashOnDelivery';
                    $dataArr[$ii]['payment']['cashOnDelivery']['displayName'] = 'CashOnDelivery';
                    $dataArr[$ii]['payment']['cashOnDelivery']['additionalFee'] = 0;
                    $dataArr[$ii]['payment']['cashOnDelivery']['description'] = 'Cash On Delivery';
                    $dataArr[$ii]['payment']['cashOnDelivery']['isSmsVerification'] = false;
                    $dataArr[$ii]['payment']['cashOnDelivery']['phoneNumber'] = '';
                    $dataArr[$ii]['payment']['cashOnDelivery']['smsCode'] = '';
                    $dataArr[$ii]['payment']['creditCard'] = null;
                    $dataArr[$ii]['payment']['threeDUrl'] = null;
                    $dataArr[$ii]['currency'] = $currency_sign;
                    $dataArr[$ii]['itemsPriceTotal'] = $total;
                    $dataArr[$ii]['discountTotal'] = 0;
                    $dataArr[$ii]['subTotal'] = $total;
                    $dataArr[$ii]['shippingTotal'] = 0;
                    $dataArr[$ii]['total'] = $total;
                    $dataArr[$ii]['taxTotalValue'] = 0;
                    $dataArr[$ii]['shippingTotalValue'] = 0;
                    $dataArr[$ii]['totalValue'] = 0;
                    $dataArr[$ii]['usedPoints'] = 0;
                    $dataArr[$ii]['usedPointsAmount'] = 0;
                    $dataArr[$ii]['rewardPoints'] = 0;
                    $dataArr[$ii]['ErrorCode'] = '';
                    $dataArr[$ii]['Message'] = '';
                    $dataArr[$ii]['UserFriendly'] = false;
                    ++$ii;
                }
            }
        }

        return $dataArr;
    }

    public function getPurchaseOrderDetail($orderId)
    {
        $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($id_currency);
        $currency_sign = $currency->sign;
        $dataArr = array();
        $ii = 0;
        $data = array();
        if (isset($orderId) && !empty($orderId)) {
            $MainOrder = 'SELECT * FROM '._DB_PREFIX_."orders WHERE id_order = '".pSQL($orderId)."'";
            $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($MainOrder);
            foreach ($res as $ov1) {
                $status_query = 'SELECT * FROM '._DB_PREFIX_.'order_history AS oh
                LEFT JOIN '._DB_PREFIX_."order_state_lang AS osl ON osl.id_order_state=oh.id_order_state
                 WHERE id_order = '".pSQL($ov1['id_order'])."'";
                $status_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($status_query);
                $query = 'SELECT * FROM '._DB_PREFIX_."order_detail WHERE id_order = '".pSQL($ov1['id_order'])."'";
                $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                if (Db::getInstance()->NumRows() > 0) {
                    $dataArr['id'] = $ov1['id_order'];
                    $dataArr['trackingNumber'] = $ov1['reference'];
                    $dataArr['orderDate'] = $ov1['date_add'];
                    $resultName = $status_results[0]['name'];
                    $statusName = (isset($resultName) && $resultName != '') ? $resultName : '';
                    $dataArr[$ii]['shippingStatus'] = $statusName;
                    $dataArr['shippingStatus'] = $statusName;
                    $dataArr['paymentStatus'] = $statusName;
                    $dataArr['ipAddress'] = '';
                    $i = 0;
                    $data['id'] = $ov1['id_cart'];
                    $total = '';
                    foreach ($results as $v1) {
                        $price = $this->productPrice($v1['product_id']);
                        $total = $total + ($price * $v1['product_quantity']);
                        $dataArr['lines'][$i]['productId'] = $v1['product_id'];
                        $dataArr['lines'][$i]['product'] = $this->getProductDetail($v1['product_id']);
                        $dataArr['lines'][$i]['quantity'] = $v1['product_quantity'];
                        $dataArr['lines'][$i]['placedPrice'] = 0;
                        $dataArr['lines'][$i]['placedPriceTotal'] = 0;
                        $dataArr['lines'][$i]['extendedPrice'] = (string) $price;
                        $dataArr['lines'][$i]['extendedPriceValue'] = (float) $price;
                        $dataArr['lines'][$i]['extendedPriceTotal'] = (string) ($price * $v1['product_quantity']);
                        $dataArr['lines'][$i]['extendedPriceTotalValue'] = (float) $price * $v1['product_quantity'];
                        $dataArr['lines'][$i]['status'] = '';
                        $dataArr['lines'][$i]['averageDeliveryDays'] = '';
                        $dataArr['lines'][$i]['variants'] = null;
                        $dataArr['delivery'] = $this->getDeliveryAddress($ov1['id_customer']);
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
                        $dataArr['payment']['cashOnDelivery']['smsCode'] = '';
                        $dataArr['payment']['creditCard'] = null;
                        $dataArr['payment']['threeDUrl'] = null;
                        $dataArr['currency'] = $currency_sign;
                        $dataArr['itemsPriceTotal'] = $total;
                        $dataArr['discountTotal'] = 0;
                        $dataArr['subTotal'] = $total;
                        $dataArr['shippingTotal'] = 0;
                        $dataArr['total'] = $total;
                        $dataArr['taxTotalValue'] = 0;
                        $dataArr['shippingTotalValue'] = 0;
                        $dataArr['totalValue'] = 0;
                        $dataArr['ErrorCode'] = '';
                        $dataArr['Message'] = '';
                        $dataArr['UserFriendly'] = false;
                        ++$i;
                    }
                }
                ++$ii;
            }
        }

        return $dataArr;
    }

    public function getProductDetail($proId = null)
    {
        $productId = $proId;
        $sql = 'SELECT p.*, pl.description, pl.description_short,sa.quantity AS stock_qty,
pl.available_now, pl.available_later, pl.link_rewrite, pl.meta_description,
 pl.meta_keywords, pl.meta_title, pl.name, cl.name AS category_default
        FROM '._DB_PREFIX_.'category_product cp
        LEFT JOIN '._DB_PREFIX_.'product p ON p.id_product = cp.id_product
		LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON sa.`id_product` = p.`id_product`
        LEFT JOIN '._DB_PREFIX_."category_lang cl ON (p.id_category_default = cl.id_category
         AND cl.id_lang = '".$this->getLangId()."')
        LEFT JOIN "._DB_PREFIX_."product_lang pl ON (p.id_product = pl.id_product
        AND pl.id_lang = '".$this->getLangId()."')
        WHERE p.active = '1' AND p.id_product = '".pSQL($productId)."' GROUP BY p.id_product ";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (Db::getInstance()->NumRows() > 0) {
            $id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
            $currency = new Currency($id_currency);
            $currency_sign = $currency->sign;
            $arr = array();
            foreach ($results as $k1 => $v1) {
                $arr[$k1] = $v1;
                $arr[$k1]['currency_sign'] = $currency_sign;
                $sql2 = 'SELECT pa.*, ag.id_attribute_group, ag.is_color_group, agl.name AS group_name,
 al.name AS attribute_name, a.id_attribute, pa.unit_price_impact
					FROM '._DB_PREFIX_.'product_attribute pa
					LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac
					 ON pac.id_product_attribute = pa.id_product_attribute
					LEFT JOIN '._DB_PREFIX_.'attribute a ON a.id_attribute = pac.id_attribute
					LEFT JOIN '._DB_PREFIX_.'attribute_group ag ON ag.id_attribute_group = a.id_attribute_group
					LEFT JOIN '._DB_PREFIX_.'attribute_lang al ON (a.id_attribute = al.id_attribute AND al.id_lang = 1)
					LEFT JOIN '._DB_PREFIX_."attribute_group_lang agl
					ON (ag.id_attribute_group = agl.id_attribute_group AND agl.id_lang = 1)
					WHERE pa.id_product = '".pSQL($v1['id_product'])."' ORDER BY group_name";
                $attr_res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                $attr_results = $this->groupBy($attr_res, 'group_name');
                $arr[$k1]['Attributes'] = $attr_results;
                $sql3 = 'SELECT * FROM '._DB_PREFIX_.'image i
					LEFT JOIN '._DB_PREFIX_."image_lang il ON (i.id_image = il.id_image)
					WHERE i.id_product = '".pSQL($v1['id_product'])."' AND il.id_lang = ".$this->getLangId().'
					ORDER BY i.position ASC';
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
                    $amount = (isset($row['price']) && $row['price'] != '') ? number_format($row['price'], 2) : 0;

                    $data['listPrice']['currency'] = $row['currency_sign'];
                    $data['listPrice']['amountDefaultCurrency'] = '';
                    $data['IsCampaign'] = true;
                    $data['strikeoutPrice']['amount'] = $amount;
                    $data['strikeoutPrice']['currency'] = $row['currency_sign'];
                    $data['strikeoutPrice']['amountDefaultCurrency'] = '';
                } else {
                    if (isset($row['price']) && !empty($row['price'])) {
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
                        $data['variants'][$ii]['groupName'] = $k2;
                        $idAttrGroup = $v2[0]['id_attribute_group'];
                        $groupId = (isset($idAttrGroup) && $idAttrGroup!= '') ? $idAttrGroup : '';
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
                }
                $data['variants'] = null;
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

    public function productPrice($proId)
    {
        $productId = $proId;
        $sql = 'SELECT p.*, pl.description, pl.description_short, pl.available_now, pl.available_later,
pl.link_rewrite, pl.meta_description, pl.meta_keywords, pl.meta_title, pl.name, cl.name AS category_default
         FROM '._DB_PREFIX_.'category_product cp
         LEFT JOIN '._DB_PREFIX_.'product p ON p.id_product = cp.id_product
        LEFT JOIN '._DB_PREFIX_."category_lang cl ON (p.id_category_default = cl.id_category
         AND cl.id_lang = '".$this->getLangId()."')
        LEFT JOIN "._DB_PREFIX_."product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '".
            $this->getLangId()."')
        WHERE p.active = '1' AND p.id_product = '".pSQL($productId)."' GROUP BY p.id_product";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $price = '';
        if (Db::getInstance()->NumRows() > 0) {
            $price = number_format($results[0]['price'], 2);
        }

        return $price;
    }

    public function getDeliveryAddress($customerId = null)
    {
        $sql = 'SELECT a.*, s.id_state,s.name AS state_name,cl.id_lang,cl.id_country,cl.name AS country_name
FROM '._DB_PREFIX_.'address a
LEFT JOIN '._DB_PREFIX_.'country_lang cl ON a.id_country = cl.id_country
 LEFT JOIN  '._DB_PREFIX_."state s ON s.id_state = a.id_state
 WHERE a.id_customer='".pSQL($customerId)."' AND a.deleted = '0'";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $data = array();
        $i = 0;
        if (Db::getInstance()->NumRows() > 0) {
            $countryName = $results[0]['country_name'];
            $idCountry = $results[0]['id_country'];
            $resultStateName = $results[0]['state_name'];
            $idState = $results[0]['id_state'];
            $data['shippingAddress']['id'] = $results[0]['id_address'];
            $data['shippingAddress']['addressName'] = $results[0]['alias'];
            $data['shippingAddress']['name'] = $results[0]['firstname'];
            $data['shippingAddress']['surname'] = $results[0]['lastname'];
            $data['shippingAddress']['email'] = '';
            $data['shippingAddress']['addressLine'] = $results[0]['address1'].' '.$results[0]['address2'];
            $data['shippingAddress']['country'] = $countryName ;
            $data['shippingAddress']['countryCode'] = $idCountry;
            $data['shippingAddress']['state'] = $resultStateName;
            $data['shippingAddress']['stateCode'] = $idState;
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
                    WHERE id_zone = '".$id_zone."' AND id_carrier = '".pSQL($v1['id_carrier'])."' AND ".$qr;
                    $results2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                    $data['shippingMethod'][$i]['id'] = $v1['id_carrier'];
                    $data['shippingMethod'][$i]['displayName'] = $v1['name'];
                    $data['shippingMethod'][$i]['trackingAddress'] = '';
                    $resultPrice = $results2[1]['price'];
                    $shipmentPrice = (isset($resultPrice) && $resultPrice != '') ?$resultPrice : '0';
                    $data['shippingMethod'][$i]['price'] = $shipmentPrice;
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
