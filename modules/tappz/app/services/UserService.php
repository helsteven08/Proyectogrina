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

class UserService extends BaseService
{
    public function getUserDetails($userid)
    {
        $sql = 'SELECT c.*, g.id_gender, g.name AS gender
	      		  FROM '._DB_PREFIX_.'customer c
	      		LEFT JOIN '._DB_PREFIX_."gender_lang AS g ON c.id_gender = g.id_gender
	      		WHERE c.id_customer='".pSQL($userid)."'";
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $arr = array();
        if (Db::getInstance()->NumRows() > 0) {
            foreach ($results as $v1) {
                $arr['User'] = $v1;
                $sql2 = 'SELECT a.*, s.id_state,s.name AS state_name,cl.id_lang,cl.id_country,cl.name AS country_name
                            FROM '._DB_PREFIX_.'address a
                          LEFT JOIN '._DB_PREFIX_.'country_lang cl ON a.id_country = cl.id_country
                          LEFT JOIN  '._DB_PREFIX_."state s ON s.id_state = a.id_state
                           WHERE a.id_customer='".pSQL($v1['id_customer'])."'";
                $customer_results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql2);
                $arr['User']['Address'] = $customer_results;
            }
        }
        $data = array();
        if (!empty($arr)) {
            foreach ($arr as $v2) {
                $data['accessToken'] = $v2['id_customer'];
                $data['fullName'] = $v2['firstname'].' '.$v2['lastname'];
                $data['firstName'] = $v2['firstname'];
                $data['lastName'] = $v2['lastname'];
                $data['gender'] = $v2['id_gender'];
                $data['IsSubscribe'] = false;
                $data['birthDate'] = str_replace('-', '', $v2['birthday']);
                $data['accept'] = true;
                $data['email'] = $v2['email'];
                $data['phone'] = '';
                $data['password'] = $v2['passwd'];
                $i = 0;
                if (!empty($v2['Address'])) {
                    foreach ($v2['Address'] as $v3) {
                        $data['addresses']['shipping'][$i]['id'] = $v3['id_address'];
                        $data['addresses']['shipping'][$i]['addressName'] = $v3['alias'];
                        $data['addresses']['shipping'][$i]['name'] = $v3['firstname'];
                        $data['addresses']['shipping'][$i]['surname'] = $v3['lastname'];
                        $data['addresses']['shipping'][$i]['email'] = '';
                        $data['addresses']['shipping'][$i]['addressLine'] = $v3['address1'].' '.$v3['address2'];
                        $data['addresses']['shipping'][$i]['country'] = $v3['country_name'];
                        $data['addresses']['shipping'][$i]['countryCode'] = $v3['id_country'];
                        $data['addresses']['shipping'][$i]['state'] = $v3['state_name'];
                        $data['addresses']['shipping'][$i]['stateCode'] = $v3['id_state'];
                        $data['addresses']['shipping'][$i]['city'] = $v3['city'];
                        $data['addresses']['shipping'][$i]['cityCode'] = '';
                        $data['addresses']['shipping'][$i]['district'] = '';
                        $data['addresses']['shipping'][$i]['districtCode'] = '';
                        $data['addresses']['shipping'][$i]['town'] = '';
                        $data['addresses']['shipping'][$i]['townCode'] = '';
                        $data['addresses']['shipping'][$i]['corporate'] = false;
                        $data['addresses']['shipping'][$i]['companyTitle'] = $v3['company'];
                        $data['addresses']['shipping'][$i]['taxDepartment'] = '';
                        $data['addresses']['shipping'][$i]['taxNo'] = '';
                        $data['addresses']['shipping'][$i]['phoneNumber'] = $v3['phone'];
                        $data['addresses']['shipping'][$i]['identityNo'] = '';
                        $data['addresses']['shipping'][$i]['zipCode'] = $v3['postcode'];
                        $data['addresses']['shipping'][$i]['usCheckoutCity'] = '';
                        $data['addresses']['shipping'][$i]['ErrorCode'] = '';
                        $data['addresses']['shipping'][$i]['Message'] = '';
                        $data['addresses']['shipping'][$i]['UserFriendly'] = false;
                        ++$i;
                    }
                    $data['giftCheques'] = null;
                } else {
                    $data['addresses']['shipping'] = null;
                    $data['giftCheques'] = null;
                }
                $data['points'] = 0;
                $data['ErrorCode'] = '';
                $data['Message'] = '';
                $data['UserFriendly'] = false;
            }
        }

