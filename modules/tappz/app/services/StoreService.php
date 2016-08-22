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

class StoreService extends BaseService
{
    public $country;
    public $states;
    public $cities;
    public $districts;
    public $towns;
    public function getCountries()
    {
        $q = 'SELECT
	              c.id_country,
	              c.active,
	              cl. NAME as name,
	              cl.id_country,
	              cl.id_lang
                FROM
	           '._DB_PREFIX_.'country AS c
                LEFT JOIN  '._DB_PREFIX_.'country_lang AS cl ON c.id_country = cl.id_country
                WHERE
	                 c.active = 1
                AND
                     cl.id_lang ='.$this->getLangId();

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($q);
    }
    public function getStates($country_id)
    {
        $q = 'SELECT
                s.id_state ,
                s.name
              FROM '._DB_PREFIX_."state s
              WHERE s.id_country = '".pSQL($country_id)."'
              ORDER BY s.name ASC";

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($q);
    }
    public function getCities()
    {
    }

    public function getTowns()
    {
    }

    public function getDistricts()
    {
    }

    public function fillCountry($results)
    {
        if (sizeof($results) > 0) {
            $i = 0;
            foreach ($results as $row) {
                $this->country['countries'][$i]['code'] = $row['id_country'];
                $this->country['countries'][$i]['name'] = $row['name'];
                ++$i;
            }
            $this->country['ErrorCode'] = '';
            $this->country['Message'] = '';
            $this->country['UserFriendly'] = false;
        }
    }

    public function fillStates($results)
    {
        if (sizeof($results) > 0) {
            $i = 0;
            foreach ($results as $row) {
                $this->states['states'][$i]['code'] = $row['id_state'];
                $this->states['states'][$i]['name'] = $row['name'];
                ++$i;
            }
            $this->states['ErrorCode'] = '';
            $this->states['Message'] = '';
            $this->states['UserFriendly'] = false;
        } else {
            $this->states['states'] = array();
            $this->states['ErrorCode'] = '';
            $this->states['Message'] = '';
            $this->states['UserFriendly'] = false;
        }
    }

    public function fillCities($results)
    {
        if (sizeof($results) > 0) {
            $i = 0;
            foreach ($results as $row) {
                $code = (int) $row['id_city'] > 0 ? $row['id_city'] : $row['id_state'];
                $this->states['cities'][$i]['code'] = $code;
                $this->states['cities'][$i]['name'] = $row['name'];
                ++$i;
            }
            $this->states['ErrorCode'] = '';
            $this->states['Message'] = '';
            $this->states['UserFriendly'] = false;
        }
    }

    public function fillTowns($results)
    {
        if (sizeof($results) > 0) {
            $i = 0;
            foreach ($results as $row) {
                $this->states['countries'][$i]['code'] = $row['id_town'];
                $this->states['countries'][$i]['name'] = $row['name'];
                ++$i;
            }
            $this->states['ErrorCode'] = '';
            $this->states['Message'] = '';
            $this->states['UserFriendly'] = false;
        }
    }
    public function fillDistricts($results)
    {
        if (sizeof($results) > 0) {
            $i = 0;
             $this->districts['code'] = '';
            $this->districts['name'] = '';
            foreach ($results as $row) {
                $this->districts['districts'][$i]['code'] = $row['id_town'];
                $this->districts['districts'][$i]['name'] = $row['name'];
                ++$i;
            }
            $this->districts['ErrorCode'] = '';
            $this->districts['Message'] = '';
            $this->districts['UserFriendly'] = false;
        } else {
                      $this->districts['code'] = '';
            $this->districts['name'] = '';
            $this->districts['districts'][0] = array(
                "code" => "",
                "name" => ""
            );
            $this->districts['ErrorCode'] = '';
            $this->districts['Message'] = '';
            $this->districts['UserFriendly'] = false;
        }
    }
    public function getUserAgreement()
    {
        $result = Db::getInstance()->executeS('
			SELECT `user_agreement`
			FROM `'._DB_PREFIX_.'tappz_user_agreement`
			order by id_tappz_user_agreement  desc limit 1');

        return empty($result[0]['user_agreement']) ? $result[0]['user_agreement'] = '' : $result[0]['user_agreement'];
    }

    public function fillUserAgreement($agreementText, $ErrorCode = '', $Message = '', $UserFriendly = false)
    {
        return array(
            'agreementText' => $agreementText,
            'ErrorCode' => $ErrorCode,
            'Message' => $Message,
            'UserFriendly' => $UserFriendly,
        );
    }
}
