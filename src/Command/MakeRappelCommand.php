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

#[AsCommand(
    name: 'make:rappel',
    description: 'Add a short description for your command',
)]
class MakeRappelCommand extends Command
{
    public function __construct(MailerInterface $mailer,SendMailService $mail, LeadsRepository $lead, EntityManagerInterface $entityManager,LeadsRepository $leadsRepository)
    {
        parent::__construct(); 
        $this->mailer = $mailer;
        $this->mail = $mail;
        $this->lead = $lead;
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
        $datederappel=$datederappel->format('Y-m-d');
        $datalead=$title->getId();
        $datacourriel=$title->getCourriel();
        $datatelephone=$title->getTelephone();
        $datatextsms=$title->getTextSms();

        $onelead=$this->leadsRepository->findOneById($datalead);
        if($datederappel == $Aujourdhui )
        {
    /*************send Email***********/ 
    if (!empty($sujet) AND !empty($text) ) {   
        $mail = (new Email())
        ->from( 'imen.jouini123@gmail.com')
        ->to($datacourriel)
         ->subject($sujet)
        ->text($text)
     ;
     $this->mailer->send($mail);

    }
    /*************send Sms***********/  
    if (!empty($datatextsms)) {  
    $sid = 'AC257e749334cf5bbde19c3c21294be554';
    $token = 'dd0f223537f0a4cf572265ae71910436';
    $client = new Client($sid, $token);

    $client->messages->create(
        // the number you'd like to send the message to
      //  $tellead,
      $datatelephone,
        [
            'from' => '+14388174255',
            'body' => $datatextsms
        ]
    );
    }  
     /*************Enregistrer Email dans la BD***********/   
     if (!empty($sujet) AND !empty($text) ) {
     $Email= new Courriel();
     $Email->setText($text);
     $Email->setSujet($sujet);
     $Email->setEmetteur("jouini.imen123@gmail.com");
     $Email->setRecepteur($datacourriel);
     $Email->setLead($onelead);
     $Email->setModele(NULL);
     $this->entityManager->persist($Email);
     $this->entityManager->flush();
    }
    /*************Enregistrer Sms dans la BD***********/   
    if (!empty($datatextsms)) {
   
         $Sms= new Sms();
          $Sms->setText($datatextsms);
          $Sms->setEmetteur("+14388174255");
          $Sms->setRecepteur($datatelephone);
          $Sms->setLead($onelead);
          $Sms->setModele(NULL);
          $this->entityManager->persist($Sms);
          $this->entityManager->flush();

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
