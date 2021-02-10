<?php

namespace App\Tests\Command;

use App\Command\CustomerImportCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Tests for @see CustomerImportCommand
 */
class CustomerImportCommandTest extends KernelTestCase
{
    /**
     * @covers CustomerImportCommand::execute
     */
    public function testExecute(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find(CustomerImportCommand::getDefaultName());
        $commandTester = new CommandTester($command);

        $outputCases = [
            'null' => 'Count: 100',
            '1000' => 'Count: 1000',
            '1000.90' => 'This value is not valid.',
            'any' => 'This value is not valid.',
            '0' => 'This value should be positive.',
        ];
        foreach ($outputCases as $arg => $expected) {
            $commandArgs = []; // run command without arguments
            if ($arg !== 'null') {
                $commandArgs = [
                    CustomerImportCommand::ARG_NAME_COUNT => $arg,
                ];
            }
            $commandTester->execute($commandArgs);
            $output = $commandTester->getDisplay();
            $this->assertStringContainsStringIgnoringCase($expected, $output, 'Case with $arg=' . $arg . ' is wrong.');
        }
    }
}