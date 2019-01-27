<?php

namespace App\Contracts\Repositories;

use App\Exceptions\Product\ProductRepositoryException;
use App\Models\External\ProductModel;

interface ProductRepository
{
    /**
     * @param string $id
     *
     * @return ProductModel
     * @throws ProductRepositoryException
     */
    public function getProductById($id);
}
