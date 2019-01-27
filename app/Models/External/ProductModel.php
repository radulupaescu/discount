<?php

namespace App\Models\External;

/**
 * Class ProductModel
 * @package App\Models\External
 */
class ProductModel
{
    /** @var string $id */
    private $id;

    /** @var string $description */
    private $description;

    /** @var int $category */
    private $category;

    /** @var float $price */
    private $price;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return ProductModel
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return ProductModel
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int $category
     *
     * @return ProductModel
     */
    public function setCategory(int $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return ProductModel
     */
    public function setPrice(float $price)
    {
        $this->price = $price;

        return $this;
    }
}
