<?php

namespace Pgrela\RatelPHP;

use HessianOptions;

class ServiceDiscovery
{
    const DS = '/';
    const SERVICES_ROOT = '/services';
    private $services;
    private $zookeeper;
    private $hessianOptions;


    public function __construct($zkHost, $typeMap = array())
    {
        $this->zookeeper = new \Zookeeper($zkHost);
        $this->services = $this->zookeeper->getChildren(self::SERVICES_ROOT);

        $this->hessianOptions = new HessianOptions();
        $this->hessianOptions->typeMap = $typeMap;
    }

    public function getClient($interface)
    {
        if (in_array($interface, $this->services)) {
            $interfacePath = self::SERVICES_ROOT . self::DS . $interface;
            $instances = $this->zookeeper->getChildren($interfacePath);
            $instance = $instances[rand(0, count($instances) - 1)];
            $host = json_decode($this->zookeeper->get($interfacePath . self::DS . $instance));
            return new \HessianClient($host->address, $this->hessianOptions);
        }
    }
}