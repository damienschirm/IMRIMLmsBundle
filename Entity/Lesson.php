<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\Lesson
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IMRIM\Bundle\LmsBundle\Entity\LessonRepository")
 */
class Lesson
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
     * @var string $title
     *
     * @ORM\Column(name = "title", type = "string", length = 255, nullable = false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-zA-Z0-9._-]+$/")
     */
    private $title;

    /**
     * @var text $content
     *
     * @ORM\Column(name = "content", type = "text", nullable = false)
     * @Assert\NotNull()
     */
    private $content;

    /**
     * @var string $type
     *
     * @ORM\Column(name = "type", type = "string", length = 25, nullable = false)
     * @Assert\NotNull()
     * @Assert\Choice(callback="getPossibleTypes")
     */
    private $type;

    /**
     * @var string $fileAttached
     *
     * @ORM\Column(name = "fileAttached", type = "string", length = 255)
     * @Assert\MaxLength(255)
     * @Assert\NotBlank()
     */
    private $fileAttached;

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
     * @var Course $course
     * 
     * @ORM\ManyToOne(targetEntity = "Course", inversedBy = "lessons")
     * @ORM\JoinColumn(name = "course_id", referencedColumnName = "id") 
     */
    private $course;

    public function __construct()
    {
        $this->creationTime = new \DateTime("now");
        $this->updateTime = new \DateTime("now");
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

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->setUpdated();
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        $this->setUpdated();
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->setUpdated();
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
     * Set fileAttached
     *
     * @param string $fileAttached
     */
    public function setFileAttached($fileAttached)
    {
        $this->fileAttached = $fileAttached;
        $this->setUpdated();
    }

    /**
     * Get fileAttached
     *
     * @return string 
     */
    public function getFileAttached()
    {
        return $this->fileAttached;
    }

    /**
     * Set coursePosition
     *
     * @param integer $coursePosition
     */
    public function setCoursePosition($coursePosition)
    {
        $this->coursePosition = $coursePosition;
        $this->setUpdated();
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
    
     /**
     * Get possible types for a lesson 
     * 
     * @return array
     */
    public static function getPossibleTypes(){
        return array(
            'text',
            'video',
            'picture',
            'html',
        );
    }

    /**
     * Set course
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Course $course
     */
    public function setCourse(\IMRIM\Bundle\LmsBundle\Entity\Course $course)
    {
        $this->course = $course;
        $this->setUpdated();
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