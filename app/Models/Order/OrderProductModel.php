<?php

namespace App\Models\Order;

use App\Models\External\ProductModel;

/**
 * Class OrderProductModel
 * @package App\Models
 */
class OrderProductModel extends AbstractOrderItem
{
    /** @var ProductModel $product */
    private $product;

    /**
     * @return ProductModel
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ProductModel $product
     *
     * @return OrderProductModel
     */
    public function setProduct(ProductModel $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $orderItem = parent::toArray();

        return array_merge(['product-id' => $this->getProduct()->getId()], $orderItem);
    }
}
