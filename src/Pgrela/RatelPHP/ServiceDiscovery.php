<?php

namespace Pgrela\RatelPHP;


require './../../../vendor/autoload.php';

class ServiceDiscovery {
    const DS = '/';
    const SERVICES_ROOT = '/services';
    private $services;
    private $zookeeper;


    public function __construct($zkHost){
        $this->zookeeper = new \Zookeeper($zkHost);
        $this->services = $this->zookeeper->getChildren(self::SERVICES_ROOT);
    }
    public function getClient($interface){
        if(in_array($interface,$this->services)) {
            $interfacePath = self::SERVICES_ROOT . self::DS . $interface;
            $instances = $this->zookeeper->getChildren($interfacePath);
            $instance = $instances[rand(0,count($instances)-1)];
            $host = json_decode($this->zookeeper->get($interfacePath.self::DS.$instance));
            return new \HessianClient($host->address);
        }
    }
}
$serviceDiscovery = new ServiceDiscovery('localhost:2185');
echo $serviceDiscovery->getClient('com.payu.discovery.tests.service.TestService')->hello();