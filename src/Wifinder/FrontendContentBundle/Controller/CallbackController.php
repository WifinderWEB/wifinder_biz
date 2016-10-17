<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Wifinder\CallbackBundle\Entity\Callback;
use Wifinder\CallbackBundle\Entity\CallbackFile;
use Wifinder\CallbackBundle\Entity\MailingAddress;
use Wifinder\CallbackBundle\Form\FrontendCallbackType;
use Wifinder\CallbackBundle\Form\FrontendCallback2Type;

class CallbackController extends Controller
{
     public function newCallbackAction(){
         $entity = new Callback();
         $form   = $this->createForm(new FrontendCallbackType(), $entity);
         
         return $this->render(
              'FrontendContentBundle:Callback:_newCallback.html.twig', array(
              'form' => $form->createView()
         ));
     }
     
     public function requestCallbackAction(Request $request){
         $entity = new Callback();
         $form = $this->createForm(new FrontendCallbackType(), $entity);
         $form->bind($request);

         if ($form->isValid()) {
             $em = $this->getDoctrine()->getManager();
             $entity->setIsReceived(false);
             $em->persist($entity);
             $em->flush();
             
             $em = $this->getDoctrine()->getRepository('CallbackBundle:MailingAddress');
             $mailTo = $em->getEmailListForType(2);
             
             $arrayEmailTo = array();
             foreach($mailTo as $one){
                 $arrayEmailTo[] = $one->getEmail();
             }
             
             $mailer = $this->get('mailer');
             $message = \Swift_Message::newInstance()
                ->setSubject('Сообщение на сайте Wifinder')
                ->setContentType('text/html')
                ->setFrom($this->container->getParameter('mailer_mail_from'))
                ->setTo($arrayEmailTo)
                ->setBody($this->renderView('FrontendContentBundle:Callback:_mail.html.twig', array('entity' => $entity)));
             $mailer->send($message);
             
             return $this->render(
                  'FrontendContentBundle:Callback:_callbackSaved.html.twig', array()
             );
         }
         
         return $this->render(
             'FrontendContentBundle:Callback:_callbackForm.html.twig', array(
             'form' => $form->createView()
         ));
     }
     
     public function newAction(Request $request, $alias) {
        $em = $this->getDoctrine()->getRepository('PageBundle:Content');
        $content = $em->GetContentByalias($alias);
         
        $entity = new Callback();
        $form   = $this->createForm(new FrontendCallback2Type(), $entity);

        return $this->render(
              'FrontendContentBundle:Callback:new.html.twig', array(
              'form' => $form->createView(),
              'content' => $content
         ));
    }

    public function createAction(Request $request, $alias) {
        $em = $this->getDoctrine()->getRepository('PageBundle:Content');
        $content = $em->GetContentByalias($alias);
         
        $entity = new Callback();
        $form = $this->createForm(new FrontendCallback2Type(), $entity);
        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $emm = $this->getDoctrine()->getRepository('CallbackBundle:MailingAddress');
            $mailTo = $emm->getEmailListForType(2);

            $arrayEmailTo = array();
            foreach ($mailTo as $one) {
                $arrayEmailTo[] = $one->getEmail();
            }

            $mailer = $this->get('mailer');
            $message = \Swift_Message::newInstance()
                    ->setSubject('Сообщение на сайте Wifinder')
                    ->setContentType('text/html')
                    ->setFrom($this->container->getParameter('mailer_mail_from'))
                    ->setTo($arrayEmailTo)
                    ->setBody($this->renderView('FrontendContentBundle:Callback:_mail.html.twig', array('entity' => $entity)));

            foreach($entity->getFiles() as $one){
                if ($one->getPath()) {
                    $message->attach(\Swift_Attachment::fromPath($one->getAbsolutePath())->setFilename($one->getOriginName()));
                }
            }

            $mailer->send($message);
             
            return $this->redirect($this->generateUrl('frontend_callback_created'));
        }

        return $this->render(
              'FrontendContentBundle:Callback:new.html.twig', array(
              'form' => $form->createView(),
              'content' => $content
         ));
    }
    
    public function callbackCreatedAction($alias){
        $em = $this->getDoctrine()->getRepository('PageBundle:Content');
        $content = $em->GetContentByalias($alias);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content with alias = ['.$alias.']');
        }
        
        return $this->render(
              'FrontendContentBundle:Callback:callbackCreated.html.twig', array(
              'content' => $content
         ));
    }
    
    
    
    
    
    
    
    public function newRegistrationAction(Request $request, $alias) {
        $em = $this->getDoctrine()->getRepository('PageBundle:Content');
        $content = $em->GetContentByalias($alias);
         
        $entity = new Callback();
        $form   = $this->createForm(new FrontendCallback2Type(), $entity);

        return $this->render(
              'FrontendContentBundle:Callback:newRegistration.html.twig', array(
              'form' => $form->createView(),
              'content' => $content
         ));
    }

    public function createRegistrationAction(Request $request, $alias) {
        $em = $this->getDoctrine()->getRepository('PageBundle:Content');
        $content = $em->GetContentByalias($alias);
         
        $entity = new Callback();
        $form = $this->createForm(new FrontendCallback2Type(), $entity);
        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $emm = $this->getDoctrine()->getRepository('CallbackBundle:MailingAddress');
            $mailTo = $emm->getEmailListForType(3);

            $arrayEmailTo = array();
            foreach ($mailTo as $one) {
                $arrayEmailTo[] = $one->getEmail();
            }

            $mailer = $this->get('mailer');
            $message = \Swift_Message::newInstance()
                    ->setSubject('Заявка на участие в Программе лояльности i-Surprise!')
                    ->setContentType('text/html')
                    ->setFrom($this->container->getParameter('mailer_mail_from'))
                    ->setTo($arrayEmailTo)
                    ->setBody($this->renderView('FrontendContentBundle:Callback:_mail_registration.html.twig', array('entity' => $entity)));

            foreach($entity->getFiles() as $one){
                if ($one->getPath()) {
                    $message->attach(\Swift_Attachment::fromPath($one->getAbsolutePath())->setFilename($one->getOriginName()));
                }
            }

            $mailer->send($message);
             
            return $this->redirect($this->generateUrl('frontend_callback_created_registration'));
        }

        return $this->render(
              'FrontendContentBundle:Callback:newRegistration.html.twig', array(
              'form' => $form->createView(),
              'content' => $content
         ));
    }
    
    public function callbackRegistrationCreatedAction($alias){
        $em = $this->getDoctrine()->getRepository('PageBundle:Content');
        $content = $em->GetContentByalias($alias);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content with alias = ['.$alias.']');
        }
        
        return $this->render(
              'FrontendContentBundle:Callback:callbackRegistrationCreated.html.twig', array(
              'content' => $content
         ));
    }
}
