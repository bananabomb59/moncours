<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        
        return $this->render('EDiffAdminBundle:User:index.html.twig', array(
            'entities' => $entities,
        	'delete'   => $isDelete
        ));
    }
    
	public function indexEleveAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'user')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
    	$session = $this->getRequest()->getSession();
		$session->set('eleve_ou_prof', "eleve");
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EDiffAdminBundle:User')->findBy(array('droits' => 'eleve'));

        $isDelete = false;
        if($this->get('request')->query->get('delete') == 'true')
        	$isDelete = true;
        
        return $this->render('EDiffAdminBundle:User:index.html.twig', array(
            'entities' => $entities,
        	'delete'   => $isDelete
        ));
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
        $eleveClasse = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findOneBy(array('user' => $id));

        if($eleveClasse) {
        	$classe = $em->getRepository('EDiffAdminBundle:Classe')->find($eleveClasse->getClasse()->getId());
        	$annee = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->find($eleveClasse->getAnnee()->getId());
        	
        	return $this->render('EDiffAdminBundle:User:show.html.twig', array(
	            'entity'      => $entity,
	        	'update'	  => $isUpdate,
	            'delete_form' => $deleteForm->createView(),
				'eleve' => $eleve,
		        'eleveClasse' => $eleveClasse,
	    		'classe' => $classe,
	        	'annee' => $annee,
	    		'exist' => true
        	));
        }
        else {
        	return $this->render('EDiffAdminBundle:User:show.html.twig', array(
	            'entity'      => $entity,
	        	'update'	  => $isUpdate,
	            'delete_form' => $deleteForm->createView(),
	    		'exist' => false
        	));
        }
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
            'form'   => $form->createView()
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
            'form'   => $form->createView()
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
            	'delete_form' => $deleteForm->createView()
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
            	'delete_form' => $deleteForm->createView()
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
            
            // suppression de l'affectation a la classe
            $eleveClasse = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findOneBy(array('user' => $id));
        	if ($eleveClasse) {
                $em->remove($eleveClasse);
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
}