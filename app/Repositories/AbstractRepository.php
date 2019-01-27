<?php

namespace App\Repositories;

use App\Contracts\ApplicationRepository;
use App\Services\JSONMapperService;
use DavidHoeck\LaravelJsonMapper\JsonMapper;

/**
 * Class AbstractRepository
 * @package App\Repositories
 */
abstract class AbstractRepository implements ApplicationRepository
{
    /** @var JSONMapperService $mapperService */
    private $mapperService;

    /**
     * AbstractRepository constructor.
     *
     * @param JSONMapperService $mapperService
     */
    public function setMapper(JSONMapperService $mapperService)
    {
        $this->mapperService = $mapperService;
    }

    /**
     * @return JsonMapper
     */
    public function getMapper()
    {
        return $this->mapperService->getMapper();
    }
}
