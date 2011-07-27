<?php
namespace Midgard\ConnectionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitializeRepositoryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('midgard:connection:init');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Generate tables
        \midgard_storage::create_base_storage();

        // And update as necessary
        $re = new \ReflectionExtension('midgard2');
        $classes = $re->getClasses();
        foreach ($classes as $refclass)
        {
            $parent_class = $refclass->getParentClass();
            if (!$parent_class)
            {
                continue;
            }
            if ($parent_class->getName() != 'midgard_object')
            {
                continue;
            }
            $type = $refclass->getName();
            
            if (\midgard_storage::class_storage_exists($type))
            {
                // FIXME: Skip updates until http://trac.midgard-project.org/ticket/1426 is fixed
                continue;

                if (!\midgard_storage::update_class_storage($type))
                {
                    $output->writeLine('Could not update ' . $type . ' tables in test database');
                }
                continue;
            }
            if (!\midgard_storage::create_class_storage($type))
            {
                $output->writeLine('Could not create ' . $type . ' tables in test database');
            }
        }

    }
}
