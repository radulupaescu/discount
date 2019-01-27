<?php

namespace App\Contracts;

use App\Services\JSONMapperService;

/**
 * Interface ApplicationRepository
 * @package App\Contracts
 */
interface ApplicationRepository
{
    /**
     * @return JSONMapperService
     */
    public function getMapper();

    /**
     * @param JSONMapperService $mapperService
     *
     * @return mixed
     */
    public function setMapper(JSONMapperService $mapperService);
}
