<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepository as ProductRepositoryContract;
use App\Exceptions\Product\ProductRepositoryException;
use App\Models\External\ProductModel;
use DavidHoeck\LaravelJsonMapper\Exceptions\JsonMapperException;

/**
 * Class ProductRepository
 * @package App\Repositories
 */
class ProductRepository extends AbstractRepository implements ProductRepositoryContract
{
    private $products = [
        'A101' => '{"id":"A101","description":"Screwdriver","category":"1","price":"9.75"}',
        'A102' => '{"id":"A102","description":"Electric screwdriver","category":"1","price":"49.50"}',
        'B101' => '{"id":"B101","description":"Basic on-off switch","category":"2","price":"4.99"}',
        'B102' => '{"id":"B102","description":"Press button","category":"2","price":"4.99"}',
        'B103' => '{"id":"B103","description":"Switch with motion detector","category":"2","price":"12.95"}'
    ];

    /**
     * @param string $id
     *
     * @return ProductModel
     * @throws ProductRepositoryException
     */
    public function getProductById($id)
    {
        $fakeCURLresponse = json_decode($this->products[$id]);

        try {
            /** @var ProductModel $product */
            $product = $this->getMapper()->map($fakeCURLresponse, new ProductModel);
        } catch (JsonMapperException $e) {
            throw ProductRepositoryException::invalidProductId($id, $e);
        }

        return $product;
    }

    /**
     * @param array $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }
}
