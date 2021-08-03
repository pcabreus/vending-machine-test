<?php

namespace App\IO;

use App\Domain\Service\ProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class VendingMachineCommand extends Command
{
    protected static $defaultName = 'app:run';
    private ProcessorInterface $processor;
    private Reader $reader;
    private Writer $writer;

    public function __construct(ProcessorInterface $processor, Reader $reader, Writer $writer, string $name = null)
    {
        parent::__construct($name);
        $this->processor = $processor;
        $this->reader = $reader;
        $this->writer = $writer;
    }


    protected function configure(): void
    {
        $this
            ->setDescription('Run the vending machine');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $writer = $this->writer;
        $writer->withOutput($output);

        // On the vending machine. It came with some items and some money
        $this->processor->on();
        $writer->writePresentation();
        $writer->writeState();

        //Listening to operation
        $helper = $this->getHelper('question');
        $question = new Question(
            "Place your order or type `help` to see all options:\n"
        );

        while (true) {
            if (!$result = $helper->ask($input, $output, $question)) {
                continue;
            }

            if ('exit' === $result) {
                return Command::SUCCESS;
            }

            if ('help' === $result) {
                $writer->writeHelp();
                continue;
            }

            $this->reader->read($result);
        }
    }
}