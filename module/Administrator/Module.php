<?php
/**
 * @link      http://github.com/zendframework/Administrator for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administrator;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Administrator\Model\Entity\ClientEntity;
use Administrator\Model\ClientTable;

class Module
{
    const NAME_MODULE = 'administrator';

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        //ATTACHS
    	$app = $e->getApplication();
        $em = $app->getEventManager();

        $app->getEventManager()->attach(
            'dispatch',
            function($e) {
                $app = $e->getApplication();
	            $routeMatch = $e->getRouteMatch();
                $sessionUser = new \Zend\Session\Container('user');
                //LOCATION
                if (isset($sessionUser->locale)) {
                    $translator = $e->getApplication()->getServiceManager()->get(\Zend\I18n\Translator\TranslatorInterface::class);
                    $translator->setLocale($sessionUser->locale);
                }
                $viewModel = $e->getViewModel();
	            $viewModel->setVariable('controller', $routeMatch->getParam('controller'));
	            $viewModel->setVariable('action', $routeMatch->getParam('action'));
                $viewModel->setVariable('username', $sessionUser->offsetGet('name'));
	        },
	        -100
	    );
    }

    public function getServiceConfig()
    {
    	return array(
    		'factories' => array(
				'Administrator\Model\ClientTable' =>  function($sm) {
    				$tableGateway = $sm->get('ClientTableGateway');
                    $responseService = $sm->get(\Application\Service\ResponseService::class);
    				return new ClientTable($tableGateway, $responseService);
    			},
    			'ClientTableGateway' => function ($sm) {
    				$dbAdapter = $sm->get('client-adapter');
    				$resultSetPrototype = new ResultSet();
    				$resultSetPrototype->setArrayObjectPrototype(new ClientEntity());
    				return new TableGateway('client', $dbAdapter, null, $resultSetPrototype);
    			},
    		)
    	);
    }
}
