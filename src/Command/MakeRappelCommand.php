<?php

namespace App\Command;

use App\Repository\LeadsRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mime\Email;
use App\Service\SendMailService;
use Symfony\Component\Mailer\MailerInterface;
use DateTime;
use Twilio\Rest\Client;
use App\Entity\Courriel;
use App\Entity\Sms;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'make:rappel',
    description: 'Add a short description for your command',
)]
class MakeRappelCommand extends Command
{
    public function __construct(MailerInterface $mailer,SendMailService $mail, LeadsRepository $lead, EntityManagerInterface $entityManager,LeadsRepository $leadsRepository,Security $security)
    {
        parent::__construct(); 
        $this->mailer = $mailer;
        $this->mail = $mail;
        $this->lead = $lead;
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->leadsRepository = $leadsRepository;
        
       

    }
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

     
     

     

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /*$data=$input->getArgument(name: 'rappel');
        if(is_null(value:$data)){
         $io= new SymfonyStyle( input:$input, output: $output )
         $io->info('test');
        } }*/

        $Aujourdhui= new DateTime('now');
        $Aujourdhui = $Aujourdhui->format('Y-m-d');
       // $daterappellead=$this->lead->findAll();

      
         
        $input->getArguments();

 
   /*************Lire les rappel et recupérer les constreintes ****************/

   $daterappellead=$this->lead->findAll();
  

       foreach($daterappellead as $title )
       {
        
        $datederappel=$title->getRappel();
      
        $sujet= $title->getSujet();
        $text= $title->getText();
        if($datederappel!= NULL)
        {
            $datederappel=$datederappel->format('Y-m-d');
             
        }

       
        $datalead=$title->getId();
        $datacourriel=$title->getCourriel();
        $datatelephone=$title->getTelephone();
        $datatextsms=$title->getTextSms();
           

             

               
        $onelead=$this->leadsRepository->findOneById($datalead);
        if($datederappel == $Aujourdhui )
        {
        
       

                 /*************Enregistrer Email dans la BD***********/   
     if (!empty($sujet) AND !empty($text) ) {
        $Email= new Courriel();
        $Email->setText($text);
        $Email->setSujet($sujet);
        $Email->setEmetteur("shajjar@genieducredit.com");
        $Email->setRecepteur($datacourriel);
        $Email->setLead($onelead);
        $Email->setModele(NULL);
 
      
        
    /*************send Email***********/ 
   
        $mail = (new Email())
        ->from( 'shajjar@genieducredit.com')
        ->to($datacourriel)
         ->subject($sujet)
        ->text($text)
     ;


     try {
     $this->mailer->send($mail);
     
    } catch (TransportExceptionInterface $e) {
        // some error prevented the email sending; display an
        // error message or try to resend the message 
       
     echo($e);
    
       
    }
    if (!isset($e))
    {
        
        $this->entityManager->persist($Email);
        $this->entityManager->flush();
    }else
    {
        
    }  

    }
    /*************send Sms***********/  
    if (!empty($datatextsms)) {  

        /*************Enregistrer Sms dans la BD***********/   
   
   
        $Sms= new Sms();
        $Sms->setText($datatextsms);
        $Sms->setEmetteur("+14386009101");
        $Sms->setRecepteur($datatelephone);
        $Sms->setLead($onelead);
        $Sms->setModele(NULL);




    $sid = 'AC7793004fae938a81497bdc5700f526c0';
    $token = '9066ca357e7b1dd2e6548ee288af547c';


    try {
        $client = new Client($sid, $token);

        $client->messages->create(
            // the number you'd like to send the message to
          //  $tellead,
          $datatelephone,
            [
                'from' => '+14386009101',
                'body' => $datatextsms
            ]
        );
        
    } catch (TransportExceptionInterface $e) {
        // some error prevented the email sending; display an
        // error message or try to resend the message 
       
     echo($e);
    
       
    }
    if (!isset($e))
    {
        
        $this->entityManager->persist($Sms);
        $this->entityManager->flush();
    }else
    {
        
    }  
   


    }
    /************* end SMS***********/ 
}  

      //  dump($datederappel);
     //   dump($Aujourdhui);
     /*   dump($datalead);
        dump($sujet);
        dump($text);
        dump($datacourriel);
        dump($datatelephone);
        dump($datatextsms);
        dump($onelead);*/
       
        


       }
      
      var_dump('fini');die;
        


        /*$io = new SymfonyStyle($input, $output);

        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.'); */

        return Command::SUCCESS;
    }
}
