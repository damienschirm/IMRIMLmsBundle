<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\Course
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IMRIM\Bundle\LmsBundle\Entity\CourseRepository")
 */
class Course
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
     * @Assert\Regex("/^[a-zA-Z0-9._\s-]+$/")
     */
    private $name;

    /**
     * @var text $summary
     *
     * @ORM\Column(name = "summary", type = "text", nullable = false)
     * @Assert\NotNull()
     */
    private $summary = "";

    /**
     * @var datetime $startDate
     *
     * @ORM\Column(name = "startDate", type = "datetime")
     * @Assert\DateTime()
     */
    private $startDate;

    /**
     * @var datetime $expirationDate
     *
     * @ORM\Column(name = "expirationDate", type = "datetime", nullable = true)
     * @Assert\DateTime()
     */
    private $expirationDate;

    /**
     * @var boolean $autoInscription
     *
     * @ORM\Column(name = "autoInscription", type = "boolean", nullable = false)
     * @Assert\NotNull()
     */
    private $autoInscription=true;

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
     * @var Category $category
     * 
     * @ORM\ManyToOne(targetEntity = "Category", inversedBy = "courses")
     * @ORM\JoinColumn(name = "category_id", referencedColumnName = "id") 
     */
    private $category;

    /**
     * @var Doctrine\Common\Collections\Collection $lessons
     * 
     * @ORM\OneToMany(targetEntity = "Lesson", mappedBy = "course")
     */
    private $lessons;
    
    /**
     * @var Doctrine\Common\Collections\Collection $exams
     * 
     * @ORM\OneToMany(targetEntity = "Exam", mappedBy = "course")
    */
    private $exams;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $teachers
     * 
     * @ORM\ManyToMany(targetEntity = "User", mappedBy = "courseResponsibilities", cascade = {"persist"}) 
     */
    private $teachers;
    
    /**
     * @var Doctrine\Common\Collections\Collection $userEnrolments
     * 
     * @ORM\OneToMany(targetEntity = "UserEnrolment", mappedBy = "course", cascade = {"persist"})
     */
    private $userEnrolments;
    
    /**
     * @var Doctrine\Common\Collections\Collection $groupEnrolments
     * 
     * @ORM\OneToMany(targetEntity = "GroupEnrolment", mappedBy = "course")
     */
    private $groupEnrolments;
    
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
     * Set summary
     *
     * @param text $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        $this->setUpdated();
    }

    /**
     * Get summary
     *
     * @return text 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set startDate
     *
     * @param datetime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        $this->setUpdated();
    }

    /**
     * Get startDate
     *
     * @return datetime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set expirationDate
     *
     * @param datetime $expirationDate
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
        $this->setUpdated();
    }

    /**
     * Get expirationDate
     *
     * @return datetime 
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Set autoInscription
     *
     * @param boolean $autoInscription
     */
    public function setAutoInscription($autoInscription)
    {
        $this->autoInscription = $autoInscription;
        $this->setUpdated();
    }

    /**
     * Get autoInscription
     *
     * @return boolean 
     */
    public function getAutoInscription()
    {
        return $this->autoInscription;
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
        $this->lessons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->exams = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userEnrolments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groupEnrolments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->updateTime = new \DateTime("now");
        $this->creationTime = new \DateTime("now");
        $this->startDate = new \DateTime("now");
    }
    
    /**
     * Set category
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Category $category
     */
    public function setCategory(\IMRIM\Bundle\LmsBundle\Entity\Category $category)
    {
        $this->category = $category;
        $this->setUpdated();
    }

    /**
     * Get category
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add lessons
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Lesson $lessons
     */
    public function addLesson(\IMRIM\Bundle\LmsBundle\Entity\Lesson $lessons)
    {
        $this->lessons[] = $lessons;
    }

    /**
     * Get lessons
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLessons()
    {
        return $this->lessons;
    }

    /**
     * Add exams
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Exam $exams
     */
    public function addExam(\IMRIM\Bundle\LmsBundle\Entity\Exam $exams)
    {
        $this->exams[] = $exams;
    }

    /**
     * Get exams
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getExams()
    {
        return $this->exams;
    }

    /**
     * Add teachers
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\User $teachers
     */
    public function addTeacher(\IMRIM\Bundle\LmsBundle\Entity\User $teachers)
    {
        $this->teachers[] = $teachers;
        $teachers->addCourse($this);
    }

    /**
     * Remove teacher
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\User $teachers
     */
    public function removeTeacher(\IMRIM\Bundle\LmsBundle\Entity\User $user)
    {
        foreach($this->teachers as $key => $teacher) {
            if($teacher->equals($user)) {
		unset($this->teachers[$key]);
            }
        }
    }

    /**
     * Get teachers
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTeachers()
    {
        return $this->teachers;
    }

    /**
     * Add userEnrolments
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\UserEnrolment $userEnrolments
     */
    public function addUserEnrolment(\IMRIM\Bundle\LmsBundle\Entity\UserEnrolment $userEnrolments)
    {
        $this->userEnrolments[] = $userEnrolments;
    }

    /**
     * Get userEnrolments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUserEnrolments()
    {
        return $this->userEnrolments;
    }

    /**
     * Add groupEnrolments
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\GroupEnrolment $groupEnrolments
     */
    public function addGroupEnrolment(\IMRIM\Bundle\LmsBundle\Entity\GroupEnrolment $groupEnrolments)
    {
        $this->groupEnrolments[] = $groupEnrolments;
    }

    /**
     * Get groupEnrolments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGroupEnrolments()
    {
        return $this->groupEnrolments;
    }
    
    /**
     * Set the updateTime to now
     */
    public function setUpdated()
    {
        return $this->setUpdateTime(new \DateTime("now"));
    }
    
    /**
     * Return the course name
     * 
     * @return string 
     */
    public function __toString()
    {
        return $this->getName();
    }
    
    /**
     * Returns true if $user is enrolled for this course
     * @param User $user
     * @return boolean 
     */
    public function isFollowedBy(User $user)
    {
        // TODO: add the groupEnrolment case
        foreach($this->getUserEnrolments() as $userEnrolment)
        {
            if($userEnrolment->getUser()->equals($user))
            {
               return true; 
            }
        }
        return false;
    }
    
    /**
     * Enrols a specific user to the course (autoinscription or forced)
     * @param User $user
     * @param type $isForced 
     */
    public function enrolUser(User $user, $isForced=false)
    {
        $enrolment = new UserEnrolment();
        $enrolment->setCourse($this);
        $enrolment->setIsForced($isForced);
        $enrolment->setEnrolmentDate(new \DateTime('now'));
        $enrolment->setUser($user);
    }
    
    /**
     * Compare two courses and returns true if they are equal.
     * @param Course $course
     * @return boolean 
     */
    public function equals(Course $course){
        if ($course->getId() == $this->getId())
        {
            return true;
        }
        return false;
    }
    
    /**
     * Return the position for the next component of the course (lesson or exam)
     * @return integer 
     */
    public function getNextPosition()
    {
        return count($this->getExams()) + count($this->getLessons()) +1;
    }

    /**
     * Add teachers
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\User $teachers
     */
    public function addUser(\IMRIM\Bundle\LmsBundle\Entity\User $teachers)
    {
        $this->teachers[] = $teachers;
    }

    public function haveLesson()
    {
	if(count($this->getLessons()) > 0)
        {
		return true;
        } 
	return false;
    }
}
