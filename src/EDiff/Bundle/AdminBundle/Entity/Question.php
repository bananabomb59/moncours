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
     * @var text $document
     *
     * @ORM\Column(name="document", type="string", length=255)
     */
    private $document;

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
    
	public function setDocument($document)
    {
        $this->document = $document;
    }

    public function getDocument()
    {
        return $this->document;
    }
    
    // On d�finit le getter et le setter associ� � la relation Matiere.
    public function getMatiere()
    {
        return $this->matiere;
    }

    // Ici, on force le type de l'argument � �tre une instance de notre entit� Matiere.
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