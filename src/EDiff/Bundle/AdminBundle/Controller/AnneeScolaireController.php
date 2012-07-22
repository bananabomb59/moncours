<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\AnneeScolaire;
use EDiff\Bundle\AdminBundle\Form\AnneeScolaireType;

/**
 * AnneeScolaire controller.
 *
 */
class AnneeScolaireController extends Controller
{
    /**
     * Lists all AnneeScolaire entities.
     *
     */
    public function indexAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'anneescolaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->findAll();

        $isDelete = false;
        if($this->get('request')->query->get('delete') == 'true')
        	$isDelete = true;
        
        return $this->render('EDiffAdminBundle:AnneeScolaire:index.html.twig', array(
            'entities' => $entities,
        	'delete'   => $isDelete
        ));
    }

    /**
     * Finds and displays a AnneeScolaire entity.
     *
     */
    public function showAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'anneescolaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AnneeScolaire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $isUpdate = false;
        if($this->get('request')->query->get('update') == 'true')
        	$isUpdate = true;
        
        return $this->render('EDiffAdminBundle:AnneeScolaire:show.html.twig', array(
            'entity'      => $entity,
        	'update'	  => $isUpdate,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new AnneeScolaire entity.
     *
     */
    public function newAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'anneescolaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $entity = new AnneeScolaire();
        $form   = $this->createForm(new AnneeScolaireType(), $entity);

        return $this->render('EDiffAdminBundle:AnneeScolaire:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new AnneeScolaire entity.
     *
     */
    public function createAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'anneescolaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $entity  = new AnneeScolaire();
        $request = $this->getRequest();
        $form    = $this->createForm(new AnneeScolaireType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('anneescolaire_show', array('id' => $entity->getId())));
            
        }

        return $this->render('EDiffAdminBundle:AnneeScolaire:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing AnneeScolaire entity.
     *
     */
    public function editAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'anneescolaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AnneeScolaire entity.');
        }

        $editForm = $this->createForm(new AnneeScolaireType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:AnneeScolaire:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing AnneeScolaire entity.
     *
     */
    public function updateAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'anneescolaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AnneeScolaire entity.');
        }

        $editForm   = $this->createForm(new AnneeScolaireType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('anneescolaire_show', array('id' => $id, 'update' => 'true')));
        }

        return $this->render('EDiffAdminBundle:AnneeScolaire:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AnneeScolaire entity.
     *
     */
    public function deleteAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'anneescolaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AnneeScolaire entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('anneescolaire', array('delete' => 'true')));
    }

    private function createDeleteForm($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'anneescolaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
