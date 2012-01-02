<?php

namespace IMRIM\Bundle\LmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

use IMRIM\Bundle\LmsBundle\Entity\Course;
use IMRIM\Bundle\LmsBundle\Form\CourseType;

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
        $courseEnrolmentForms = array();
        $courseResponsibilities = array();
        $inscriptibleCourses = array();
        $inscriptibleCourseForm = array();
        
        $em = $this->getDoctrine();
        $user = $this->get('security.context')->getToken()->getUser();
        $repository = $em->getRepository('IMRIMLmsBundle:UserEnrolment');
        
        // Find all the user enrolments
        $courseEnrolments = $repository->findUserCourses($user);       
     
        foreach($courseEnrolments as $courseEnrolment)
        {
            $id = $courseEnrolment->getCourse()->getId();
            // Creates the form to unsubscribe to the course
            $courseEnrolmentForms[$id] = $this->createUnEnrolmentForm($id)->createView();
            // Detects if the user is one the teachers of the course, so if he can edit the course
            if ($user->isResponsibleFor($courseEnrolment->getCourse()))
            {
                $courseResponsibilities[$id] = true;
            } 
            else 
            {
                $courseResponsibilities[$id] = false;
            }
        }
        
        $courseRepository = $em->getRepository('IMRIMLmsBundle:Course');
        
        // Finds all the public courses
        $autoInscriptibleCourses = $courseRepository->findByAutoInscription(true);
        foreach($autoInscriptibleCourses as $course)
        {
            if(!$course->isFollowedBy($user))
            {
                $inscriptibleCourses[$course->getId()] = $course;
                $inscriptibleCourseForm[$course->getId()] = $this->createEnrolmentForm($course->getId())->createView();
                if ($user->isResponsibleFor($course))
                {
                    $courseResponsibilities[$course->getId()] = true;
                } 
                else 
                {
                    $courseResponsibilities[$course->getId()] = false;
                }
            }
        }
        
        return array('courseEnrolments' => $courseEnrolments,
            'courseEnrolmentForms' => $courseEnrolmentForms,
            'inscriptibleCourses' => $inscriptibleCourses, 
            'inscriptibleCourseForm' => $inscriptibleCourseForm,
            'courseResponsibilities' => $courseResponsibilities,
            );
    }
   
    /**
     * Lists all teacher courses
     * @Route("course/list/teacher", name = "imrim_lms_course_list_teacher")
     * @Template()
     * @Secure(roles="ROLE_TEACHER")
     * @Method({"GET"})
     */
    public function teacherListAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $courses = $user->getCourseResponsibilities();

        return array(
            'courses' => $courses,
        );
    }

    /**
     * Lists all courses for admin
     * @Route("course/list/admin", name = "imrim_lms_course_list_admin")
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     * @Method({"GET"})
     */
    public function adminListAction()
    {

        $em = $this->getDoctrine()->getEntityManager();
        $courses = $em->getRepository('IMRIMLmsBundle:Course')->findAll();

        return array(
            'courses' => $courses,
        );
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
            $em->persist($entity); // TODO check if user is allowed to enrol
            $em->flush();
        }
        return $this->redirect($this->generateUrl('imrim_lms_course_list'));
    }
    
     /**
     * Unsubscribe the current user at the $id course
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
           
            $em->remove($enrolement); // TODO check if user is allowed to unenrol
            $em->flush();
        }
        return $this->redirect($this->generateUrl('imrim_lms_course_list'));
    }
    
    /**
     * Creates a course
     * @Route("course/create", name = "imrim_lms_course_create")
     * @Template()
     * @Secure("ROLE_TEACHER, ROLE_ADMIN")
     * @Method({"GET","POST"})
     */
    public function createAction(){
        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();
                
        $course = new Course();
        // Creation of the associated form
        $form = $this->createForm(new CourseType(), $course);
        
        if ($request->getMethod() == 'POST')
        {
            // Fills the form with what was in the parameters
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $course->addTeacher($user);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($course);
                $em->flush();
                return $this->redirect($this->generateUrl('imrim_lms_course_list_teacher'));
            }
        }
        return array('form'=>$form->createView());
    }
    
    /**
     * Edit a course
     * @Route("course/{id}/edit", name = "imrim_lms_course_edit")
     * @Template()
     * @Secure("ROLE_TEACHER, ROLE_ADMIN")
     * @Method({"GET","POST"})
     */
    public function editAction($id){
        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();
                
        $em = $this->getDoctrine()->getEntityManager();
        $course = $em->getRepository('IMRIMLmsBundle:Course')->find($id);
        
        // Verifications
        if ($course == null)
        {
            throw new AccessDeniedException("Vous n'avez pas l'autorisation d'éditer ce cours.");
        }
        if (!$user->isResponsibleFor($course) && !$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            throw new AccessDeniedException("Vous n'avez pas l'autorisation d'éditer ce cours.");
        }
        
        // Creation of the associated form
        $form = $this->createForm(new CourseType(), $course);
        
        if ($request->getMethod() == 'POST')
        {
            // Fills the form with what was in the parameters
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $em->persist($course);
                $em->flush();
            }
        }
        
        $ressources = $this->getDoctrine()->getRepository("IMRIMLmsBundle:Lesson")->findByCourse($course->getId());
        
        return array('form'=>$form->createView(),
            'id' => $id,
            'deleteCourseForm' => $this->createDeleteForm($id)->createView(),
            'associatedRessources' => $ressources,
            );
    }
    
    /**
     * Deletes the course.
     * @Route("course/{id}/delete", name = "imrim_lms_course_delete")
     * @Secure("ROLE_TEACHER, ROLE_ADMIN")
     * @Method({"POST"})
     * @param integer $id
     * @throws AccessDeniedException 
     */
    public function deleteAction($id){
        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();
                   
        // Creation of the associated form
        $form = $this->createDeleteForm($id);
        
       // Fills the form with what was in the parameters
       $form->bindRequest($request);
       if ($form->isValid())
       {
            $em = $this->getDoctrine()->getEntityManager();
            $course = $em->getRepository('IMRIMLmsBundle:Course')->find($id);
        
            // Verifications
            if ($course == null)
            {
                throw new AccessDeniedException("Vous n'avez pas l'autorisation d'éditer ce cours.");
            }
            if (!$user->isResponsibleFor($course) && !$this->get('security.context')->isGranted('ROLE_ADMIN'))
            {
                throw new AccessDeniedException("Vous n'avez pas l'autorisation d'éditer ce cours.");
            }
                
            $em->remove($course);
            $em->flush();
            return $this->redirect($this->generateUrl('imrim_lms_course_list_teacher'));
        }
       
        throw new AccessDeniedException();
    }

    private function createDeleteForm($courseId)
    {
        return $this->createFormBuilder(array('courseId' => $courseId))
            ->add('courseId', 'hidden')
            ->getForm()
        ;   
    }

    /**
     * View
     * @Route("course/{courseId}/view/position/{position}", name = "imrim_lms_course_view")
     * @Secure(roles="ROLE_TEACHER, ROLE_STUDENT")
     * @Method({"GET"})
     * @Template()
     * @param integer $courseId
     * @param integer $positionId
     * @throws AccessDeniedException
     */
    public function viewAction($courseId, $position) {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getEntityManager();
	$hasnext = true;
	$hasprevius = true;
	$edit = false;
        $course = $em->getRepository('IMRIMLmsBundle:Course')->find($courseId);
	if($course == null) {
		throw new AccessDeniedException();
	}
        $lesson = $em->getRepository('IMRIMLmsBundle:Lesson')->findOneByCourseAndCoursePosition($course, $position);
	if($lesson == null) {
		throw new AccessDeniedException();
        }
	
	if(null == $em->getRepository('IMRIMLmsBundle:Lesson')->findOneByCourseAndCoursePosition($course, $position-1)) {
		$hasprevius = false;
	}
	if(null == $em->getRepository('IMRIMLmsBundle:Lesson')->findOneByCourseAndCoursePosition($course, $position+1)) {
		$hasnext = false;
	}

	if($user->isResponsibleFor($course) && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
		$edit = true;
	} else {
		if(!$course->isFollowedBy($user)){
			throw new AccessDeniedException();
		}
	}

	
        return array(
            'course' => $course,
            'lesson' => $lesson,
            'hasnext' => $hasnext,
            'hasprevius' => $hasprevius,
            'position' => $position,
            'edit' => $edit
        );
    }
}
