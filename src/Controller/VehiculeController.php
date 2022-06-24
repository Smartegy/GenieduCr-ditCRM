<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\Typemedia;
use App\Entity\Concessionnaire;
use App\Entity\GalerieVehicule;
use App\Entity\Marchand;
use App\Entity\Medias;
use App\Repository\VehiculeRepository;
use App\Repository\ModeleRepository;
use App\Repository\ConcessionnaireRepository;
use App\Repository\FabriquantRepository;
use App\Repository\MarchandRepository;
use App\Repository\StatusRepository;
use App\Repository\PartenaireRepository;
use App\Repository\TestRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\VehiculeType;
use App\Form\MediaType;
use App\Repository\AdministrateurRepository;
use App\Repository\AgentRepository;
use App\Repository\ConcessionnairemarchandRepository;
use App\Repository\GalerieVehiculeRepository;
use App\Repository\MediasRepository;
use App\Repository\TypemediaRepository;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UtilisateurRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


use App\Entity\Fabriquant;
use App\Entity\Modele;
 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
 use App\Form\TestType;
use App\Entity\Status;
use App\Entity\Utilisateur;
use App\Repository\VendeurrRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

/**
* @IsGranted("IS_AUTHENTICATED_FULLY")
*/


class VehiculeController extends AbstractController
{
    public function __construct(MediasRepository $MR,
     PartenaireRepository $partenaireRepository, 
     AgentRepository $agentRepository,
     ConcessionnairemarchandRepository $concessionnairemarchandRepository,
     AdministrateurRepository $administrateurRepository,
     ObjectManager $om,
     GalerieVehiculeRepository $repositorygalerie,
     VehiculeRepository $vehiculeRepository,
     

    )
    {
        $this->MR = $MR;
        $this->om = $om;
        
        //ici on instancie le repo
        $this->vehiculeRepository=$vehiculeRepository;
       
        
    }

 

    #[Route('/vehicule', name: 'vehicule')]
    public function filter( ModeleRepository $MRep ,Security $security, VehiculeRepository $repository,FabriquantRepository $Frep,  StatusRepository $Rstatus , Request $request ,VendeurrRepository $vendeurr, UtilisateurRepository $Users,ConcessionnaireRepository $consi,AgentRepository $agents,MarchandRepository $marchand,PartenaireRepository $partenaire)
    {
        
        /** @var User $user */
        $user = $security->getUser();
        $userrole = $user->getRoles();
        $userId= $user->getId();

        if($userrole[0] == 'ROLE_AGENT' )
        {
            ////////////////véhicules visibles par l'agent////////////////   
            /////////////véhicules de l'agent//////////////////  
            $agentt= $Users->findOneById($userId);
            $voitureagentt=$repository->findListbyCompagnie($agentt);
         
            ///////////////////véhicules du concessionnaire de cet agent/////////////////////////////
            $agent=$agents->findOneByIdutilisateur($userId);
            $consbyagent =$consi->findByAgent($agent);
       
            $tabcons=[];
            foreach ($consbyagent as $value)
            {
                 $userr=$Users->findOneByConcessionnaire($value);
                 array_push($tabcons,$userr); 
            }

            $tabconsveh=[];
            foreach ($tabcons as $val)
            {
                $voiturconsisionnaire=$repository->findListbyCompagnie($val);
                array_push($tabconsveh,$voiturconsisionnaire); 
            }
          
            $vehicons=call_user_func_array('array_merge',$tabconsveh);
            //////////////////////////////////véhicules du partenaire de cet agent//////////////////////////////////////////////////////////
          
            $partbyagent=$partenaire->findByAgent($agent);
            $tabpart=[];
            foreach ($partbyagent as $value)
            {
                   $userpart=$Users->findOneByPartenaire($value);
                 array_push($tabpart,$userpart); 
            }
            
            $tabpartveh=[];
            foreach ($tabpart as $value)
            {
                 $voiturpartenaire=$repository->findListbyCompagnie($value);
                 array_push($tabpartveh,$voiturpartenaire); 
            }
            
            $vehipart=call_user_func_array('array_merge',$tabpartveh);
           ////////////////////////////////////véhicules du marchand de cet agent///////////////////////
            $marchanbyagent=$marchand->findByAgent($agent);
            $tabmarch=[];
            foreach ($marchanbyagent as $value)
            {
                 $userrmarch=$Users->findOneByMarchand($value);
                 array_push($tabmarch,$userrmarch); 
            }
            $tabmarchveh=[];
            foreach ($tabmarch as $value)
            {
                 $voiturmarchand=$repository->findListbyCompagnie($value);
                 array_push($tabmarchveh,$voiturmarchand); 
            }
            $vehimarch=call_user_func_array('array_merge',$tabmarchveh);
            /////////////////////////grouprmrnt de le liste des véhicule visible par l'agent/////////
       
 
            $vehiculeagent = array_merge($voitureagentt,$vehipart,$vehimarch,$vehicons);
           
      
            ////////////////fin/////////////////////////////////////////
        }elseif($userrole[0] == 'ROLE_VENDEUR' )
        {
            

               ////////////////véhicules visibles par vendeur////////////////   
            /////////////véhicules de vendeur//////////////////  
            $vendeurrs= $Users->findOneById($userId);
         
            $voiturevendeurr=$repository->findListbyCompagnie($vendeurrs);
           
            ///////////////////véhicules du concessionnaire de ce vendeur/////////////////////////////
            $vendeur=$vendeurr->findIdByUtilisateur($userId);
           
            $consbyvendeur =$consi->findByVendeur($vendeur);
            $tabcons=[];
            foreach ($consbyvendeur as $value)
            {
                 $userr=$Users->findOneByConcessionnaire($value);
                 array_push($tabcons,$userr); 
            }

            $tabconsveh=[];
            foreach ($tabcons as $val)
            {
                $voiturconsisionnaire=$repository->findListbyCompagnie($val);
                array_push($tabconsveh,$voiturconsisionnaire); 
            }
            $vehicons=call_user_func_array('array_merge',$tabconsveh);
            //////////////////////////////////véhicules du partenaire de ce vendeur//////////////////////////////////////////////////////////
          
            $partbyagent=$partenaire->findByVendeur($vendeur);
            $tabpart=[];
            foreach ($partbyagent as $value)
            {
                   $userpart=$Users->findOneByPartenaire($value);
                 array_push($tabpart,$userpart); 
            }
            
            $tabpartveh=[];
            foreach ($tabpart as $value)
            {
                 $voiturpartenaire=$repository->findListbyCompagnie($value);
                 array_push($tabpartveh,$voiturpartenaire); 
            }
            
            $vehipart=call_user_func_array('array_merge',$tabpartveh);
           ////////////////////////////////////véhicules du marchand de ce vendeur///////////////////////
            $marchanbyagent=$marchand->findByVendeur($vendeur);
            $tabmarch=[];
            foreach ($marchanbyagent as $value)
            {
                 $userrmarch=$Users->findOneByMarchand($value);
                 array_push($tabmarch,$userrmarch); 
            }
            $tabmarchveh=[];
            foreach ($tabmarch as $value)
            {
                 $voiturmarchand=$repository->findListbyCompagnie($value);
                 array_push($tabmarchveh,$voiturmarchand); 
            }
            $vehimarch=call_user_func_array('array_merge',$tabmarchveh);
            /////////////////////////grouprmrnt de le liste des véhicule visible par vendeur/////////
       
 
            $vehiculevendeur = array_merge($voiturevendeurr,$vehipart,$vehimarch,$vehicons);
           
      
            ////////////////fin/////////////////////////////////////////
        }
        elseif(($userrole[0] == 'ROLE_CONCESSIONNAIRE') || ($userrole[0] == 'ROLE_PARTENAIRE') || ($userrole[0] == 'ROLE_MARCHAND'))
        {
////////////////véhicules visibles par Concessionnaire et marchand et partenaire////////////////  
      $con= $Users->findOneById($userId);
      $data=$repository->findListbyCompagnie($con);
 ////////////////fin/////////////////////////////////////////   
      }  







      if($userrole[0] == 'ROLE_ADMIN' )
  {

        $form = $this->createFormBuilder()
        ->add('Year',
            'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
            'choices' => $this->getYears(1960) , 
            'label' => false,
            'required' => false
        ])


        ->add('Status',EntityType::class,array(
            'class' => Status::class,
            'choice_label' => function ($status) {
             
                return $status->getNom();
             },
             'expanded' => false ,
             'required' => false ,
             'label' => false 

        ))

        ->add('Marque',EntityType::class,array(
            'class' => Fabriquant::class,
            'choice_label' => function ($F) {
             
                return $F->getNom();
             },
             'required' => false ,
             'label' => false 

        ))

        ->add('Modele',EntityType::class,array(
            'class' => Modele::class,
            'choice_label' => function ($M) {
             
                return $M->getNom();
             },
             'required' => false ,
             'label' => false 

        ))


        ->add('Users',EntityType::class,array(
            'class' => Utilisateur::class,
            'choice_label' => function ($users) {
             
                return $users->getnom();
             },
             'required' => false ,
             'label' => false 

        ))

        ->add('Inv',EntityType::class,array(
            'class' => Vehicule::class,
            'choice_label' => function ($vehicules) {
             
                return $vehicules->getNuminventaire();
             },
             'required' => false ,
             'label' => false 

        ))
        ->add('Submit', SubmitType::class)

        ->add('Reset', ResetType::class )

        ->getForm();
        ;

        $form -> handleRequest($request);
        $y =$form->get('Year')->getData() ;
        $Status =$form->get('Status')->getData() ;
        $marque =$form->get('Marque')->getData() ;
        $Modele =$form->get('Modele')->getData() ; 
        $Users =$form->get('Users')->getData();  
        $vehicules = $repository -> findAll();
        $vehicule1 = $repository -> findOneById(1);
        $Inv =$form->get('Inv')->getData();           
          //   dd($vehicule1->getMainPhoto()) ; die () ;
      

       $phe ='' ;
       $condition = '' ;
       if ($marque)
       { $marque_form = ($form->get('Marque')->getData( ))->getnom() ;
        $condition .=  '$v->getmarque()->getnom()  ==   $marque_form '  ; }
        if ($Modele) 
        { $modele_form = ($form->get('Modele')->getData( ))->getnom()  ;
            $condition .=  '&& $v->getmodele()->getnom() == $modele_form ' ; }
            if ($Inv)
            {   $Inv_form = ($form->get('Inv')->getData())->getNuminventaire()  ;
                $condition .=  '&& $v->getNuminventaire() == $Inv_form ' ; }
                if ($Users  )
                { $U_form = $form->get('Users')->getData()->getNomutilisateur();

                    $condition .=  '&& $v->getutilisateur()->getNomutilisateur() == $U_form ' ; }
                    if ($y  )
                    {$Y_form = $form->get('Year')->getData() ;
                        $condition .=  '&& $v->getannee() == $Y_form ' ; } 
                        if ($Status )
                        {$S_form = $form->get('Status')->getData()->getnom();
                            $condition .=  '&& $v->getstatus()->getnom() == $S_form  ' ; }




                            if ( substr($condition, 0,2) == '&&' ) 

                            { $condition[0] = " " ;
                                $condition[1] = " " ;

                            }


                            {$cmd = ' if (' . $condition . ') ';
                                $cmd .= ' {  ' ;
          // $condition .= $phe . ' = true' ;
          $cmd .= '$phe = true ;'   ;
          $cmd .= '  } ' ;
          $cmd .= ' else {  $phe = false ;}   ' ;




          $i =0 ;
          $filterr = $repository -> findAll() ;
          if(!empty($condition))

          {   $filterr = [] ;
            foreach ( $vehicules as $v)
            {
                ++$i ;

                if($condition)
                { eval( $cmd );
                    if($phe == 'true') 
                    { 
                        $filterr[$i] = $v ; }
     
                    }
                }
            }
            else {$filterr = $repository -> findAll() ;}
        }
 } 
 elseif($userrole[0] == 'ROLE_AGENT' )
 {

       $form = $this->createFormBuilder()
       ->add('Year',
           'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
           'choices' => $this->getYears(1960) , 
           'label' => false,
           'required' => false
       ])


