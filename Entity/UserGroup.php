<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\UserGroup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass = "IMRIM\Bundle\LmsBundle\Entity\UserGroupRepository")
 */
class UserGroup
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name = "name", type = "string", length = 255, nullable = false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-zA-Z0-9_-]$/")
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name = "description", type = "text", nullable = false)
     * @Assert\NotNull()
     */
    private $description;

    /**
     * @var datetime $creationTime
     *
     * @ORM\Column(name = "creationTime", type = "datetime", nullable = false)
     * @Assert\DateTime()
     * @Assert\NotNull()
     */
    private $creationTime;

    /**
     * @var datetime $updateTime
     *
     * @ORM\Column(name = "updateTime", type = "datetime", nullable = false)
     * @Assert\DateTime()
     * @Assert\NotNull()
     */
    private $updateTime;

    /**
     * @var Doctrine\Common\Collections\Collection $members
     * 
        * @ORM\OneToMany(targetEntity = "GroupMembers", mappedBy = "group")
     */
    private $members;
    
    /**
     * @var Doctrine\Common\Collections\Collection $enrolments
     * 
     * @ORM\OneToMany(targetEntity = "GroupEnrolment", mappedBy = "group")
     */
    private $enrolments;

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
        $this->setUpdated();
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
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->setUpdated();
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creationTime = new \DateTime("now");
        $this->updateTime = new \DateTime("now");
    }
    
    /**
     * Add members
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\GroupMembers $members
     */
    public function addGroupMembers(\IMRIM\Bundle\LmsBundle\Entity\GroupMembers $members)
    {
        $this->members[] = $members;
    }

    /**
     * Get members
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Add enrolments
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\GroupEnrolment $enrolments
     */
    public function addGroupEnrolment(\IMRIM\Bundle\LmsBundle\Entity\GroupEnrolment $enrolments)
    {
        $this->enrolments[] = $enrolments;
    }

    /**
     * Get enrolments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEnrolments()
    {
        return $this->enrolments;
    }
    
    /**
     * Set the updateTime to now
     */
    public function setUpdated()
    {
        return $this->setUpdateTime(new \DateTime("now"));
    }
}