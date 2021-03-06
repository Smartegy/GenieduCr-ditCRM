<?php

namespace App\Controller;

use App\Entity\Concessionnaire;
use App\Entity\Concessionnairemarchand;
use App\Entity\Medias;
use App\Entity\Utilisateur;
use App\Form\ConcessionnairemarchandType;
use App\Form\ConcessionnaireType;
use App\Form\EditConcessionnairemarchandType;
use App\Form\EditConcessionnaireType;
use App\Form\SecConcessionnaireMarchandType;
use App\Form\SecConcessionnaireType;
use App\Form\SecUtilisateurType;
use App\Repository\AgentRepository;
use App\Repository\ConcessionnaireRepository;
use App\Repository\ConcessionnairemarchandRepository;
use App\Repository\FabriquantRepository;
use App\Repository\MediasRepository;
use App\Repository\TypemediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;

/**
* @IsGranted("IS_AUTHENTICATED_FULLY")
*/
class ConcessionnaireController extends AbstractController
{
    private MediasRepository $MR;
    private FabriquantRepository $fabriquantRepository;
    private EntityManagerInterface $objectManager;
    

    public function __construct(MediasRepository $MR,
     FabriquantRepository $fabriquantRepository, 
     AgentRepository $agentRepository,
     ConcessionnaireRepository $concessionnaireRepository,

     ObjectManager $om
     )
    {
        $this->MR = $MR;
        $this->om = $om;
        //ici on instancie le repo
        $this->fabriquantRepository = $fabriquantRepository;
        $this->AgentRepository = $agentRepository;
        $this->ConcessionnaireRepository = $concessionnaireRepository;

        
    }


    #[Route('/concessionnaire', name: 'concessionnaire')]
    public function index(ConcessionnaireRepository $repository,Security $security): Response
    {
          /** @var User $user */
          $user = $security->getUser();
          $userrole = $user->getRoles();
        //  $usernom= $user->getNomutilisateur();
          $usernom= $user->getId();
         // dd($userrole[0]);
         
  
        
        
         
       if($userrole[0] == 'ROLE_ADMIN' )
       {
          $concessionnaires = $repository -> findAll();
         // dd($concessionnaires);die;

       }
       if($userrole[0] == 'ROLE_CONCESSIONNAIRE' )
       {
          $concessionnaires = $repository -> findIdByUtilisateur($usernom);
         
         
       }
       
       
        return $this->render('concessionnaire/index.html.twig', [
            'concessionnaires' => $concessionnaires
        ]);
    }


    #[Route('/concessionnaire/{id}', name: 'suppression_concessionnaire')]
    public function suppression(Concessionnaire $concessionnaires, Request $request){

       $om=$this->om;
       // if($this->isCsrfTokenValid("SUP". $concessionnaires->getId(),$request->get('_token'))){
            $om->remove($concessionnaires);
            $om->flush();
            return $this->redirectToRoute("concessionnaire");
        
      //  }
   
 
    }


    #[Route('/concessionnaire_modification/{id}', name: 'modification_concessionnaire', methods:'GET|POST')]
    public function ajout_modification( Concessionnaire $concessionnaires = null, TypemediaRepository $repository, Request $request)
    {

     
        if(!$concessionnaires){

            $concessionnaires = new Concessionnaire();
            
        }
        $om=$this->om;

       


        $medias = new Medias();

       

       // $cr = $this->MR->findAll();
        //$medias->Concessionnairemarchand::class->getfabriquants()->setMedia($cr);

        //Ici on va creeer un tableau pour les liens des logos des fabricants
        $lienLogo = [];

        //On recupere tous les Fabs depuis le repo instanci?? dans le __construct()
        $fabs = $this->fabriquantRepository->findAll();

        //On cr??e une boucle sur tous les fabs
        foreach($fabs as $fab){
            //Pour chaque fab on recupere l'id et le liens du logo
            //Puis on l'enregistre dans le tableau
            //L'id ce met en KEY et le lien en VALUE

            if($fab->getMedia())
            { $lienLogo[$fab->getId()] = $fab->getMedia()->getLien();}
            else 
            { 
               $lienLogo[$fab->getId()] = "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png" ;
             }
        }

        
        $form = $this->createForm(EditConcessionnaireType::class, $concessionnaires)->remove("password");
       
        
         

        //On recupere le concessmarchand
       // $form->get('concessionnairemarchand')->get('utilisateur')->get('roles')->setData(["ROLE_CONCESSIONNAIRE"]);
        //if($concessvalue != null){
        //On recupere les vendeurs li??s au concessionnaire
      //    $vdrs = $this->AgentRepository->fillVendeursbyConcessionnairemarchand($concessvalue->getId());

       
        //On ajoute les valeurs selected dans la select list Vendeurs
      //  $form->get('concessionnairemarchand')->get('vendeurs')->setData($vdrs);
        
       // }
     

        
        $form -> handleRequest($request);

       
        //$user= new Utilisateur();

      
        if($form->isSubmitted()){

        //    $vendeurs =$form->get('concessionnairemarchand')->get('vendeurs')->getData();
           
            //Ajoute la liste des vendeurs (unmapped)
         //   foreach ($vendeurs as $vendeur){
              //  $concessionnaires->getConcessionnairemarchand()->addAgent($vendeur);
           // }
            
         
            


            //R??cup??re l'image
            $media = $form->getData()->getConcessionnairemarchand()->getMedia();
           
           
            //R??cup??re le fichier image
            $mediafile = $form->getData()->getConcessionnairemarchand()->getMedia()->getImageFile();
            //Ajouter le nom
            if ($mediafile) {
            $name = $mediafile->getClientOriginalName();
            //D??placer le fichier
            $lien = '/media/logos/'.$name;
            $mediafile->move('../public/media/logos', $name);
            
            //D??finit les valeurs
            $media->setNom($name);
            $media->setLien($lien);
            }
            //Ajoute le type du m??dia
           
           // $type = $repository->gettype('photo');
           //dd($form->getData());die;
           $modif = $concessionnaires->getId() !== null;
           $this->om->persist($concessionnaires);
            $om->flush();
            $this->addFlash("success", "Cet Utilisateur est modifi?? avec succ??s");
            return $this->redirectToRoute("concessionnaire");
          
        }
          
        
        return $this->render('concessionnaire/modificationConcessionnaire.html.twig', [
            'concessionnaire' => $concessionnaires,
            'medias'=>$medias,
            'form' => $form->createView(),
            'isModification' => $concessionnaires->getId() !== null,
            // On passe le tableau cree plus haut en param
            'listeLogo'=>$lienLogo

           
        ]);
    }

