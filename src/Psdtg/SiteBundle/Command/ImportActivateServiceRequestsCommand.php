<?php
namespace Psdtg\SiteBundle\Command;

use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Psdtg\SiteBundle\Entity\Requests\ActivateServiceRequest;
use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;

class ImportActivateServiceRequestsCommand extends ContainerAwareCommand
{
    protected function configure()
    {

        $this
            ->setName('lms:importactivateservicerequests')
            ->setDescription('Import a CSV with line data')
            ->addOption('file', null, InputOption::VALUE_REQUIRED, 'xls file to import from')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting ImportCSV process');
        $this->container = $this->getContainer();
        $em = $this->container->get('doctrine')->getManager();
        $mmservice = $this->container->get('psdtg.mm.service');
        $this->cvsParsingOptions = array(
            'ignoreFirstLine' => true
        );
        $xls = $this->parseCSV($input->getOption('file'));
        $headersRow = $xls->getRowIterator(1)->current();
        $headers = $this->parseHeadersToArray($headersRow);
        foreach ($xls->getRowIterator(2) as $row) {
            $fields = $this->parseRowToArray($row, $headers);
            $circuit = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit')->findOneBy(array(
                'number' => ($fields['conphone']),
            ));
            if(isset($circuit)) {
                $output->writeln('Skipping circuit: '.$circuit->getNumber());
            } else {
                $circuit = new PhoneCircuit();
                try {
                    $unit = $mmservice->findOneUnitBy(array('mm_id' => $fields['MM_id']));
                } catch(\RunTimeException $e) {
                    $output->writeln('Unit not found for: '.$fields['MM_id']);
                    continue;
                }
                $circuit->setUnit($unit);
                $circuit->setNumber($fields['conphone']);
                // Circuit type
                $map = array(
                    'ISDN ADSL' => 'isdn_adsl',
                    'PSTN ADSL' => 'pstn_adsl',
                );
                if(!isset($map[$fields['typos_grammis'].' ADSL'])) {
                    var_dump($fields);
                    $output->writeln('Circuit type '.$fields['typos_grammis'].' ADSL not found for : '.$fields['MM_id']);
                    continue;
                }
                $connectivityType = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\ConnectivityType')->findOneBy(array(
                    'name' => $map[$fields['typos_grammis'].' ADSL']
                ));
                if(!isset($connectivityType)) {
                    throw new \Exception('Circuit type not found - database error');
                }
                $circuit->setConnectivityType($connectivityType);
                // End circuit type
                // Bandwidth profile
                $bandwidthProfile = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile')->findOneBy(array(
                    'connectivityType' => $connectivityType,
                    'bandwidth' => '24576/1024Kbps',
                ));
                if(!isset($bandwidthProfile)) {
                    $output->writeln('Bandwidth profile (connectivityType: '.$connectivityType->getName().', 24mb) not found for : '.$fields['MM_id']);
                    continue;
                }
                $circuit->setBandwidthProfile($bandwidthProfile);
                // End bandwidth profile
                $circuit->setComments($fields['paratiriseis']);
                $circuit->setPaidByPsd(false);
                $circuit->setCreatedBy('lmsadmin');
                $circuit->setUpdatedBy('lmsadmin');
            }
            //ActivateServiceRequest $request
            $request = $em->getRepository('Psdtg\SiteBundle\Entity\Requests\ActivateServiceRequest')->findOneBy(array(
                'number' => ($fields['conphone']),
            ));
            if(isset($request)) {
                $output->writeln('Skipping request: '.$request->getNumber());
                continue;
            }
            $request = new ActivateServiceRequest();
            $request->setCircuit($circuit);
            $request->setNewConnectivityType($connectivityType);
            $request->setNewBandwidthProfile($bandwidthProfile);
            $request->setNumber($fields['conphone']);
            $request->setStatus(ActivateServiceRequest::STATUS_APPROVED);
            $request->setUnit($unit);
            $now = new \DateTime('now');
            $request->setComments('Imported at '.$now->format('d-m-Y'));
            if($fields['hmerominia_fax'] != "") {
                $date = \DateTime::createFromFormat('j/n/Y', $fields['hmerominia_fax']);
                $request->setCreatedAt($date);
            }
            if($fields['hmerominia_apostolis_ote'] != "") {
                $date = \DateTime::createFromFormat('j/n/Y', $fields['hmerominia_apostolis_ote']);
                $request->setUpdatedAt($date instanceof \DateTime ? $date : null);
                $request->setActivatedAt($date instanceof \DateTime ? $date : null);
            }
            $em->persist($circuit);
            $em->persist($request);
            $em->flush(array($circuit, $request));
        }

        $output->writeln('Lines imported successfully');
    }

    private function parseHeadersToArray($headersRow) {
        $cellIterator = $headersRow->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false); 
        $result = array();
        foreach ($cellIterator as $cell) {
            $result[] = $cell->getValue();
        }
        return $result;
    }

    private function parseRowToArray($row, $headers) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false); 
        $result = array();
        $i = 0;
        foreach ($cellIterator as $cell) {
            $result[$headers[$i]] = $cell->getValue();
            $i++;
        }
        return $result;
    }

    private function parseCSV($file)
    {
        $ignoreFirstLine = $this->cvsParsingOptions['ignoreFirstLine'];

        $finder = new Finder();
        $finder->files()->in(dirname($file))->name(basename($file));
        ;
        foreach ($finder as $file) { $csv = $file; }

        $phpExcelObject = $this->getContainer()->get('xls.load_xls2007')->load($csv->getRealPath());
        $sheet = $phpExcelObject->getSheet(0);
        //$objReader = PHPExcel_IOFactory::createReader($inputFileType);
        return $sheet;
    }
}