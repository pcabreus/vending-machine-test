<?php

namespace App\IO;

use App\Application\GetItem\GetItem;
use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Model\Item;
use App\Domain\Service\ProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class VendingMachineCommand extends Command
{
    protected static $defaultName = 'app:run';
    private ProcessorInterface $processor;
    private Writer $writer;
    private MessageBusInterface $bus;

    public function __construct(
        ProcessorInterface $processor,
        MessageBusInterface $bus,
        Writer $writer,
        string $name = null
    ) {
        parent::__construct($name);
        $this->processor = $processor;
        $this->writer = $writer;
        $this->bus = $bus;
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
        $writer->writeStatus();

        //Listening to operation
        $helper = $this->getHelper('question');
        $question = new Question(
            "<info>Place your order or type `help` to see all options:</info>\n"
        );

        while (true) {
            if (!$request = $helper->ask($input, $output, $question)) {
                continue;
            }

            if ('exit' === $request) {
                return Command::SUCCESS;
            }

            if ('help' === $request) {
                $writer->writeHelp();
                continue;
            }

            if ('STATUS' === $request) {
                $writer->writeStatus();
                continue;
            }

            [$action, $list] = $this->read($request);

            if ('RETURN-COIN' === $action) {
                $writer->writeResult($list);
                continue;
            }


            if (0 === strpos($action, 'GET-')) {
                $selector = substr($action, 4);

                if (!Item::isSelectorValid($selector)) {
                    $this->writer->writeError(new InvalidInputException(sprintf('Invalid item name: `%s`', $selector)));
                    continue;
                }

                try {
                    $envelope = $this->bus->dispatch(new GetItem($list, $selector));
                    $handledStamp = $envelope->last(HandledStamp::class);
                    /** @var CoinList $change */
                    $change = $handledStamp->getResult();
                    $writer->writeResult($change, $selector);
                } catch (HandlerFailedException $e) {
                    $writer->writeError($e->getPrevious());
                } catch (\Exception $e) {
                    $writer->writeError($e);
                }
                continue;
            }


            $writer->writeError(new InvalidInputException(sprintf('Invalid input: `%s`', $request)));
        }
    }

    public function read(string $input): array
    {
        $parts = explode(',', $input);
        $action = trim(array_pop($parts));
        $list = CoinList::create();
        foreach ($parts as $part) {
            $list->addCoin(Coin::create($part));
        }

        return [$action, $list];
    }


}