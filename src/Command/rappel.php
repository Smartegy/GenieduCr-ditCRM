

<?php 
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the "name" and "description" arguments of AsCommand replace the
// static $defaultName and $defaultDescription properties
#[AsCommand(
    name: 'app:create-rappel',
    description: 'excuter un rappel.',
    hidden: false,
    aliases: ['app:add-rappel']
)]
class CreateRappelCommand extends Command
{
  
}


/*
public function execute(InputInterface $input, OutputInterface $output): int
{
    // outputs multiple lines to the console (adding "\n" at the end of each line)s
    $output->writeln([
        'User Creator',
        '============',
        '',
    ]);

    // the value returned by someMethod() can be an iterator (https://secure.php.net/iterator)
    // that generates and returns the messages with the 'yield' PHP keyword
    $output->writeln($this->someMethod());

    // outputs a message followed by a "\n"
    $output->writeln('Whoa!');

    // outputs a message without adding a "\n" at the end of the line
    $output->write('You are about to ');
    $output->write('create a user.');

    return Command::SUCCESS;
}*/
?>