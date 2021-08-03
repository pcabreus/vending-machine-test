<?php

namespace App\Tests\IO;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class VendingMachineCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            // pass arguments to the helper
            'username' => 'Wouter',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Username: Wouter', $output);

        // ...
    }
}