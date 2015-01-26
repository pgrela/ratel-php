<?php


namespace Pgrela\RatelPHP;


class ServiceDiscoveryTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $serviceDiscovery = new ServiceDiscovery('localhost:2185');
        echo $serviceDiscovery->getClient('com.payu.discovery.tests.service.TestService')->hello();
    }
}
