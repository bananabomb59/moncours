<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\Reponse;
use EDiff\Bundle\AdminBundle\Form\ReponseType;

/**
 * Reponse controller.
 *
 */
class ReponseController extends Controller
{
    /**
     * Lists all Reponse entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EDiffAdminBundle:Reponse')->findAll();

        return $this->render('EDiffAdminBundle:Reponse:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a Reponse entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Reponse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reponse entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:Reponse:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Reponse entity.
     *
     */
    public function newAction()
    {
    	// On recupere la question
    	$em = $this->getDoctrine()->getEntityManager();
    	$question = $em->getRepository('EDiffAdminBundle:Question')->find($this->get('request')->query->get('question'));
    	
        $entity = new Reponse();
        $entity->setQuestion($question);
        $form   = $this->createForm(new ReponseType(), $entity);

        return $this->render('EDiffAdminBundle:Reponse:new.html.twig', array(
            'entity' => $entity,
        	'question' => $question,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Reponse entity.
     *
     */
    public function createAction()
    {
        $entity  = new Reponse();
        $request = $this->getRequest();
        $form    = $this->createForm(new ReponseType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', array('id' => $entity->getQuestion()->getId())));
            
        }

        return $this->render('EDiffAdminBundle:Reponse:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Reponse entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Reponse')->find($id);
		$question = $entity->getQuestion();
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reponse entity.');
        }

        $editForm = $this->createForm(new ReponseType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:Reponse:edit.html.twig', array(
            'entity'      => $entity,
        	'question'    => $question,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Reponse entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Reponse')->find($id);
        $question = $entity->getQuestion();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reponse entity.');
        }

        $editForm   = $this->createForm(new ReponseType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', array('id' => $question->getId())));
        }

        return $this->render('EDiffAdminBundle:Reponse:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Reponse entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('EDiffAdminBundle:Reponse')->find($id);
        $question = $entity->getQuestion();

        if (!$entity) {
        	throw $this->createNotFoundException('Unable to find Reponse entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('question_show', array('id' => $question->getId())));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
