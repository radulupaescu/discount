<?php

namespace tests\unit\app\Models\External;

use App\Models\External\CustomerModel;

class CustomerModelTest extends \TestCase
{
    public function testGettersAndSettersSetDataCorrectly()
    {
        $mockCustomerData = [
            'id'      => 1,
            'name'    => 'customer',
            'since'   => new \DateTime('2004-04-24'),
            'revenue' => 2004
        ];

        $customer = new CustomerModel;

        $customer->setId($mockCustomerData['id'])
            ->setName($mockCustomerData['name'])
            ->setSince($mockCustomerData['since'])
            ->setRevenue($mockCustomerData['revenue']);

        self::assertEquals($mockCustomerData['id'], $customer->getId());
        self::assertEquals($mockCustomerData['name'], $customer->getName());
        self::assertEquals($mockCustomerData['since'], $customer->getSince());
        self::assertEquals($mockCustomerData['revenue'], $customer->getRevenue());
    }
}
