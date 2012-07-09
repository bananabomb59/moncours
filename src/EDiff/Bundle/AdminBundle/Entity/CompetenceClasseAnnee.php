<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDiff\Bundle\AdminBundle\Entity\CompetenceClasseAnnee
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\CompetenceClasseAnneeRepository")
 */
class CompetenceClasseAnnee
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
     * @var integer $nb_points_a_atteindre
     *
     * @ORM\Column(name="nb_points_a_atteindre", type="integer")
     */
    private $nb_points_a_atteindre;

    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Competence")
     */
    private $competence;
    
    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Classe")
     */
    private $classe;

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
     * Set nb_points_a_atteindre
     *
     * @param integer $nbPointsAAtteindre
     */
    public function setNbPointsAAtteindre($nbPointsAAtteindre)
    {
        $this->nb_points_a_atteindre = $nbPointsAAtteindre;
    }

    /**
     * Get nb_points_a_atteindre
     *
     * @return integer 
     */
    public function getNbPointsAAtteindre()
    {
        return $this->nb_points_a_atteindre;
    }
    
    // On d�finit le getter et le setter associ� � la relation Competence.
    public function getCompetence()
    {
        return $this->competence;
    }

    // Ici, on force le type de l'argument � �tre une instance de notre entit� Competence.
    public function setCompetence(\EDiff\Bundle\AdminBundle\Entity\Competence $competence)
    {
        $this->competence = $competence;
    }

    // On d�finit le getter et le setter associ� � la relation Classe.
    public function getClasse()
    {
        return $this->classe;
    }

    // Ici, on force le type de l'argument � �tre une instance de notre entit� Classe.
    public function setClasse(\EDiff\Bundle\AdminBundle\Entity\Classe $classe)
    {
        $this->classe = $classe;
    }

    // On d�finit le getter et le setter associ� � la relation AnneeScolaire.
    public function getAnneeScolaire()
    {
        return $this->anneescolaire;
    }

    // Ici, on force le type de l'argument � �tre une instance de notre entit� AnneeScolaire.
    public function setAnneeScolaire(\EDiff\Bundle\AdminBundle\Entity\AnneeScolaire $anneescolaire)
    {
        $this->anneescolaire = $anneescolaire;
    }        
}