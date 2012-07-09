<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\CompetenceClasseAnnee;
use EDiff\Bundle\AdminBundle\Form\CompetenceClasseAnneeType;

/**
 * CompetenceClasseAnnee controller.
 *
 */
class CompetenceClasseAnneeController extends Controller
{
    /**
     * Lists all CompetenceClasseAnnee entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EDiffAdminBundle:CompetenceClasseAnnee')->findAll();

        return $this->render('EDiffAdminBundle:CompetenceClasseAnnee:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a CompetenceClasseAnnee entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:CompetenceClasseAnnee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetenceClasseAnnee entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:CompetenceClasseAnnee:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new CompetenceClasseAnnee entity.
     *
     */
    public function newAction()
    {
        $entity = new CompetenceClasseAnnee();
        $form   = $this->createForm(new CompetenceClasseAnneeType(), $entity);

        return $this->render('EDiffAdminBundle:CompetenceClasseAnnee:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new CompetenceClasseAnnee entity.
     *
     */
    public function createAction()
    {
        $entity  = new CompetenceClasseAnnee();
        $request = $this->getRequest();
        $form    = $this->createForm(new CompetenceClasseAnneeType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competenceclasseannee_show', array('id' => $entity->getId())));
            
        }

        return $this->render('EDiffAdminBundle:CompetenceClasseAnnee:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing CompetenceClasseAnnee entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:CompetenceClasseAnnee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetenceClasseAnnee entity.');
        }

        $editForm = $this->createForm(new CompetenceClasseAnneeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:CompetenceClasseAnnee:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing CompetenceClasseAnnee entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:CompetenceClasseAnnee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetenceClasseAnnee entity.');
        }

        $editForm   = $this->createForm(new CompetenceClasseAnneeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competenceclasseannee_edit', array('id' => $id)));
        }

        return $this->render('EDiffAdminBundle:CompetenceClasseAnnee:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CompetenceClasseAnnee entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EDiffAdminBundle:CompetenceClasseAnnee')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CompetenceClasseAnnee entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('competenceclasseannee'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
