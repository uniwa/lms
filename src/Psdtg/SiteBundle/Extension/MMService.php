<?php

namespace Psdtg\SiteBundle\Extension;

use Psdtg\SiteBundle\Entity\Unit;

class MMService {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function find($mmid) {
        $em = $this->container->get('doctrine')->getEntityManager();
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
                $unit = $this->hydrate($mmUnitEntries[0]);
            } else {
                $unit = null;
            }
        }
        return $unit;
    }

    public function findBy(array $filters = array()) {
        $results = array();
        if(isset($filters['name']) && $filters['name'] != '') {
            $mmUnitEntries = $this->queryUnits(array(
                'unit_name' => $filters['name'],
            ));
            foreach($mmUnitEntries as $curMmUnitEntry) {
                $results[] = $this->hydrate($curMmUnitEntry);
            }
        } else if(isset($filters['ldapuid']) && $filters['ldapuid'] != '') {
            /* ldap – Πίνακας λογαριασμών ldap
            Πεδίο	Τύπος	Όνομα Πεδίου	Περιγραφή
            ldap_id	int(11)		Ο κωδικός του λογαριασμού ldap
            uid	varchar(255)		To uid του λογαριασμού ldap
            unit_id	int(11)		Η μοναδα που ανήκει ο λογαριασμός ldap */
            $results[] = $this->find('3');
        }
        return $results;
    }

    protected function hydrate($entry) {
        // Unit not found or its too old. Query the WS for fresh data.
        $em = $this->container->get('doctrine')->getEntityManager();

        $unit = new Unit;
        $unit->setMmId($entry->mm_id);
        $unit->setUnitId($entry->mm_id);
        $unit->setName($entry->unit_name);
        $unit->setPostalCode($entry->postal_code);
        $unit->setRegistryNo($entry->registry_no);
        $unit->setStreetAddress($entry->street_address);
        $unit->setCreatedAt(new \DateTime('now'));
        $unit->setUpdatedAt(new \DateTime('now'));
        $em->merge($unit);
        $em->flush();

        return $unit;
    }

    protected function queryUnits($params = array()) {
        if(!isset($params['state']) || $params['state'] == '') {
            $params['state'] = "ΕΝΕΡΓΗ";
        }
        if(!isset($params['count']) || $params['count'] == '') {
            $params['count'] = 10;
        }
        if(!isset($params['startat']) || $params['startat'] == '') {
            $params['startat'] = 0;
        }
        /*$params = array(
            "category" => "ΣΧΟΛΙΚΕΣ ΚΑΙ ΔΙΟΙΚΗΤΙΚΕΣ ΜΟΝΑΔΕΣ",
        );*/
        return $this->queryMM('units', $params);
    }

    protected function queryLDAP($params = array()) {
        return array(
            'uid' => $params['uid'],
            'unit_id' => '03',
        );
        //return $this->queryMM('ldap', $params);
    }

    protected function queryMM($resource, $params = array()) {
        $username = "mmsch";
        $password = "mmsch";
        $server = 'http://mmsch.teiath.gr/server/'.$resource.'_json.php';

        $curl = curl_init ($server);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
        curl_setopt($curl, CURLOPT_USERPWD, $username.":".$password);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params) );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec ($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($http_status == 200)
        {
            $data = json_decode($data);
            //echo 'Found : '.count($data).' unit'.(count($data) == 1 ? '' : 's').'<br><br>';
            //var_dump($data);
            return $data;
        }
        else
        {
            throw new \Exception('MMSCH Error: '.$data);
        }
    }
}
?>
