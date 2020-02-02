<?php
namespace Administrator\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;

/**
 * The factory responsible for creating of authentication service.
 */
class AuthManagerFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service
     * and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authAdapter = $container->get(AuthenticationService::class);
        // Create the service and inject dependencies into its constructor.
        return new $requestedName($authAdapter);
    }
}