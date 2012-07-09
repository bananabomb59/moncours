<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\CompetenceEleveAnnee;
use EDiff\Bundle\AdminBundle\Form\CompetenceEleveAnneeType;

/**
 * CompetenceEleveAnnee controller.
 *
 */
class CompetenceEleveAnneeController extends Controller
{
    /**
     * Lists all CompetenceEleveAnnee entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EDiffAdminBundle:CompetenceEleveAnnee')->findAll();

        return $this->render('EDiffAdminBundle:CompetenceEleveAnnee:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a CompetenceEleveAnnee entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:CompetenceEleveAnnee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetenceEleveAnnee entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:CompetenceEleveAnnee:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new CompetenceEleveAnnee entity.
     *
     */
    public function newAction()
    {
        $entity = new CompetenceEleveAnnee();
        $form   = $this->createForm(new CompetenceEleveAnneeType(), $entity);

        return $this->render('EDiffAdminBundle:CompetenceEleveAnnee:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new CompetenceEleveAnnee entity.
     *
     */
    public function createAction()
    {
        $entity  = new CompetenceEleveAnnee();
        $request = $this->getRequest();
        $form    = $this->createForm(new CompetenceEleveAnneeType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competenceeleveannee_show', array('id' => $entity->getId())));
            
        }

        return $this->render('EDiffAdminBundle:CompetenceEleveAnnee:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing CompetenceEleveAnnee entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:CompetenceEleveAnnee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetenceEleveAnnee entity.');
        }

        $editForm = $this->createForm(new CompetenceEleveAnneeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:CompetenceEleveAnnee:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing CompetenceEleveAnnee entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:CompetenceEleveAnnee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetenceEleveAnnee entity.');
        }

        $editForm   = $this->createForm(new CompetenceEleveAnneeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competenceeleveannee_edit', array('id' => $id)));
        }

        return $this->render('EDiffAdminBundle:CompetenceEleveAnnee:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CompetenceEleveAnnee entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EDiffAdminBundle:CompetenceEleveAnnee')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CompetenceEleveAnnee entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('competenceeleveannee'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
