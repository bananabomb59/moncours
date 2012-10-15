<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\Questionnaire;
use EDiff\Bundle\AdminBundle\Form\QuestionnaireType;

/**
 * Questionnaire controller.
 *
 */
class QuestionnaireController extends Controller
{
    /**
     * Lists all Questionnaire entities.
     *
     */
    public function indexAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
    	$pagination_question_par_page = 10;
		if($this->get('request')->query->get('pagination_questionnaire_page')) {
			$page = $this->get('request')->query->get('pagination_questionnaire_page');
		}
		else {
			$page = 0;
		}
    	
    	$filtreMatiere = $this->get('request')->request->get('choix_matiere');
        if($filtreMatiere==null) {
        	$filtreMatiere = -1;
        }
        
    	$filtreClasse = $this->get('request')->request->get('choix_classe');
        if($filtreClasse==null) {
        	$filtreClasse = -1;
        }
        
    	$filtreAnnee = $this->get('request')->request->get('choix_annee');
        if($filtreAnnee==null) {
        	$filtreAnnee = -1;
        }
        
    	$filtreStatut = $this->get('request')->request->get('choix_statut');
        if($filtreStatut==null) {
        	$filtreStatut = -1;
        }
    	
        $em = $this->getDoctrine()->getEntityManager();

        $all_entities = $em->getRepository('EDiffAdminBundle:Questionnaire')->getAllSearch($filtreAnnee, $filtreClasse, $filtreMatiere, $filtreStatut);
		$entities = $em->getRepository('EDiffAdminBundle:Questionnaire')->getSearch($page, $pagination_question_par_page, $filtreAnnee, $filtreClasse, $filtreMatiere, $filtreStatut);        
		$matieres = $em->getRepository('EDiffAdminBundle:Matiere')->findAll();
		$classes = $em->getRepository('EDiffAdminBundle:Classe')->findAll();
		$annees = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->findAll();
        
		$nb_elements = count($all_entities);
        $nb_pages = ceil($nb_elements / $pagination_question_par_page);
		
        $isDelete = false;
        if($this->get('request')->query->get('delete') == 'true')
        	$isDelete = true;
        
        return $this->render('EDiffAdminBundle:Questionnaire:index.html.twig', array(
            'entities' => $entities,
        	'delete'   => $isDelete,
        	'nb_pages' => $nb_pages,
        	'page'	   => $page,
        	'matieres' => $matieres,
        	'classes' => $classes,
        	'annees' => $annees,
        	'filtreMatiere' => $filtreMatiere,
        	'filtreClasse' => $filtreClasse,
        	'filtreAnnee' => $filtreAnnee,
        	'filtreStatut' => $filtreStatut
        ));
    }

    /**
     * Finds and displays a Questionnaire entity.
     *
     */
    public function showAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Questionnaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionnaire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $isUpdate = false;
        if($this->get('request')->query->get('update') == 'true')
        	$isUpdate = true;
        
        return $this->render('EDiffAdminBundle:Questionnaire:show.html.twig', array(
            'entity'      => $entity,
        	'update'	  => $isUpdate,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Questionnaire entity.
     *
     */
    public function newAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $entity = new Questionnaire();
        $form   = $this->createForm(new QuestionnaireType(), $entity);

        return $this->render('EDiffAdminBundle:Questionnaire:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Questionnaire entity.
     *
     */
    public function createAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $entity  = new Questionnaire();
        $request = $this->getRequest();
        $form    = $this->createForm(new QuestionnaireType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('questionnaire_show', array('id' => $entity->getId())));
            
        }

        return $this->render('EDiffAdminBundle:Questionnaire:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Questionnaire entity.
     *
     */
    public function editAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Questionnaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionnaire entity.');
        }

        $editForm = $this->createForm(new QuestionnaireType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EDiffAdminBundle:Questionnaire:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Questionnaire entity.
     *
     */
    public function updateAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EDiffAdminBundle:Questionnaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionnaire entity.');
        }

        $editForm   = $this->createForm(new QuestionnaireType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('questionnaire_show', array('id' => $id, 'update' => 'true')));
        }

        return $this->render('EDiffAdminBundle:Questionnaire:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Questionnaire entity.
     *
     */
    public function deleteAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EDiffAdminBundle:Questionnaire')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Questionnaire entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('questionnaire', array('delete' => 'true')));
    }

    private function createDeleteForm($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaire')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
