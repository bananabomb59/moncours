<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDiff\Bundle\AdminBundle\Entity\Competence
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\CompetenceRepository")
 */
class Competence
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
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

	/**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Matiere")
     */
    private $matiere;


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
     * Set libelle
     *
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
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
    
    public function __toString()
    {
    	return $this->libelle;
    }
    
}