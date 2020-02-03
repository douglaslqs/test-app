<?php

/**
*
*/
namespace Administrator\Controller\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Administrator\Service\ApiRequestService;

class UserFactory implements FactoryInterface
{

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
	    $controller = new $requestedName($container->get(ApiRequestService::class));
	    return $controller;
	}
}