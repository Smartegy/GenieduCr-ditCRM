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
use App\Entity\Status;
use App\Entity\Utilisateur;
use App\Entity\Vendeurr;
use App\Form\AgentType;
use App\Form\CourrielType;
use App\Form\LeadsType;
use App\Form\ModeleemailType;
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
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
* @IsGranted("IS_AUTHENTICATED_FULLY")
*/
class EmailController extends AbstractController
{
    #[Route('/inbox/email', name: 'email')]
    public function index(CourrielRepository $mailRepository): Response
    {
        $Listedesemails = $mailRepository->findAll() ;
       // dd($Listedesemails) ; die ;
        return $this->render('inbox/email/index.html.twig', [
            'Liste' => $Listedesemails,
        ]);
    }
    #[Route('/inbox/email/view/{id}', name: 'view_email' , methods: ['GET'])]
    public function view(Courriel $Courriel): Response
    {
      //  $Listedesemails = $mailRepository->findAll() ;
       // dd($Listedesemails) ; die ;
        return $this->render('inbox/email/view.html.twig', [
            'courriel' => $Courriel,
        ]);
    }
    #[Route('/inbox/modele/view/{id}', name: 'view_modele' , methods: ['GET'])]
    public function viewmodele(Modeleemail $Courriel): Response
    {
      //  $Listedesemails = $mailRepository->findAll() ;
       // dd($Listedesemails) ; die ;
        return $this->render('inbox/modele_email/view.html.twig', [
            'courriel' => $Courriel,
        ]);
    }
    #[Route('/inbox/modele/new', name: 'new_modele')]
    public function new(ModeleemailRepository $mailRepository): Response
    {
        $Listedesemails = $mailRepository->findAll() ;
       // dd($Listedesemails) ; die ;
        return $this->render('inbox/modele_email/new.html.twig', [
            'Liste' => $Listedesemails,
        ]);
    }
    #[Route('/inbox/modele', name: 'modele')]
    public function liste( ModeleemailRepository $modele): Response
    {
        $Listedesemails = $modele->findAll() ;
       // dd($Listedesemails) ; die ;
        return $this->render('inbox/modele_email/liste.html.twig', [
            'Liste' => $Listedesemails,
        ]);
    }
    #[Route('/leads/courriel/{idlead}', name: 'courriel', methods: ['GET', 'POST'])]
    public function courriel(Request $request,  Security $security , ModeleemailRepository $modele_e,CourrielRepository $mailRepository, EntityManagerInterface $entityManager,LeadsRepository $leadsRepository,SendMailService $mail, MailerInterface $mailer ,UtilisateurRepository $utilisateur): Response
    {
        $Listedesemails = $mailRepository->findAll() ;
      //  dd($Listedesemails) ; die ;
        $data= $request->getPathInfo();
        $res= explode('/',$data,4);
        $param=$res[3];
      //  dd($param);die;
       $paranfinal= intval($param);
       $maill = new Courriel();
      // $maill2 = new Modeleemail();
       $form = $this->createForm(CourrielType::class, $maill);
       $onelead=$leadsRepository->findOneById($paranfinal);


       /** @var User $user */
         $user = $security->getUser();
         if(!empty($user)){
          $userEmail = $user->getCourriel();
          }
       $maillead=$onelead->getCourriel();
       $form->get('lead')->setData($onelead);
       $form->get('emetteur')->setData($userEmail);
       $form->get('recepteur')->setData($maillead);
        $form->handleRequest($request);
        /*récupération de historique courriel de chaque lead*/
        $mixhistory=[];
        $historiqueenvoi=$mailRepository->findByEmetteur($maillead);
        $historiquereception=$mailRepository->findByRecepteur($maillead);
  
      
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
               $userr[]=$key->getCourriel();
               

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

                   
                    if (( in_array($var2, $userr)) && (!in_array($var2, $tabmailing))){

                      // dd('sucess');die;
                        
                         array_push($tabmailing,$var2);
  
                      }
 
                  
               }
               //dd($var2);die;
             //  dd($tabmailing);die;
             foreach($tabmailing as $value){
                $user= $utilisateur->findIdByCourriel($value);
                array_push($tabuser,$user);
               /*  foreach($tabuser as $tab){
                $info=$tab->getId();
                }*/
               }
              // dd($tabuser);die;
             // dd($user);die;   
  
      //  dd($mixhistory);

            /*fin récupération*/


        if ($form->isSubmitted() && $form->isValid()) {
           $entityManager->persist( $maill);
           $entityManager->flush();
         //  dd($form) ; die() ;
           $mail = (new Email())
            ->from( $userEmail )
            ->to($maillead)
           // ->subject($subject)
             ->subject($form->get('sujet')->getData())
            //->text($Message)
            ->text($form->get('text')->getData())
         ;
         $mailer->send($mail);
         $uri = $request->getUri();
         $subject =$form->get('modele')->getData() ;
        //$x = $form->getData() ;
    

     
       //  dd($x) ; die ;
            return $this->redirectToRoute('courriel',array('idlead' => $paranfinal));
        }
        $datatitle= $modele_e->FindAll();
        $tab= [];
        foreach($datatitle as $title )
        {
         $data2=$title->getTitre();
         array_push($tab, $data2);
        }
        //dd($tab);die;

    //*************************************************rappel Programée********************* *//
    for($i=0;$i<1;$i++)
   {
     $data1= new DateTime('now');
     $data2= $data1;
     if($data1 == $data2 )
     {
/*************Save in Data base ***********/
  $emaile2 = new Courriel();
  $onelead1=$leadsRepository->findOneById($paranfinal);
  $emaile2->setText("Test depuis Génie du crédit");
  $emaile2->setSujet("Test depuis Génie du crédit");
  $emaile2->setEmetteur("jouini.imen123@gmail.com");
  $emaile2->setRecepteur("etudiant.jouini.imen@uvt.tn");
  $emaile2->setLead($onelead1);
  $emaile2->setModele(NULL);
  $entityManager->persist( $emaile2);
  $entityManager->flush();
  /*************Send Email ***********/
   $emaile = (new Email())
     ->from( 'jouini.imen123@gmail.com' )
     ->to('etudiant.jouini.imen@uvt.tn')
    // ->subject($subject)
      ->subject('Test depuis Génie du crédit')
     //->text($Message)
     ->text('Test depauis Génie du crédit')
  ;
  $mailer->send($emaile);

  /*************Send Sms ***********/ 
  $sid = 'AC257e749334cf5bbde19c3c21294be554';
  $token = 'dd0f223537f0a4cf572265ae71910436';
  $client = new Client($sid, $token);

  $client->messages->create(
      // the number you'd like to send the message to
    //  $tellead,
    "--",
      [
          // A Twilio phone number you purchased at twilio.com/console
          'from' => "+14388174255",
         // 'from' => $teluser,
          // the body of the text message you'd like to send
         
          'body' => "Test depuis Génie du crédit"
      ]
  );
  echo("evoi email et sms");
  



}

   }

    //*************************************************rappel Programée********************* *//
        return $this->renderForm('inbox/index.html.twig', [
            'form' => $form,
           // 'form1' =>$form1

           'modelemail'=>$datatitle,
           'lead'=> $onelead,
           'history'=>$mixhistory,
           'user'=>$tabuser,
        ]);
    }
}