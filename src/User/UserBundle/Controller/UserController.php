<?php

namespace User\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use User\UserBundle\Entity\User;
use User\UserBundle\Form\UserType;

class UserController extends Controller
{
    public function homeAction()
    {
        return $this->render('UserUserBundle:User:home.html.twig');
    }
    
    public function indexAction(Request $request)
    {
        
        //$users = $em->getRepository('UserUserBundle:User')->findAll();
        /*
        $res = 'Lista de Usuarios: <br />'; 
        
        foreach($users as $user)
        {
            $res .='Usuario: ' .$user->getUsername(). ' Email: '.$user->getEmail(). '<br />'; 
        }
        return new Response($res);
        */
        
        $searchQuery = $request->get('query');
        
        if(!empty($searchQuery))
        {
            $finder = $this->container->get('fos_elastica.finder.app.user');
            $users = $finder->createPaginatorAdapter($searchQuery);
        }
        else
        {
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT u FROM UserUserBundle:User u ORDER BY u.id DESC";
            $users = $em->createQuery($dql);            
        }
 
        $paginator = $this->get('knp_paginator');
       
        $pagination = $paginator->paginate($users, $request->query->getInt('page',1)/*numero de pagina*/,5/*numero de registro por pagina*/);
        
        $deleteFormAjax = $this->createCustomForm(':USER_ID', 'DELETE', 'user_user_delete');
        
        return $this->render('UserUserBundle:User:index.html.twig', array('pagination' => $pagination, 'delete_form_ajax' => $deleteFormAjax->createView()));
    }
    
    public function addAction()
    {
    	$user = new User();
    	$form = $this->createCreateForm($user);
    
    	return $this->render('UserUserBundle:User:add.html.twig', array('form' => $form->createView()));
    }
    
    private function createCreateForm(User $entity) {
    	
        $form = $this->createForm(new UserType(), $entity, array(
                'action' => $this->generateUrl('user_user_create'),
                'method' => 'POST'
            ));
        
        return $form;
    }
    
    public function createAction(Request $request)
    {
    	$user = new User();
        $form = $this->createCreateForm($user);
        $form->handleRequest($request);
    	
    	if($form->isValid())
    	{
            $password = $form->get('password')->getData();
            
            $passwordConstraint = new Assert\NotBlank();
            $errorList = $this->get('validator')->validate($password, $passwordConstraint);
            
            //print_r($errorList);
            //exit();
            
            if(count($errorList) == 0)
            {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $password);
            
                $user->setPassword($encoded);
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
    	    
    	        $successMessage = $this->get('translator')->trans('The user has been created.');
    	        $this->addFlash('mensaje', $successMessage);

                return $this->redirectToRoute('user_user_index');
            }
            else
            {
                
                $errorMessage = new FormError($errorList[0]->getMessage());
                $form->get('password')->addError($errorMessage);
            }
    	}
    	
