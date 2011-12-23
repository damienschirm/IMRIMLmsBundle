<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\UserEnrolment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IMRIM\Bundle\LmsBundle\Entity\UserEnrolmentRepository")
 */
class UserEnrolment
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
     * @var integer $timeSpent
     *
     * @ORM\Column(name = "timeSpent", type = "integer")
     * @Assert\Min(0)
     */
    private $timeSpent=0;

    /**
     * @var string $courseStatus
     *
     * @ORM\Column(name = "courseStatus", type = "string", length = 30, nullable = false)
     * @Assert\NotNull()
     * @Assert\Choice(callback="getPossibleStatuses")
     */
    private $courseStatus='Not started';

    /**
     * @var integer $stepProgression
     *
     * @ORM\Column(name = "stepProgression", type = "integer", nullable = false)
     * @Assert\NotNull()
     * @Assert\Min(0)
     */
    private $stepProgression=0;

    /**
     * @var datetime $enrolmentDate
     *
     * @ORM\Column(name = "enrolmentDate", type = "datetime", nullable = false)
     * @Assert\DateTime()
     * @Assert\NotNull()
     */
    private $enrolmentDate;

     /**
     * @var Course $course
     * 
     * @ORM\ManyToOne(targetEntity = "Course", inversedBy = "userEnrolments", cascade = {"persist"})
     * @ORM\JoinColumn(name = "course_id", referencedColumnName = "id") 
     */
    private $course;
    
    /**
     * @var User $user
     * 
     * @ORM\ManyToOne(targetEntity = "User", inversedBy = "enrolments", cascade = {"persist"})
     * @ORM\JoinColumn(name = "user_id", referencedColumnName = "id") 
     */
    private $user;
    
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
     * Set timeSpent
     *
     * @param integer $timeSpent
     */
    public function setTimeSpent($timeSpent)
    {
        $this->timeSpent = $timeSpent;
    }

    /**
     * Get timeSpent
     *
     * @return integer 
     */
    public function getTimeSpent()
    {
        return $this->timeSpent;
    }

    /**
     * Set courseStatus
     *
     * @param string $courseStatus
     */
    public function setCourseStatus($courseStatus)
    {
        $this->courseStatus = $courseStatus;
    }

    /**
     * Get courseStatus
     *
     * @return string 
     */
    public function getCourseStatus()
    {
        return $this->courseStatus;
    }

    /**
     * Set stepProgression
     *
     * @param integer $stepProgression
     */
    public function setStepProgression($stepProgression)
    {
        $this->stepProgression = $stepProgression;
    }

    /**
     * Get stepProgression
     *
     * @return integer 
     */
    public function getStepProgression()
    {
        return $this->stepProgression;
    }
    
    /**
     * Get possible statuses for a course 
     * 
     * @return array
     */
    public static function getPossibleStatuses(){
        return array(
            'Not started',
            'Started',
            'Finished',
            'Cancelled',
        );
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
        $course->addUserEnrolment($this);
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
     * Set user
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\User $user
     */
    public function setUser(\IMRIM\Bundle\LmsBundle\Entity\User $user)
    {
        $this->user = $user;
        $user->addUserEnrolment($this); 
    }

    /**
     * Get user
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}