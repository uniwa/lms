<?php
namespace Kp\SiteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use EWZ\Bundle\SearchBundle\Lucene\LuceneSearch;

class BuildIndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {

        $this
            ->setName('kp:buildindex')
            ->setDescription('Build search index')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting BuildIndex process');
        $this->container = $this->getContainer();
        $search = $this->container->get('ewz_search.lucene');
        // Delete all items from the index first
        $search = $this->container->get('ewz_search.lucene');
        $query = '+(saved:yes)';
        $results = $search->find($query);
        foreach($results as $curResult) {
            $search->getIndex()->delete($curResult);
        }
        // Add all pages to the index
        $em = $this->container->get('doctrine')->getEntityManager();
        $pages = $em->getRepository('KpSiteBundle:BasePage')->findAll();
        foreach($pages as &$curPage) {
            // Add to index
            if(strlen($curPage->getContent()) > 50) {
                $this->container->get('kp.page.listener.prepersist')->addToIndex($curPage);
            }
        }
        $search->updateIndex();
        $output->writeln('The search index was built successfully');
    }
}