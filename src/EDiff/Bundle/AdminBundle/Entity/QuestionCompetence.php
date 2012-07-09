<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDiff\Bundle\AdminBundle\Entity\QuestionCompetence
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\QuestionCompetenceRepository")
 */
class QuestionCompetence
{
	/**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Question")
     */
    private $question;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Competence")
     */
    private $competence;

    /**
     * @var smallint $nb_points
     *
     * @ORM\Column(name="nb_points", type="smallint")
     */
    private $nb_points;


    // On définit le getter et le setter pour Classe
    public function getQuestion()
    {
        return $this->question;
    }
    public function setQuestion(\EDiff\Bundle\AdminBundle\Entity\Question $question)
    {
        $this->question = $question;
    }

    // On définit le getter et le setter pour Competence
    public function getCompetence()
    {
        return $this->competence;
    }
    public function setCompetence(\EDiff\Bundle\AdminBundle\Entity\Competence $competence)
    {
        $this->competence = $competence;
    }

    /**
     * Set nb_points
     *
     * @param smallint $nbPoints
     */
    public function setNbPoints($nbPoints)
    {
        $this->nb_points = $nbPoints;
    }

    /**
     * Get nb_points
     *
     * @return smallint 
     */
    public function getNbPoints()
    {
        return $this->nb_points;
    }
}