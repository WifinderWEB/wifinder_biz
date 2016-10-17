<?php
namespace Wifinder\FrontendContentBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NewsletterCommand extends Command
{
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output); //initialize parent class method
        $this->container = $this->getApplication()->getKernel()->getContainer();
        $this->em = $this->container->get('doctrine')->getManager(); // This loads Doctrine, you can load your own services as well
    }

    
    protected function configure()
    {
        $this->setName('news:send')
            ->setDescription('Newsletter');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $news = $this->em->getRepository('NewsBundle:NewsItem')->GetNewsNotSent();

        $mailTo = $this->em->getRepository('CallbackBundle:MailingAddress')->getEmailListForType(1);
        $arrayEmailTo = array();
        foreach($mailTo as $one){
            $arrayEmailTo[] = $one->getEmail();
        }
        
        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
            $output->writeln('News will be sent [array(' . implode(', ', $arrayEmailTo) . ')]');
        } 

        foreach($news as $one){

            if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity())
                $output->writeln('News id = [' . $one->GetId() . ']');

            $obj = $one->getTranslations();
            $title = '';
            $anons = '';
            foreach($obj as $text){
                if($text->getLocale() == $this->container->getParameter('locale')){
                    if($text->getField() == 'title'){
                        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) 
                            $output->writeln('title' . $text->getContent()); 
                        $title = $text->getContent();
                    }
                    if($text->getField() == 'anons'){
                        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) 
                            $output->writeln('anons' . $text->getContent()); 
                        $anons = $text->getContent();
                    }
                } 
            }

            $message = \Swift_Message::newInstance()
                  ->setSubject('Новости ООО Вайфайндер. '. $title)
                  ->setContentType('text/html')
                  ->setFrom($this->container->getParameter('mailer_mail_from'))
                  ->setTo($arrayEmailTo)
                  ->setBody(
                      $this->container->get('templating')->render(
                          'FrontendContentBundle:News:_newsletter.html.twig', array(
                              'entity' => $one,
                              'title' => $title,
                              'anons' => $anons,
                              'host' => $this->container->getParameter('host_name'))
                      ));
            $this->container->get('mailer')->send($message);
            
            if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                $output->writeln('sent');
            }
            $one->setIsSent(true);
            $this->em->persist($one);
            $this->em->flush();
        }
    }
}