        return $data;
    }

    public function addUser($input_xml)
    {
        $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
        if (isset($data_arr) && !empty($data_arr)) {
            $sqql = 'SELECT * FROM '._DB_PREFIX_."customer WHERE email = '".pSQL($data_arr['email'])."'";
            $chk_data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sqql);
            if (count($chk_data) > 0) {
                $msg = 'User Already registered.';
                $data_arr['accessToken'] = null;
            } else {
                $insert_sql = 'INSERT INTO '._DB_PREFIX_."customer SET
					firstname = '".pSQL($data_arr['firstName'])."',
					lastname = '".pSQL($data_arr['lastName'])."',
					birthday = '".pSQL($data_arr['birthDate'])."',
					id_gender = '".pSQL($data_arr['gender'])."',
					email = '".pSQL($data_arr['email'])."',
					passwd = '".pSQL(MD5(_COOKIE_KEY_.$data_arr['password']))."',
					id_lang = '".($this->getLangId())."',
					secure_key = '".pSQL(md5(uniqid(rand())))."',
					active = '1',
					date_add = '".date('Y-m-d h:i:s')."',
					date_upd = '".date('Y-m-d h:i:s')."'
				";
                Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($insert_sql);
                $lastInsertId = Db::getInstance()->Insert_ID();
                $data_arr['accessToken'] = $lastInsertId;
                if (isset($data_arr['addresses']['shipping']) && !empty($data_arr['addresses']['shipping'])) {
                    foreach ($data_arr['addresses']['shipping'] as $v1) {
                        $insert_sql2 = '
							INSERT INTO '._DB_PREFIX_."address SET
								id_country = '".pSQL($v1['countryCode'])."',
								id_customer = '".pSQL($lastInsertId)."',
								id_state = '".pSQL($v1['stateCode'])."',
								alias = '".pSQL($v1['addressName'])."',
								company = '".pSQL($v1['companyTitle'])."',
								lastname = '".pSQL($v1['surname'])."',
								firstname = '".pSQL($v1['name'])."',
								address1 = '".pSQL($v1['addressLine'])."',
								postcode = '".pSQL($v1['zipCode'])."',
								city = '".pSQL($v1['city'])."',
								phone = '".pSQL($v1['phoneNumber'])."',
								date_add = '".date('Y-m-d h:i:s')."',
								date_upd = '".date('Y-m-d h:i:s')."',
								active = '1'
						";
                        Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($insert_sql2);
                    }
                }
                $msg = 'New user registred';
            }
            $data_arr['Message'] = $msg;
        } else {
            $data_arr['accessToken'] = null;
            $data_arr['Message'] = 'Error on registration. request is empty';
            $data_arr['ErrorCode'] = '707.7';
        }

        return $data_arr;
    }

    public function updateUser($input_xml)
    {
        $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
        if (isset($data_arr) && !empty($data_arr)) {
            $sqql = 'SELECT * FROM '._DB_PREFIX_."customer WHERE email = '".pSQL($data_arr['email'])."'";
            $chk_data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sqql);
            $usr_id = $chk_data[0]['id_customer'];
            $update_sql = 'UPDATE '._DB_PREFIX_."customer SET
				firstname = '".pSQL($data_arr['firstName'])."',
				lastname = '".pSQL($data_arr['lastName'])."',
				birthday = '".pSQL($data_arr['birthDate'])."',
				id_gender = '".pSQL($data_arr['gender'])."',
				email = '".pSQL($data_arr['email'])."',
				id_lang = '".$this->getLangId()."',
				secure_key = '".pSQL(md5(uniqid(rand())))."',
				active = '1',
				date_add = '".date('Y-m-d h:i:s')."',
				date_upd = '".date('Y-m-d h:i:s')."'
				WHERE id_customer = '".$usr_id."'
			";
            Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($update_sql);
            if (isset($data_arr['addresses']['shipping']) && !empty($data_arr['addresses']['shipping'])) {
                foreach ($data_arr['addresses']['shipping'] as $v1) {
                    $update_sql2 = '
						UPDATE '._DB_PREFIX_."address SET
							id_country = '".pSQL($v1['countryCode'])."',
							id_customer = '".pSQL($usr_id)."',
							id_state = '".pSQL($v1['stateCode'])."',
							alias = '".pSQL($v1['addressName'])."',
							company = '".pSQL($v1['companyTitle'])."',
							lastname = '".pSQL($v1['surname'])."',
							firstname = '".pSQL($v1['name'])."',
							address1 = '".pSQL($v1['addressLine'])."',
							postcode = '".pSQL($v1['zipCode'])."',
							city = '".pSQL($v1['city'])."',
							phone = '".pSQL($v1['phoneNumber'])."',
							date_add = '".date('Y-m-d h:i:s')."',
							date_upd = '".date('Y-m-d h:i:s')."',
							active = '1'
							WHERE id_address = '".pSQL($v1['id'])."'
					";
                    Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($update_sql2);
                    $msg = 'Profile updated';
                }
            }
            $data_arr['accessToken'] = $usr_id;
            $data_arr['Message'] = $msg;
        } else {
            $data_arr['accessToken'] = null;
            $data_arr['Message'] = 'Error on registration. request is empty';
            $data_arr['ErrorCode'] = '707.7';
        }

        return $data_arr;
    }

    public function updateAddress($input_xml)
    {
        $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';

        if (isset($data_arr['id']) && $data_arr['id'] != '') {
            $insert_sql = 'UPDATE '._DB_PREFIX_."address SET
		                                            id_country='".pSQL($data_arr['countryCode'])."',
													id_state='".pSQL($data_arr['stateCode'])."',
													alias='".pSQL($data_arr['addressName'])."',
													company='".pSQL($data_arr['companyTitle'])."',
													lastname='".pSQL($data_arr['surname'])."',
													firstname='".pSQL($data_arr['name'])."',
													address1='".pSQL($data_arr['addressLine'])."',
													postcode='".pSQL($data_arr['zipCode'])."',
													city='".pSQL($data_arr['city'])."',
													phone='".pSQL($data_arr['phoneNumber'])."',
													date_upd='".date('Y-m-d H:i:s')."'
													 WHERE id_address='".$data_arr['id']."'";
            Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($insert_sql);
            $msg = 'Address updated successfully';
        } else {
            $msg = 'Invalid request';
        }
        $data_arr['Message'] = $msg;

        return $data_arr;
    }

    public function deleteUserAddress($input_xml)
    {
        $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
        if (isset($data_arr['id']) && $data_arr['id'] != '') {
            $insert_sql = 'DELETE FROM '._DB_PREFIX_."address WHERE id_address='".pSQL($data_arr['id'])."'";
            Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($insert_sql);
            $msg = 'Address deleted successfully';
        } else {
            $msg = 'Invalid request';
        }
        $data_arr['Message'] = $msg;

        return $data_arr;
    }

    public function addUserAddress($input_xml)
    {
        $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
        $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        $request_header_str = (isset($authorization) && $authorization != '') ? $authorization : '';
        $user_id = @end(@explode(' ', $request_header_str));
        if (isset($user_id) && $user_id != '') {
            $sql = 'SELECT e.id_customer FROM '._DB_PREFIX_."customer AS e
             WHERE e.id_lang='".$this->getLangId()."' AND e.id_customer='".pSQL($user_id)."'";
            $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if (count($results) > 0) {
                $customer_id = (isset($results[0]['id_customer'])) ? $results[0]['id_customer'] : 0;
                $insert_sql = 'INSERT INTO '._DB_PREFIX_."address SET
		                                            id_country='".pSQL($data_arr['countryCode'])."',
													id_state='".pSQL($data_arr['stateCode'])."',
													id_customer='".pSQL($customer_id)."',
													alias='".pSQL($data_arr['addressName'])."',
													company='".pSQL($data_arr['companyTitle'])."',
													lastname='".pSQL($data_arr['surname'])."',
													firstname='".pSQL($data_arr['name'])."',
													address1='".pSQL($data_arr['addressLine'])."',
													postcode='".pSQL($data_arr['zipCode'])."',
													city='".pSQL($data_arr['city'])."',
													phone='".pSQL($data_arr['phoneNumber'])."',
													date_add='".date('Y-m-d H:i:s')."',
													date_upd='".date('Y-m-d H:i:s')."',
													active='1',
													deleted='0'
		   ";
                Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute($insert_sql);
                $msg = 'Address added successfully';
                $lastInsertId = Db::getInstance()->Insert_ID();
                $data_arr['id'] = $lastInsertId;
            } else {
                $msg = 'Customer is not valid';
                $data_arr['id'] = null;
            }
        } else {
            $msg = 'Invalid request';
            $data_arr['id'] = null;
        }
        $data_arr['Message'] = $msg;

        return $data_arr;
    }

    public function updateUserPassword($input_xml)
    {
        if (!empty($input_xml)) {
            $data_arr = (isset($input_xml) && $input_xml != '') ? Tools::jsonDecode($input_xml, true) : '';
            $data = array();
            if (isset($data_arr['email']) && !empty($data_arr['email'])) {
                $email = $data_arr['email'];
                $context = Context::getContext();
                $customer = new Customer();
                $customer->getByemail($email);
                ($min_time = (int) Configuration::get('PS_PASSWD_TIME_FRONT'));
                $timer = (strtotime($customer->last_passwd_gen.'+'.$min_time.' minutes') - time());
                if (!Validate::isLoadedObject($customer)) {
                    $data['email'] = $data_arr['email'];
                    $data['userMessage'] = 'There is no account registered for this email address.';
                    $data['ErrorCode'] = '403';
                    $data['Message'] = 'There is no account registered for this email address.';
                    $data['UserFriendly'] = true;
                } elseif (!$customer->active) {
                    $data['email'] = $data_arr['email'];
                    $data['userMessage'] = 'You cannot regenerate the password for this account.';
                    $data['ErrorCode'] = '403';
                    $data['Message'] = 'You cannot regenerate the password for this account.';
                    $data['UserFriendly'] = true;
                } elseif ($timer > 0) {
                    $data['email'] = $data_arr['email'];
                    $data['userMessage'] = '';
                    $data['ErrorCode'] = '403';
                    $message = "You can regenerate your password only every %d minute(s)";
                    $data['Message'] = sprintf("$message", (int) $min_time);
                    $data['UserFriendly'] = true;
                } else {
                    $mail_params = array(
                        '{email}' => $customer->email,
                        '{lastname}' => $customer->lastname,
                        '{firstname}' => $customer->firstname,
                        '{url}' => $context->link->getPageLink(
                            'password',
                            true,
                            null,
                            'token='.$customer->secure_key.'&id_customer='.(int) $customer->id
                        ),
                    );
                    if (Mail::Send(
                        $context->language->id,
                        'password_query',
                        Mail::l('Password query confirmation'),
                        $mail_params,
                        $customer->email,
                        $customer->firstname.' '.$customer->lastname
                    )) {
                        $data['email'] = $data_arr['email'];
                        $data['userMessage'] = 'Your password send successfully. check your mail box.';
                        $data['ErrorCode'] = 'An error occurred while sending the email';
                        $data['Message'] = 'Your password send successfully. check your mail box.';
                        $data['UserFriendly'] = true;
                    } else {
                        $data['email'] = $data_arr['email'];
                        $data['userMessage'] = 'An error occurred while sending the email';
                        $data['ErrorCode'] = '403';
                        $data['Message'] = 'An error occurred while sending the email';
                        $data['UserFriendly'] = true;
                    }
                }
            } else {
                $data['Message'] = 'An error has occurred.';
            }
        } else {
            $data['Message'] = 'An error has occurred.';
        }

        return $data;
    }
}
