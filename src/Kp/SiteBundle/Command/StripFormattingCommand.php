<?php
namespace Kp\SiteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StripFormattingCommand extends ContainerAwareCommand
{
    protected function configure()
    {

        $this
            ->setName('kp:stripformatting')
            ->setDescription('Strip fomratting from page content')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting StripFormatting process');
        $this->container = $this->getContainer();
        $em = $this->container->get('doctrine')->getEntityManager();
        $pages = $em->getRepository('KpSiteBundle:BasePage')->findAll();
        foreach($pages as &$curPage) {
            $content = $curPage->getContent();
            $content = strip_tags($content, '<p><a><div><h1><h2><h3><h4><h5><strong><em><img>');
            $content = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $content);
            echo 'Cleaned '.$curPage->getId()."\n";
            $curPage->setContent($content);
            $em->persist($curPage);
            $em->flush();
        }
        $output->writeln('Format stripping completed successfully');
    }
}