    	return $this->render('UserUserBundle:User:add.html.twig', array('form' => $form->createView()));
    }
    
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserUserBundle:User')->find($id);
        
        if (!$user)
        {
            $MessageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException($MessageException);
        }
        
        $form = $this->createEditForm($user);
        return $this->render('UserUserBundle:User:edit.html.twig', array('user' => $user, 
        'form' => $form->createView()));
        
        
    }
    
    private function createEditForm(User $entity) {
    	
        $form = $this->createForm(new UserType(), $entity, array('action' => $this->generateUrl
        ('user_user_update', array('id' => $entity->getId())),'method' => 'PUT'));
        
        return $form;
    }
    
    public function updateAction($id, Request $request)
    {
    	
    	$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserUserBundle:User')->find($id);
        
    	if (!$user)
        {
            $MessageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException($MessageException);
        }
    	
    	$form = $this->createEditForm($user);
    
        $form->handleRequest($request);
    	
    	if($form->isSubmitted() && $form->isValid())
    	{
            
            $password = $form->get('password')->getData();
            
            if(!empty($password)){
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $password);
            
                $user->setPassword($encoded); 
            }
            else{
                
                $recoverPass = $this->recoverPass($id);
                //print_r($recoverPass[0]['password']);
                //exit();
                $user->setPassword( $recoverPass[0]['password']);
                
            }
            
            if($form->get('role')->getData() == 'ROLE_ADMIN')
    	    {
    	        $user->setIsActive(1);
    	    }
            $em->flush();
    	    
    	    $successMessage = $this->get('translator')->trans('The user has been modified.');
    	    $this->addFlash('mensaje', $successMessage);

            //return $this->redirectToRoute('user_user_edit', array('id' => $user->getId()));
            return $this->redirectToRoute('user_user_index');
    	}
    	
    	return $this->render('UserUserBundle:User:edit.html.twig', array('user' => $user, 'form' => $form->createView()));
    }
    
    private function recoverPass($id)
    {
        $rc = $this->getDoctrine()->getManager();
        
        $query = $rc->createQuery('SELECT u.password FROM UserUserBundle:User u
        WHERE u.id = :id')->setParameter('id', $id);
        
        $currentPass = $query->getResult();
        
        return $currentPass;
        
    }
    public function viewAction($id)
    {
    	$repository = $this->getDoctrine()->getRepository('UserUserBundle:User');
    	
    	//$user = $repository->find($id);
    	$user = $repository->findOneById($id);
    	
    	if (!$user)
        {
            $MessageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException($MessageException);
        }
        
        //$deleteForm = $this->createDeleteForm($user);
        $deleteForm = $this->createCustomForm($user->getId(), 'DELETE', 'user_user_delete');
        
        $form = $this->createViewForm($user);
        return $this->render('UserUserBundle:User:view.html.twig', array('user' => $user, 'delete_form'
        => $deleteForm->createView()));
    
    	//return new Response('Usuario: '.$user->getUsername().' con email: '.$user->getEmail());
    }
    
    private function createDeleteForm($user)
    {
    	
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('user_user_delete', array('id'=>$user->getId())))
        ->setMethod('DELETE')
        ->getForm();
    }
    
    private function createViewForm(User $entity) {
    	
        $form = $this->createForm(new UserType(), $entity, array('action' => $this->generateUrl
        ('user_user_update', array('id' => $entity->getId())),'method' => 'PUT'));
        
        return $form;
    }
    
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('UserUserBundle:User')->find($id);
        
        if(!$user)
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException($messageException);
        }
        
                
        $allUsers = $em->getRepository('UserUserBundle:User')->findAll();
        $countUsers = count($allUsers);
        
        
        
        //$form = $this->createDeleteForm($user);
        $form = $this->createCustomForm($user->getId(), 'DELETE', 'user_user_delete');
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            if($request->isXMLHttpRequest())
            {
                $res = $this->deleteUser($user->getRole(), $em, $user);
                
                return new Response(
                    json_encode(array('removed'=>$res['removed'], 'message'=>$res['message'],
                    'countUsers' => $countUsers)),
                    200,
                    array('Content-Type'=>'application/json'));
            }
            
            $res = $this->deleteUser($user->getRole(), $em, $user);
            
            //$em->remove($user);
            //$em->flush();
            
            //$successMessage = $this->get('translator')->trans('The user has been deleted.');
    	    //$this->addFlash('mensaje', $successMessage);
    	    
    	    $this->addFlash($res['alert'], $res['message']);
            return $this->redirectToRoute('user_user_index'); 
        }    
        
    }
    
    private function deleteUser($role, $em, $user)
    {
        if($role == 'ROLE_USER')
        {
            $em->remove($user);
            $em->flush();
            
            $message = $this->get('translator')->trans('The user has been deleted.');
            $removed = 1;
            $alert = 'mensaje';
        }
        elseif($role == 'ROLE_ADMIN')
        {
            $message = $this->get('translator')->trans('The user could not be deleted.');
            $removed = 0;
            $alert = 'error';
        }
        
        return array('removed' => $removed, 'message' => $message, 'alert' => $alert);
    }
    
    private function createCustomForm($id, $method, $route)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl($route, array('id'=>$id)))
        ->setMethod($method)
        ->getForm();
    }
}
