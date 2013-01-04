<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EDiff\Bundle\AdminBundle\Entity\Questionnaire;
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
    public function indexAction($questionnaire, $eleve)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        $em = $this->getDoctrine()->getEntityManager();
        $myEleve = $em->getRepository('EDiffAdminBundle:User')->find($eleve);
        $myQuestionnaire = $em->getRepository('EDiffAdminBundle:Questionnaire')->find($questionnaire);
        $reponses = $em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->getAllSearch(-1, $eleve, $questionnaire);
        
        return $this->render('EDiffAdminBundle:QuestionnaireEleve:index.html.twig', array(
            'reponses' => $reponses,
        	'myEleve' => $myEleve,
        	'myQuestionnaire' => $myQuestionnaire,
        	'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        ));
    }
    
	public function indexEtape1Action()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));

        $em = $this->getDoctrine()->getEntityManager();
        
        $classes = $em->getRepository('EDiffAdminBundle:Classe')->findAll();
		$annees = $em->getRepository('EDiffAdminBundle:AnneeScolaire')->findAll();
		$matieres = $em->getRepository('EDiffAdminBundle:Matiere')->findAll();

        return $this->render('EDiffAdminBundle:QuestionnaireEleve:indexEtape1.html.twig', array(
            'classes' => $classes,
        	'annees'   => $annees,
        	'matieres'  => $matieres,
        	'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"       	
        ));
    }
    
	public function indexEtape1RecupQuestionnaireAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));

    	// on récupère la reponse via le formulaire
    	$request = $this->get('request');
    	$classe = $request->request->get('classe');
    	$annee = $request->request->get('annee');
    	$matiere = $request->request->get('matiere');
    	
    	// On récupère les questionnaires
        $em = $this->getDoctrine()->getEntityManager();
        $questionnaires = $em->getRepository('EDiffAdminBundle:Questionnaire')->getAllSearch($annee, $classe, $matiere, -1);

        // On retourne la réponse AJAX
        return $this->render('EDiffAdminBundle:QuestionnaireEleve:indexEtape1RecupQuestionnaire.html.twig', array(
            'questionnaires' => $questionnaires
        ));
    }
    
	public function indexEtape2Action()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));

    	// on récupère la reponse via les paramètres
    	$request = $this->get('request');
    	$classe = $request->query->get('classe');
    	$annee = $request->query->get('annee');
    	$questionnaire = $request->query->get('id');
    	
		// On recupere les objets qui vont bien
        $em = $this->getDoctrine()->getEntityManager();
        $quest = $em->getRepository('EDiffAdminBundle:Questionnaire')->find($questionnaire);
		$elevesClasse = $em->getRepository('EDiffAdminBundle:Classe_Eleve_Annee')->findBy(array('classe' => $classe, 'annee' => $annee));

		$elevesId = array();
		foreach ($elevesClasse as $eleveClasse)
		{
			$elevesId[] = $eleveClasse->getUser()->getId();
		} 
		
		$eleves = $em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->getQuestionnaireEleve(array(1,4), $questionnaire);
		
        return $this->render('EDiffAdminBundle:QuestionnaireEleve:indexEtape2.html.twig', array(
            'eleves' => $eleves,
        	'quest' => $quest,
        	'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        ));
    }

    /**
     * Finds and displays a QuestionnaireEleve entity.
     *
     */
    public function showAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
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
        	'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        ));
    }

    /**
     * Displays a form to create a new QuestionnaireEleve entity.
     *
     */
    public function newAction()
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
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
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
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
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
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
        	'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        ));
    }

    /**
     * Edits an existing QuestionnaireEleve entity.
     *
     */
    public function updateAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
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
        	'layout' => "EDiffAdminBundle::layout_".$this->getRequest()->getSession()->get('user')->getDroits().".html.twig"
        ));
    }

    /**
     * Deletes a QuestionnaireEleve entity.
     *
     */
    public function deleteAction($id)
    {
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
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
    	if(AccueilController::verifUserAdmin($this->getRequest()->getSession(), 'questionnaireeleve')) return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
    	
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
