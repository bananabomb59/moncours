<?php

namespace EDiff\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EDiff\Bundle\AdminBundle\Entity\QuestionnaireEleve;
use EDiff\Bundle\AdminBundle\Utils;

class AccueilController extends Controller
{
    
    public function indexAction()
    {
    	// $logger = $this->get('logger');
    	// $logger->info("login : " . $login);
    	
    	$hasError = false;
        if($this->get('request')->query->get('hasError') == 'true')
        	$hasError = true;
        	
        /*on verifie si un user n'est pas deja identifie, si oui on le redirige vers la bonne page */
   		 $session = $this->getRequest()->getSession();
			$user=$session->get('user', false);
			if ($user) {
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
    	$session->clear();
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
        		if($user->getEncryptedPassword() == Utils::encrypt_password($password)) {
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
    	
    	/* Vérifier si le user est en session, sinon retour à l'écran de login */
    	$session = $this->getRequest()->getSession();
    	$user=$session->get('user',false);
    	if (!$user || $user->getDroits() != 'eleve') {
			return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
		}

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
    	
    	/* Vérifier si le user est en session, sinon retour à l'écran de login */
    	$session = $this->getRequest()->getSession();
    	$user=$session->get('user',false);
		if (!$user || $user->getDroits() != 'eleve') {
			return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
		}
    	
    	// on récupère la matière de la session
		$session = $this->getRequest()->getSession();
		$matiere = $session->get('matiere',false);
		
    	/*pas de matiere en session, retour au choix des matieres */
		if (!$matiere) {
			return $this->redirect($this->generateUrl('EDiffAdminBundle_liste_matieres', array()));
		}
    	
        $questionnaires = $em->getRepository('EDiffAdminBundle:Questionnaire')->findByMatiere($matiere->getId());
        	
        /*
         * affiner selon les statuts
         * 1/ statut Préparation > ne pas afficher
         * 2/ statut Fermé > afficher un lien pour consultation uniquement
         * 3/ statut Ouvert > afficher un lien pour complétion OU si l'eleve a déjà répondu au questionnaire, afficher un lien pour consultation
         */
        
        $liste_questionnaires_deja_completes = AccueilController::getQuestionnairesDejaRemplisForEleve($user);
        
        /* on met en session tous les questionnaires, et le mode lecture ou edition */
        foreach ($questionnaires as $q){
        	if ($q->getStatut()==0) {
        		/* questionnaire en preparation, impossible de l'atteindre normalement */
        	}
        	if ($q->getStatut() == 2){
        		/* questionnaire fermé, lecture uniquement */
        		$session->set('questionnaire_'.$q->getId(), array('mode' => 'lecture', 'num_question' => 1));
        	}
        	if ($q->getStatut()==1){
        		/* questionnaire ouvert, 2 cas a distinguer */
        		if (in_array($q->getId(),$liste_questionnaires_deja_completes)){
        			$session->set('questionnaire_'.$q->getId(), array('mode' => 'lecture', 'num_question' => 1));
        		}
        		else {
        			$session->set('questionnaire_'.$q->getId(), array('mode' => 'edition'));
        		}
        	}
        }
        
        return $this->render('EDiffAdminBundle:Accueil:questionnaire.html.twig', array(
            'entities' => $questionnaires,
        	'liste' => $liste_questionnaires_deja_completes
        ));
    }
    
	public function getQuestionnairesDejaRemplisForEleve($eleve)
    {
    	$liste=array();
    	$em = $this->getDoctrine()->getEntityManager();   	
        $questionnaireeleves=$em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->findByEleve($eleve->getId());

        foreach ($questionnaireeleves as $q){
        	$id=$q->getQuestionnaire()->getId();
        	if(!in_array($id,$liste) && ($q->getQuestionnaire()->getNbQuestionsARepondre() == $q->getNumeroQuestion())) $liste[]=$id;
        }	
        return $liste;
    }
    
    public function validerQuestionnaireAction()
    {
    	$message = "";
    	$request = $this->get('request');
	        
		if( $request->getMethod() == 'POST' ) {
        	$questionnaireId = $request->request->get('questionnaire_choisi');
        	// on met le questionnaire en session
			$session = $this->getRequest()->getSession();
			$session->set('questionnaire_courant', $questionnaireId);
        	
        	return $this->redirect($this->generateUrl('EDiffAdminBundle_question_front', array('id' => $questionnaireId)));
		}  
		else {
			$message="Impossible d'effectuer cette action";
			return $this->redirect($this->generateUrl('EDiffAdminBundle_liste_questionnaires', array('hasError' => 'true', 'message' => $message)));
		}
    }
    
	public function questionAction($id)
    {
    	/* Vérifier si le user est en session, sinon retour à l'écran de login */
    	$session = $this->getRequest()->getSession();
    	$user=$session->get('user',false);
		if (!$user || $user->getDroits() != 'eleve') {
			return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
		}
		
		
		/* On vérifie également si le questionnaire_id en session correspond à l'id qu'on essaye d'atteindre, histoire d'éviter les ptits malins
		 * qui accèdent à un questionnaire par l'url
		 */
		$session_questionnaire_id=$session->get('questionnaire_courant',false);
    	if (!$session_questionnaire_id) {
			return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
		}
		if($session_questionnaire_id!= $id){
			return $this->redirect($this->generateUrl('EDiffAdminBundle_question_front', array('id' => $session_questionnaire_id)));
		} 
		
    	$em = $this->getDoctrine()->getEntityManager();   	
        $questionnaire = $em->getRepository('EDiffAdminBundle:Questionnaire')->find($id);
        
		$info_questionnaire=$session->get('questionnaire_'.$questionnaire->getId(), false);
		if (!$info_questionnaire){
			$message="Impossible d'effectuer cette action";
			return $this->redirect($this->generateUrl('EDiffAdminBundle_liste_questionnaires', array('hasError' => 'true', 'message' => $message)));	
		}
		else if ($info_questionnaire['mode'] == "edition") {
        
	        /* DEROULEMENT DE LA RECUPERATION DE LA QUESTION
	         * 
	         * 0/vérifier s'il y a une question à afficher ou non (nb de questions du questionnaire vs nb de questions déjà répondues)
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
	        
	        /* Etape 0 */
	        $nb_questions_a_repondre=$questionnaire->getNbQuestionsARepondre();
	        $nb_questions_deja_repondues=AccueilController::getNumQuestionMaxForQuestionnaireAndEleve($questionnaire, $user);
	        if ($nb_questions_deja_repondues >= $nb_questions_a_repondre) {
	        	return $this->redirect($this->generateUrl('EDiffAdminBundle_recap_final', array('id' => $questionnaire->getId())));
	        }
	        
	        /* Etape 1 */
	        $niveau_question_precedente=$session->get('question_precedente_niveau', false);
	        $reponse_question_precedente=$session->get('question_precedente_bonne_ou_mauvaise', false);
	        if (!$niveau_question_precedente || !$reponse_question_precedente){
	        	$niveau=2;
	        }
	        else if ($niveau_question_precedente !=1 && $reponse_question_precedente=="mauvaise"){
	        	$niveau=$niveau_question_precedente-1;
	        }
	        else if ($niveau_question_precedente ==1 && $reponse_question_precedente=="mauvaise"){
	        	$niveau=1;
	        }        
	    	else if ($niveau_question_precedente !=3 && $reponse_question_precedente=="bonne"){
	        	$niveau=$niveau_question_precedente+1;
	        }
	        else if ($niveau_question_precedente ==3 && $reponse_question_precedente=="bonne"){
	        	$niveau=3;
	        }
	        
	        /* Etape 2 */
	        $questions_deja_repondues=AccueilController::getQuestionsDejaReponduesForQuestionnaireAndEleve($questionnaire, $user);
	                
	        /* Etape 3 & 4 */
	         $question = AccueilController::getQuestionSuivanteForQuestionnaireAndNiveauAndNotInDejaRepondues($questionnaire, $niveau, $questions_deja_repondues);
	         if (!$question){
				/* plus de question disponible > recap */
	         	return $this->redirect($this->generateUrl('EDiffAdminBundle_recap_final', array('id' => $questionnaire->getId())));
	         }
	        
	        // les réponses associées a la question
	        $reponses = $em->getRepository('EDiffAdminBundle:Reponse')->findByQuestion($question->getId());
	
	        
	        return $this->render('EDiffAdminBundle:Accueil:question.html.twig', array(
	            'questionnaire' => $questionnaire,
	        	'question' => $question,
	        	'nbQuestion' => $nb_questions_a_repondre,
	        	'reponses' => $reponses,
	        	'numQuestion' => $nb_questions_deja_repondues+1
	        ));
		}
		else if ($info_questionnaire['mode'] == "lecture") {
			$num_question=$info_questionnaire['num_question'];
			
			$info_questionnaire=$session->set('questionnaire_'.$questionnaire->getId(), array('mode' => 'lecture', 'num_question' => $num_question+1));
			
			if ($num_question > $questionnaire->getNbQuestionsARepondre()) {
				return $this->redirect($this->generateUrl('EDiffAdminBundle_recap_final', array('id' => $questionnaire->getId())));
			}
			
			$questionnaireeleve = $em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->findOneBy(
				array("questionnaire" => $questionnaire->getId(), "numero_question" => $num_question, 'eleve' => $user->getId())
			);
				
			if (!$questionnaireeleve){
				return $this->render('EDiffAdminBundle:Accueil:questionnaire_non_rempli.html.twig', array());
			}
			
			$question=$questionnaireeleve->getQuestion();
						
			// les réponses associées a la question
	        $reponses = $em->getRepository('EDiffAdminBundle:Reponse')->findByQuestion($question->getId());
	        
	        foreach($reponses as $r){
	        	if($r->getBonneOuMauvaise()==1){
	        		$bonneReponse=$r;
	        	}
	        }
	        
	        $reponseChoisie = $em->getRepository('EDiffAdminBundle:Reponse')->findOneById($questionnaireeleve->getReponse());
	        			
	        return $this->render('EDiffAdminBundle:Accueil:question_read.html.twig', array(
	            'questionnaire' => $questionnaire,
	        	'question' => $question,
	        	'nbQuestion' => $questionnaire->getNbQuestionsARepondre(),
	        	'reponses' => $reponses,
	        	'numQuestion' => $num_question,
	        	'reponseChoisie' => $reponseChoisie,
	        	'bonneOuMauvaise' => $questionnaireeleve->getBonneOuMauvaise(),
	        	'bonneReponse' => $bonneReponse
	        ));

		}
	        
    }
    
    
    public function getNumQuestionMaxForQuestionnaireAndEleve($questionnaire,$eleve)
    {
    	$num=0;
    	$em = $this->getDoctrine()->getEntityManager();   	
        $questionnaireeleve=$em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->findBy(
        	array("questionnaire" => $questionnaire->getId(), "eleve" => $eleve->getId()),
        	array("numero_question" => "DESC"),
        	'1'
        );
        foreach ($questionnaireeleve as $q){
        	$num=$q->getNumeroQuestion();
        }	
        return $num;
    }
    
	public function getQuestionsDejaReponduesForQuestionnaireAndEleve($questionnaire,$eleve)
    {
    	$liste_id=array();
    	$em = $this->getDoctrine()->getEntityManager();   	
        $questionnaireeleves=$em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->findBy(
        	array("questionnaire" => $questionnaire->getId(), "eleve" => $eleve->getId())
        );
        foreach ($questionnaireeleves as $q) {
        	$question=$q->getQuestion();
        	$liste_id[]=$question->getId();
        }
        return $liste_id;

    }
    
    public function getQuestionSuivanteForQuestionnaireAndNiveauAndNotInDejaRepondues($questionnaire, $niveau, $liste)
    {
    	$questions=$questionnaire->getQuestions();
    	foreach ($questions as $question){
    		if ($question->getNiveau()==$niveau) {
    			if (!in_array($question->getId(),$liste)) {
    				return $question;
    			}
    		}
    	}
    	foreach ($questions as $question){
  			if (!in_array($question->getId(),$liste)) {
   				return $question;
   			}
    	}
    	return false;
    }   
     
	public function validerQuestionAction($id)
    {
    	$logger = $this->get('logger');	
    	
    	// on récupère la reponse via le formulaire
    	$request = $this->get('request');
    	$reponseId = $request->request->get('reponse');
    	$questionnaireId = $request->request->get('questionnaire');
    	$numQuestion = $request->request->get('numQuestion');
    	
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
        $questionEleve->setNumeroQuestion($numQuestion);
        $questionEleve->setReponse($reponseId);
        $questionEleve->setQuestionnaire($questionnaire);
        $questionEleve->setQuestion($question);
        $questionEleve->setEleve($eleve);
        
        
        /* mettre en session le niveau */
        $session->set('question_precedente_niveau', $question->getNiveau());
        
        // on redirige en fonction de la bonne reponse ou non
    	if($bonneReponse->getId()==$reponseId) {
    		$questionEleve->setBonneOuMauvaise(1);
    		$em->persist($questionEleve);
			$em->flush();
			/* mettre en session la bonne reponse */
        	$session->set('question_precedente_bonne_ou_mauvaise', 'bonne');
        	/*
        	 * TODO attribuer les points de competence
        	 */
        	return $this->render('EDiffAdminBundle:Accueil:bonne_reponse.html.twig', array());
    	}
    	else {
    		$questionEleve->setBonneOuMauvaise(0);
    		$em->persist($questionEleve);
			$em->flush();
			/* mettre en session la mauvaise reponse */
        	$session->set('question_precedente_bonne_ou_mauvaise', 'mauvaise');
    		return $this->render('EDiffAdminBundle:Accueil:mauvaise_reponse.html.twig', array('reponse' => $bonneReponse->getLibelle()));
    	}
    }
    
	public function recapAction($id)
    {	
    	/* Vérifier si le user est en session, sinon retour à l'écran de login */
    	$session = $this->getRequest()->getSession();
    	$user=$session->get('user',false);
		if (!$user || $user->getDroits() != 'eleve') {
			return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
		}
		
		
		/* On vérifie également si le questionnaire_id en session correspond à l'id qu'on essaye d'atteindre, histoire d'éviter les ptits malins
		 * qui accèdent à un questionnaire par l'url
		 */
		$session_questionnaire_id=$session->get('questionnaire_courant',false);
    	if (!$session_questionnaire_id) {
			return $this->redirect($this->generateUrl('EDiffAdminBundle_accueil', array()));
		}
		if($session_questionnaire_id!= $id){
			return $this->redirect($this->generateUrl('EDiffAdminBundle_question_front', array('id' => $session_questionnaire_id)));
		} 
		
		/* Calculer la note, virer ce qui concerne le questionnaire en session */
		$note=0;
		$em = $this->getDoctrine()->getEntityManager();
		$questionnaire = $em->getRepository('EDiffAdminBundle:Questionnaire')->find($id);
		$questionnaireeleves=$em->getRepository('EDiffAdminBundle:QuestionnaireEleve')->findBy(
        	array("questionnaire" => $questionnaire->getId(), "eleve" => $user->getId())
        );
        foreach ($questionnaireeleves as $q){
        	if($q->getBonneOuMauvaise() == 1){
        		$note++;
        	}
        }
          
		
        return $this->render('EDiffAdminBundle:Accueil:recap.html.twig', array(
        	"note" => $note,
        	"points_max" => $questionnaire->getNbQuestionsARepondre(),
        	"questionnaire" => $questionnaire
        )
        );
    }
    
	static public function verifUserAdmin($session, $page)
    {		
    	$redirect = false;
    	
		/* Vérifier si le user est en session et s'il a les droits admin, sinon retour à l'écran de login */
		$user=$session->get('user', false);
		if ($user) {
			// On récupère les droits pour rediriger vers le site admin ou front
			$droits = $user->getDroits();
			if($droits == 'eleve') {
				$redirect = true;
			}
			if($droits == 'prof') {
				if($page != 'competence' && $page != 'question' && $page != 'questionnaire' && $page != 'questionnaireeleve' && $page != 'user') {
					$redirect = true;
				}
			}
		}
		else {
			$redirect = true;
		}
		
		return $redirect;
    }
}