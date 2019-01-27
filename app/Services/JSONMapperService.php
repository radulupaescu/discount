<?php

namespace App\Services;

use DavidHoeck\LaravelJsonMapper\JsonMapper;

/**
 * Class JSONMapperService
 * @package App\Services
 */
class JSONMapperService
{
    /** @var JsonMapper */
    private $mapper;

    /**
     * JSONMapperService constructor.
     */
    public function __construct()
    {
        $this->mapper = new JsonMapper;
    }

    /**
     * @return JsonMapper
     */
    public function getMapper()
    {
        return $this->mapper;
    }
}
