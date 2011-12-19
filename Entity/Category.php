<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IMRIM\Bundle\LmsBundle\Entity\CategoryRepository")
 */
class Category
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
     * @var string $name
     *
     * @ORM\Column(name = "name", type = "string", length = 255, nullable = false)
     * @Assert\MaxLength(255)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-zA-Z0-9._-]+$/")
     */
    private $name;

    /**
     * @var boolean $isPublic
     *
     * @ORM\Column(name = "isPublic", type = "boolean", nullable = false)
     * @Assert\NotNull()
     */
    private $isPublic=true;

    /**
     * @var datetime $creationTime
     *
     * @ORM\Column(name = "creationTime", type = "datetime", nullable = false)
     * @Assert\NotNull()
     * @Assert\DateTime()
     */
    private $creationTime;

    /**
     * @var datetime $updateTime
     *
     * @ORM\Column(name = "updateTime", type = "datetime", nullable = false)
     * @Assert\NotNull()
     * @Assert\DateTime()
     */
    private $updateTime;

    /**
     * @var Doctrine\Common\Collections\Collection $courses
     * 
     * @ORM\OneToMany(targetEntity = "Course", mappedBy = "category")
     */
    private $courses;

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set creationTime
     *
     * @param datetime $creationTime
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;
    }

    /**
     * Get creationTime
     *
     * @return datetime 
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * Set updateTime
     *
     * @param datetime $updateTime
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
    }

    /**
     * Get updateTime
     *
     * @return datetime 
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }
    public function __construct()
    {
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setCreationTime(new \DateTime("now"));
        $this->setUpdateTime(new \DateTime("now"));
    }
    
    /**
     * Add courses
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Course $courses
     */
    public function addCourse(\IMRIM\Bundle\LmsBundle\Entity\Course $courses)
    {
        $this->courses[] = $courses;
    }

    /**
     * Get courses
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCourses()
    {
        return $this->courses;
    }
   
    /**
     * Set the updateTime to now
     */
    public function setUpdated()
    {
        return $this->setUpdateTime(new \DateTime("now"));
    }
}