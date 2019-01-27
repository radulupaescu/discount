<?php

namespace App\Services;

use App\Contracts\ApplicationRepository;

abstract class AbstractDatasourceService
{
    /** @var ApplicationRepository $repository */
    protected $repository;

    /**
     * AbstractService constructor.
     *
     * @param ApplicationRepository $repository
     * @param JSONMapperService     $mapperService
     */
    public function __construct(ApplicationRepository $repository, JSONMapperService $mapperService)
    {
        $repository->setMapper($mapperService);
        $this->repository = $repository;
    }

    /**
     * @return ApplicationRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}
