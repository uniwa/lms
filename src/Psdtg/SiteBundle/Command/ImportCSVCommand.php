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
        foreach($this->parseCSV($input->getOption('file')) as $row) {
            $fields = explode(',', $row[0]);
            $circuit = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit')->findOneBy(array(
                'number' => ($fields[10]),
            ));
            if(isset($circuit)) {
                $output->writeln('Skipping circuit: '.$circuit->getNumber());
                continue;
            }
            $circuit = new PhoneCircuit();
            try {
                $unit = $mmservice->findOneUnitBy(array('registry_no' => $fields[7]));
            } catch(\RunTimeException $e) {
                $output->writeln('Unit not found for: '.$fields[7]);
                continue;
            }
            $circuit->setUnit($unit);
            $circuit->setNumber($fields[10]);
            // Circuit type
            $map = array(
                /*'ADSL',
                'ETHERNET',*/
                'ISDN ISDN' => 'isdn_dialup',
                'ISDN ADSL' => 'isdn_adsl',
                'PSTN PSTN' => 'pstn_dialup',
                'PSTN ADSL' => 'pstn_adsl',
                /*'WIRELESS',
                'LL' => 'll',
                'εκκρεμεί',
                'εκκρεμεί - ΕΛΛ ΧΩΝΕΥΤΗΣ',
                'ραντ. 25/4 εκκρεμεί',*/
            );
            if(!isset($map[$fields[8]])) {
                $output->writeln('Circuit type not found for : '.$fields[10]);
                continue;
            }
            $connectivityType = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\ConnectivityType')->findOneBy(array(
                'name' => $map[$fields[8].' '.$fields[9]]
            ));
            if(!isset($connectivityType)) {
                throw new \Exception('Circuit type not found - database error');
            }
            $circuit->setCircuitType($connectivityType);
            // End circuit type
            if($connectivityType->getName() == 'pstn_dialup') {
                $bandwidthProfile = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile')->findOneBy(array(
                    'connectivityType' => $connectivityType,
                    'name' => '56kbps',
                ));
            } else if($connectivityType->getName() == 'pstn_adsl' || $connectivityType->getName() == 'isdn_adsl') {
                if($fields[17]) {
                    $bandwidthProfile = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile')->findOneBy(array(
                        'connectivityType' => $connectivityType,
                        'name' => '24576/1024Kbps',
                    ));
                } else {
                    $bandwidthProfile = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile')->findOneBy(array(
                        'connectivityType' => $connectivityType,
                        'name' => '2048/512Kbps',
                    ));
                }
            } else if($connectivityType->getName() == 'isdn_dialup') {
                $bandwidthProfile = $em->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile')->findOneBy(array(
                    'connectivityType' => $connectivityType,
                    'name' => '128Kbps',
                ));
            }
            $circuit->setBandwidth($bandwidthProfile);
            //$requestedAt = $fields[7];
            if($fields[6] != "") {
                $circuit->setActivatedAt(\DateTime::createFromFormat('m-d-Y', $fields[6]));
            }
            $circuit->setComments($fields[18]);
            $circuit->setPaidByPsd(true);
            $circuit->setCreatedBy('lmsadmin');
            $circuit->setUpdatedBy('lmsadmin');
            $em->persist($circuit);
            $em->flush($circuit);
        }

        $output->writeln('Lines imported successfully');
    }

    private function parseCSV($file)
    {
        $ignoreFirstLine = $this->cvsParsingOptions['ignoreFirstLine'];

        $finder = new Finder();
        $finder->files()->in(dirname($file))->name(basename($file));
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