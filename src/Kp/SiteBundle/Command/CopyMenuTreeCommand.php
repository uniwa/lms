<?php
namespace Kp\SiteBundle\Command;

use Kp\SiteBundle\Entity\MenuItem2;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use EWZ\Bundle\SearchBundle\Lucene\LuceneSearch;

class CopyMenuTreeCommand extends ContainerAwareCommand
{
    protected function configure()
    {

        $this
            ->setName('kp:copymenutree')
            ->setDescription('Copy menu tree to a new table')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting BuildIndex process');
        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
        $treeRepo = $this->em->getRepository('KpSiteBundle:MenuItem');
        $root = $treeRepo->getRootNodes();

        // Create new root
        /*$newitem = new MenuItem2();
        $newitem->setName('== Root ==');
        $newitem->setSearchResultsPage(false);
        $newitem->setPage('');
        $newitem->setIsTag('false');
        $newitem->setLocale('en');
        $this->em->persist($newitem);
        $this->em->flush();*/

        $newroot = $this->em->getRepository('KpSiteBundle:MenuItem2')->find(1);
        $this->copyMenuItemsRecursive($root[0], $newroot);
    }

    protected function copyMenuItemsRecursive($root, $newroot) {
        foreach($root->getChildren() as $curItem) {
            $newitem = new MenuItem2();
            $newitem->setName($curItem->getName());
            $newitem->setSearchResultsPage($curItem->getSearchResultsPage());
            $newitem->setPage($curItem->getPage());
            $newitem->setIsTag($curItem->getIsTag());
            $newitem->setLocale($curItem->getLocale());
            $newitem->setParent($newroot);
            $this->em->persist($newitem);
            $this->em->flush();
            if($curItem->getChildren()->count() > 0) {
                $this->copyMenuItemsRecursive($curItem, $newitem);
            }
        }
    }
}