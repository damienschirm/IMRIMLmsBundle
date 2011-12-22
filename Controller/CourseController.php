<?php

namespace IMRIM\Bundle\LmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CourseController extends Controller
{
    /**
     * Lists all available courses.
     * @Route("course/list", name = "imrim_lms_course_list")
     * @Template()
     */
    public function listAction()
    {
        $em = $this->getDoctrine();
        $user = $this->get('security.context')->getToken()->getUser();
        $repository = $em->getRepository('IMRIMLmsBundle:UserEnrolment');
        $courseEnrolments = $repository->findUserCourses($user);
        return array('courseEnrolments' => $courseEnrolments);
    }
}
