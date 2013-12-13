<?php

namespace Psdtg\SiteBundle\Extension;

use Psdtg\SiteBundle\Exception\MMException;
use Psdtg\SiteBundle\Entity\Unit;
use Psdtg\SiteBundle\Extension\MMSyncableListener;
use Psdtg\SiteBundle\Entity\MMSyncableEntity;
use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;
use Psdtg\SiteBundle\Entity\Circuits\ConnectivityType;

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
            if(count($mmUnitEntries) == 1) {
                $unit = $this->hydrateUnit($mmUnitEntries[0]);
            } elseif(count($mmUnitEntries) > 1) {
                throw new MMException('Found more than one unit: '.count($mmUnitEntries));
            } else {
                $unit = null;
            }
        }
        return $unit;
    }

    public function findUnitsBy(array $filters = array()) {
        $results = array();
        $params = array();
        if(isset($filters['mm_id']) && $filters['mm_id'] != '') {
            $params['mm_id'] = $filters['mm_id'];
        }
        if(isset($filters['registry_no']) && $filters['registry_no'] != '') {
            $params['registry_no'] = $filters['registry_no'];
        }
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
            throw new MMException('The unit was not found');
        }
        if(count($units) > 1) {
            throw new MMException('Found more than one unit: '.count($units));
        }
        return $units[0];
    }

    public function findCircuitByNumberAndUnit($number, Unit $unit) {
        $circuits = $this->queryMM('circuits', array(
            'mm_id' => $unit->getMmId(),
        ));
        foreach($circuits as $curCircuit) {
            if($curCircuit->phone_number == $number) {
                return $curCircuit;
            }
        }
        return null;
    }

    public function findConnectivityTypeByName($name) {
        $types = $this->queryMM('connectivity_types');
        foreach($types as $curType) {
            if($curType->name == $name) {
                return $curType;
            }
        }
        return null;
    }

    public function persistMM(MMSyncableEntity $entity) {
        if($entity instanceof PhoneCircuit) {
            return $this->persistCircuit($entity);
        } elseif ($entity instanceof ConnectivityType) {
            return $this->persistConnectivityType($entity);
        } else {
            throw new MMException('Unsupported entity');
        }
    }

    protected function hydrateUnit($entry, $flush = false) {
        // Unit not found or its too old. Query the WS for fresh data.
        $em = $this->container->get('doctrine')->getManager();

        $unit = new Unit;
        $unit->setMmId($entry->mm_id);
        $unit->setUnitId($entry->mm_id);
        $unit->setState($entry->state);
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

    public function persistCircuit(PhoneCircuit $circuit) {
        if($circuit->getNumber() == null) {
            return false;
        }
        if($circuit->getConnectivityType() == null) {
            throw new \Exception('No connectivity type');
        }
        if($circuit->getConnectivityType()->getMmSyncId() == null) {
            $this->persistConnectivityType($circuit->getConnectivityType());
        }
        if($circuit->getMmSyncId() != null) {
            $method = 'PUT';
            $extraParams = array('circuit_id' => $circuit->getMmSyncId());
        } else {
            if(($curCircuit = $this->findCircuitByNumberAndUnit($circuit->getNumber(), $circuit->getUnit())) != null) { // Check if already exists
                $circuit->setMmSyncId($curCircuit->circuit_id);
                $circuit->setMmSyncLastUpdateDate(new \DateTime('now'));
                return;
            }
            $method = 'POST';
            $extraParams = array();
        }
        if($circuit->getUnit() == null) {
            throw new MMException('Unit cannot be null');
        }
        $params = array_merge($extraParams, array(
               "mm_id" => $circuit->getUnit()->getMmId(),
               "name" => $circuit->__toString(),
               "connectivity_type" => $circuit->getConnectivityType()->getMmSyncId(),
               "phone_number" => $circuit->getNumber(),
               "status" => $circuit->isActive(),
               "activated_date" => $circuit->getActivatedAt() instanceof \DateTime ? $circuit->getActivatedAt()->format('Y-m-d H:i') : null,
               "updated_date" => $circuit->getUpdatedAt() instanceof \DateTime ? $circuit->getUpdatedAt()->format('Y-m-d H:i') : null,
               "deactivated_date" => $circuit->getDeletedAt() instanceof \DateTime ? $circuit->getDeletedAt()->format('Y-m-d H:i') : null,
               "bandwidth" => $circuit->getBandwidthProfile()->getBandwidth(),
               "readspeed" => $circuit->getRealspeed(),
               "paid_by_psd" => $circuit->getPaidByPsd(),
        ));

        $curl = curl_init("http://mmsch.teiath.gr/api/circuits");

        $username = 'mmschadmin';
        $password = 'mmschadmin';
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD,  $username.":".$password);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode( $params ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $origData = curl_exec($curl);
        $data = json_decode($origData);
        if($data->status == 200) {
            if($method == 'POST') {
                $circuit->setMmSyncId($data->circuit_id);
                $circuit->setMmSyncLastUpdateDate(new \DateTime('now'));
            }
        } else {
            throw new MMException('Error adding circuit: '.$origData);
        }
    }

    public function persistConnectivityType(ConnectivityType $connectivityType) {
        $translator = $this->container->get('translator');
        if($connectivityType->getMmSyncId() != null) {
            $method = 'PUT';
            $extraParams = array('connectivity_type_id' => $connectivityType->getMmSyncId());
        } else {
            if(($curConType = $this->findConnectivityTypeByName($translator->trans($connectivityType->getName()))) != null) { // Check if already exists
                $connectivityType->setMmSyncId($curConType->connectivity_type_id);
                $connectivityType->setMmSyncLastUpdateDate(new \DateTime('now'));
                return;
            }
            $method = 'POST';
            $extraParams = array();
        }
        $params = array_merge($extraParams, array("name" => $translator->trans($connectivityType->getName())));

        $curl = curl_init("http://mmsch.teiath.gr/api/connectivity_types");

        $username = 'mmschadmin';
        $password = 'mmschadmin';
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD,  $username.":".$password);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode( $params ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $origData = curl_exec($curl);
        $data = json_decode($origData);
        if($data->status == 200) {
            if($method == 'POST') {
                $connectivityType->setMmSyncId($data->connectivity_type_id);
                $connectivityType->setMmSyncLastUpdateDate(new \DateTime('now'));
            }
        } else {
            throw new MMException('Error adding connectivity type: '.$origData);
        }
    }
}
?>
