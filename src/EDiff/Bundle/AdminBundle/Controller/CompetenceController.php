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
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EDiffAdminBundle:Competence')->findAll();

        return $this->render('EDiffAdminBundle:Competence:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a Competence entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Competence')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competence entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:Competence:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Competence entity.
     *
     */
    public function newAction()
    {
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

            return $this->redirect($this->generateUrl('competence_edit', array('id' => $id)));
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

        return $this->redirect($this->generateUrl('competence'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
