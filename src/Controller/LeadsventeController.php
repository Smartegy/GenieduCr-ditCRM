<?php


namespace App\Controller;

use App\Entity\Administrateur;
use App\Entity\Agent;
use App\Entity\Concessionnaire;
use App\Entity\Leads;
use App\Entity\Marchand;
use App\Entity\Notes;
use App\Entity\OperationAchat;
use App\Entity\Partenaire;
use App\Entity\Status;
use App\Entity\Utilisateur;
use App\Entity\Vendeurr;
use App\Form\AgentType;

use App\Form\LeadsventeType;
use App\Repository\AdministrateurRepository;
use App\Repository\AgentRepository;
use App\Repository\LeadsRepository;
use App\Repository\ConcessionnaireRepository;
use App\Repository\MarchandRepository;
use App\Repository\ModeleemailRepository;
use App\Repository\ModelesmsRepository;
use App\Repository\NotesRepository;
use App\Repository\PartenaireRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VehiculeRepository;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use EightPoints\Bundle\GuzzleBundle\Events\Event;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\Date;


#[Route('/leadsvente')]
class LeadsventeController extends AbstractController
{
    #[Route('/', name: 'leadsvente_index', methods: ['GET'])]
    public function index(LeadsRepository $leadsRepository,Request $request): Response
    {
      
       

        $today = date("F j, Y, g:i a");          
        $tomorrow  = date('Y-m-d',strtotime("+1 days"));         // March 10, 2001, 5:16 pm
        $yesterday  = date('Y-m-d',strtotime("-1 days"));         // March 10, 2001, 5:16 pm

        //dump($tomorrow ) ; dump($yesterday) ; die ; 
        //dd($yesterday) ; die ; 
        $time = date('d/m/Y');
        $leadss = $leadsRepository ->findvente() ;
        $output = array();
        $yestR = array();
        $tomworrowR = array();

        
        foreach($leadss as $e)
        {
          $data =$e->getrappel();
          $data= $data->format('Y-m-d');
          $car = $e->getModele()  ;
          $car .= ' ';
          $car .= $e->getMarque() ;
          
          if($data == $tomorrow)
             { $tomworrowR[] = array(
              'id'=>$e->getid(),
              'nom'=>$e->getnom() ,
               'email'=>$e->getcourriel() , 
               'Phone'=>$e->getTelephone() , 
               'StatusLead' =>$e->getStatusleads(),
               'car' =>$car ,
               'date' =>$data
                   );
             }
  
        } 
        //dump($tomworrowR) ;

        foreach($leadss as $e)
        {
          $data =$e->getrappel();
          $data= $data->format('Y-m-d');
          $car = $e->getModele()  ;
          $car .= ' ';
          $car .= $e->getMarque() ;
          
          if($data == $yesterday)
             { $yestR[] = array(
              'id'=>$e->getid(),
              'nom'=>$e->getnom() ,
               'email'=>$e->getcourriel() , 
               'Phone'=>$e->getTelephone() , 
               'StatusLead' =>$e->getStatusleads(),
               'car' =>$car ,
               'date' =>$data
                   );
             }
  
        }

        //dump($yestR) ; 
    
        foreach($leadss as $e)
        {
          $data =$e->getrappel();
          $data1 =$e->getrappel();
          $data= $data->format('Y-m-d');
          

           
        
          $old_date = date('l, F d y h:i:s');   
         
          $now = date('Y-m-d'); 
          $car = $e->getModele()  ;
          $car .= ' ';
          $car .= $e->getMarque() ;
          
          if($data == $now)
             { $output[] = array(
              'id'=>$e->getid(),
              'nom'=>$e->getnom() ,
               'email'=>$e->getcourriel() , 
               'Phone'=>$e->getTelephone() , 
               'StatusLead' =>$e->getStatusleads(),
               'car' =>$car ,
               'date' =>$data
                   );
             }
  
        }       //  dump($output) ; die ;


     //  dd($output);die() ;
    // $size = count($output);
      

        $leads = $leadsRepository ->findvente();
        $form = $this->createFormBuilder()
    

      // -> add('nom', null , array('required' => false))
       // ->add('telephone', null , array('required' => false))
      //  ->add('courriel', null , array('required' => false))
        
        ->add('agent', EntityType::class,array(
            'class' => Agent::class,
            'choice_label' => 'utilisateur.nomutilisateur',
            'required' => false,
            'label' => false 
              
            ))
            ->add('vendeurr', EntityType::class,array(
                'class' => Vendeurr::class,
                'choice_label' => 'utilisateur.nomutilisateur',
                'required' => false,
                'label' => false 
                  
                ))
              ->add('partenaire', EntityType::class,array(
                    'class' => Partenaire::class,
                    'choice_label' => 'utilisateur.nomutilisateur',
                    'required' => false,
                    'label' => false 
                      
                    ))
                    ->add('concessionnaire', EntityType::class,array(
                        'class' => Concessionnaire ::class,
                        'choice_label' => 'concessionnairemarchand.utilisateur.nomutilisateur',
                        'required' => false,
                        'label' => false 

                        ))
                    ->add('marchand', EntityType::class,array(
                      'class' => Marchand ::class,
                        'choice_label' => 'concessionnairemarchand.utilisateur.nomutilisateur',
                        'required' => false,
                        'label' => false 
                                
                       ))
                            
                    ->add('administrateur',EntityType::class,array(
                                
                       'class' => Administrateur::class,
                        'choice_label' => 'utilisateur.nomutilisateur',
                        'required' => false,
                        'label' => false       
                  
                            ))
         ->add('rappel' , DateType::class , array('required' => false))
         ->add('datecreation', DateType::class , array('required' => false)) 
        ->add('End', DateType::class , array('required' => false)) 
        // ->add('datecreationend', DateType::class , array(  'required' => false)) 
         ->add('Submit', SubmitType::class)

        ->add('Reset', ResetType::class )

       ->getForm();
        ;
        $form -> handleRequest($request);  
      //  $nom =$form->get('nom')->getData() ;
      //  $tel =$form->get('telephone')->getData() ;    
      //  $email =$form->get('courriel')->getData() ;     
        $agent =$form->get('agent')->getData() ;
        $vendeurr =$form->get('vendeurr')->getData() ;  
        $partenaire =$form->get('partenaire')->getData() ; 
        $concessionnaire =$form->get('concessionnaire')->getData() ; 
        $marchand =$form->get('marchand')->getData() ;  
        $administrateur =$form->get('administrateur')->getData() ;
        $rappel =$form->get('rappel')->getData() ; 
        $datecreation =$form->get('datecreation')->getData() ;   
       // $datecreationend =$form->get('datecreationend')->getData() ;   

        $End =$form->get('End')->getData() ;   

       // $string =$End->format('Y-m-d')  ;
    //  dd($string) ; die() ;       

        
        $phe ='' ;
        $condition = '' ;
        if ($agent )
         { $U_form = $form->get('agent')->getData();
         $condition .=  '&& $v->getAgent() == $U_form ' ; }
         if ($vendeurr )
         { $U_form = $form->get('vendeurr')->getData();
         $condition .=  '&& $v->getVendeurr() == $U_form ' ; }
         if ($partenaire )
         { $U_form = $form->get('partenaire')->getData();
         $condition .=  '&& $v->getPartenaire() == $U_form ' ; }
         if ($concessionnaire )
         { $U_form = $form->get('concessionnaire')->getData();
         $condition .=  '&& $v->getConcessionnaire() == $U_form ' ; }
         if ($marchand )
         { $U_form = $form->get('marchand')->getData();
         $condition .=  '&& $v->getMarchand() == $U_form ' ; }
         if ($administrateur )
         { $U_form = $form->get('administrateur')->getData();
         $condition .=  '&& $v->getAdministrateur() == $U_form ' ; }
       //  if ($nom) 
        // { $nom_form = $form->get('nom')->getData( ) ;
        //   $condition .=  '&& $v->getNom() == $nom_form ' ; }
        //   if ($tel) 
       //    { $tel_form = $form->get('telephone')->getData( );
      //       $condition .=  '&& $v->getTelephone() == $tel_form ' ; }
      //   if ($email)
       //  { $email_form = $form->get('courriel')->getData();
         // $condition .=  '&& $v->getCourriel == $email_form ' ; }
          if ($rappel)
         {  $rappel_form = $form->get('rappel')->getData();
          $condition .=  '&& $v->getRappel() == $rappel_form ' ; }


          if ($datecreation && $End )
               {  $datecreation_form = $form->get('datecreation')->getData() ;
                  $End_form = $form->get('End')->getData() ;
                 $condition .=  '&& $v->getDatecreation() <= $End_form && $v->getDatecreation() >= $datecreation_form' ; 
                }
            elseif ($datecreation )
                {
                    $datecreation_form = $form->get('datecreation')->getData() ;
                    $condition .=  '&& $v->getDatecreation() >= $datecreation_form' ;
                
                }
            elseif ($End)
          {  $End_form = $form->get('End')->getData() ;
           $condition .=  '&& $v->getDatecreation() <= $End_form ' ; }


                
         //  dd($datecreationend);die;
         //  dd($condition) ; die() ;

 
                             if ( substr($condition, 0,2) == '&&' ) 
 
                             { $condition[0] = " " ;
                                 $condition[1] = " " ;
 
                             }
 
 
                            $cmd = ' if (' . $condition . ') ';
                                 $cmd .= ' {  ' ;

                                    
           // $condition .= $phe . ' = true' ;
           $cmd .= '$phe = true ;'   ;
           $cmd .= '  } ' ;
           $cmd .= ' else {  $phe = false ;}   ' ;
           $i =0 ;
           $filterr = $leadsRepository -> findvente() ;
          // dd($cmd) ; die () ;
          // dd($cmd) ; die() ;
           if(!empty($condition))
 
           {   $filterr = [] ;
             foreach ( $leads as $v)
             {

             //  dump($string );
                 ++$i ;
 
                 if($condition)
                 { eval( $cmd );
                     if($phe == 'true') 
                     { 
                         $filterr[$i] = $v ;
                       // dd($cmd); die() ;
                        
                        }
      
                     }
                 }
                  // dd('End') ;
             }
             else {$filterr = $leadsRepository -> findvente() ;}
         
 
          //   dd($filterr); die() ;


//  dd($filterr);die;
                                 
      //  dd(filterr);die;
        return $this->render('leadsvente/index.html.twig', [
         
            'form' => $form->createView(),
            'leads' => $filterr , 
            'rappel' =>   $output , 
            'yesterday' =>   $yestR , 
            'tomworrow' =>   $tomworrowR , 

         //   'size' => $size
           
        ]);
    }

