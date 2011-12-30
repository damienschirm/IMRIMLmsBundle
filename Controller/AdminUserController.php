<?php

namespace IMRIM\Bundle\LmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use IMRIM\Bundle\LmsBundle\Entity\User;
use IMRIM\Bundle\LmsBundle\Form\UserType;

/**
 * User controller for administration.
 *
 * @Route("/admin/user")
 */
class AdminUserController extends Controller {

    /**
     * Lists all User entities.
     *
     * @Route("/", name="admin_user")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('IMRIMLmsBundle:User')->findAll();

        $studentRoleForms = array();
        $teacherRoleForms = array();
        $adminRoleForms = array();

        foreach ($entities as $user) {
            $studentRoleForms[$user->getId()] = $this->createSwitchRoleForm($user->getId())->createView();
            $teacherRoleForms[$user->getId()] = $this->createSwitchRoleForm($user->getId())->createView();
            $adminRoleForms[$user->getId()] = $this->createSwitchRoleForm($user->getId())->createView();
        }
        return array('entities' => $entities,
            'studentRoleForms' => $studentRoleForms,
            'teacherRoleForms' => $teacherRoleForms,
            'adminRoleForms' => $adminRoleForms,
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/show", name="admin_user_show")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('IMRIMLmsBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction() {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="admin_user_create")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("IMRIMLmsBundle:AdminUser:new.html.twig")
     */
    public function createAction() {
        $entity = new User();
        $request = $this->getRequest();
        $form = $this->createForm(new UserType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('IMRIMLmsBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/update", name="admin_user_update")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("IMRIMLmsBundle:AdminUser:edit.html.twig")
     */
    public function updateAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('IMRIMLmsBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}/delete", name="admin_user_delete")
     * @Secure(roles="ROLE_ADMIN")
     * @Method("post")
     */
    public function deleteAction($id) {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('IMRIMLmsBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_user'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * Create a button to switch on / off a role for the user. 
     * @param integer $userId
     * @return type 
     */
    private function createSwitchRoleForm($userId) {
        return $this->createFormBuilder(array('userId' => $userId))
                        ->add('userId', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * Switch on / off the role for the user
     * @param integer $userId 
     * @Route("/admin/user/{userId}/{role}/switch", name = "admin_user_role_switch")
     */
    public function switchRoleAction($userId, $role) {
        $className = ucfirst($role) . 'Role';
        $fullClassName = '\\IMRIM\\Bundle\\LmsBundle\\Entity\\' . $className;
        if (!class_exists($fullClassName)) {
            return $this->redirect($this->generateUrl('admin_user'));
        }
        $setter = 'set' . $className;
        $getter = 'get' . $className;
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('IMRIMLmsBundle:User')->find($userId);
        if ($user == null) {
            return $this->redirect($this->generateUrl('admin_user'));
        }
        if ($user->$getter()) {
            $em->remove($user->$getter());
        } else {
            $user->$setter(new $fullClassName());
            $em->persist($user);
        }
        $em->flush();
        return $this->redirect($this->generateUrl('admin_user'));
    }

}
