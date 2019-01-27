<?php

namespace tests\unit\app\Models\External;

use App\Models\External\ProductModel;

class ProductModelTest extends \TestCase
{
    public function testGettersAndSettersSetDataCorrectly()
    {
        $mockProductData = [
            'id'          => 'some-product-id',
            'description' => 'This is the mock product we\'re using.',
            'category'    => 1,
            'price'       => 20.04
        ];

        $product = new ProductModel;

        $product->setId($mockProductData['id'])
            ->setDescription($mockProductData['description'])
            ->setCategory($mockProductData['category'])
            ->setPrice($mockProductData['price']);

        self::assertEquals($mockProductData['id'], $product->getId());
        self::assertEquals($mockProductData['description'], $product->getDescription());
        self::assertEquals($mockProductData['category'], $product->getCategory());
        self::assertEquals($mockProductData['price'], $product->getPrice());
    }
}
