<?php
namespace Psdtg\SiteBundle\Command;

use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\SiteBundle\Entity\TelephoneLine;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTelephoneLinesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('psdtg:createtelephonelines')
            ->setDescription('Create telephone lines based on approved requests')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting CreateTelephoneLines process');
        $this->container = $this->getContainer();
        $em = $this->container->get('doctrine')->getEntityManager();
        $approvedNewLineRequests = $em->getRepository('Psdtg\SiteBundle\Entity\Requests\Request')->findBy(array(
            'status' => Request::STATUS_APPROVED,
            'line' => null,
        ));
        foreach($approvedNewLineRequests as $curRequest) {
            $line = new TelephoneLine();
            $line->setYpepthId($curRequest->getYpepthId());
            $line->setAddress('123Address');
            $line->setLineType($curRequest->getLineType());
            $curRequest->setLine($line);
            $em->persist($line);
            $em->persist($curRequest);
            $em->flush();
            $output->writeln('Created telephone line for request: '.$curRequest->getId());
        }
    }
}