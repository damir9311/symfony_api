<?php

namespace App\Command;

use App\Service\CustomerService;
use App\Utils\EventBus\EventBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CustomerImportCommand extends Command
{
    protected static $defaultName = 'app:customer:import';

    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService) {
        $this->customerService = $customerService;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $d=0;

        return 1;
    }
}