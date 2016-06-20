<?php

namespace Images\ImagesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table("image")
 * @ORM\Entity(repositoryClass="Images\ImagesBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     * Assert\NotBlank
     */
     public $adresse;
     
     /**
     * @ORM\Column(type="string",length=255)
     * Assert\NotBlank
     */
     public $titre;
     
     /**
     * @ORM\Column(type="string",length=255)
     * Assert\NotBlank
     */
     public $affiche;
     
     public $file;
     
     function __construct($utilisateur) {
        $this->utilisateur = $utilisateur;
    }
     
     public function getUploadRootDir() {
     	return __dir__.'/../../../../web/uploads/'.$this->utilisateur;
     }
     
     public function getAbsolutePath() {
     	return $this->adresse === null ? null : $this->getUploadRootDir().'/'.$this->adresse;
     }
     
    /**
     * @ORM\Prepersist()
     * @ORM\Preupdate()
     */
     
     public function preUpload() {
     	$this->tempFile = $this->getAbsolutePath();
     	$this->oldFile = $this->getAdresse();
     	if($this->file !== null) {
     		$this->adresse = $this->utilisateur.'/'.$this->getTitre().'.'.$this->file->guessExtension();
     	}
     }
     
    /**
     * @ORM\Postpersist()
     * @ORM\Postupdate()
     */
     
     public function upload() {
     	if($this->file !== null) {
     		$this->file->move($this->getUploadRootDir(), $this->adresse);
     		unset($this->file);
     	}
     	if($this->oldFile != null) {
     		unlink($this->tempFile);
     	}
     }

    /**
     * @ORM\Preremove()
     */
     
     public function preRemoveUpload() {
     	$this->tempFile = $this->getAbsolutePath();
     }
     
    /**
     * @ORM\Postremove()
     */
     
     public function removeUpload() {
     	if(file_exists($this->tempFile)) {
     		unlink($this->tempFile);
     	}
     }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getAdresse() {
    	return $this->adresse;
    }
    
    public function getTitre() {
    	return $this->titre;
    }
    
    public function getAffiche() {
    	return $this->affiche;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Image
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Image
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Set affiche
     *
     * @param string $affiche
     * @return Image
     */
    public function setAffiche($affiche)
    {
        $this->affiche = $affiche;
    
        return $this;
    }
}