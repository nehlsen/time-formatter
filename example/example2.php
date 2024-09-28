<?php declare(strict_types=1);

namespace App\Command;

use nehlsen\TimeFormatterBundle\TimeFormatter\TimeFormatter;
use nehlsen\TimeFormatterBundle\TimeFormatter\TimeUnit;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'time-formatter-example', description: 'Test command to provide some examples from the time-formatter')]
class TimeFormatterExample extends Command
{
    public function __construct(
        private readonly TimeFormatter $timeFormatter,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dateOfMyEvent = new \DateTimeImmutable('next week 12:13:37');
        $secondsUntilEvent = $dateOfMyEvent->getTimestamp() - time();

        $output->writeln('TimeFormatter Example');
        $output->writeln(sprintf('Date: %s', $dateOfMyEvent->format(DATE_ATOM)));
        $output->writeln('remaining time:');
        $output->writeln('');

        // 2 Days 3 Hours 33 Minutes 48 Seconds
        $output->writeln($this->timeFormatter->format($secondsUntilEvent));

        // 2d 3h 33m 48s
        $output->writeln($this->timeFormatter->format($secondsUntilEvent, ['abbreviate' => true]));

        // 2d 3h
        $output->writeln($this->timeFormatter->format($secondsUntilEvent, ['abbreviate' => true, 'significant' => 2]));

        // 2.1 Days
        $output->writeln($this->timeFormatter->format($secondsUntilEvent, ['fraction' => true]));

        // 51.6 Hours
        $output->writeln($this->timeFormatter->format($secondsUntilEvent, ['fraction' => true, 'fixed_unit' => TimeUnit::HOURS]));

        return Command::SUCCESS;
    }
}
