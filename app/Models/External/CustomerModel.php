<?php

namespace App\Models\External;

/**
 * Class CustomerModel
 * @package App\Models\External
 */
class CustomerModel
{
    /** @var int $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var \DateTime $since */
    private $since;

    /** @var float $revenue */
    private $revenue;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return CustomerModel
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return CustomerModel
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSince()
    {
        return $this->since;
    }

    /**
     * @param \DateTime $since
     *
     * @return CustomerModel
     */
    public function setSince(\DateTime $since)
    {
        $this->since = $since;

        return $this;
    }

    /**
     * @return float
     */
    public function getRevenue()
    {
        return $this->revenue;
    }

    /**
     * @param float $revenue
     *
     * @return CustomerModel
     */
    public function setRevenue(float $revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }
}