       ->add('Status',EntityType::class,array(
           'class' => Status::class,
           'choice_label' => function ($status) {
            
               return $status->getNom();
            },
            'expanded' => false ,
            'required' => false ,
            'label' => false 

       ))

       ->add('Marque',EntityType::class,array(
           'class' => Fabriquant::class,
           'choice_label' => function ($F) {
            
               return $F->getNom();
            },
            'required' => false ,
            'label' => false 

       ))

       ->add('Modele',EntityType::class,array(
           'class' => Modele::class,
           'choice_label' => function ($M) {
            
               return $M->getNom();
            },
            'required' => false ,
            'label' => false 

       ))


       ->add('Users',EntityType::class,array(
           'class' => Utilisateur::class,
           'choice_label' => function ($users) {
            
               return $users->getnom();
            },
            'required' => false ,
            'label' => false 

       ))

       ->add('Inv',EntityType::class,array(
           'class' => Vehicule::class,
           'choice_label' => function ($vehicules) {
            
               return $vehicules->getNuminventaire();
            },
            'required' => false ,
            'label' => false 

       ))
       ->add('Submit', SubmitType::class)

       ->add('Reset', ResetType::class )

       ->getForm();
       ;

       $form -> handleRequest($request);
       $y =$form->get('Year')->getData() ;
       $Status =$form->get('Status')->getData() ;
       $marque =$form->get('Marque')->getData() ;
       $Modele =$form->get('Modele')->getData() ; 
       $Users =$form->get('Users')->getData();  
       $vehicules = $repository -> findAll();
       $vehicule1 = $repository -> findOneById(1);
       $Inv =$form->get('Inv')->getData();           
         //   dd($vehicule1->getMainPhoto()) ; die () ;
     

      $phe ='' ;
      $condition = '' ;
      if ($marque)
      { $marque_form = ($form->get('Marque')->getData( ))->getnom() ;
       $condition .=  '$v->getmarque()->getnom()  ==   $marque_form '  ; }
       if ($Modele) 
       { $modele_form = ($form->get('Modele')->getData( ))->getnom()  ;
           $condition .=  '&& $v->getmodele()->getnom() == $modele_form ' ; }
           if ($Inv)
           {   $Inv_form = ($form->get('Inv')->getData())->getNuminventaire()  ;
               $condition .=  '&& $v->getNuminventaire() == $Inv_form ' ; }
               if ($Users  )
               { $U_form = $form->get('Users')->getData()->getNomutilisateur();

                   $condition .=  '&& $v->getutilisateur()->getNomutilisateur() == $U_form ' ; }
                   if ($y  )
                   {$Y_form = $form->get('Year')->getData() ;
                       $condition .=  '&& $v->getannee() == $Y_form ' ; } 
                       if ($Status )
                       {$S_form = $form->get('Status')->getData()->getnom();
                           $condition .=  '&& $v->getstatus()->getnom() == $S_form  ' ; }




