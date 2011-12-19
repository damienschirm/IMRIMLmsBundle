<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\Exam
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IMRIM\Bundle\LmsBundle\Entity\ExamRepository")
 */
class Exam
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
     * @var boolean $isFinal
     *
     * @ORM\Column(name = "isFinal", type = "boolean", nullable = false)
     * @Assert\NotNull()
     */
    private $isFinal=false;

    /**
     * @var integer $maxAttempts
     *
     * @ORM\Column(name = "maxAttempts", type = "integer", nullable = false)
     * @Assert\NotNull()
     * @Assert\Min(1)
     */
    private $maxAttempts;

    /**
     * @var integer $coursePosition
     *
     * @ORM\Column(name = "coursePosition", type = "integer", nullable = false)
     * @Assert\NotNull()
     * @Assert\Min(1)
     */
    private $coursePosition;

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
     * @var Doctrine\Common\Collections\Collection $questions
     * 
     * @ORM\OneToMany(targetEntity = "Question", mappedBy = "exam")
     */
    private $questions;
    
    /**
     * @var Doctrine\Common\Collections\Collection $attempts
     * 
     * @ORM\OneToMany(targetEntity = "ExamAttempts", mappedBy = "exam")
     */
    private $attempts;

    /**
     * @var Course $course
     * 
     * @ORM\ManyToOne(targetEntity = "Course", inversedBy = "exams")
     * @ORM\JoinColumn(name = "course_id", referencedColumnName = "id") 
     */
    private $course;
    
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
     * Set isFinal
     *
     * @param boolean $isFinal
     */
    public function setIsFinal($isFinal)
    {
        $this->isFinal = $isFinal;
    }

    /**
     * Get isFinal
     *
     * @return boolean 
     */
    public function getIsFinal()
    {
        return $this->isFinal;
    }

    /**
     * Set maxAttempts
     *
     * @param integer $maxAttempts
     */
    public function setMaxAttempts($maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;
    }

    /**
     * Get maxAttempts
     *
     * @return integer 
     */
    public function getMaxAttempts()
    {
        return $this->maxAttempts;
    }

    /**
     * Set coursePosition
     *
     * @param integer $coursePosition
     */
    public function setCoursePosition($coursePosition)
    {
        $this->coursePosition = $coursePosition;
    }

    /**
     * Get coursePosition
     *
     * @return integer 
     */
    public function getCoursePosition()
    {
        return $this->coursePosition;
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
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attempts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->updateTime = new \DateTime("now");
        $this->creationTime = new \DateTime("now");
    }
    
    /**
     * Add questions
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Question $questions
     */
    public function addQuestion(\IMRIM\Bundle\LmsBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;
    }

    /**
     * Get questions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add attempts
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\ExamAttempts $attempts
     */
    public function addExamAttempts(\IMRIM\Bundle\LmsBundle\Entity\ExamAttempts $attempts)
    {
        $this->attempts[] = $attempts;
    }

    /**
     * Get attempts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAttempts()
    {
        return $this->attempts;
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
     * Set the updateTime to now
     */
    public function setUpdated()
    {
        return $this->setUpdateTime(new \DateTime("now"));
    }
}