<?php

namespace Psdtg\SiteBundle\Extension;

use Psdtg\SiteBundle\Exception\MMException;
use Psdtg\SiteBundle\Entity\Unit;

class MMService {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function findUnit($mmid) {
        $em = $this->container->get('doctrine')->getManager();
        $repo = $em->getRepository('Psdtg\SiteBundle\Entity\Unit');
        $unit = $repo->find($mmid);
        $yesterday = new \DateTime('yesterday');
        if(!isset($unit) || $unit->getUpdatedAt() < $yesterday) {
            // Query the MM and try to find the unit
            $mmUnitEntries = $this->queryUnits(array(
                'mm_id' => $mmid,
                'count' => 1,
            ));
            if(count($mmUnitEntries) > 0) {
                $unit = $this->hydrateUnit($mmUnitEntries[0]);
            } else {
                $unit = null;
            }
        }
        return $unit;
    }

    public function findUnitsBy(array $filters = array()) {
        $results = array();
        $params = array();
        if(isset($filters['name']) && $filters['name'] != '') {
            $params['name'] = $filters['name'];
        }
        if(isset($filters['fy']) && $filters['fy'] != '') {
            $params['implementation_entity'] = $filters['fy'];
        }
        if(isset($filters['ldapuid']) && $filters['ldapuid'] != '') {
            /* ldap – Πίνακας λογαριασμών ldap
            Πεδίο	Τύπος	Όνομα Πεδίου	Περιγραφή
            ldap_id	int(11)		Ο κωδικός του λογαριασμού ldap
            uid	varchar(255)		To uid του λογαριασμού ldap
            unit_id	int(11)		Η μοναδα που ανήκει ο λογαριασμός ldap */
            $params['mm_id'] = '1000003';
        }
        $mmUnitEntries = $this->queryUnits($params);
        foreach($mmUnitEntries as $curMmUnitEntry) {
            $results[] = $this->hydrateUnit($curMmUnitEntry);
        }
        $this->container->get('doctrine')->getManager()->flush($results);
        return $results;
    }

    public function findOneUnitBy(array $filters = array()) {
        $units = $this->findUnitsBy($filters+array('limit' => 1));
        if(!isset($units[0])) {
            throw new MMException('Η μονάδα δεν βρέθηκε');
        }
        if(count($units) > 1) {
            throw new MMException('Βρέθηκαν περισσότερες της μιας μονάδας: '.count($units));
        }
        return $units[0];
    }

    protected function hydrateUnit($entry, $flush = false) {
        // Unit not found or its too old. Query the WS for fresh data.
        $em = $this->container->get('doctrine')->getManager();

        $unit = new Unit;
        $unit->setMmId($entry->mm_id);
        $unit->setUnitId($entry->mm_id);
        $unit->setFyName($entry->implementation_entity);
        $unit->setFyInitials($entry->implementation_entity_initials);
        $unit->setName($entry->name);
        $unit->setPostalCode($entry->postal_code);
        $unit->setRegistryNo($entry->registry_no);
        $unit->setStreetAddress($entry->street_address);
        $unit->setCategoryName($entry->category);
        $unit->setCreatedAt(new \DateTime('now'));
        $unit->setUpdatedAt(new \DateTime('now'));

        $unit = $em->merge($unit);
        if($flush == true) {
            $em->flush($unit);
        }

        return $unit;
    }

    protected function queryUnits($params = array()) {
        if(!isset($params['state']) || $params['state'] == '') {
            $params['state'] = "ΕΝΕΡΓΗ";
        }
        if(!isset($params['limit']) || $params['limit'] == '') {
            $params['count'] = 10;
        } else {
            $params['count'] = $params['limit'];
        }
        if(!isset($params['startat']) || $params['startat'] == '') {
            $params['startat'] = 0;
        }
        /*if(!isset($params['category']) || $params['category'] == '') {
            "category" => "ΣΧΟΛΙΚΕΣ ΚΑΙ ΔΙΟΙΚΗΤΙΚΕΣ ΜΟΝΑΔΕΣ",
        }*/
        return $this->queryMM('units', $params);
    }

    protected function queryMM($resource, $params = array()) {
        $username = "mmsch";
        $password = "mmsch";
        $server = 'http://mmsch.teiath.gr/api/'.$resource;

        $curl = curl_init ($server);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD,  $username.":".$password);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode( $params ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


        $data = curl_exec ($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($http_status == 200)
        {
            $decodedData = json_decode($data);
            if(!$decodedData || !isset($decodedData->data)) {
                throw new MMException('MMSCH Error: '.$data);
            }
            return $decodedData->data;
        }
        else
        {
            throw new MMException('MMSCH Error: '.$data);
        }
    }
}
?>
