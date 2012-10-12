<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\Question;
use EDiff\Bundle\AdminBundle\Form\QuestionType;

/**
 * Question controller.
 *
 */
class QuestionController extends Controller
{
    /**
     * Lists all Question entities.
     *
     */
    public function indexAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'question')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
    	$pagination_question_par_page = 10;
		if($this->get('request')->query->get('pagination_question_page')) {
			$page = $this->get('request')->query->get('pagination_question_page');
		}
		else {
			$page = 0;
		}
		
    	$filtreNiveau = $this->get('request')->request->get('choix_niveau');
        if($filtreNiveau==null) {
        	$filtreNiveau = -1;
        }
        
    	$filtreMatiere = $this->get('request')->request->get('choix_matiere');
        if($filtreMatiere==null) {
        	$filtreMatiere = -1;
        }
    	
        $em = $this->getDoctrine()->getEntityManager();
		$matieres = $em->getRepository('EDiffAdminBundle:Matiere')->findAll();
		$all_entities = $em->getRepository('EDiffAdminBundle:Question')->getAllSearch($filtreNiveau, $filtreMatiere);
		$entities = $em->getRepository('EDiffAdminBundle:Question')->getSearch($page, $pagination_question_par_page, $filtreNiveau, $filtreMatiere);

        $nb_elements = count($all_entities);
        $nb_pages = ceil($nb_elements / $pagination_question_par_page);
        
        $isDelete = false;
        if($this->get('request')->query->get('delete') == 'true')
        	$isDelete = true;
        
        return $this->render('EDiffAdminBundle:Question:index.html.twig', array(
            'entities' => $entities,
        	'delete'   => $isDelete,
        	'nb_pages' => $nb_pages,
        	'page'	   => $page,
        	'matieres' => $matieres,
        	'filtreNiveau' => $filtreNiveau,
        	'filtreMatiere' => $filtreMatiere
        ));
    }

    /**
     * Finds and displays a Question entity.
     *
     */
    public function showAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'question')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $reponses = $em->getRepository('EDiffAdminBundle:Reponse')->findByQuestion($id);
        
        $deleteForm = $this->createDeleteForm($id);

        $isUpdate = false;
        if($this->get('request')->query->get('update') == 'true')
        	$isUpdate = true;
        	
        return $this->render('EDiffAdminBundle:Question:show.html.twig', array(
            'entity'      => $entity,
        	'reponses'    => $reponses,
        	'update'	  => $isUpdate,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Question entity.
     *
     */
    public function newAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'question')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $entity = new Question();
        $form   = $this->createForm(new QuestionType(), $entity);

        return $this->render('EDiffAdminBundle:Question:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Question entity.
     *
     */
    public function createAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'question')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $entity  = new Question();
        $request = $this->getRequest();
        $form    = $this->createForm(new QuestionType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', array('id' => $entity->getId())));
            
        }

        return $this->render('EDiffAdminBundle:Question:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Question entity.
     *
     */
    public function editAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'question')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createForm(new QuestionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:Question:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Question entity.
     *
     */
    public function updateAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'question')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm   = $this->createForm(new QuestionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', array('id' => $id, 'update' => 'true')));
        }

        return $this->render('EDiffAdminBundle:Question:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Question entity.
     *
     */
    public function deleteAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'question')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EDiffAdminBundle:Question')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Question entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('question', array('delete' => 'true')));
    }

    private function createDeleteForm($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'question')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
