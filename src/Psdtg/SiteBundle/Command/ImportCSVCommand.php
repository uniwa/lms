<?php
namespace Psdtg\SiteBundle\Command;

use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;

class ImportCSVCommand extends ContainerAwareCommand
{
    protected function configure()
    {

        $this
            ->setName('lms:importcsv')
            ->setDescription('Import a CSV with line data')
            ;
    }

    private $cvsParsingOptions = array(
	'finder_in' => 'C:\Users\Niral\Desktop\psdtg\comments',
	'finder_name' => 'stoixeia_pros_dimos8eni.csv',
	'ignoreFirstLine' => true
    );

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting ImportCSV process');
        $this->container = $this->getContainer();
        $em = $this->container->get('doctrine')->getManager();
        $mmservice = $this->container->get('psdtg.mm.service');
        foreach($this->parseCSV() as $row) {
            $circuit = new PhoneCircuit();
            $fields = explode(',', $row[0]);
            try {
                $unit = $mmservice->findOneUnitBy(array('mm_id' => $fields[0]));
            } catch(\RunTimeException $e) {
                $output->writeln('Unit not found for: '.$fields[0]);
                continue;
            }
            $circuit->setUnit($unit);
            $circuit->setNumber($fields[3].$fields[4]);
            // Circuit type
            if($fields[5] == '') { $fields[5] = 'ADSL'; } // Handle blank fields
            $map = array(
                /*'ADSL',
                'ETHERNET',*/
                'ISDN' => 'isdn_dialup',
                'ISDN εκκρεμεί Μεταφορά' => 'isdn_dialup',
                'ISDN-ADSL' => 'isdn_adsl',
                'LL' => 'll',
                'PSTN' => 'pstn_dialup',
                'PSTN-ADSL' => 'pstn_adsl',
                /*'WIRELESS',
                'εκκρεμεί',
                'εκκρεμεί - ΕΛΛ ΧΩΝΕΥΤΗΣ',
                'ραντ. 25/4 εκκρεμεί',*/
            );
            $circuitType = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\CircuitType')->findOneBy(array(
                'name' => $map[$fields[5]]
            ));
            $circuit->setCircuitType($circuitType);
            // End circuit type
            $circuit->setBandwidth($fields[6]);
            //$requestedAt = $fields[7];
            if($fields[8] != "") {
                $circuit->setActivatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $fields[8]));
            }
            $circuit->setComments($fields[9]);
            $circuit->setPaidByPsd(true);
            $circuit->setCreatedBy('lmsadmin');
            $circuit->setUpdatedBy('lmsadmin');
            $em->persist($circuit);
            $em->flush($circuit);
        }

        $output->writeln('Lines imported successfully');
    }

    private function parseCSV()
    {
        $ignoreFirstLine = $this->cvsParsingOptions['ignoreFirstLine'];

        $finder = new Finder();
        $finder->files()
            ->in($this->cvsParsingOptions['finder_in'])
            ->name($this->cvsParsingOptions['finder_name'])
        ;
        foreach ($finder as $file) { $csv = $file; }

        $rows = array();
        if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                $i++;
                if ($ignoreFirstLine && $i == 1) { continue; }
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }
}