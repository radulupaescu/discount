<?php

namespace tests\unit\app\Services;

use App\Services\JSONMapperService;
use DavidHoeck\LaravelJsonMapper\JsonMapper;

class JSONMapperServiceTest extends \TestCase
{
    public function testCallingConstructInstantiatesMapperObject()
    {
        $service = new JSONMapperService;

        $mapper = $service->getMapper();

        self::assertInstanceOf(JsonMapper::class, $mapper);
    }
}
