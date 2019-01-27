<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepository;
use App\Exceptions\Product\ProductRepositoryException;
use App\Models\External\ProductModel;

/**
 * Class ProductService
 * @package App\Services
 *
 * @property ProductRepository $repository
 */
class ProductService extends AbstractDatasourceService
{
    /**
     * @param mixed $id
     *
     * @return ProductModel
     * @throws ProductRepositoryException
     */
    public function getProductById($id)
    {
        return $this->repository->getProductById($id);
    }
}