                           if ( substr($condition, 0,2) == '&&' ) 

                           { $condition[0] = " " ;
                               $condition[1] = " " ;

                           }


                           {$cmd = ' if (' . $condition . ') ';
                               $cmd .= ' {  ' ;
         // $condition .= $phe . ' = true' ;
         $cmd .= '$phe = true ;'   ;
         $cmd .= '  } ' ;
         $cmd .= ' else {  $phe = false ;}   ' ;




         $i =0 ;
         $filterr = $vehiculeagent ;
         if(!empty($condition))

         {   $filterr = [] ;
           foreach ( $vehicules as $v)
           {
               ++$i ;

               if($condition)
               { eval( $cmd );
                   if($phe == 'true') 
                   { 
                       $filterr[$i] = $v ; }
    
                   }
               }
           }
           else {$filterr = $vehiculeagent ;}
       }
}   if($userrole[0] == 'ROLE_ADMIN' )
{

      $form = $this->createFormBuilder()
      ->add('Year',
          'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
          'choices' => $this->getYears(1960) , 
          'label' => false,
          'required' => false
      ])


      ->add('Status',EntityType::class,array(
          'class' => Status::class,
          'choice_label' => function ($status) {
           
              return $status->getNom();
           },
           'expanded' => false ,
           'required' => false ,
           'label' => false 

      ))

      ->add('Marque',EntityType::class,array(
          'class' => Fabriquant::class,
          'choice_label' => function ($F) {
           
              return $F->getNom();
           },
           'required' => false ,
           'label' => false 

      ))

      ->add('Modele',EntityType::class,array(
          'class' => Modele::class,
          'choice_label' => function ($M) {
           
              return $M->getNom();
           },
           'required' => false ,
           'label' => false 

      ))


      ->add('Users',EntityType::class,array(
          'class' => Utilisateur::class,
          'choice_label' => function ($users) {
           
              return $users->getnom();
           },
           'required' => false ,
           'label' => false 

      ))

      ->add('Inv',EntityType::class,array(
          'class' => Vehicule::class,
          'choice_label' => function ($vehicules) {
           
              return $vehicules->getNuminventaire();
           },
           'required' => false ,
           'label' => false 

      ))
      ->add('Submit', SubmitType::class)

      ->add('Reset', ResetType::class )

      ->getForm();
      ;

      $form -> handleRequest($request);
      $y =$form->get('Year')->getData() ;
      $Status =$form->get('Status')->getData() ;
      $marque =$form->get('Marque')->getData() ;
      $Modele =$form->get('Modele')->getData() ; 
      $Users =$form->get('Users')->getData();  
      $vehicules = $repository -> findAll();
      $vehicule1 = $repository -> findOneById(1);
      $Inv =$form->get('Inv')->getData();           
        //   dd($vehicule1->getMainPhoto()) ; die () ;
    

     $phe ='' ;
     $condition = '' ;
     if ($marque)
     { $marque_form = ($form->get('Marque')->getData( ))->getnom() ;
      $condition .=  '$v->getmarque()->getnom()  ==   $marque_form '  ; }
      if ($Modele) 
      { $modele_form = ($form->get('Modele')->getData( ))->getnom()  ;
          $condition .=  '&& $v->getmodele()->getnom() == $modele_form ' ; }
          if ($Inv)
          {   $Inv_form = ($form->get('Inv')->getData())->getNuminventaire()  ;
              $condition .=  '&& $v->getNuminventaire() == $Inv_form ' ; }
              if ($Users  )
              { $U_form = $form->get('Users')->getData()->getNomutilisateur();

                  $condition .=  '&& $v->getutilisateur()->getNomutilisateur() == $U_form ' ; }
                  if ($y  )
                  {$Y_form = $form->get('Year')->getData() ;
                      $condition .=  '&& $v->getannee() == $Y_form ' ; } 
                      if ($Status )
                      {$S_form = $form->get('Status')->getData()->getnom();
                          $condition .=  '&& $v->getstatus()->getnom() == $S_form  ' ; }




                          if ( substr($condition, 0,2) == '&&' ) 

                          { $condition[0] = " " ;
                              $condition[1] = " " ;

                          }


                          {$cmd = ' if (' . $condition . ') ';
                              $cmd .= ' {  ' ;
        // $condition .= $phe . ' = true' ;
        $cmd .= '$phe = true ;'   ;
        $cmd .= '  } ' ;
        $cmd .= ' else {  $phe = false ;}   ' ;




        $i =0 ;
        $filterr = $repository -> findAll() ;
        if(!empty($condition))

        {   $filterr = [] ;
          foreach ( $vehicules as $v)
          {
              ++$i ;

              if($condition)
              { eval( $cmd );
                  if($phe == 'true') 
                  { 
                      $filterr[$i] = $v ; }
   
                  }
              }
          }
          else {$filterr = $repository -> findAll() ;}
      }
} 
elseif($userrole[0] == 'ROLE_VENDEUR' )
{

     $form = $this->createFormBuilder()
     ->add('Year',
         'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
         'choices' => $this->getYears(1960) , 
         'label' => false,
         'required' => false
     ])


     ->add('Status',EntityType::class,array(
         'class' => Status::class,
         'choice_label' => function ($status) {
          
             return $status->getNom();
          },
          'expanded' => false ,
          'required' => false ,
          'label' => false 

     ))

     ->add('Marque',EntityType::class,array(
         'class' => Fabriquant::class,
         'choice_label' => function ($F) {
          
             return $F->getNom();
          },
          'required' => false ,
          'label' => false 

     ))

     ->add('Modele',EntityType::class,array(
         'class' => Modele::class,
         'choice_label' => function ($M) {
          
             return $M->getNom();
          },
          'required' => false ,
          'label' => false 

     ))


     ->add('Users',EntityType::class,array(
         'class' => Utilisateur::class,
         'choice_label' => function ($users) {
          
             return $users->getnom();
          },
          'required' => false ,
          'label' => false 

     ))

     ->add('Inv',EntityType::class,array(
         'class' => Vehicule::class,
         'choice_label' => function ($vehicules) {
          
             return $vehicules->getNuminventaire();
          },
          'required' => false ,
          'label' => false 

     ))
     ->add('Submit', SubmitType::class)

     ->add('Reset', ResetType::class )

     ->getForm();
     ;

     $form -> handleRequest($request);
     $y =$form->get('Year')->getData() ;
     $Status =$form->get('Status')->getData() ;
     $marque =$form->get('Marque')->getData() ;
     $Modele =$form->get('Modele')->getData() ; 
     $Users =$form->get('Users')->getData();  
     $vehicules = $vehiculevendeur;
     $vehicule1 = $repository -> findOneById(1);
     $Inv =$form->get('Inv')->getData();           
       //   dd($vehicule1->getMainPhoto()) ; die () ;
   

    $phe ='' ;
    $condition = '' ;
    if ($marque)
    { $marque_form = ($form->get('Marque')->getData( ))->getnom() ;
     $condition .=  '$v->getmarque()->getnom()  ==   $marque_form '  ; }
     if ($Modele) 
     { $modele_form = ($form->get('Modele')->getData( ))->getnom()  ;
         $condition .=  '&& $v->getmodele()->getnom() == $modele_form ' ; }
         if ($Inv)
         {   $Inv_form = ($form->get('Inv')->getData())->getNuminventaire()  ;
             $condition .=  '&& $v->getNuminventaire() == $Inv_form ' ; }
             if ($Users  )
             { $U_form = $form->get('Users')->getData()->getNomutilisateur();

                 $condition .=  '&& $v->getutilisateur()->getNomutilisateur() == $U_form ' ; }
                 if ($y  )
                 {$Y_form = $form->get('Year')->getData() ;
                     $condition .=  '&& $v->getannee() == $Y_form ' ; } 
                     if ($Status )
                     {$S_form = $form->get('Status')->getData()->getnom();
                         $condition .=  '&& $v->getstatus()->getnom() == $S_form  ' ; }




                         if ( substr($condition, 0,2) == '&&' ) 

                         { $condition[0] = " " ;
                             $condition[1] = " " ;

                         }


                         {$cmd = ' if (' . $condition . ') ';
                             $cmd .= ' {  ' ;
       // $condition .= $phe . ' = true' ;
       $cmd .= '$phe = true ;'   ;
       $cmd .= '  } ' ;
       $cmd .= ' else {  $phe = false ;}   ' ;




       $i =0 ;
       $filterr = $vehiculevendeur ;
       if(!empty($condition))

       {   $filterr = [] ;
         foreach ( $vehicules as $v)
         {
             ++$i ;

             if($condition)
             { eval( $cmd );
                 if($phe == 'true') 
                 { 
                     $filterr[$i] = $v ; }
  
                 }
             }
         }
         else {$filterr = $vehiculevendeur ;}
        }
     
} 
 elseif( ($userrole[0] == 'ROLE_CONCESSIONNAIRE') || ($userrole[0] == 'ROLE_PARTENAIRE') || ($userrole[0] == 'ROLE_MARCHAND') )
 {

       $form = $this->createFormBuilder()
       ->add('Year',
           'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
           'choices' => $this->getYears(1960) , 
           'label' => false,
           'required' => false
       ])


       ->add('Status',EntityType::class,array(
           'class' => Status::class,
           'choice_label' => function ($status) {
            
               return $status->getNom();
            },
            'expanded' => false ,
            'required' => false ,
            'label' => false 

       ))

       ->add('Marque',EntityType::class,array(
           'class' => Fabriquant::class,
           'choice_label' => function ($F) {
            
               return $F->getNom();
            },
            'required' => false ,
            'label' => false 

       ))

       ->add('Modele',EntityType::class,array(
           'class' => Modele::class,
           'choice_label' => function ($M) {
            
               return $M->getNom();
            },
            'required' => false ,
            'label' => false 

       ))


       ->add('Users',EntityType::class,array(
           'class' => Utilisateur::class,
           'choice_label' => function ($users) {
            
               return $users->getnom();
            },
            'required' => false ,
            'label' => false 

       ))

       ->add('Inv',EntityType::class,array(
           'class' => Vehicule::class,
           'choice_label' => function ($vehicules) {
            
               return $vehicules->getNuminventaire();
            },
            'required' => false ,
            'label' => false 

       ))
       ->add('Submit', SubmitType::class)

       ->add('Reset', ResetType::class )

       ->getForm();
       ;

       $form -> handleRequest($request);
       $y =$form->get('Year')->getData() ;
       $Status =$form->get('Status')->getData() ;
       $marque =$form->get('Marque')->getData() ;
       $Modele =$form->get('Modele')->getData() ; 
       $Users =$form->get('Users')->getData();  
       $vehicules = $data;
       $vehicule1 = $repository -> findOneById(1);
       $Inv =$form->get('Inv')->getData();           
         //   dd($vehicule1->getMainPhoto()) ; die () ;
     

      $phe ='' ;
      $condition = '' ;
      if ($marque)
      { $marque_form = ($form->get('Marque')->getData( ))->getnom() ;
       $condition .=  '$v->getmarque()->getnom()  ==   $marque_form '  ; }
       if ($Modele) 
       { $modele_form = ($form->get('Modele')->getData( ))->getnom()  ;
           $condition .=  '&& $v->getmodele()->getnom() == $modele_form ' ; }
           if ($Inv)
           {   $Inv_form = ($form->get('Inv')->getData())->getNuminventaire()  ;
               $condition .=  '&& $v->getNuminventaire() == $Inv_form ' ; }
               if ($Users  )
               { $U_form = $form->get('Users')->getData()->getNomutilisateur();

                   $condition .=  '&& $v->getutilisateur()->getNomutilisateur() == $U_form ' ; }
                   if ($y  )
                   {$Y_form = $form->get('Year')->getData() ;
                       $condition .=  '&& $v->getannee() == $Y_form ' ; } 
                       if ($Status )
                       {$S_form = $form->get('Status')->getData()->getnom();
                           $condition .=  '&& $v->getstatus()->getnom() == $S_form  ' ; }




                           if ( substr($condition, 0,2) == '&&' ) 

                           { $condition[0] = " " ;
                               $condition[1] = " " ;

                           }


                           {$cmd = ' if (' . $condition . ') ';
                               $cmd .= ' {  ' ;
         // $condition .= $phe . ' = true' ;
         $cmd .= '$phe = true ;'   ;
         $cmd .= '  } ' ;
         $cmd .= ' else {  $phe = false ;}   ' ;




         $i =0 ;
         $filterr = $data ;
         if(!empty($condition))

         {   $filterr = [] ;
           foreach ( $vehicules as $v)
           {
               ++$i ;

               if($condition)
               { eval( $cmd );
                   if($phe == 'true') 
                   { 
                       $filterr[$i] = $v ; }
    
                   }
               }
           }
           else {$filterr = $data;}
       }
}  


        return $this->render('vehicule/index.html.twig', [
            'form' => $form->createView(),
            'vehicule' => $filterr  ]);   
     }

     #[Route('/liquidation', name: 'liquidation')]
     public function filterliquidation( ModeleRepository $MRep ,Security $security, VehiculeRepository $repository,FabriquantRepository $Frep,  StatusRepository $Rstatus , Request $request ,VendeurrRepository $vendeurr, UtilisateurRepository $Users,ConcessionnaireRepository $consi,AgentRepository $agents,MarchandRepository $marchand,PartenaireRepository $partenaire)
     {
         
         /** @var User $user */
         $user = $security->getUser();
         $userrole = $user->getRoles();
         $userId= $user->getId();
 
         if($userrole[0] == 'ROLE_AGENT' )
         {
             ////////////////véhicules visibles par l'agent////////////////   
             /////////////véhicules de l'agent//////////////////  
             $agentt= $Users->findOneById($userId);
             $voitureagentt=$repository->findListbyCompagnie($agentt);
          
             ///////////////////véhicules du concessionnaire de cet agent/////////////////////////////
             $agent=$agents->findOneByIdutilisateur($userId);
             $consbyagent =$consi->findByAgent($agent);
             $tabcons=[];
             foreach ($consbyagent as $value)
             {
                  $userr=$Users->findOneByConcessionnaire($value);
                  array_push($tabcons,$userr); 
             }
 
             $tabconsveh=[];
             foreach ($tabcons as $val)
             {
                 $voiturconsisionnaire=$repository->findListbyCompagnie($val);
                 array_push($tabconsveh,$voiturconsisionnaire); 
             }
             $vehicons=call_user_func_array('array_merge',$tabconsveh);
             //////////////////////////////////véhicules du partenaire de cet agent//////////////////////////////////////////////////////////
           
             $partbyagent=$partenaire->findByAgent($agent);
             $tabpart=[];
             foreach ($partbyagent as $value)
             {
                    $userpart=$Users->findOneByPartenaire($value);
                  array_push($tabpart,$userpart); 
             }
             
             $tabpartveh=[];
             foreach ($tabpart as $value)
             {
                  $voiturpartenaire=$repository->findListbyCompagnie($value);
                  array_push($tabpartveh,$voiturpartenaire); 
             }
             
             $vehipart=call_user_func_array('array_merge',$tabpartveh);
            ////////////////////////////////////véhicules du marchand de cet agent///////////////////////
             $marchanbyagent=$marchand->findByAgent($agent);
             $tabmarch=[];
             foreach ($marchanbyagent as $value)
             {
                  $userrmarch=$Users->findOneByMarchand($value);
                  array_push($tabmarch,$userrmarch); 
             }
             $tabmarchveh=[];
             foreach ($tabmarch as $value)
             {
                  $voiturmarchand=$repository->findListbyCompagnie($value);
                  array_push($tabmarchveh,$voiturmarchand); 
             }
             $vehimarch=call_user_func_array('array_merge',$tabmarchveh);
             /////////////////////////grouprmrnt de le liste des véhicule visible par l'agent/////////
        
  
             $vehiculeagent = array_merge($voitureagentt,$vehipart,$vehimarch,$vehicons);
            
       
             ////////////////fin/////////////////////////////////////////
         }elseif($userrole[0] == 'ROLE_VENDEUR' )
         {
             
 
                ////////////////véhicules visibles par vendeur////////////////   
             /////////////véhicules de vendeur//////////////////  
             $vendeurrs= $Users->findOneById($userId);
             $voiturevendeurr=$repository->findListbyCompagnie($vendeurrs);
          
             ///////////////////véhicules du concessionnaire de ce vendeur/////////////////////////////
             $vendeur=$vendeurr->findOneByIdutilisateur($userId);
             $consbyvendeur =$consi->findByVendeur($vendeur);
             $tabcons=[];
             foreach ($consbyvendeur as $value)
             {
                  $userr=$Users->findOneByConcessionnaire($value);
                  array_push($tabcons,$userr); 
             }
 
             $tabconsveh=[];
             foreach ($tabcons as $val)
             {
                 $voiturconsisionnaire=$repository->findListbyCompagnie($val);
                 array_push($tabconsveh,$voiturconsisionnaire); 
             }
             $vehicons=call_user_func_array('array_merge',$tabconsveh);
             //////////////////////////////////véhicules du partenaire de ce vendeur//////////////////////////////////////////////////////////
           
             $partbyagent=$partenaire->findByVendeur($vendeur);
             $tabpart=[];
             foreach ($partbyagent as $value)
             {
                    $userpart=$Users->findOneByPartenaire($value);
                  array_push($tabpart,$userpart); 
             }
             
             $tabpartveh=[];
             foreach ($tabpart as $value)
             {
                  $voiturpartenaire=$repository->findListbyCompagnie($value);
                  array_push($tabpartveh,$voiturpartenaire); 
             }
             
             $vehipart=call_user_func_array('array_merge',$tabpartveh);
            ////////////////////////////////////véhicules du marchand de ce vendeur///////////////////////
             $marchanbyagent=$marchand->findByVendeur($vendeur);
             $tabmarch=[];
             foreach ($marchanbyagent as $value)
             {
                  $userrmarch=$Users->findOneByMarchand($value);
                  array_push($tabmarch,$userrmarch); 
             }
             $tabmarchveh=[];
             foreach ($tabmarch as $value)
             {
                  $voiturmarchand=$repository->findListbyCompagnie($value);
                  array_push($tabmarchveh,$voiturmarchand); 
             }
             $vehimarch=call_user_func_array('array_merge',$tabmarchveh);
             /////////////////////////grouprmrnt de le liste des véhicule visible par vendeur/////////
        
  
             $vehiculevendeur = array_merge($voiturevendeurr,$vehipart,$vehimarch,$vehicons);
            
       
             ////////////////fin/////////////////////////////////////////
         }
         elseif(($userrole[0] == 'ROLE_CONCESSIONNAIRE') || ($userrole[0] == 'ROLE_PARTENAIRE') || ($userrole[0] == 'ROLE_MARCHAND'))
         {
 ////////////////véhicules visibles par Concessionnaire et marchand et partenaire////////////////  
       $con= $Users->findOneById($userId);
       $data=$repository->findListbyCompagnie($con);
  ////////////////fin/////////////////////////////////////////   
       }  
 
 
 
 
 
 
 
       if($userrole[0] == 'ROLE_ADMIN' )
   {
 
         $form = $this->createFormBuilder()
         ->add('Year',
             'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
             'choices' => $this->getYears(1960) , 
             'label' => false,
             'required' => false
         ])
 
 
         ->add('Status',EntityType::class,array(
             'class' => Status::class,
             'choice_label' => function ($status) {
              
                 return $status->getNom();
              },
              'expanded' => false ,
              'required' => false ,
              'label' => false 
 
         ))
 
         ->add('Marque',EntityType::class,array(
             'class' => Fabriquant::class,
             'choice_label' => function ($F) {
              
                 return $F->getNom();
              },
              'required' => false ,
              'label' => false 
 
         ))
 
         ->add('Modele',EntityType::class,array(
             'class' => Modele::class,
             'choice_label' => function ($M) {
              
                 return $M->getNom();
              },
              'required' => false ,
              'label' => false 
 
         ))
 
 
         ->add('Users',EntityType::class,array(
             'class' => Utilisateur::class,
             'choice_label' => function ($users) {
              
                 return $users->getnom();
              },
              'required' => false ,
              'label' => false 
 
         ))
 
         ->add('Inv',EntityType::class,array(
             'class' => Vehicule::class,
             'choice_label' => function ($vehicules) {
              
                 return $vehicules->getNuminventaire();
              },
              'required' => false ,
              'label' => false 
 
         ))
         ->add('Submit', SubmitType::class)
 
         ->add('Reset', ResetType::class )
 
         ->getForm();
         ;
 
         $form -> handleRequest($request);
         $y =$form->get('Year')->getData() ;
         $Status =$form->get('Status')->getData() ;
         $marque =$form->get('Marque')->getData() ;
         $Modele =$form->get('Modele')->getData() ; 
         $Users =$form->get('Users')->getData();  
         $vehicules = $repository -> findAll();
         $vehicule1 = $repository -> findOneById(1);
         $Inv =$form->get('Inv')->getData();           
           //   dd($vehicule1->getMainPhoto()) ; die () ;
       
 
        $phe ='' ;
        $condition = '' ;
        if ($marque)
        { $marque_form = ($form->get('Marque')->getData( ))->getnom() ;
         $condition .=  '$v->getmarque()->getnom()  ==   $marque_form '  ; }
         if ($Modele) 
         { $modele_form = ($form->get('Modele')->getData( ))->getnom()  ;
             $condition .=  '&& $v->getmodele()->getnom() == $modele_form ' ; }
             if ($Inv)
             {   $Inv_form = ($form->get('Inv')->getData())->getNuminventaire()  ;
                 $condition .=  '&& $v->getNuminventaire() == $Inv_form ' ; }
                 if ($Users  )
                 { $U_form = $form->get('Users')->getData()->getNomutilisateur();
 
                     $condition .=  '&& $v->getutilisateur()->getNomutilisateur() == $U_form ' ; }
                     if ($y  )
                     {$Y_form = $form->get('Year')->getData() ;
                         $condition .=  '&& $v->getannee() == $Y_form ' ; } 
                         if ($Status )
                         {$S_form = $form->get('Status')->getData()->getnom();
                             $condition .=  '&& $v->getstatus()->getnom() == $S_form  ' ; }
 
 
 
 
                             if ( substr($condition, 0,2) == '&&' ) 
 
                             { $condition[0] = " " ;
                                 $condition[1] = " " ;
 
                             }
 
 
                             {$cmd = ' if (' . $condition . ') ';
                                 $cmd .= ' {  ' ;
           // $condition .= $phe . ' = true' ;
           $cmd .= '$phe = true ;'   ;
           $cmd .= '  } ' ;
           $cmd .= ' else {  $phe = false ;}   ' ;
 
 
 
 
           $i =0 ;
           $filterr = $repository -> findAll() ;
           if(!empty($condition))
 
           {   $filterr = [] ;
             foreach ( $vehicules as $v)
             {
                 ++$i ;
 
                 if($condition)
                 { eval( $cmd );
                     if($phe == 'true') 
                     { 
                         $filterr[$i] = $v ; }
      
                     }
                 }
             }
             else {$filterr = $repository -> findAll() ;}
         }
  } 
  elseif($userrole[0] == 'ROLE_AGENT' )
  {
 
        $form = $this->createFormBuilder()
        ->add('Year',
            'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
            'choices' => $this->getYears(1960) , 
            'label' => false,
            'required' => false
        ])
 
 
        ->add('Status',EntityType::class,array(
            'class' => Status::class,
            'choice_label' => function ($status) {
             
                return $status->getNom();
             },
             'expanded' => false ,
             'required' => false ,
             'label' => false 
 
        ))
 
        ->add('Marque',EntityType::class,array(
            'class' => Fabriquant::class,
            'choice_label' => function ($F) {
             
                return $F->getNom();
             },
             'required' => false ,
             'label' => false 
 
        ))
 
        ->add('Modele',EntityType::class,array(
            'class' => Modele::class,
            'choice_label' => function ($M) {
             
                return $M->getNom();
             },
             'required' => false ,
             'label' => false 
 
        ))
 
 
        ->add('Users',EntityType::class,array(
            'class' => Utilisateur::class,
            'choice_label' => function ($users) {
             
                return $users->getnom();
             },
             'required' => false ,
             'label' => false 
 
        ))
 
        ->add('Inv',EntityType::class,array(
            'class' => Vehicule::class,
            'choice_label' => function ($vehicules) {
             
                return $vehicules->getNuminventaire();
             },
             'required' => false ,
             'label' => false 
 
        ))
        ->add('Submit', SubmitType::class)
 
        ->add('Reset', ResetType::class )
 
        ->getForm();
        ;
 
        $form -> handleRequest($request);
        $y =$form->get('Year')->getData() ;
        $Status =$form->get('Status')->getData() ;
        $marque =$form->get('Marque')->getData() ;
        $Modele =$form->get('Modele')->getData() ; 
        $Users =$form->get('Users')->getData();  
        $vehicules = $repository -> findAll();
        $vehicule1 = $repository -> findOneById(1);
        $Inv =$form->get('Inv')->getData();           
          //   dd($vehicule1->getMainPhoto()) ; die () ;
      
 
       $phe ='' ;
       $condition = '' ;
       if ($marque)
       { $marque_form = ($form->get('Marque')->getData( ))->getnom() ;
        $condition .=  '$v->getmarque()->getnom()  ==   $marque_form '  ; }
        if ($Modele) 
        { $modele_form = ($form->get('Modele')->getData( ))->getnom()  ;
            $condition .=  '&& $v->getmodele()->getnom() == $modele_form ' ; }
            if ($Inv)
            {   $Inv_form = ($form->get('Inv')->getData())->getNuminventaire()  ;
                $condition .=  '&& $v->getNuminventaire() == $Inv_form ' ; }
                if ($Users  )
                { $U_form = $form->get('Users')->getData()->getNomutilisateur();
 
                    $condition .=  '&& $v->getutilisateur()->getNomutilisateur() == $U_form ' ; }
                    if ($y  )
                    {$Y_form = $form->get('Year')->getData() ;
                        $condition .=  '&& $v->getannee() == $Y_form ' ; } 
                        if ($Status )
                        {$S_form = $form->get('Status')->getData()->getnom();
                            $condition .=  '&& $v->getstatus()->getnom() == $S_form  ' ; }
 
 
 
 
                            if ( substr($condition, 0,2) == '&&' ) 
 
                            { $condition[0] = " " ;
                                $condition[1] = " " ;
 
                            }
 
 
                            {$cmd = ' if (' . $condition . ') ';
                                $cmd .= ' {  ' ;
          // $condition .= $phe . ' = true' ;
          $cmd .= '$phe = true ;'   ;
          $cmd .= '  } ' ;
          $cmd .= ' else {  $phe = false ;}   ' ;
 
 
 
 
          $i =0 ;
          $filterr = $vehiculeagent ;
          if(!empty($condition))
 
          {   $filterr = [] ;
            foreach ( $vehicules as $v)
            {
                ++$i ;
 
                if($condition)
                { eval( $cmd );
                    if($phe == 'true') 
                    { 
                        $filterr[$i] = $v ; }
     
                    }
                }
            }
            else {$filterr = $vehiculeagent ;}
        }
 }   if($userrole[0] == 'ROLE_ADMIN' )
 {
 
       $form = $this->createFormBuilder()
       ->add('Year',
           'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
           'choices' => $this->getYears(1960) , 
           'label' => false,
           'required' => false
       ])
 
 
       ->add('Status',EntityType::class,array(
           'class' => Status::class,
           'choice_label' => function ($status) {
            
               return $status->getNom();
            },
            'expanded' => false ,
            'required' => false ,
            'label' => false 
 
       ))
 
       ->add('Marque',EntityType::class,array(
           'class' => Fabriquant::class,
           'choice_label' => function ($F) {
            
               return $F->getNom();
            },
            'required' => false ,
            'label' => false 
 
       ))
 
       ->add('Modele',EntityType::class,array(
           'class' => Modele::class,
           'choice_label' => function ($M) {
            
               return $M->getNom();
            },
            'required' => false ,
            'label' => false 
 
       ))
 
 
       ->add('Users',EntityType::class,array(
           'class' => Utilisateur::class,
           'choice_label' => function ($users) {
            
               return $users->getnom();
            },
            'required' => false ,
            'label' => false 
 
       ))
 
       ->add('Inv',EntityType::class,array(
           'class' => Vehicule::class,
           'choice_label' => function ($vehicules) {
            
               return $vehicules->getNuminventaire();
            },
            'required' => false ,
            'label' => false 
 
       ))
       ->add('Submit', SubmitType::class)
 
       ->add('Reset', ResetType::class )
 
       ->getForm();
       ;
 
       $form -> handleRequest($request);
       $y =$form->get('Year')->getData() ;
       $Status =$form->get('Status')->getData() ;
       $marque =$form->get('Marque')->getData() ;
       $Modele =$form->get('Modele')->getData() ; 
       $Users =$form->get('Users')->getData();  
       $vehicules = $repository -> findAll();
       $vehicule1 = $repository -> findOneById(1);
       $Inv =$form->get('Inv')->getData();           
         //   dd($vehicule1->getMainPhoto()) ; die () ;
     
 
      $phe ='' ;
      $condition = '' ;
      if ($marque)
      { $marque_form = ($form->get('Marque')->getData( ))->getnom() ;
       $condition .=  '$v->getmarque()->getnom()  ==   $marque_form '  ; }
       if ($Modele) 
       { $modele_form = ($form->get('Modele')->getData( ))->getnom()  ;
           $condition .=  '&& $v->getmodele()->getnom() == $modele_form ' ; }
           if ($Inv)
           {   $Inv_form = ($form->get('Inv')->getData())->getNuminventaire()  ;
               $condition .=  '&& $v->getNuminventaire() == $Inv_form ' ; }
               if ($Users  )
               { $U_form = $form->get('Users')->getData()->getNomutilisateur();
 
                   $condition .=  '&& $v->getutilisateur()->getNomutilisateur() == $U_form ' ; }
                   if ($y  )
                   {$Y_form = $form->get('Year')->getData() ;
                       $condition .=  '&& $v->getannee() == $Y_form ' ; } 
                       if ($Status )
                       {$S_form = $form->get('Status')->getData()->getnom();
                           $condition .=  '&& $v->getstatus()->getnom() == $S_form  ' ; }
 
 
 
 
                           if ( substr($condition, 0,2) == '&&' ) 
 
                           { $condition[0] = " " ;
                               $condition[1] = " " ;
 
                           }
 
 
                           {$cmd = ' if (' . $condition . ') ';
                               $cmd .= ' {  ' ;
         // $condition .= $phe . ' = true' ;
         $cmd .= '$phe = true ;'   ;
         $cmd .= '  } ' ;
         $cmd .= ' else {  $phe = false ;}   ' ;
 
 
 
 
         $i =0 ;
         $filterr = $repository -> findAll() ;
         if(!empty($condition))
 
         {   $filterr = [] ;
           foreach ( $vehicules as $v)
           {
               ++$i ;
 
               if($condition)
               { eval( $cmd );
                   if($phe == 'true') 
                   { 
                       $filterr[$i] = $v ; }
    
                   }
               }
           }
           else {$filterr = $repository -> findAll() ;}
       }
 } 
 elseif($userrole[0] == 'ROLE_VENDEUR' )
 {
 
      $form = $this->createFormBuilder()
      ->add('Year',
          'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
          'choices' => $this->getYears(1960) , 
          'label' => false,
          'required' => false
      ])
 
 
      ->add('Status',EntityType::class,array(
          'class' => Status::class,
          'choice_label' => function ($status) {
           
              return $status->getNom();
           },
           'expanded' => false ,
           'required' => false ,
           'label' => false 
 
      ))
 
      ->add('Marque',EntityType::class,array(
          'class' => Fabriquant::class,
          'choice_label' => function ($F) {
           
              return $F->getNom();
           },
           'required' => false ,
           'label' => false 
 
      ))
 
      ->add('Modele',EntityType::class,array(
          'class' => Modele::class,
          'choice_label' => function ($M) {
           
              return $M->getNom();
           },
           'required' => false ,
           'label' => false 
 
      ))
 
 
      ->add('Users',EntityType::class,array(
          'class' => Utilisateur::class,
          'choice_label' => function ($users) {
           
              return $users->getnom();
           },
           'required' => false ,
           'label' => false 
 
      ))
 
      ->add('Inv',EntityType::class,array(
          'class' => Vehicule::class,
          'choice_label' => function ($vehicules) {
           
              return $vehicules->getNuminventaire();
           },
           'required' => false ,
           'label' => false 
 
      ))
      ->add('Submit', SubmitType::class)
 
      ->add('Reset', ResetType::class )
 
      ->getForm();
      ;
 
      $form -> handleRequest($request);
      $y =$form->get('Year')->getData() ;
      $Status =$form->get('Status')->getData() ;
      $marque =$form->get('Marque')->getData() ;
      $Modele =$form->get('Modele')->getData() ; 
      $Users =$form->get('Users')->getData();  
      $vehicules = $vehiculevendeur;
      $vehicule1 = $repository -> findOneById(1);
      $Inv =$form->get('Inv')->getData();           
        //   dd($vehicule1->getMainPhoto()) ; die () ;
    
 
     $phe ='' ;
     $condition = '' ;
     if ($marque)
     { $marque_form = ($form->get('Marque')->getData( ))->getnom() ;
      $condition .=  '$v->getmarque()->getnom()  ==   $marque_form '  ; }
      if ($Modele) 
      { $modele_form = ($form->get('Modele')->getData( ))->getnom()  ;
          $condition .=  '&& $v->getmodele()->getnom() == $modele_form ' ; }
          if ($Inv)
          {   $Inv_form = ($form->get('Inv')->getData())->getNuminventaire()  ;
              $condition .=  '&& $v->getNuminventaire() == $Inv_form ' ; }
              if ($Users  )
              { $U_form = $form->get('Users')->getData()->getNomutilisateur();
 
                  $condition .=  '&& $v->getutilisateur()->getNomutilisateur() == $U_form ' ; }
                  if ($y  )
                  {$Y_form = $form->get('Year')->getData() ;
                      $condition .=  '&& $v->getannee() == $Y_form ' ; } 
                      if ($Status )
                      {$S_form = $form->get('Status')->getData()->getnom();
                          $condition .=  '&& $v->getstatus()->getnom() == $S_form  ' ; }
 
 
 
 
                          if ( substr($condition, 0,2) == '&&' ) 
 
                          { $condition[0] = " " ;
                              $condition[1] = " " ;
 
                          }
 
 
                          {$cmd = ' if (' . $condition . ') ';
                              $cmd .= ' {  ' ;
        // $condition .= $phe . ' = true' ;
        $cmd .= '$phe = true ;'   ;
        $cmd .= '  } ' ;
        $cmd .= ' else {  $phe = false ;}   ' ;
 
 
 
 
        $i =0 ;
        $filterr = $vehiculevendeur ;
        if(!empty($condition))
 
        {   $filterr = [] ;
          foreach ( $vehicules as $v)
          {
              ++$i ;
 
              if($condition)
              { eval( $cmd );
                  if($phe == 'true') 
                  { 
                      $filterr[$i] = $v ; }
   
                  }
              }
          }
          else {$filterr = $vehiculevendeur ;}
         }
      
 } 
  elseif( ($userrole[0] == 'ROLE_CONCESSIONNAIRE') || ($userrole[0] == 'ROLE_PARTENAIRE') || ($userrole[0] == 'ROLE_MARCHAND') )
  {
 
        $form = $this->createFormBuilder()
        ->add('Year',
            'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
            'choices' => $this->getYears(1960) , 
            'label' => false,
            'required' => false
        ])
 
 
        ->add('Status',EntityType::class,array(
            'class' => Status::class,
            'choice_label' => function ($status) {
             
                return $status->getNom();
             },
             'expanded' => false ,
             'required' => false ,
             'label' => false 
 
        ))
 
        ->add('Marque',EntityType::class,array(
            'class' => Fabriquant::class,
            'choice_label' => function ($F) {
             
                return $F->getNom();
             },
             'required' => false ,
             'label' => false 
 
        ))
 
        ->add('Modele',EntityType::class,array(
            'class' => Modele::class,
            'choice_label' => function ($M) {
             
                return $M->getNom();
             },
             'required' => false ,
             'label' => false 
 
        ))
 
 
        ->add('Users',EntityType::class,array(
            'class' => Utilisateur::class,
            'choice_label' => function ($users) {
             
                return $users->getnom();
             },
             'required' => false ,
             'label' => false 
 
        ))
 
        ->add('Inv',EntityType::class,array(
            'class' => Vehicule::class,
            'choice_label' => function ($vehicules) {
             
                return $vehicules->getNuminventaire();
             },
             'required' => false ,
             'label' => false 
 
        ))
        ->add('Submit', SubmitType::class)
 
        ->add('Reset', ResetType::class )
 
        ->getForm();
        ;
 
        $form -> handleRequest($request);
        $y =$form->get('Year')->getData() ;
        $Status =$form->get('Status')->getData() ;
        $marque =$form->get('Marque')->getData() ;
        $Modele =$form->get('Modele')->getData() ; 
        $Users =$form->get('Users')->getData();  
        $vehicules = $data;
        $vehicule1 = $repository -> findOneById(1);
        $Inv =$form->get('Inv')->getData();           
          //   dd($vehicule1->getMainPhoto()) ; die () ;
      
 
       $phe ='' ;
       $condition = '' ;
       if ($marque)
       { $marque_form = ($form->get('Marque')->getData( ))->getnom() ;
        $condition .=  '$v->getmarque()->getnom()  ==   $marque_form '  ; }
        if ($Modele) 
        { $modele_form = ($form->get('Modele')->getData( ))->getnom()  ;
            $condition .=  '&& $v->getmodele()->getnom() == $modele_form ' ; }
            if ($Inv)
            {   $Inv_form = ($form->get('Inv')->getData())->getNuminventaire()  ;
                $condition .=  '&& $v->getNuminventaire() == $Inv_form ' ; }
                if ($Users  )
                { $U_form = $form->get('Users')->getData()->getNomutilisateur();
 
                    $condition .=  '&& $v->getutilisateur()->getNomutilisateur() == $U_form ' ; }
                    if ($y  )
                    {$Y_form = $form->get('Year')->getData() ;
                        $condition .=  '&& $v->getannee() == $Y_form ' ; } 
                        if ($Status )
                        {$S_form = $form->get('Status')->getData()->getnom();
                            $condition .=  '&& $v->getstatus()->getnom() == $S_form  ' ; }
 
 
 
 
                            if ( substr($condition, 0,2) == '&&' ) 
 
                            { $condition[0] = " " ;
                                $condition[1] = " " ;
 
                            }
 
 
                            {$cmd = ' if (' . $condition . ') ';
                                $cmd .= ' {  ' ;
          // $condition .= $phe . ' = true' ;
          $cmd .= '$phe = true ;'   ;
          $cmd .= '  } ' ;
          $cmd .= ' else {  $phe = false ;}   ' ;
 
 
 
 
          $i =0 ;
          $filterr = $data ;
          if(!empty($condition))
 
          {   $filterr = [] ;
            foreach ( $vehicules as $v)
            {
                ++$i ;
 
                if($condition)
                { eval( $cmd );
                    if($phe == 'true') 
                    { 
                        $filterr[$i] = $v ; }
     
                    }
                }
            }
            else {$filterr = $data;}
        }
 }  
 
 
         return $this->render('vehicule/index_liquidation.html.twig', [
             'form' => $form->createView(),
             'vehicule' => $filterr  ]);   
      }
 

   







    #[Route('/edit-vehicule/{id}', name: 'edit-vehicule', methods:'GET|POST')]
    #[Route('/add-vehicule', name: 'add_vehicule')]
    public function ajouter(Vehicule $vehicules = null,TypemediaRepository $repository,Request $request)
    {

       if(!$vehicules){
            $vehicules = new Vehicule(); }
            $om = $this->om;
            $form = $this->createForm(VehiculeType::class,$vehicules);
            $form -> handleRequest($request);
               // dd($request->files->get('galerie')); die;
            if($form->isSubmitted()&& $form->isValid()){
                $galerie =$form->getData()->getGalerie();
                    // Should be array of "UploadedFile" objects
                    $files = $request->files;
                    if($files)
                    {   // Iterating over the array
                        // "file" should be an instance of UploadedFile
                        foreach( $files as $file)
                        {
                                        $galerie = $file['galerie'];
                                        $file_count = count($galerie);
                                            for ($i=0; $i<$file_count; $i++) {


                                                $photogalerienom[$i] = $galerie[$i]->getClientOriginalName();

                                                //Déplacer le fichier
                                                $photogalerielien[$i] = '/media/galerie/'.$photogalerienom[$i];
                                                $galerie[$i]->move('../public/media/galerie', $photogalerienom[$i]);
                                                $vehiculegalerie = new GalerieVehicule();
                                                $vehiculegalerie->setNom($photogalerienom[$i]);
                                                $vehiculegalerie->setLien($photogalerielien[$i]);
                                                $vehicules->addGalerie($vehiculegalerie);
                                            }
                            
                        }  

                    }
                   //$photogalerie ->setNom($photogalerienom);
                   //$photogalerie ->setLien($photogalerielien);
                //Récupère l'image

              $media = $form->getData()->getMedia();
              if ($media){ 
                //Récupère le fichier image
                $mediafile = $form->getData()->getMedia()->getImageFile();
                if ($mediafile){ 
                
                $name = $mediafile->getClientOriginalName();
                //Ajouter le nom
                //Déplacer le fichier
                $lien = '/media/logos/'.$name;
                $mediafile->move('../public/media/logos', $name);

                //Définit les valeurs
                $media->setNom($name);
                $media->setLien($lien);

                //Ajoute le type du média
                /* $type = 'photo';*/
                //$type = $repository->gettype('photo');
               // $media->setType($type);

            }
        }
        $modif = $vehicules->getId() !== null;
        if($modif)
        {
            $this->addFlash("success", "Le véhicule  est modifié avec succès ");
        }else{
            $this->addFlash("success",'L\'ajout d\'un nouveau véhicule a été effectuée avec succès');
        }
      
        
            $this->om->persist($vehicules);
           
           
            $om->flush();
            return $this->redirectToRoute("vehicule");
                
        
                 
             
        }

        
      
        return $this->render('vehicule/ajouter-modif.html.twig', [
            
            'vehicules' => $vehicules,
             'form' => $form->createView(),
             'isModification' => $vehicules->getId() !== null
       
            
        ]);   
    }     
            

    #[Route('/delete-vehicule/{id}', name: 'delete-vehicule')]
    public function delete (Vehicule $vehicules, Request $request,ObjectManager $objectManager)
    {
     
            //dd($vehicules->getGalerie());die;
            $objectManager->remove($vehicules);
            $objectManager->flush();
            return $this->redirectToRoute("vehicule");
  

    }
  

   
     

         
    #[Route('/image/{id}', name:'galerie_delete_image')]
     
    public function deleteImage(GalerieVehicule $galerie, Request $request,ObjectManager $objectManager){


       $referer= $request->headers->get('referer');

            $objectManager->remove($galerie);
            $objectManager->flush();

        return $this->redirect($referer);
       
    }    





    #[Route('/conslt-vehicule/{id}', name: 'consultation_vehicule', methods:'GET|POST')]
     public function consultation( Vehicule  $vehicules): Response
 {
     
      $vehiculeRepository = $this->vehiculeRepository;
      
      $vehicules = $vehiculeRepository ->findOneById ($vehicules->getId());
     
      return $this->render('vehicule/consultation.html.twig', [
      'vehicules' => $vehicules
      
     ]);
 
 }
 
 #[Route('/options-vehicule/{id}', name: 'options_vehicule', methods:'GET|POST')]
 public function consultationoptions( Vehicule  $vehicules): Response
{
 
  $vehiculeRepository = $this->vehiculeRepository;
  
  $vehicules = $vehiculeRepository ->findOneById ($vehicules->getId());
 
 return $this->render('vehicule/options-vehicule.html.twig', [
    'vehicules' => $vehicules
    
   ]);
}




private function getYears($min, $max='current')
{
     $years = range($min, ($max === 'current' ? date('Y') : $max));

     return array_combine($years, $years);
}

}    



  
    
