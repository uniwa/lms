<?php
namespace Kp\SiteBundle\Entity\Repositories;

class BasePageRepository extends BaseRepository
{
    public function findPages($filters = array(), $paginate = true) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('p, RAND() as HIDDEN rand');
        $qb->from($this->_entityName, 'p');

        // Basic Filters
        // Search
        if(isset($filters['inSlideshow']) && $filters['inSlideshow'] != "") {
            $qb->andWhere('p.inSlideshow = :inslideshow');
            $qb->setParameter('inslideshow', $filters['inSlideshow']);
        }
        if(isset($filters['id_pool']) && $filters['id_pool'] != "") {
            if(count($filters['id_pool']) > 0) {
                $qb->andWhere('p.id IN ('.  implode(', ', $filters['id_pool']).')');
            } else {
                return array();
            }
        }
        if(isset($filters['search']) && $filters['search'] != "") {
            $tokens = explode(' ', $filters['search']);
            $qb->join('p.tags', 'tag');
            $i = 0;
            foreach($tokens as $token) {
                $qb->andWhere('p.content LIKE :searchterm'.$i.' OR tag.name LIKE :searchterm'.$i);
                $qb->setParameter('searchterm'.$i, '%'.$token.'%');
                $i++;
            }
        }
        if(isset($filters['minchars']) && $filters['minchars'] != "") {
            $qb->andWhere('LENGTH(p.content) > :minchars');
            $qb->setParameter('minchars', $filters['minchars']);
        }
        // Remove home page results
        $qb->andWhere('p.id NOT LIKE :homeresults');
        $qb->setParameter('homeresults', 'home_%');

        // Ordering
        if(isset($filters['sortBy']) && $filters['sortBy'] != "") {
            if(!isset($filters['sortDirection']) || $filters['sortDirection'] == "") {
                throw new \Exception('A sorting direction must be set');
            }
            $qb->orderBy('p.'.$filters['sortBy'], $filters['sortDirection']);
        } else {
            $qb->orderBy('p.created', 'DESC');
        }
        // Limit
        if(isset($filters['limit']) && $filters['limit'] != "") {
            $qb->setMaxResults($filters['limit']);
        }

        return $this->getResult($qb, $paginate);
    }
}
?>