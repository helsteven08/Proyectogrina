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

class ProductAction extends BaseAction
{
    public function index()
    {
        if (empty(Tools::getValue('barcode'))) {
            false;
        }
        $productService = new ProductService();
        $result = $productService->getProductByBarcode((string) Tools::getValue('barcode'));
        if (sizeof($result) > 0) {
            $response = $productService->getProductById($result[0]['id_product']);
            $productService->response($response);
        }
    }

    public function show($id)
    {
        $productService = new ProductService();
        $response = $productService->getProductById($id);
        $productService->response($response);
    }

    public function relatedIndex($id)
    {
        $productService = new ProductService();
        $response = $productService->getproductRelated($id);
        $productService->response($response);
    }
}