    #[Route('/new', name: 'leadsvente_new', methods: ['GET', 'POST'])]
    public function new(LeadsRepository $leads,ModeleemailRepository $email,ModelesmsRepository $sms,Request $request, EntityManagerInterface $entityManager , UtilisateurRepository $u, AgentRepository $agent ,PartenaireRepository $partenaire , AdministrateurRepository $administrateur , ConcessionnaireRepository $concessionnaire , MarchandRepository $marcha): Response
    {
      
   
        $lead = new Leads();
       // dd($lead);
      
        $form = $this->createForm(LeadsventeType::class, $lead);

     
        
        $form->get('type')->setData(false);
        $form->get('isCLient')->setData(false);
        $form->handleRequest($request);
        $modele=$email->findAll();
        
      
      
        if ($form->isSubmitted() && $form->isValid()) {
 
       
            $entityManager->persist($lead);
            $entityManager->flush();


            return $this->redirectToRoute('leadsvente_index', [], Response::HTTP_SEE_OTHER);
        }
     

        return $this->renderForm('leadsvente/new.html.twig', [
           
            'lead' => $lead,
            'form' => $form,
            'modelee'=>$modele,
            
        ]);
    }

    #[Route('/{id}/show', name: 'leadsvente_show', methods: ['GET'])]
    public function show(Leads $lead): Response
    {
       // dd($lead);
        return $this->render('leadsvente/show.html.twig', [
            'lead' => $lead,

        ]);
    }
  

