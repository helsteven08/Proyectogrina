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

class BasketAction extends BaseAction
{
    public function index()
    {
        $token = $this->getAuthorization();
        $BasketService = new BasketService();
        $result = $BasketService->getUserFirstBasket($token);
        $BasketService->response($result);
    }

    public function store()
    {
        $user_id = $this->getAuthorization();
        $BasketService = new BasketService();
        $result = $BasketService->getUserFirstBasket($user_id);
        $BasketService->response($result);
    }

    public function show($id)
    {
        $BasketService = new BasketService();
        $result = $BasketService->getUserBasket($id);
        $BasketService->response($result);
    }

    public function addressStore($id)
    {
        $user_id = $this->getAuthorization();
        $json = $this->getJson();
        $BasketService = new BasketService();
        $result = $BasketService->updateBasketAddress($json, $id, $user_id);
        $BasketService->response($result);
    }

    public function linesStore($id)
    {
        $json = $this->getJson();
        $basketService = new BasketService();
        $result = $basketService->updateBasket($json, $id);
        $basketService->response($result);
    }

    public function discountStore($id)
    {
        $user_id = $this->getAuthorization();
        $json = $this->getJson();
        $basketService = new BasketService();
        $result = $basketService->addBasketDiscount($json, $id, $user_id);
        $basketService->response($result);
    }

    public function contractIndex()
    {
        $basketService = new BasketService();
        $result = $basketService->getContractInfo();
        $basketService->response($result);
    }

    public function paymentStore($id)
    {
        $json = $this->getJson();
        $user_id = $this->getAuthorization();
        $paymentService = new PaymentService();
        $result = $paymentService->payBankWire($json, $id, $user_id);
        $paymentService->response($result);
    }

    public function purchaseStoreCashOnDelivery($id)
    {
        $json = $this->getJson();
        $user_id = $this->getAuthorization();
        $paymentService = new PaymentService();
        $result = $paymentService->payCashOnDelivery($json, $id, $user_id);
        $paymentService->response($result);
    }

    public function purchaseStorePaypal($id)
    {
        $json = $this->getJson();
        $user_id = $this->getAuthorization();
        $paymentService = new PaymentService();
        $result = $paymentService->purchaseWithPayPal($json, $id, $user_id);
        $paymentService->response($result);
    }
    public function purchaseStoreCard($id)
    {
        $json = $this->getJson();
        $user_id = $this->getAuthorization();
        $paymentService = new PaymentService();
        $result = $paymentService->purchaseWithCreditCard($json, $id, $user_id);
        $paymentService->response($result);
    }
    public function discountPost($id)
    {
        $json = $this->getJson();
        $basketService = new BasketService();
        $response = $basketService->addBasketDiscount($json, $id);
        $basketService->response($response);
    }

    public function discountDestroy()
    {
        $json = $this->getJson();
        $basketService = new BasketService();
        $response = $basketService->deleteBasketDiscount($json);
        $basketService->response($response);
    }
}
