<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\Competence;
use EDiff\Bundle\AdminBundle\Form\CompetenceType;

/**
 * Competence controller.
 *
 */
class CompetenceController extends Controller
{
    /**
     * Lists all Competence entities.
     *
     */
    public function indexAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'competence')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EDiffAdminBundle:Competence')->findAll();
        
        $isDelete = false;
        if($this->get('request')->query->get('delete') == 'true')
        	$isDelete = true;

        return $this->render('EDiffAdminBundle:Competence:index.html.twig', array(
            'entities' => $entities,
        	'delete'   => $isDelete
        ));
    }

    /**
     * Finds and displays a Competence entity.
     *
     */
    public function showAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'competence')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Competence')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competence entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $isUpdate = false;
        if($this->get('request')->query->get('update') == 'true')
        	$isUpdate = true;
        	
        return $this->render('EDiffAdminBundle:Competence:show.html.twig', array(
            'entity'      => $entity,
        	'update'	  => $isUpdate,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Competence entity.
     *
     */
    public function newAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'competence')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $entity = new Competence();
        $form   = $this->createForm(new CompetenceType(), $entity);

        return $this->render('EDiffAdminBundle:Competence:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Competence entity.
     *
     */
    public function createAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'competence')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $entity  = new Competence();
        $request = $this->getRequest();
        $form    = $this->createForm(new CompetenceType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competence_show', array('id' => $entity->getId())));
            
        }

        return $this->render('EDiffAdminBundle:Competence:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Competence entity.
     *
     */
    public function editAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'competence')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Competence')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competence entity.');
        }

        $editForm = $this->createForm(new CompetenceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:Competence:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Competence entity.
     *
     */
    public function updateAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'competence')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Competence')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competence entity.');
        }

        $editForm   = $this->createForm(new CompetenceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competence_show', array('id' => $id, 'update' => 'true')));
        }

        return $this->render('EDiffAdminBundle:Competence:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Competence entity.
     *
     */
    public function deleteAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'competence')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EDiffAdminBundle:Competence')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Competence entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('competence', array('delete' => 'true')));
    }

    private function createDeleteForm($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'competence')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
