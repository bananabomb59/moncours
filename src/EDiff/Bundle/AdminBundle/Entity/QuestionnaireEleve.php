<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDiff\Bundle\AdminBundle\Entity\QuestionnaireEleve
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\QuestionnaireEleveRepository")
 */
class QuestionnaireEleve
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
     * @var smallint $numero_question
     *
     * @ORM\Column(name="numero_question", type="smallint")
     */
    private $numero_question;

    /**
     * @var text $reponse
     *
     * @ORM\Column(name="reponse", type="text")
     */
    private $reponse;

    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Questionnaire")
     */
    private $questionnaire;

    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Question")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\User")
     */
    private $eleve;
        
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
     * Set numero_question
     *
     * @param smallint $numeroQuestion
     */
    public function setNumeroQuestion($numeroQuestion)
    {
        $this->numero_question = $numeroQuestion;
    }

    /**
     * Get numero_question
     *
     * @return smallint 
     */
    public function getNumeroQuestion()
    {
        return $this->numero_question;
    }

    /**
     * Set reponse
     *
     * @param text $reponse
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;
    }

    /**
     * Get reponse
     *
     * @return text 
     */
    public function getReponse()
    {
        return $this->reponse;
    }

	// On définit le getter et le setter associé à la relation Questionnaire.
    public function getQuestionnaire()
    {
        return $this->questionnaire;
    }

    // Ici, on force le type de l'argument à être une instance de notre entité Questionnaire.
    public function setQuestionnaire(\EDiff\Bundle\AdminBundle\Entity\Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }   

	// On définit le getter et le setter associé à la relation Question.
    public function getQuestion()
    {
        return $this->question;
    }

    // Ici, on force le type de l'argument à être une instance de notre entité Question.
    public function setQuestion(\EDiff\Bundle\AdminBundle\Entity\Question $question)
    {
        $this->question = $question;
    }

	// On définit le getter et le setter associé à la relation User (que j'appelle arbitrairement Eleve).
    public function getEleve()
    {
        return $this->eleve;
    }

    // Ici, on force le type de l'argument à être une instance de notre entité User (idem).
    public function setEleve(\EDiff\Bundle\AdminBundle\Entity\User $eleve)
    {
        $this->eleve = $eleve;
    }     
}