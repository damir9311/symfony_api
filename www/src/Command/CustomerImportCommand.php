<?php

namespace App\Command;

use App\Service\CustomerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * Import customers to DB
 */
class CustomerImportCommand extends Command
{
    public const ARG_NAME_COUNT = 'count';

    protected static $defaultName = 'app:customer:import';

    protected CustomerService $customerService;

    protected int $minCountToImport = 100;

    /**
     * CustomerImportCommand constructor.
     *
     * @param CustomerService $customerService
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Import customers to DB.')
            ->addArgument(self::ARG_NAME_COUNT, InputArgument::OPTIONAL, 'How many records should be stored.', $this->minCountToImport)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $input->getArgument(self::ARG_NAME_COUNT);

        $violations = $this->validateInput($count);
        if (0 !== count($violations)) {
            foreach ($violations as $violation) {
                $output->writeln('<error>' . $violation->getMessage() . '</error>');
            }

            return Command::FAILURE;
        }

        $this->customerService->importUsersToDatabase($count);
        $output->writeln('Count: ' . $count);

        return Command::SUCCESS;
    }

    /**
     * @param $input
     *
     * @return ConstraintViolationListInterface
     */
    protected function validateInput($input)
    {
        $validator = Validation::createValidator();
        return $validator->validate($input, [
            new Regex(['pattern' => '/^([0]{1}|[1-9]{1}[0-9]*)$/']),
            new Positive(),
        ]);
    }
}