<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EDiff\Bundle\AdminBundle\Entity\QuestionnaireEleve;

class AccueilController extends Controller
{
    
    public function indexAction()
    {
    	// $logger = $this->get('logger');
    	// $logger->info("login : " . $login);
    	
    	$hasError = false;
        if($this->get('request')->query->get('hasError') == 'true')
        	$hasError = true;
    	
        return $this->render('EDiffAdminBundle:Accueil:index.html.twig', array('hasError' => $hasError, 'message' => $this->get('request')->query->get('message')));
    }
    
    public function indexAdminAction()
    {
        return $this->render('EDiffAdminBundle:Accueil:indexAdmin.html.twig', array());
    }
    
	public function indexProfAction()
    {
        return $this->render('EDiffAdminBundle:Accueil:indexProf.html.twig', array());
    }
    
	public function deconnexionAction()
    {
    	$session = $this->getRequest()->getSession();
    	$session->set('user', '');
        return $this->render('EDiffAdminBundle:Accueil:index.html.twig', array('hasError' => false, 'message' => ''));
    }
    
	public function connectAction()
    {
    	$message = "";
    	$request = $this->get('request');
	        
		if( $request->getMethod() == 'POST' ) {
        	$login = $request->request->get('login');
	        $password = $request->request->get('password');
	        
	        $em = $this->getDoctrine()->getEntityManager();
        	$user = $em->getRepository('EDiffAdminBundle:User')->findOneByLogin($login);
        	
        	if( $user != null )
        	{
				if($user->getPassword() == $password) {
					// on met le user en session
					$session = $this->getRequest()->getSession();
					$session->set('user', $user);
					
					// On récupère les droits pour rediriger vers le site admin ou front
					$droits = $user->getDroits();
					if($droits == 'eleve') {
						return $this->redirect($this->generateUrl('EDiffAdminBundle_liste_matieres', array()));
					}
					else if($droits == 'prof') {
						return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil_BO_prof', array()));
					}
					else if($droits == 'admin') {
						return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil_BO_admin', array()));
					}
					else {
						// Aucun droits en BDD
						$message = "Une erreur est survenue. Veuillez réessayer.";
					}
				}
				else {
					// Password non correct
					$message = "Le mot de passe n'est pas correct !";
				}
        	}
        	else {
        		// le login n'existe pas en BDD
        		$message = "Le login '" . $login . "' n'existe pas !";
        	}
		}
		else {
			// On vient pas d'un POST => ERREUR
			$message = "Une erreur est survenue. Veuillez réessayer.";
		}

        return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array('hasError' => 'true', 'message' => $message)));
    }

	public function matiereAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EDiffAdminBundle:Matiere')->findAll();
        	
        return $this->render('EDiffAdminBundle:Accueil:matiere.html.twig', array(
            'entities' => $entities
        ));
    }
    
	public function validerMatiereAction()
    {
    	$message = "";
    	$request = $this->get('request');
	        
		if( $request->getMethod() == 'POST' ) {
        	$matiereId = $request->request->get('choix_matiere');
	        
	        $em = $this->getDoctrine()->getEntityManager();
        	$matiere = $em->getRepository('EDiffAdminBundle:Matiere')->findOneById($matiereId);
        	
        	if( $matiere != null )
        	{
				// on met la matière en session
				$session = $this->getRequest()->getSession();
				$session->set('matiere', $matiere);
				
				return $this->redirect($this->generateUrl('EDiffAdminBundle_liste_questionnaires', array()));
        	}
        	else {
        		// le login n'existe pas en BDD
        		$message = "La matiere d'id '" . $matiereId . "' n'existe pas !";
        	}
		}
		else {
			// On vient pas d'un POST => ERREUR
			$message = "Une erreur est survenue. Veuillez réessayer.";
		}

        return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array('hasError' => 'true', 'message' => $message)));
    }
    
	public function questionnaireAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	// on récupère la matière de la session
		$session = $this->getRequest()->getSession();
		$matiere = $session->get('matiere');
    	
        $questionnaires = $em->getRepository('EDiffAdminBundle:Questionnaire')->findByMatiere($matiere->getId());
        	
        return $this->render('EDiffAdminBundle:Accueil:questionnaire.html.twig', array(
            'entities' => $questionnaires
        ));
    }
    
	public function questionAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$logger = $this->get('logger');		
		$logger->info("id : " . $id);
    	
        $questionnaire = $em->getRepository('EDiffAdminBundle:Questionnaire')->find($id);
        
        // on recupere les infos sur le questionnaire
        $nbQuestions = $questionnaire->getNbQuestionsARepondre();
        
        /* TODO
         * [JU] DEROULEMENT DE LA RECUPERATION DE LA QUESTION
         * 
         * 0/vérifier s'il y a une questions à afficher ou non (nb de questions du questionnaires vs nb de questions déjà répondues)
         * 
         * 1/recuperer (en session) les infos de la question precedente (QP) : niveau, bonne ou mauvaise reponse 
         *  	si rien en session -> niveau = 2
         *  	si niveau != 1 et QP fausse -> niveau --
         *  	si niveau ==1 et QP fausse -> niveau = 1
         *  	si niveau !=3 et QP bonne -> niveau ++
         *  	si niveau ==3 et QP bonne -> niveau = 3
         *  
         * 2/recuperer la liste des id des questions déjà répondues (via l'objet Questionnaire Eleve)
         * 
         * 3/recuperer la question suivante en récupérant les questions du questionnaire avec le bon niveau et where id not in la liste précédente limit(1)
         * 
         * 4/si pas de question trouvée -> reeffectuer l'opération tous niveaux confondus
         * 
         */
        
        $questions = $questionnaire->getQuestions();
        
        // on récupere la question
        $question = $questions[1];

        $logger->info("question id : " . $question->getId());
        
        // puis les réponses associées
        $reponses = $em->getRepository('EDiffAdminBundle:Reponse')->findByQuestion($question->getId());
        $logger->info("reponses : " . count($reponses));
        
        return $this->render('EDiffAdminBundle:Accueil:question.html.twig', array(
            'questionnaire' => $questionnaire,
        	'question' => $question,
        	'nbQuestion' => $nbQuestions,
        	'reponses' => $reponses
        ));
    }
    
	public function validerQuestionAction($id)
    {
    	$logger = $this->get('logger');	
    	
    	// on récupère la reponse via le formulaire
    	$request = $this->get('request');
    	$reponseId = $request->request->get('reponse');
    	$questionnaireId = $request->request->get('questionnaire');
    	
    	// on récupere la bonne reponse a la question
    	$session = $this->getRequest()->getSession();
		$user = $session->get('user');
		
		// on récupère les objets pour la création du questionnaire eleve
    	$em = $this->getDoctrine()->getEntityManager();   
    	$eleve = $em->getRepository('EDiffAdminBundle:User')->find($user->getId());
    	$question = $em->getRepository('EDiffAdminBundle:Question')->find($id);
    	$reponse = $em->getRepository('EDiffAdminBundle:Reponse')->find($reponseId);
    	$questionnaire = $em->getRepository('EDiffAdminBundle:Questionnaire')->find($questionnaireId); 
        $bonneReponse = $em->getRepository('EDiffAdminBundle:Reponse')->findOneBy(array('question' => $id, 'bonne_ou_mauvaise' => '1'));     
		
        // on enregistre la reponse de l'élève     
        $questionEleve = new QuestionnaireEleve();
        $questionEleve->setNumeroQuestion(1);
        $questionEleve->setReponse($reponseId);
        $questionEleve->setQuestionnaire($questionnaire);
        $questionEleve->setQuestion($question);
        $questionEleve->setEleve($eleve);
        
        
        /*
         * TODO
         * [JU]il faudra mettre en session le niveau et le bool bonne ou mauvaise réponse
         */
        
        // on redirige en fonction de la bonne reponse ou non
    	if($bonneReponse->getId()==$reponseId) {
    		$questionEleve->setBonneOuMauvaise(1);
    		$em->persist($questionEleve);
			$em->flush();
        	return $this->render('EDiffAdminBundle:Accueil:bonne_reponse.html.twig', array());
    	}
    	else {
    		$questionEleve->setBonneOuMauvaise(2);
    		$em->persist($questionEleve);
			$em->flush();
    		return $this->render('EDiffAdminBundle:Accueil:mauvaise_reponse.html.twig', array('reponse' => $bonneReponse->getLibelle()));
    	}
    }
    
	public function recapAction()
    {	
        return $this->render('EDiffAdminBundle:Accueil:recap.html.twig', array());
    }
    
}
