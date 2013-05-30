<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\Classe_Eleve_Annee;
use EDiff\Bundle\AdminBundle\Entity\User;
use EDiff\Bundle\AdminBundle\Form\UserType;

/**
* User controller.
*
*/
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     */
    public function indexProfAction()
    {
      if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
      
        $session = $this->getRequest()->getSession();
        $session->set('eleve_ou_prof', "prof");
      
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EDiffAdminBundle:User')->findBy(array('droits' => 'prof'));

        $isDelete = false;
        if($this->get('request')->query->get('delete') == 'true')
            $isDelete = true;
    
        return $this->render('EDiffAdminBundle:User:index.prof.html.twig', array(
            'entities' => $entities,
            'delete'   => $isDelete,
            'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        ));
    }
    
      public function indexEleveAction()
    {
      if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
      
      	$session = $this->getRequest()->getSession();
        $session->set('eleve_ou_prof', "eleve");
      
            $pagination_eleve_par_page = 30;
            if($this->get('request')->query->get('pagination_eleve_page')) {
                  $page = $this->get('request')->query->get('pagination_eleve_page');
            }
            else {
                  $page = 0;
            }
            
      $filtreClasse = $this->get('request')->request->get('choix_classe');
        if($filtreClasse==null) {
            $filtreClasse = -1;
        }
        
      $filtreAnnee = $this->get('request')->request->get('choix_annee');
        if($filtreAnnee==null) {
            $filtreAnnee = -1;
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $all_entities = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->getAllEleves($filtreClasse, $filtreAnnee);
        $users = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->getAvecEleves($page, $pagination_eleve_par_page, $filtreClasse, $filtreAnnee);
            $annees = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->findAll();
            $classes = $em->getRepository('EDiffAdminBundle:Classe')->findAll();
        
        $nb_elements = count($all_entities);
        $nb_pages = ceil($nb_elements / $pagination_eleve_par_page);
        
        $isDelete = false;
        if($this->get('request')->query->get('delete') == 'true')
            $isDelete = true;
            
            $logger = $this->get('logger');
    	$logger->info("DROITS : " . $this->getRequest()->getSession()->get('user')->getDroits());
    	$logger->info("DROITS : " . $this->getRequest()->getSession()->get('user')->getDroits());
    	$logger->info("DROITS : " . $this->getRequest()->getSession()->get('user')->getDroits());
    	$logger->info("DROITS : " . $this->getRequest()->getSession()->get('user')->getDroits());
    	$logger->info("DROITS : " . $this->getRequest()->getSession()->get('user')->getDroits());
        
            
        $var=array(
            'entities' => $users,
            'delete'   => $isDelete,
            'nb_pages' => $nb_pages,
            'page'         => $page,
            'annees'   => $annees,
            'classes'  => $classes,
            'filtreClasse' => $filtreClasse,
            'filtreAnnee' => $filtreAnnee,
            'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        );

        return $this->render('EDiffAdminBundle:User:index.eleve.html.twig',$var);
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
      if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
      
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        
        $isUpdate = false;
        if($this->get('request')->query->get('update') == 'true')
            $isUpdate = true;

        $eleve = $em->getRepository('EDiffAdminBundle:User')->find($id);
        $affectations = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findBy(array('user' => $id));
        $classes = $em->getRepository('EDiffAdminBundle:Classe')->findAll();
        $annees = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->findAll();

        return $this->render('EDiffAdminBundle:User:show.html.twig', array(
          'entity'      => $entity,
          'update'      => $isUpdate,
          'delete_form' => $deleteForm->createView(),
          'eleve' 		=> $eleve,
          'affectations' => $affectations,
          'classes' 	=> $classes,
          'annees' 		=> $annees,
          'layout' 		=> "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        ));
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
      if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
      
        $entity = new User();
        $form   = $this->createForm(new UserType(), $entity);

        return $this->render('EDiffAdminBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        ));
    }

    /**
     * Creates a new User entity.
     *
     */
    public function createAction()
    {
      if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
      
        $entity  = new User();
        $request = $this->getRequest();
        $form    = $this->createForm(new UserType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
            
        }

        return $this->render('EDiffAdminBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
      if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
      
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        // on récupère les objets métier qui vont bien
        $eleve = $em->getRepository('EDiffAdminBundle:User')->find($id);
        $eleveClasse = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findOneBy(array('user' => $id));
        $classes = $em->getRepository('EDiffAdminBundle:Classe')->findAll();
        $annees = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->findAll();
        
      	if($eleveClasse) {
            return $this->render('EDiffAdminBundle:User:edit.html.twig', array(
                  'eleve' => $eleve,
                  'eleveClasse' => $eleveClasse,
                  'classes' => $classes,
                  'annees' => $annees,
                  'exist' => true,
                  'entity'      => $entity,
                  'edit_form'   => $editForm->createView(),
                  'delete_form' => $deleteForm->createView(),
                  'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
                   ));
        }
        else {
            return $this->render('EDiffAdminBundle:User:edit.html.twig', array(
                  'eleve' => $eleve,
                  'eleveClasse' => $eleveClasse,
                  'classes' => $classes,
                  'annees' => $annees,
                  'exist' => false,
                  'entity'      => $entity,
                  'edit_form'   => $editForm->createView(),
                  'delete_form' => $deleteForm->createView(),
                  'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
                   ));
        }
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction($id)
    {
      if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
      
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm   = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);
        
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $id, 'update' => 'true')));
        }

        return $this->render('EDiffAdminBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        ));
    }

    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction($id)
    {
      if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
      
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EDiffAdminBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }
            
            // suppression des affectations a la classe
            $affectations = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findBy(array('user' => $id));
            if ($affectations) {
                foreach($affectations as $affectation) {
            		$em->remove($affectation);
                }
            }
            
        	// suppression des réponses de l'élève
            $reponses = $em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->findBy(array('eleve' => $id));
            if ($reponses) {
                foreach($reponses as $reponse) {
            		$em->remove($reponse);
                }
            }
            
            $em->remove($entity);
            $em->flush();
        }

        $session = $this->getRequest()->getSession();
        if($session->get('eleve_ou_prof', "prof") == 'prof') {
            return $this->redirect($this->generateUrl('user_prof', array('delete' => 'true')));
        }
        else {
        	return $this->redirect($this->generateUrl('user_eleve', array('delete' => 'true')));
        }
    }

    private function createDeleteForm($id)
    {
      if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
      
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    public function showAffectationsAction(){
            if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
      
        $em = $this->getDoctrine()->getEntityManager();
        
    	// On récupère la valeur des filtres
        $request = $this->get('request');
    	$idEleve = $request->request->get('eleve');
    	$idAnnee = $request->request->get('annee');
    	$idClasse = $request->request->get('classe');
    	
    	// On enregistre l'affectation
    	$eleve = $em->getRepository('EDiffAdminBundle:User')->find($idEleve);
    	$classe = $em->getRepository('EDiffAdminBundle:Classe')->find($idClasse);
        $annee = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->find($idAnnee);

        // Creation de l'affectation
   		$eleveClasseAnnee = new Classe_Eleve_Annee();
   		$eleveClasseAnnee->setClasse($classe);
   		$eleveClasseAnnee->setAnnee($annee);
   		$eleveClasseAnnee->setUser($eleve);
    		
   		// Sauvegarde de l'affectation
   		$em->persist($eleveClasseAnnee);
		$em->flush();

		// On récupère les classes et annees pour peupler le formulaire
		$affectations = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findBy(array('user' => $idEleve));
        $classes = $em->getRepository('EDiffAdminBundle:Classe')->findAll();
        $annees = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->findAll();      
        
        // On retourne la réponse AJAX
        return $this->render('EDiffAdminBundle:User:showAffectations.html.twig', array(
            'classes' => $classes,
            'annees' => $annees,
        	'eleve' => $eleve,
        	'affectations' => $affectations
        ));
    }
}