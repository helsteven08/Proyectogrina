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

class BranchesService extends BaseService
{
    public function fillBranches()
    {
        $data = array();
        $data['branches'][] = array(
            'branchId' => 0,
            'title' => '',
            'address' => '',
            'description' => '',
            'imageUrl' => '',
            'shareUrl' => '',
            'latitude' => 0,
            'longitude' => 0,
            'workingHours' => '',
            'workingDays' => '',
            'distance' => '',
            'cityId' => 0,
            'cityName' => '',
            'phoneNumber' => '',
        );
        $data['ErrorCode'] = '';
        $data['Message'] = '';
        $data['UserFriendly'] = true;

        return $data;
    }

    public function fillBranchCities()
    {
        $data = array();
        $data[0]['cityId'] = 0;
        $data[0]['name'] = '';
        $data[0]['latitude'] = '';
        $data[0]['longitude'] = '';
        $data[0]['Message'] = '';
        $data[0]['UserFriendly'] = true;

        return $data;
    }
}
