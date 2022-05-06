<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Administrateur;
use App\Entity\Agent;
use App\Entity\Concessionnaire;
use App\Entity\Courriel;
use App\Entity\Leads;
use App\Entity\Marchand;
use App\Entity\Modeleemail;
use App\Entity\Notes;
use App\Entity\Partenaire;
use App\Entity\Sms;
use App\Entity\Status;
use App\Entity\Utilisateur;
use App\Entity\Vendeurr;
use App\Form\AgentType;
use App\Form\CourrielType;
use App\Form\LeadsType;
use App\Form\ModeleemailType;
use App\Form\SmsType;
use App\Repository\AdministrateurRepository;
use App\Repository\AgentRepository;
use App\Repository\LeadsRepository;
use App\Repository\ConcessionnaireRepository;
use App\Repository\CourrielRepository;
use App\Repository\MarchandRepository;
use App\Repository\ModeleemailRepository;
use App\Repository\ModelesmsRepository;
use App\Repository\NotesRepository;
use App\Repository\PartenaireRepository;
use App\Repository\SmsRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
 use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\Mailer\MailerInterface;
 use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mime\Email;
use App\Service\SendMailService;
use Twilio\Rest\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Notifier\NotifierInterface;

/**
* @IsGranted("IS_AUTHENTICATED_FULLY")
*/
class SmsController extends AbstractController
{
    #[Route('/chat/messages', name: 'chat')]
    public function index(CourrielRepository $mailRepository): Response
    {
        $Listedesemails = $mailRepository->findAll() ; 

       // dd($Listedesemails) ; die ; 
        
        return $this->render('chat/private.html.twig', [
            'Liste' => $Listedesemails,
        ]);
    }


    #[Route('/leads/sms/{idlead}', name: 'sms', methods: ['GET', 'POST'])]
    public function sms(Request $request,  Security $security ,ModelesmsRepository $modele_s,SmsRepository $smsRepository, EntityManagerInterface $entityManager,LeadsRepository $leadsRepository,SendMailService $mail, MailerInterface $mailer ,UtilisateurRepository $utilisateur,NotifierInterface $notifier ): Response
    {
        //$Listedesemails = $mailRepository->findAll() ;
      //  dd($Listedesemails) ; die ;
        $data= $request->getPathInfo();
        $res= explode('/',$data,4);
        $param=$res[3];
      //  dd($param);die;
       $paranfinal= intval($param);
       $sms = new Sms();
      // $maill2 = new Modeleemail();
       $form = $this->createForm(SmsType::class, $sms);
       $onelead=$leadsRepository->findOneById($paranfinal);
/*
       /** @var User $user 
         $user = $security->getUser();
         if(!empty($user)){
          $userEmail = $user->getTelephone();
          }
       $maillead=$onelead->getCourriel();
       $form->get('lead')->setData($onelead);
       $tellead=$onelead->getTelephone();
       $form->get('emetteur')->setData($userEmail);
       $form->get('recepteur')->setData($maillead);
*/
$form->get('lead')->setData($onelead);
$teluser="+14388174255";
$tellead="+15145836799"; 
$form->get('emetteur')->setData($teluser);
$form->get('recepteur')->setData($tellead);





        /*récupération de historique courriel de chaque lead*/
        $mixhistory=[];
        $historiqueenvoi=$smsRepository->findByEmetteur($tellead);
        $historiquereception=$smsRepository->findByRecepteur($tellead);
  
      
        foreach($historiqueenvoi as $value){
            array_push($mixhistory, $value);
       }
       foreach($historiquereception as $value){
        array_push($mixhistory, $value);
       }
       




       usort($mixhistory, function($a, $b)
       {
           if ($a->datecreation== $b->datecreation)
               return (0);
           return (($a->datecreation < $b->datecreation) ? -1 : 1);
       });
       
             $tabuser=[];
             $tabmailing=[];
             $users= $user= $utilisateur->findAll();
             foreach ($users as $key ){
               $userr[]=$key->getTelephone();
               

             }
            // dd($userr);
            
            
            foreach($mixhistory as $value){
                 $var1=$value->getRecepteur();
                // dd($var1);
                $var2=$value->getEmetteur();
             
                  if (( in_array($var1, $userr)) && (!in_array($var1, $tabmailing))){

                       //dd($var1);die;
                       array_push($tabmailing,$var1);

                    }

                   
                    elseif (( in_array($var2, $userr)) && (!in_array($var2, $tabmailing))){

                      // dd('sucess');die;
                        
                         array_push($tabmailing,$var2);
  
                      }
 
                  
               }
               //dd($var2);die;
             //  dd($tabmailing);die;
             foreach($tabmailing as $value){
                $user= $utilisateur->findIdByTelephone($value);
                array_push($tabuser,$user);
               /*  foreach($tabuser as $tab){
                $info=$tab->getId();
                }*/
               }
              // dd($tabuser);die;
             // dd($user);die;   
  
      //  dd($mixhistory);

            /*fin récupération*/





//dd($form->get('recepteur')) ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          //  dd($form->getData());die;

            $entityManager->persist($sms);
            $entityManager->flush();
      
  // dd($form->getData());die;

            $sid = 'AC257e749334cf5bbde19c3c21294be554';
            $token = 'dd0f223537f0a4cf572265ae71910436';
            $client = new Client($sid, $token);

            $client->messages->create(
                // the number you'd like to send the message to
              //  $tellead,
              $tellead,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => $teluser,
                   // 'from' => $teluser,
                    // the body of the text message you'd like to send
                   
                    'body' => $form->get('text')->getData()
                ]
            );

            
     //  dd($form->getData());die;
        return $this->redirectToRoute('sms',array('idlead' => $paranfinal));
    }



    $datatitle= $modele_s->FindAll();
    $tab= [];
    foreach($datatitle as $title )
    {
     $data2=$title->getTitre();
     array_push($tab, $data2);
    }

           

       
        //dd($tab);die;
        return $this->renderForm('sms/index.html.twig', [
            'form' => $form,
           // 'form1' =>$form1
           'history'=>$mixhistory,
          'modelesms'=>$datatitle,
          'lead'=> $onelead,
          'user'=>$tabuser,
        
        ]);
    }





}