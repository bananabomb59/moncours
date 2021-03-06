<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * QuestionnaireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuestionnaireRepository extends EntityRepository
{
	public function getSearch($page, $nb_per_page, $annee, $classe, $matiere, $statut)
    {
        $qb = $this->createQueryBuilder('q');

        if($annee != -1) {
        	$qb->andWhere('q.anneescolaire = :annee')
        	   ->setParameter('annee', $annee);
        }
        
    	if($classe != -1) {
        	$qb->andWhere('q.classe = :classe')
        	   ->setParameter('classe', $classe);
        }
        
    	if($matiere != -1) {
        	$qb->andWhere('q.matiere = :matiere')
        	   ->setParameter('matiere', $matiere);
        }
        
    	if($statut != -1) {
        	$qb->andWhere('q.statut = :statut')
        	   ->setParameter('statut', $statut);
        }
        	
        $qb	->setFirstResult($page)
    		->setMaxResults($nb_per_page);
        	
        // Enfin, on retourne le résultat.
        return $qb->getQuery()
                   ->getResult();
    }
    
	public function getAllSearch($annee, $classe, $matiere, $statut)
    {
        $qb = $this->createQueryBuilder('q');

    	if($annee != -1) {
        	$qb->andWhere('q.anneescolaire = :annee')
        	   ->setParameter('annee', $annee);
        }
        
    	if($annee != -1) {
        	$qb->andWhere('q.classe = :classe')
        	   ->setParameter('classe', $classe);
        }
        
    	if($matiere != -1) {
        	$qb->andWhere('q.matiere = :matiere')
        	   ->setParameter('matiere', $matiere);
        }
        
    	if($statut != -1) {
        	$qb->andWhere('q.statut = :statut')
        	   ->setParameter('statut', $statut);
        }
        	
        // Enfin, on retourne le résultat.
        return $qb->getQuery()
                   ->getResult();
    }
}