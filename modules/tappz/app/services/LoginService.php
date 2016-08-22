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

class LoginService extends BaseService
{
    public function getLogin($input_xml)
    {
        $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';

        if (isset($data_arr['email']) && $data_arr['email'] != '') {
            $pass = $data_arr['password'];

            $sql = 'SELECT e.id_customer,e.firstname,e.lastname,e.id_gender,e.email,e.newsletter,e.birthday FROM
                  '._DB_PREFIX_."customer AS e
                   WHERE e.id_lang='".$this->getLangId()."'
                   AND e.email='".pSQL($data_arr['email'])."'
                   AND e.passwd='".pSQL(MD5(_COOKIE_KEY_.$pass))."'
                   AND e.active='1'
                   AND e.deleted='0'";
            $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if (Db::getInstance()->NumRows() > 0) {
                $i = 0;
                $data = array();
                foreach ($results as $row) {
                    $data['accessToken'] = $row['id_customer'];
                    $data['fullName'] = $row['firstname'].' '.$row['lastname'];
                    $data['firstName'] = $row['firstname'];
                    $data['lastName'] = $row['lastname'];
                    $data['gender'] = $row['id_gender'];
                    if ($row['newsletter'] == 1) {
                        $data['IsSubscribe'] = 1;
                    } else {
                        $data['IsSubscribe'] = 0;
                    }
                    $data['birthDate'] = $row['birthday'];
                    $data['accept'] = false;
                    $data['email'] = $data_arr['email'];
                    $data['phone'] = '';
                    $data['password'] = $data_arr['password'];
                    $data['addresses'] = '';
                    $data['giftCheques'] = '';
                    $data['points'] = 0;
                    $data['ErrorCode'] = '';
                    $data['Message'] = '';
                    $data['UserFriendly'] = true;
                    $data['giftCheques'] = array();
                    $sql_child = 'SELECT
                                    ads.company,
                                    ads.id_address,
                                    ads.alias,
                                    ads.lastname,
                                    ads.firstname,
                                    ads.address1,
                                    ads.address2,
                                    ads.postcode,
                                    ads.city,
                                    ads.other,
                                    ads.phone,
                                    ads.phone_mobile,
                                    s.id_state,
                                    s.name,
                                    c.id_country,c.name
                                    FROM '._DB_PREFIX_.'address AS ads
                                    LEFT JOIN '._DB_PREFIX_.'country_lang AS c ON c.id_country=ads.id_country
                                    LEFT JOIN '._DB_PREFIX_."state AS s ON s.id_state=ads.id_state
                                    WHERE ads.id_customer='".pSQL($row['id_customer'])."' GROUP BY ads.id_address";
                    $result_childs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql_child);
                    $child_data = array();
                    if (Db::getInstance()->NumRows() > 0) {
                        $j = 0;
                        foreach ($result_childs as $child_row) {
                            $child_data['shipping'][$j]['id'] = $child_row['id_address'];
                            $child_data['shipping'][$j]['addressName'] = $child_row['alias'];
                            $child_data['shipping'][$j]['name'] = $child_row['firstname'].' '.$child_row['lastname'];
                            $child_data['shipping'][$j]['surname'] = '';
                            $child_data['shipping'][$j]['email'] = '';
                            $child_data['shipping'][$j]['addressLine'] = $child_row['address1'];
                            $child_data['shipping'][$j]['country'] = $child_row['name'];
                            $child_data['shipping'][$j]['countryCode'] = $child_row['id_country'];
                            $child_data['shipping'][$j]['state'] = $child_row['name'];
                            $child_data['shipping'][$j]['stateCode'] = $child_row['id_state'];
                            $child_data['shipping'][$j]['city'] = $child_row['city'];
                            $child_data['shipping'][$j]['cityCode'] = '';
                            $child_data['shipping'][$j]['district'] = '';
                            $child_data['shipping'][$j]['districtCode'] = '';
                            $child_data['shipping'][$j]['town'] = '';
                            $child_data['shipping'][$j]['townCode'] = '';
                            $child_data['shipping'][$j]['corporate'] = '';
                            $child_data['shipping'][$j]['companyTitle'] = $child_row['company'];
                            $child_data['shipping'][$j]['taxDepartment'] = '';
                            $child_data['shipping'][$j]['taxNo'] = '';
                            $child_data['shipping'][$j]['phoneNumber'] = $child_row['phone'];
                            $child_data['shipping'][$j]['identityNo'] = '';
                            $child_data['shipping'][$j]['zipCode'] = $child_row['postcode'];
                            $child_data['shipping'][$j]['usCheckoutCity'] = '';
                            $child_data['shipping'][$j]['ErrorCode'] = '';
                            $child_data['shipping'][$j]['Message'] = '';
                            $child_data['shipping'][$j]['UserFriendly'] = true;
                            ++$j;
                        }
                        $data['addresses'] = $child_data;
                    } else {
                        $data['addresses'] = '';
                    }
                    ++$i;
                }
            } else {
                $data['accessToken'] = '';
                $data['fullName'] = '';
                $data['firstName'] = '';
                $data['lastName'] = '';
                $data['gender'] = 0;
                $data['IsSubscribe'] = 0;
                $data['birthDate'] = '';
                $data['accept'] = false;
                $data['email'] = '';
                $data['phone'] = '';
                $data['password'] = '';
                $data['addresses'] = '';
                $data['giftCheques'] = '';
                $data['points'] = 0;
                $data['ErrorCode'] = '401';
                $data['Message'] = 'Email and password not match';
                $data['UserFriendly'] = true;
            }
        } else {
            $data['accessToken'] = '';
            $data['fullName'] = '';
            $data['firstName'] = '';
            $data['lastName'] = '';
            $data['gender'] = 0;
            $data['IsSubscribe'] = 0;
            $data['birthDate'] = '';
            $data['accept'] = false;
            $data['email'] = '';
            $data['phone'] = '';
            $data['password'] = '';
            $data['addresses'] = '';
            $data['giftCheques'] = '';
            $data['points'] = 0;
            $data['ErrorCode'] = '401';
            $data['Message'] = 'Email and password not match';
            $data['UserFriendly'] = true;
        }

        return $data;
    }
}
