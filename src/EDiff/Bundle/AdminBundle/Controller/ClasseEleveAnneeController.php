<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\Classe;
use EDiff\Bundle\AdminBundle\Entity\AnneeScolaire;
use EDiff\Bundle\AdminBundle\Entity\User;

/**
 * Classe controller.
 *
 */
class ClasseEleveAnneeController extends Controller
{
    /**
     * Lists all Classe entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $classes = $em->getRepository('EDiffAdminBundle:Classe')->findAll();
        $eleves = $em->getRepository('EDiffAdminBundle:User')->findBy(array('droits' => 'eleve'));
        $annees = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->findAll();
        	
        return $this->render('EDiffAdminBundle:ClasseEleveAnnee:index.html.twig', array(
            'classes' => $classes,
        	'eleves' => $eleves,
        	'annees' => $annees
        ));
    }
    
	public function choisirEleveAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

    	// On récupère la valeur des filtres
        $request = $this->get('request');
    	$idEleve = $request->request->get('id');
    	
    	// on récupère les objets métier qui vont bien
        $eleve = $em->getRepository('EDiffAdminBundle:User')->find($idEleve);
        $eleveClasse = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findBy(array('user' => $idEleve));
        $classes = $em->getRepository('EDiffAdminBundle:Classe')->findAll();
        $annees = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->findAll();
        
        if($eleveClasse) {
    		return $this->render('EDiffAdminBundle:ClasseEleveAnnee:edit.html.twig', array(
	        	'eleve' => $eleve,
	        	'eleveClasse' => $eleveClasse,
    			'classes' => $classes,
        		'annees' => $annees,
    			'exist' => true
       		 ));
        }
        else {
    		return $this->render('EDiffAdminBundle:ClasseEleveAnnee:edit.html.twig', array(
	        	'eleve' => $eleve,
	        	'eleveClasse' => $eleveClasse,
    			'classes' => $classes,
        		'annees' => $annees,
    			'exist' => false
       		 ));
        }
    }
    
	public function validationAffectationAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

    	// On récupère la valeur des filtres
        $request = $this->get('request');
    	$idEleve = $request->request->get('eleve');
    	$idAnnee = $request->request->get('annee');
    	$idClasse = $request->request->get('classe');

    	$eleve = $em->getRepository('EDiffAdminBundle:User')->find($idEleve);
    	$classe = $em->getRepository('EDiffAdminBundle:Classe')->find($idClasse);
        $annee = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->find($idAnnee);
        
        $eleveClasse = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findBy(array('user' => $idEleve));
    	
        $logger = $this->get('logger');
        
    	if($eleveClasse) {
    		
    		$logger->info("eleveclasse EXIST");
    		$logger->info("eleveclasse EXIST");
    		$logger->info("eleveclasse EXIST");
    		
    		$eleveClasse->setClasse($classe->getId());
    		$eleveClasse->setAnnee($annee);
    	}
    	else {
    		
    		$logger->info("eleveclasse NOT EXIST");
    		$logger->info("eleveclasse NOT EXIST");
    		$logger->info("eleveclasse NOT EXIST");
    		
    		$eleveClasseAnnee = new Classe_Eleve_Annee();
    		$eleveClasse->setClasse($classe);
    		$eleveClasse->setAnnee($annee);
    		$eleveClasse->setUser($eleve);
    	}
    	
    	$em->persist($eleveClasse);
		$em->flush();
    	
    	return $this->render('EDiffAdminBundle:ClasseEleveAnnee:validation_affectation.html.twig', array());
    }

    
}
