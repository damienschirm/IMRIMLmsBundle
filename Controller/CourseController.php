<?php

namespace IMRIM\Bundle\LmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class CourseController extends Controller
{
    /**
     * Lists all available courses : user's courses and autoinscription 
     * @Route("course/list", name = "imrim_lms_course_list")
     * @Template()
     * @Secure(roles="ROLE_STUDENT")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $em = $this->getDoctrine();
        $user = $this->get('security.context')->getToken()->getUser();
        $repository = $em->getRepository('IMRIMLmsBundle:UserEnrolment');
        $courseEnrolments = $repository->findUserCourses($user);
        $courseEnrolmentForms = array();
        foreach($courseEnrolments as $courseEnrolment)
        {
            $id = $courseEnrolment->getCourse()->getId();
            $courseEnrolmentForms[$id] = $this->createUnEnrolmentForm($id)->createView();
        }
        $courseRepository = $em->getRepository('IMRIMLmsBundle:Course');
        $autoInscriptibleCourses = $courseRepository->findByAutoInscription(true);
        $inscriptibleCourses = array();
        $inscriptibleCourseForm = array();
        foreach($autoInscriptibleCourses as $course)
        {
            if(!$course->isFollowedBy($user))
            {
                $inscriptibleCourses[$course->getId()] = $course;
                $inscriptibleCourseForm[$course->getId()] = $this->createEnrolmentForm($course->getId())->createView();
            }
        }
        return array('courseEnrolments' => $courseEnrolments,
            'courseEnrolmentForms' => $courseEnrolmentForms,
            'inscriptibleCourses' => $inscriptibleCourses, 
            'inscriptibleCourseForm' => $inscriptibleCourseForm);
    }
    
    private function createEnrolmentForm($courseId)
    {
        return $this->createFormBuilder(array('courseId' => $courseId))
            ->add('courseId', 'hidden')
            ->getForm()
        ;   
    }
    
    private function createUnEnrolmentForm($courseId)
    {
        return $this->createFormBuilder(array('courseId' => $courseId))
            ->add('courseId', 'hidden')
            ->getForm()
        ;   
    }
    
    /**
     * 
     * @Route("course/{id}/subscribe", name = "imrim_lms_course_subscribe")
     * @Template()
     * @Secure(roles="ROLE_STUDENT")
     * @Method({"POST"})
     */
    public function subscribeAction($id){
        $form = $this->createEnrolmentForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);
        $user = $this->get('security.context')->getToken()->getUser();
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('IMRIMLmsBundle:Course')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Course entity.');
            }
            
            $enrolement = $em->getRepository('IMRIMLmsBundle:UserEnrolment')->findOneByUserAndCourse($user, $entity);
            if($enrolement != null)
            {
                throw $this->createNotFoundException('You already follow that course.');
            }
            $entity->enrolUser($user);
            $em->persist($entity); //TODO check if user is allowed to enrol
            $em->flush();
        }
        return $this->redirect($this->generateUrl('imrim_lms_course_list'));
    }
    
        /**
     * 
     * @Route("course/{id}/unsubscribe", name = "imrim_lms_course_unsubscribe")
     * @Template()
     * @Secure(roles="ROLE_STUDENT")
     * @Method({"POST"})
     */
    public function unSubscribeAction($id){
        $form = $this->createEnrolmentForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);
        $user = $this->get('security.context')->getToken()->getUser();
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('IMRIMLmsBundle:Course')->find($id);
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Course entity.');
            }
            
                $enrolement = $em->getRepository('IMRIMLmsBundle:UserEnrolment')->findOneByUserAndCourse($user, $entity);
            if($enrolement == null)
            {
                throw $this->createNotFoundException('You already follow that course.');
            }
           
            $em->remove($enrolement); //TODO check if user is allowed to unenrol
            $em->flush();
        }
        return $this->redirect($this->generateUrl('imrim_lms_course_list'));
    }
}
