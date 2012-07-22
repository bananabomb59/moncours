<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\Classe;
use EDiff\Bundle\AdminBundle\Entity\AnneeScolaire;
use EDiff\Bundle\AdminBundle\Entity\User;
use EDiff\Bundle\AdminBundle\Entity\Classe_Eleve_Annee;

use EDiff\Bundle\AdminBundle\Controller\AccueilController;

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
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'classeeleveannee')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
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
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'classeeleveannee')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

    	// On récupère la valeur des filtres
        $request = $this->get('request');
    	$idEleve = $request->request->get('id');
    	
    	// on récupère les objets métier qui vont bien
        $eleve = $em->getRepository('EDiffAdminBundle:User')->find($idEleve);
        $eleveClasse = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findOneBy(array('user' => $idEleve));
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
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'classeeleveannee')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

    	// On récupère la valeur des filtres
        $request = $this->get('request');
    	$idEleve = $request->request->get('eleve');
    	$idAnnee = $request->request->get('annee');
    	$idClasse = $request->request->get('classe');

    	$eleve = $em->getRepository('EDiffAdminBundle:User')->find($idEleve);
    	$classe = $em->getRepository('EDiffAdminBundle:Classe')->find($idClasse);
        $annee = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->find($idAnnee);
        
        $eleveClasse = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findOneBy(array('user' => $idEleve));

    	if($eleveClasse) {
    		$eleveClasse->setClasse($classe);
    		$eleveClasse->setAnnee($annee);
    	}
    	else {
    		$eleveClasseAnnee = new Classe_Eleve_Annee();
    		$eleveClasseAnnee->setClasse($classe);
    		$eleveClasseAnnee->setAnnee($annee);
    		$eleveClasseAnnee->setUser($eleve);
    		
    		$em->persist($eleveClasseAnnee);
    	}
    	
		$em->flush();
    	
    	return $this->render('EDiffAdminBundle:ClasseEleveAnnee:validation_affectation.html.twig', array());
    }

    
}
