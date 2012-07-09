<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDiff\Bundle\AdminBundle\Entity\Questionnaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\QuestionnaireRepository")
 */
class Questionnaire
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $titre
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;
    
    /**
     * @var string $statut
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;    
        
    /**
     * @var smallint $nb_questions_a_repondre
     *
     * @ORM\Column(name="nb_questions_a_repondre", type="smallint")
     */
    private $nb_questions_a_repondre;

    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Matiere")
     */
    private $matiere;

    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\AnneeScolaire")
     */
    private $anneescolaire;
    
    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Classe")
     */
    private $classe;

 	/**
     * @ORM\ManyToMany(targetEntity="EDiff\Bundle\AdminBundle\Entity\Question")
     */
    private $questions;    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set statut
     *
     * @param string $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }
       
        
    /**
     * Set nb_questions_a_repondre
     *
     * @param smallint $nbQuestionsARepondre
     */
    public function setNbQuestionsARepondre($nbQuestionsARepondre)
    {
        $this->nb_questions_a_repondre = $nbQuestionsARepondre;
    }

    /**
     * Get nb_questions_a_repondre
     *
     * @return smallint 
     */
    public function getNbQuestionsARepondre()
    {
        return $this->nb_questions_a_repondre;
    }
	
    // On définit le getter et le setter associé à la relation Matiere.
    public function getMatiere()
    {
        return $this->matiere;
    }

    // Ici, on force le type de l'argument à être une instance de notre entité Matiere.
    public function setMatiere(\EDiff\Bundle\AdminBundle\Entity\Matiere $matiere)
    {
        $this->matiere = $matiere;
    }
	
    // On définit le getter et le setter associé à la relation AnneeScolaire.
    public function getAnneeScolaire()
    {
        return $this->anneescolaire;
    }

    // Ici, on force le type de l'argument à être une instance de notre entité AnneeScolaire.
    public function setAnneeScolaire(\EDiff\Bundle\AdminBundle\Entity\AnneeScolaire $anneescolaire)
    {
        $this->anneescolaire = $anneescolaire;
    }
    
	// On définit le getter et le setter associé à la relation Classe.
    public function getClasse()
    {
        return $this->classe;
    }

    // Ici, on force le type de l'argument à être une instance de notre entité Classe.
    public function setClasse(\EDiff\Bundle\AdminBundle\Entity\Classe $classe)
    {
        $this->classe = $classe;
    }

    // Comme la propriété question doit être un ArrayCollection, souvenez-vous ;
    // on doit la définir dans un constructeur.
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection;
    }

    // On est dans le côté propriétaire, on définit le getter.
    // Notez le « s » à getGroupes, on récupère une liste de groupes ici !
    public function getQuestions()
    {
        return $this->questions;
    }

    // Le setter. Attention, on n'a pas un setGroupes() mais un addGroupe() sans « s » !
    public function addQuestion(\EDiff\Bundle\AdminBundle\Entity\Question $question)
    {
        // On traite vraiment notre ArrayCollection comme un tableau.
        $this->questions[] = $question;
    }    
    
    
    public function __toString()
    {
    	return $this->titre;
    }
}