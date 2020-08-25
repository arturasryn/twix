<?php

namespace App\Facade;


use Symfony\Component\DependencyInjection\ContainerInterface;

class Facade
{
    private static $instance = null;
    private static $container;

    private function __construct(ContainerInterface $container)
    {
        self::$container = $container;
    }

    public static function get($serviceId)
    {
        if (null === self::$instance) {
            throw new \Exception("Facade is not instantiated");
        }

        return self::$container->get($serviceId);
    }

    public static function init(ContainerInterface $container)
    {
        if (null === self::$instance) {
            self::$instance = new self($container);
        }

        return self::$container;
    }
}