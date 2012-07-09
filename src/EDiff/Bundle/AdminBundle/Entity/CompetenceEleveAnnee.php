<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDiff\Bundle\AdminBundle\Entity\CompetenceEleveAnnee
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\CompetenceEleveAnneeRepository")
 */
class CompetenceEleveAnnee
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
     * @var integer $nb_points
     *
     * @ORM\Column(name="nb_points", type="integer")
     */
    private $nb_points;

    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Competence")
     */
    private $competence;
    
    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\User")
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\AnneeScolaire")
     */
    private $anneescolaire;
        
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
     * Set nb_points
     *
     * @param integer $nbPoints
     */
    public function setNbPoints($nbPoints)
    {
        $this->nb_points = $nbPoints;
    }

    /**
     * Get nb_points
     *
     * @return integer 
     */
    public function getNbPoints()
    {
        return $this->nb_points;
    }
    
    // On définit le getter et le setter associé à la relation Competence.
    public function getCompetence()
    {
        return $this->competence;
    }

    // Ici, on force le type de l'argument à être une instance de notre entité Competence.
    public function setCompetence(\EDiff\Bundle\AdminBundle\Entity\Competence $competence)
    {
        $this->competence = $competence;
    }

    // On définit le getter et le setter associé à la relation Eleve.
    public function getEleve()
    {
        return $this->eleve;
    }

    // Ici, on force le type de l'argument à être une instance de notre entité Eleve.
    public function setEleve(\EDiff\Bundle\AdminBundle\Entity\User $eleve)
    {
        $this->eleve = $eleve;
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
}