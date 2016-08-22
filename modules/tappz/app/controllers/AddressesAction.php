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

class AddressesAction extends BaseAction
{
    public function index()
    {
    }

    public function store()
    {
        $json = $this->getJson();
        $userService = new UserService();
        $result = $userService->addUserAddress($json);
        $userService->response($result);
    }

    public function update()
    {
        $json = $this->getJson();
        $userService = new UserService();
        $result = $userService->updateAddress($json);
        $userService->response($result);
    }

    public function delete()
    {
        $json = $this->getJson();
        $userService = new UserService();
        $result = $userService->deleteUserAddress($json);
        $userService->response($result);
    }
}
