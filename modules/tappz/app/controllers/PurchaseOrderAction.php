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

class PurchaseOrderAction extends BaseAction
{
    public function index()
    {
        $customerId = $this->getAuthorization();
        $orderService = new  OrderService();
        $result = $orderService->getPurchaseOrder($customerId);
        $orderService->response($result);
    }

    public function show($id)
    {
        $orderService = new  OrderService();
        $result = $orderService->getPurchaseOrderDetail($id);
        $orderService->response($result);
    }
}
