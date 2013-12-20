<?php
namespace Psdtg\SiteBundle\Command;

use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
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
                'number' => ($fields['edunet_line']),
            ));
            if(isset($circuit)) {
                $output->writeln('Skipping circuit: '.$circuit->getNumber());
                continue;
            }
            $circuit = new PhoneCircuit();
            try {
                $unit = $mmservice->findOneUnitBy(array('mm_id' => $fields['mm_id']));
            } catch(\RunTimeException $e) {
                $output->writeln('Unit not found for: '.$fields['mm_id']);
                continue;
            }
            $circuit->setUnit($unit);
            $circuit->setNumber($fields['edunet_line']);
            // Circuit type
            $map = array(
                /*'ADSL',
                'ETHERNET',*/
                'ISDN ISDN' => 'isdn_dialup',
                'ISDN ADSL' => 'isdn_adsl',
                'PSTN PSTN' => 'pstn_dialup',
                'PSTN ADSL' => 'pstn_adsl',
                'PSTN ADSL' => 'pstn_adsl',
                /*'WIRELESS',
                'LL' => 'll',
                'εκκρεμεί',
                'εκκρεμεί - ΕΛΛ ΧΩΝΕΥΤΗΣ',
                'ραντ. 25/4 εκκρεμεί',*/
            );
            if(!isset($map[$fields['type'].' '.$fields['service']])) {
                var_dump($fields);
                $output->writeln('Circuit type '.$fields['type'].' '.$fields['service'].' not found for : '.$fields['mm_id']);
                continue;
            }
            $connectivityType = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\ConnectivityType')->findOneBy(array(
                'name' => $map[$fields['type'].' '.$fields['service']]
            ));
            if(!isset($connectivityType)) {
                throw new \Exception('Circuit type not found - database error');
            }
            $circuit->setConnectivityType($connectivityType);
            // End circuit type
            // Bandwidth profile
            if($connectivityType->getName() == 'pstn_dialup') {
                $bandwidthProfile = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile')->findOneBy(array(
                    'connectivityType' => $connectivityType,
                    'bandwidth' => '56kbps',
                ));
            } else if($connectivityType->getName() == 'pstn_adsl' || $connectivityType->getName() == 'isdn_adsl') {
                if(trim($fields['24mb']) === 'yes') {
                    $bandwidthProfile = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile')->findOneBy(array(
                        'connectivityType' => $connectivityType,
                        'bandwidth' => '24576/1024Kbps',
                    ));
                } else {
                    $bandwidthProfile = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile')->findOneBy(array(
                        'connectivityType' => $connectivityType,
                        'bandwidth' => '2048/512Kbps',
                    ));
                }
            } else if($connectivityType->getName() == 'isdn_dialup') {
                if(trim($fields['24mb']) === 'yes') {
                    $bandwidthProfile = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile')->findOneBy(array(
                        'connectivityType' => $connectivityType,
                        'bandwidth' => '128Kbps',
                    ));
                } else {
                    $bandwidthProfile = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile')->findOneBy(array(
                        'connectivityType' => $connectivityType,
                        'bandwidth' => '64Kbps',
                    ));
                }
            }
            if(!isset($bandwidthProfile)) {
                $output->writeln('Bandwidth profile (connectivityType: '.$connectivityType->getName().', '.trim($fields['24mb']).') not found for : '.$fields['mm_id']);
                continue;
            }
            $circuit->setBandwidthProfile($bandwidthProfile);
            // End bandwidth profile
            if($fields['date_active'] != "") {
                $date = \DateTime::createFromFormat('j/n/Y', $fields['date_active']);
                $circuit->setActivatedAt($date instanceof \DateTime ? $date : null);
            }
            $circuit->setComments($fields['comments']);
            $circuit->setPaidByPsd(true);
            $circuit->setCreatedBy('lmsadmin');
            $circuit->setUpdatedBy('lmsadmin');
            $em->persist($circuit);
            $em->flush($circuit);
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

    private function fixInconsistencies() {
        /*
Fix incorrect type ids
----------------------------------------
# Dialup with 2mbps
UPDATE Circuit SET type_id = 2 WHERE type_id = 1 and LOWER(bandwidth) = '2mbps';
UPDATE Circuit SET type_id = 2 WHERE type_id = 1 and LOWER(bandwidth) = '2 mbps';

# Dialup with 24mbps
UPDATE Circuit SET type_id = 2 WHERE type_id = 1 and LOWER(bandwidth) = '24mbps';
UPDATE Circuit SET type_id = 2 WHERE type_id = 1 and LOWER(bandwidth) = '24 mbps';

# ISDN with 2mbps
UPDATE Circuit SET type_id = 5 WHERE type_id = 4 and LOWER(bandwidth) = '2mbps';
UPDATE Circuit SET type_id = 5 WHERE type_id = 4 and LOWER(bandwidth) = '2 mbps';

# ISDN with 24mbps
UPDATE Circuit SET type_id = 5 WHERE type_id = 4 and LOWER(bandwidth) = '24mbps';
UPDATE Circuit SET type_id = 5 WHERE type_id = 4 and LOWER(bandwidth) = '24 mbps';

# ISDN ADSL with 128kbps
UPDATE Circuit SET bandwidth = '24mbps' WHERE type_id = 5 and LOWER(bandwidth) = '128kbps';
UPDATE Circuit SET bandwidth = '24mbps' WHERE type_id = 5 and LOWER(bandwidth) = '128 kbps';

Set bandwidth profiles
----------------------------------------
# ADSL 2 Mbps
UPDATE Circuit SET bandwidth_profile_id = 10, bandwidth = NULL WHERE type_id = 2 and LOWER(bandwidth) = '2mbps';
UPDATE Circuit SET bandwidth_profile_id = 10, bandwidth = NULL WHERE type_id = 2 and LOWER(bandwidth) = '2 mbps';

# ADSL 24 Mbps
UPDATE Circuit SET bandwidth_profile_id = 10, bandwidth = NULL WHERE type_id = 2 and LOWER(bandwidth) = '24mbps';
UPDATE Circuit SET bandwidth_profile_id = 10, bandwidth = NULL WHERE type_id = 2 and LOWER(bandwidth) = '24 mbps';

# ISDN 128kbps
UPDATE Circuit SET bandwidth_profile_id = 2, bandwidth = NULL WHERE type_id = 4 and LOWER(bandwidth) = '128kbps';
UPDATE Circuit SET bandwidth_profile_id = 2, bandwidth = NULL WHERE type_id = 4 and LOWER(bandwidth) = '128 kbps';
         */
    }
}