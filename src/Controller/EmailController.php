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
use Twilio\TwiML\Voice\Client as VoiceClient;

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
            $this->addFlash('success', 'courriel envoyé avec succès');
           $entityManager->persist( $maill);
           $entityManager->flush();
        
          
          // $this->addFlash('success', 'L\'ajout a été effectuée avec succeés');
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

 
        return $this->renderForm('inbox/index.html.twig', [
            'form' => $form,


           'modelemail'=>$datatitle,
           'lead'=> $onelead,
           'history'=>$mixhistory,
           'user'=>$tabuser,
        ]);
    }
}