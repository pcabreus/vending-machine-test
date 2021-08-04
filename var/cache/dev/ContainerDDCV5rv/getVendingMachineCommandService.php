<?php

namespace ContainerDDCV5rv;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getVendingMachineCommandService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'App\IO\VendingMachineCommand' shared autowired service.
     *
     * @return \App\IO\VendingMachineCommand
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/console/Command/Command.php';
        include_once \dirname(__DIR__, 4).'/src/IO/VendingMachineCommand.php';
        include_once \dirname(__DIR__, 4).'/src/IO/Writer.php';
        include_once \dirname(__DIR__, 4).'/src/Domain/Service/ProcessorInterface.php';
        include_once \dirname(__DIR__, 4).'/src/Domain/Service/VendingMachine.php';

        $a = ($container->privates['App\\Domain\\Service\\VendingMachineProcessor'] ?? ($container->privates['App\\Domain\\Service\\VendingMachineProcessor'] = new \App\Domain\Service\VendingMachineProcessor()));

        $container->privates['App\\IO\\VendingMachineCommand'] = $instance = new \App\IO\VendingMachineCommand($a, ($container->services['messenger.default_bus'] ?? $container->load('getMessenger_DefaultBusService')), new \App\IO\Writer($a));

        $instance->setName('app:run');

        return $instance;
    }
}