    #[Route('/add-concessionnaire', name: 'add_concessionnaire')]
    public function add_concessionnaire(Concessionnaire $concessionnaires = null, TypemediaRepository $repository, UserPasswordHasherInterface $userPasswordHasher, ObjectManager $objectManager, Request $request)
    {

        if(!$concessionnaires){

            $concessionnaires = new Concessionnaire();

        }
        $om=$this->om;


        //$medias = new Medias();



    
        //$medias->Concessionnairemarchand::class->getfabriquants()->setMedia($cr);

        //Ici on va creeer un tableau pour les liens des logos des fabricants
        $lienLogo = [];

        //On recupere tous les Fabs depuis le repo instanci?? dans le __construct()
        $fabs = $this->fabriquantRepository->findAll();

        //On cr??e une boucle sur tous les fabs
        foreach($fabs as $fab){
            //Pour chaque fab on recupere l'id et le liens du logo
            //Puis on l'enregistre dans le tableau
            //L'id ce met en KEY et le lien en VALUE
            if($fab->getMedia())
            { $lienLogo[$fab->getId()] = $fab->getMedia()->getLien();}
            else 
            { 
               $lienLogo[$fab->getId()] = "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png" ;
             }
        }


        $form = $this->createForm(ConcessionnaireType::class, $concessionnaires);


        $form->get('concessionnairemarchand')->get('utilisateur')->get('roles')->setData(["ROLE_CONCESSIONNAIRE"]);

        $form -> handleRequest($request);


        $user= new Utilisateur();


        if($form->isSubmitted()  ){

 
             $concessionnaires->getConcessionnairemarchand()->getUtilisateur()->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('concessionnairemarchand')->get('utilisateur')->get('password')->getData()
                    )
                );
           // }

        
            
           $media = $form->getData()->getConcessionnairemarchand()->getMedia();
          
      
     
            if ($media) {
                //R??cup??re le fichier image
                $mediafile = $form->getData()->getConcessionnairemarchand()->getMedia()->getImageFile();
      
                //Ajouter le nom
                $name = $mediafile->getClientOriginalName();
               
                //D??placer le fichier
                $lien = '/media/logos/'.$name;
               
                $mediafile->move('../public/media/logos', $name);
               
                //D??finit les valeurs
                $media->setNom($name);
                
                $media->setLien($lien);
               // dd($media->setLien($lien));die;

  
            }
                 
                  
            $this->addFlash('success', 'L\'ajout a ??t?? effectu??e avec succe??s');
           // dd($concessionnaires) ;die;
            $this->om->persist($concessionnaires);
           
            $om->flush();
       
            return $this->redirectToRoute("concessionnaire");
        }

        return $this->render('concessionnaire/ajoutConcessionnaire.html.twig', [
            'concessionnaire' => $concessionnaires,
            //'medias'=>$medias,
            'form' => $form->createView(),
            'isModification' => $concessionnaires->getId() !== null,
            // On passe le tableau cree plus haut en param
            'listeLogo'=>$lienLogo


        ]);
    }

    #[Route('/consulter_concessionnaire/{id}', name: 'consultation_concessionnaire', methods:'GET|POST')]
    public function consultation(Concessionnaire $concessionnaire): Response
    {
        $concessionnaire = $this->ConcessionnaireRepository->findOneById($concessionnaire->getId());
       
        //$concessionnairemarchand = $concessionnaire->getConcessionnairemarchand();
       
        
      //  $agents = $this->AgentRepository-> fillAgentsbyConcessionnairemarchand($concessionnairemarchand->getId());
        
      //  $vendeurs = $this->AgentRepository-> fillVendeursbyConcessionnairemarchand($concessionnairemarchand->getId());
        
         
         // dd($vendeurs);
        
        return $this->render('concessionnaire/consultation.html.twig', [
            'concessionnaire' => $concessionnaire,
       

        ]);
    }

    #[Route('/security-concessionnaire/{id}', name: 'security_concessionnaire', methods:'GET|POST')]
    public function secure(UserPasswordHasherInterface $userPasswordHasher, ObjectManager $objectManager, Request $request, $id)
    {


        $concess = $this->ConcessionnaireRepository->findOneById($id);

        $user= $concess->getConcessionnairemarchand()->getUtilisateur();
        $form = $this->createForm(SecUtilisateurType::class,$user);
        $form -> handleRequest($request);





        

        if($form->isSubmitted() && $form->isValid()){


                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

          
                $this->addFlash('success', 'le mot de passe a ??t?? chang?? avec succ??s');
            $objectManager->persist($user);
            $objectManager->flush();

            return $this->redirectToRoute("concessionnaire");


        }



        return $this->render('concessionnaire/securityConcessionnaire.html.twig', [
            'utilisateur' => $user,
            'concessessionnaire' => $concess,
            'form' => $form->createView()


        ]);

    }
}
 
 