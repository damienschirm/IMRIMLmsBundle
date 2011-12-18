<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\ExamAttempts
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IMRIM\Bundle\LmsBundle\Entity\ExamAttemptsRepository")
 */
class ExamAttempts
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
     * @var datetime $date
     *
     * @ORM\Column(name = "date", type = "datetime", nullable = false)
     * @Assert\DateTime()
     * @Assert\NotNull()
     */
    private $date;

    /**
     * @var integer $score
     *
     * @ORM\Column(name = "score", type = "integer", nullable = false)
     * @Assert\NotNull()
     * @Assert\Min(0)
     */
    private $score;

    /**
     * @var text $answersData
     *
     * @ORM\Column(name = "answersData", type = "text", nullable = false)
     * @Assert\NotNull()
     */
    private $answersData;

    /**
     * @var User $user
     * 
     * @ORM\ManyToOne(targetEntity = "User", inversedBy = "examAttempts")
     * @ORM\JoinColumn(name = "user_id", referencedColumnName = "id") 
     */
    private $user;
    
    /**
     * @var Exam $exam
     * 
     * @ORM\ManyToOne(targetEntity = "Exam", inversedBy = "attempts")
     * @ORM\JoinColumn(name = "exam_id", referencedColumnName = "id") 
     */
    private $exam;
    
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
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set score
     *
     * @param integer $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set answersData
     *
     * @param text $answersData
     */
    public function setAnswersData($answersData)
    {
        $this->answersData = $answersData;
    }

    /**
     * Get answersData
     *
     * @return text 
     */
    public function getAnswersData()
    {
        return $this->answersData;
    }

    /**
     * Set user
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\User $user
     */
    public function setUser(\IMRIM\Bundle\LmsBundle\Entity\User $user)
    {
        $this->user = $user;
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

    /**
     * Set exam
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Exam $exam
     */
    public function setExam(\IMRIM\Bundle\LmsBundle\Entity\Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get exam
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\Exam 
     */
    public function getExam()
    {
        return $this->exam;
    }
}