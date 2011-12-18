<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\GroupEnrolment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IMRIM\Bundle\LmsBundle\Entity\GroupEnrolmentRepository")
 */
class GroupEnrolment
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
     * @var boolean $isForced
     *
     * @ORM\Column(name = "isForced", type = "boolean", nullable = false)
     * @Assert\NotNull()
     */
    private $isForced=false;

    /**
     * @var datetime $enrolmentDate
     *
     * @ORM\Column(name = "enrolmentDate", type = "datetime", nullable = false)
     * @Assert\NotNull()
     * @Assert\DateTime()
     */
    private $enrolmentDate;

    /**
     * @var Course $course
     * 
     * @ORM\ManyToOne(targetEntity = "Course", inversedBy = "groupEnrolments")
     * @ORM\JoinColumn(name = "course_id", referencedColumnName = "id") 
     */
    private $course;
    
    /**
     * @var UserGroup $group
     * 
     * @ORM\ManyToOne(targetEntity = "UserGroup", inversedBy = "enrolments")
     * @ORM\JoinColumn(name = "user_group_id", referencedColumnName = "id") 
     */
    private $group;
    
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
     * Set isForced
     *
     * @param boolean $isForced
     */
    public function setIsForced($isForced)
    {
        $this->isForced = $isForced;
    }

    /**
     * Get isForced
     *
     * @return boolean 
     */
    public function getIsForced()
    {
        return $this->isForced;
    }

    /**
     * Set enrolmentDate
     *
     * @param datetime $enrolmentDate
     */
    public function setEnrolmentDate($enrolmentDate)
    {
        $this->enrolmentDate = $enrolmentDate;
    }

    /**
     * Get enrolmentDate
     *
     * @return datetime 
     */
    public function getEnrolmentDate()
    {
        return $this->enrolmentDate;
    }

    /**
     * Set course
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Course $course
     */
    public function setCourse(\IMRIM\Bundle\LmsBundle\Entity\Course $course)
    {
        $this->course = $course;
    }

    /**
     * Get course
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\Course 
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set group
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\UserGroup $group
     */
    public function setGroup(\IMRIM\Bundle\LmsBundle\Entity\UserGroup $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\UserGroup 
     */
    public function getGroup()
    {
        return $this->group;
    }
}