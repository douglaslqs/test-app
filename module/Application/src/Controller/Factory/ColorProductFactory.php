<?php

/**
*
*/
namespace Application\Controller\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Application\Model\ColorProductTable;
use Application\Form\ColorProductForm;
use Application\Controller\controller;
use Application\Service\ResponseService;
use Application\Service\FilterService;
use Application\Service\LoggerService;

class ColorProductFactory implements FactoryInterface
{

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
	    $table = $container->get(ColorProductTable::class);
	    $responseService = $container->get(ResponseService::class);
	    $controller = new $requestedName($responseService, $table);
	    $controller->setLogger($container->get(LoggerService::class));
	    $controller->setFilterService($container->get(FilterService::class));
	    $controller->setForm(new ColorProductForm());
	    return $controller;
	}
}