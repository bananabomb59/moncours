<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDiff\Bundle\AdminBundle\Entity\Question
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\QuestionRepository")
 */
class Question
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
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string $niveau
     *
     * @ORM\Column(name="niveau", type="string", length=255)
     */
    private $niveau;

    /**
     * @var text $libelle
     *
     * @ORM\Column(name="libelle", type="text")
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Matiere")
     */
    private $matiere;
    
    /**
     * @var string $pathtodocument
     *
     * @ORM\Column(name="pathtodocument", type="string", length=255)
     */
    private $pathtodocument;
    
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
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set niveau
     *
     * @param string $niveau
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
    }

    /**
     * Get niveau
     *
     * @return string 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set libelle
     *
     * @param text $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * Get libelle
     *
     * @return text 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
    
	/**
     * Set pathtodocument
     *
     * @param string $pathtodocument
     */
    public function setPathtodocument($pathtodocument)
    {
        $this->pathtodocument = $pathtodocument;
    }

    /**
     * Get pathtodocument
     *
     * @return string 
     */
    public function getPathtodocument()
    {
        return $this->pathtodocument;
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
    
	/**
     * Methode __toString()
     */
    public function __toString(){
    	return $this->libelle;
    }
}