    #[Route('/{id}/modif', name: 'leadsvente_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Leads $lead,LeadsRepository $leadrep,ModeleemailRepository $email,EntityManagerInterface $entityManager,VehiculeRepository $vehicule)
    {
        $form = $this->createForm(LeadsventeType::class, $lead);
        $form->handleRequest($request);
        $modele=$email->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
           // $numserie=$form->get('numserie')->getData(); 
          
           if($form->get('statusleads')->getData() == "Livrés")
           { 
        
          /*  $name=$form->get('nom')->getData();
            $modele=$form->get('modele_vente')->getData();
           
         
            $namelead=$leadrep->findOneByNom($name);

            $operationA= new OperationAchat();
        
           
            $operationA->setModele($modele);
          
            $operationA->setNumserie('NULL');
                $operationA->setVehicule(NULL);
                $operationA->setLeads($namelead);
          
               $entityManager->persist($operationA);
               $entityManager->flush(); 


               $onecar->setActif('0');
               $entityManager->persist($onecar);
               $entityManager->flush();*/
               $numserie=$form->get('numserie')->getData(); 
               $prix_achat=$form->get('prix_achat')->getData(); 
            
               $modelevente=$form->get('modele_vente')->getData();  
                $marquevente=$form->get('marquevente')->getData(); 
                $prix_vente=$form->get('prix_vente')->getData(); 
    
                    $onecar= $vehicule->findOneByVin($numserie);
                    $name=$form->get('nom')->getData();
                    $namelead=$leadrep->findOneByNom($name);
     
                    $operationA= new OperationAchat();
                    
                    if( $numserie != null )
                    {
                    $operationA->setNumserie($numserie);
                    $operationA->setVehicule($onecar);
              
                    $operationA->setPrixAchat($prix_achat);

                  
                    $onecar->setActif('0');
                    $entityManager->persist($onecar);
                    $entityManager->flush();
                    

                    }
                    $operationA->setPrixVente($prix_vente);
                    $operationA->setModele($modelevente);
                    $operationA->setMarque($marquevente);
                    $operationA->setLeads($namelead);
               
                       
                  
                       $entityManager->persist($operationA);
                       $entityManager->flush();
                 
                       
                     
           }
            return $this->redirectToRoute('leadsvente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('leadsvente/edit.html.twig', [
            'lead' => $lead,
            'form' => $form,
            'modelee'=>$modele,
        ]);
    }


   






    #[Route('/{id}/delete', name: 'leadsvente_delete')]
  
    public function delete(Request $request, Leads $lead, EntityManagerInterface $entityManager): Response
    {
      // dd('hello');die;
            $entityManager->remove($lead);
            $entityManager->flush();
        

        return $this->redirectToRoute('leadsvente_index');
    }
}
