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

class MergeBasketAction extends BaseAction
{
    public function store()
    {
        $userId = $this->getAuthorization();
        $json = $this->getJson();
        $basketService = new BasketService();
        $result = $basketService->mergeBasket($userId, $json);
        $basketService->response($result);
    }
}
