<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wifinder\CallbackBundle\Entity\MailingAddress;
use Wifinder\CallbackBundle\Form\SubscribeType;

class MailerController extends Controller{
        public function newSubscribeAction(){
         $entity = new MailingAddress();
         $form   = $this->createForm(new SubscribeType(), $entity);
         
         return $this->render(
              'FrontendContentBundle:Mailer:_newSubscribe.html.twig', array(
              'form' => $form->createView()
         ));
     }
     
     public function requestSubscribeAction(Request $request){
         $entity = new MailingAddress();
         $form = $this->createForm(new SubscribeType(), $entity);
         $form->bind($request);
         if ($form->isValid()) {
             $em = $this->getDoctrine()->getManager();
             $entity->addType($em->getRepository('CallbackBundle:EmailType')->find(1));
             
             $params = $request->request->get($form->getName());
             $entity->setIsConfirm(false);
             $entity->setIsActive(true);
             $entity->setConfirmCode(md5(md5($entity->getEmail()).md5(date('d-m-Y')).md5($params['_token'])));
             $em->persist($entity);
             $em->flush();

             $message = \Swift_Message::newInstance()
                  ->setSubject('Подтверждение адреса')
                  ->setContentType('text/html')
                  ->setFrom($this->container->getParameter('mailer_mail_from'))
                  ->setTo($entity->getEmail())
                  ->setBody(
                      $this->renderView(
                          'FrontendContentBundle:Mailer:_email.html.twig', array(
                              'email' => $entity->getEmail(), 
                              'confirmCode' => $entity->getConfirmCode(),
                              'id' => $entity->getId(),
                              'host' => $this->container->getParameter('host_name'))
                      ));
            $this->get('mailer')->send($message);
    
             return $this->render(
                  'FrontendContentBundle:Mailer:_subscribeSaved.html.twig', array()
             );
         }
         
         return $this->render(
             'FrontendContentBundle:Mailer:_errorSubscribe.html.twig', array(
             'form' => $form->createView()
         ));
     }
     
     public function ConfirmAction($id, $confirmCode){
         if($id && $confirmCode){
             $em = $this->getDoctrine()->getManager();
             $mail = $em->getRepository('CallbackBundle:MailingAddress')->find($id);
             if(!$mail)
                 throw $this->createNotFoundException('Unable to find entity.');
             
             if($mail->getConfirmCode() == $confirmCode){
                 $mail->setIsConfirm(true);
                 $em->persist($mail);
                 $em->flush();
                 
                 return $this->redirect($this->generateUrl('frontend_mailer_confirmed'));
             }
             else
                 throw $this->createNotFoundException('Unable to find entity.');
         }
         else
             throw $this->createNotFoundException('Unable to find entity.');
     }
}
