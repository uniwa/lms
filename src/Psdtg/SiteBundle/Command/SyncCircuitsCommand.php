<?php
namespace Psdtg\SiteBundle\Command;

use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCircuitsCommand extends ContainerAwareCommand
{
    protected function configure()
    {

        $this
            ->setName('lms:synccircuits')
            ->setDescription('Sync circuits with MM')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting SyncCircuits process');
        $this->container = $this->getContainer();
        $em = $this->container->get('doctrine')->getManager();
        $mmservice = $this->container->get('psdtg.mm.service');
        $batchSize = 20;
        $i = 0;
        $q = $em->createQuery('select u from Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit pc');
        $iterableResult = $q->iterate();
        foreach($iterableResult AS $row) {
            $mmservice->persistMM($row);
            if (($i % $batchSize) == 0) {
                $em->flush();
                $em->clear();
            }
            ++$i;
        }

        $output->writeln('Circuits synced successfully');
    }
}