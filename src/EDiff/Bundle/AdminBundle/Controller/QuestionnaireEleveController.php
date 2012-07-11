<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\QuestionnaireEleve;
use EDiff\Bundle\AdminBundle\Form\QuestionnaireEleveType;

/**
 * QuestionnaireEleve controller.
 *
 */
class QuestionnaireEleveController extends Controller
{
    /**
     * Lists all QuestionnaireEleve entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->findAll();
        $eleves = $em->getRepository('EDiffAdminBundle:User')->findAll();
        $matieres = $em->getRepository('EDiffAdminBundle:Matiere')->findAll();

        $isDelete = false;
        if($this->get('request')->query->get('delete') == 'true')
        	$isDelete = true;
        
        return $this->render('EDiffAdminBundle:QuestionnaireEleve:index.html.twig', array(
            'entities' => $entities,
        	'delete'   => $isDelete
        ));
    }

    /**
     * Finds and displays a QuestionnaireEleve entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QuestionnaireEleve entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        
        $isUpdate = false;
        if($this->get('request')->query->get('update') == 'true')
        	$isUpdate = true;
        	
        return $this->render('EDiffAdminBundle:QuestionnaireEleve:show.html.twig', array(
            'entity'      => $entity,
        	'update'	  => $isUpdate,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new QuestionnaireEleve entity.
     *
     */
    public function newAction()
    {
        $entity = new QuestionnaireEleve();
        $form   = $this->createForm(new QuestionnaireEleveType(), $entity);

        return $this->render('EDiffAdminBundle:QuestionnaireEleve:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new QuestionnaireEleve entity.
     *
     */
    public function createAction()
    {
        $entity  = new QuestionnaireEleve();
        $request = $this->getRequest();
        $form    = $this->createForm(new QuestionnaireEleveType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('questionnaireeleve_show', array('id' => $entity->getId())));
            
        }

        return $this->render('EDiffAdminBundle:QuestionnaireEleve:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing QuestionnaireEleve entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QuestionnaireEleve entity.');
        }

        $editForm = $this->createForm(new QuestionnaireEleveType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:QuestionnaireEleve:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing QuestionnaireEleve entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QuestionnaireEleve entity.');
        }

        $editForm   = $this->createForm(new QuestionnaireEleveType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('questionnaireeleve_show', array('id' => $id, 'update' => 'true')));
        }

        return $this->render('EDiffAdminBundle:QuestionnaireEleve:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a QuestionnaireEleve entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find QuestionnaireEleve entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('questionnaireeleve', array('delete' => 'true')));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
