<?php

namespace App\Controller;

use App\Entity\Marchand;
use App\Entity\Medias;
use App\Entity\Utilisateur;
use App\Form\MarchandType;
use App\Form\EditMarchandType;
use App\Form\SecMarchandType;
use App\Repository\AgentRepository;
use App\Form\SecUtilisateurType;
use App\Repository\MarchandRepository;
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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;

/**
* @IsGranted("IS_AUTHENTICATED_FULLY")
*/
class MarchandController extends AbstractController
{
    private MediasRepository $MR;
    private FabriquantRepository $fabriquantRepository;
    private EntityManagerInterface $objectManager;
    

    public function __construct(MediasRepository $MR,
     FabriquantRepository $fabriquantRepository, 
     AgentRepository $agentRepository,
     MarchandRepository $marchandRepository,
     ConcessionnairemarchandRepository $concessionnairemarchandRepository,
     ObjectManager $om
     )
    {
        $this->MR = $MR;
        $this->om = $om;
        //ici on instancie le repo
        $this->fabriquantRepository = $fabriquantRepository;
        $this->AgentRepository = $agentRepository;
        $this->marchandRepository = $marchandRepository;
        $this->concessionnairemarchandRepository = $concessionnairemarchandRepository;
        
    }

    #[Route('/marchand', name: 'marchand')]
    public function index(MarchandRepository $repository,Security $security): Response
    {
          /** @var User $user */
          $user = $security->getUser();
          $userrole = $user->getRoles();
        //  $usernom= $user->getNomutilisateur();
          $usernom= $user->getId();
         
            
          if($userrole[0] == 'ROLE_ADMIN' )
          {
             $marchands = $repository -> findAll();
            // dd($concessionnaires);die;
   
          }
          if($userrole[0] == 'ROLE_MARCHAND' )
          {
             $marchands = $repository -> findIdByUtilisateur($usernom);
            
            
          }
       
        return $this->render('marchand/index.html.twig', [
            'marchands' => $marchands
        ]);
    }


    #[Route('/marchand/{id}', name: 'suppression_marchand')]
    public function suppression(Marchand $marchands, Request $request){

       $om=$this->om;
      //  if($this->isCsrfTokenValid("SUP". $marchands->getId(),$request->get('_token'))){
       
            $om->remove($marchands);
            $om->flush();
            return $this->redirectToRoute("marchand");
        
      //  }
 
    }


    #[Route('/marchand-modif/{id}', name: 'modification_marchand', methods:'GET|POST')]
    public function modification(Marchand $marchand = null, TypemediaRepository $repository, Request $request)
    {
        if(!$marchand){
            $marchand = new Marchand();
        }
        $om=$this->om;
        $medias = new Medias();
        $cr = $this->MR->findAll();
        $lienLogo = [];
        //On recupere tous les Fabs depuis le repo instancié dans le __construct()
        $fabs = $this->fabriquantRepository->findAll();
        //On crée une boucle sur tous les fabs
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
        $form = $this->createForm(EditMarchandType::class, $marchand)->remove("password");


        //On recupere le concessmarchand
        $concessvalue = $form->get('concessionnairemarchand')->getData();
    
        $form -> handleRequest($request);


        if($form->isSubmitted()){

            
            //Récupère l'image
            $media = $form->getData()->getConcessionnairemarchand()->getMedia();
            //Récupère le fichier image
            $mediafile = $form->getData()->getConcessionnairemarchand()->getMedia()->getImageFile();
            if ( $mediafile) {
            //Ajouter le nom
            $name = $mediafile->getClientOriginalName();
            //Déplacer le fichier
            $lien = '/media/logos/'.$name;
            $mediafile->move('../public/media/logos', $name);
            
            //Définit les valeurs
            $media->setNom($name);
            $media->setLien($lien);
            }
            //Ajoute le type du média
           
           // $type = $repository->gettype('photo');
           
           // $media->setType($type);
         
           $this->om->persist($marchand);
            $om->flush();
            $this->addFlash("success", "Cet Utilisateur est modifié avec succeés");
            return $this->redirectToRoute("marchand");
        }
        
        return $this->render('marchand/modificationmarchand.html.twig', [
            'marchand' => $marchand,
            'medias'=>$medias,
            'form' => $form->createView(),
            'isModification' => $marchand->getId() !== null,
            'listeLogo'=>$lienLogo // On passe le tableau créé plus haut en param
           
        ]);
    }

    #[Route('/createmarchand', name: 'creation_marchand')]
    public function ajout_modification(Marchand $marchand = null, TypemediaRepository $repository, UserPasswordHasherInterface $userPasswordHasher, Request $request)
    {

       //  dd("hello");die;

        if(!$marchand){
            $marchand = new Marchand();
        }
        $om=$this->om;
        $medias = new Medias();
        $cr = $this->MR->findAll();
        //$medias->Concessionnairemarchand::class->getfabriquants()->setMedia($cr);
        //Ici on va creeer un tableau pour les liens des logos des fabricants
        $lienLogo = [];
        //On recupere tous les Fabs depuis le repo instancié dans le __construct()
        $fabs = $this->fabriquantRepository->findAll();
        //On crée une boucle sur tous les fabs
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
        $form = $this->createForm(MarchandType::class, $marchand);
        //On recupere le concessmarchand
        $concessvalue = $form->get('concessionnairemarchand')->getData();
 
        $form->get('concessionnairemarchand')->get('utilisateur')->get('roles')->setData(["ROLE_MARCHAND"]);
        $form -> handleRequest($request);
        $user= new Utilisateur();
        if($form->isSubmitted() && $form->isValid()){

            // encode the plain password
            $marchand->getConcessionnairemarchand()->getUtilisateur()->setPassword(
                $userPasswordHasher->hashPassword($user,
                    $form->get('concessionnairemarchand')->get('utilisateur')->get('password')->getData()
                )
            );

           // $vendeurs =$form->get('concessionnairemarchand')->get('vendeurs')->getData();

            //Ajoute la liste des vendeurs (unmapped)
 

            //Récupère l'image
            $media = $form->getData()->getConcessionnairemarchand()->getMedia();
            if ($media) {
                //Récupère le fichier image
                $mediafile = $form->getData()->getConcessionnairemarchand()->getMedia()->getImageFile();
                //Ajouter le nom
                $name = $mediafile->getClientOriginalName();
                //Déplacer le fichier
                $lien = '/media/logos/'.$name;
                $mediafile->move('../public/media/logos', $name);

                //Définit les valeurs
                $media->setNom($name);
                $media->setLien($lien);
            }
            //Ajoute le type du média

           // $type = $repository->gettype('photo');

            //$media->setType($type);
            $this->addFlash('success', 'L\'ajout a été effectuée avec succeès');
            $this->om->persist($marchand);
            $om->flush();
            return $this->redirectToRoute("marchand");
        }



        return $this->render('marchand/ajoutmarchand.html.twig', [
            'marchands' => $marchand,
            'medias'=>$medias,
            'form' => $form->createView(),
            'isModification' => $marchand->getId() !== null,
            'listeLogo'=>$lienLogo // On passe le tableau créé plus haut en param

        ]);
    }

    #[Route('/consulter-marchand/{id}', name: 'consultation_marchand', methods:'GET|POST')]
    public function consultation(Marchand $marchand, MarchandRepository $marchandRepository ): Response
    {
//On récupère le marchand
        $marchand = $marchandRepository ->findOneById($marchand->getId());
        //On récupère le concessionnairemarchand
   



        
        //var_dump($marchand);die();

        return $this->render('marchand/consultation.html.twig', [
            'marchand' => $marchand,
       
        ]);
    }

    #[Route('/security-marchand/{id}', name: 'security_marchand', methods:'GET|POST')]
    public function secure(UserPasswordHasherInterface $userPasswordHasher, ObjectManager $objectManager, Request $request, $id)
    {

        $marchan = $this->marchandRepository->findOneById($id);

        $user= $marchan->getConcessionnairemarchand()->getUtilisateur();
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

        
            $this->addFlash('success', 'le mot de passe a été changé avec succès');
            $objectManager->persist($user);
            $objectManager->flush();

            return $this->redirectToRoute("marchand");


        }



        return $this->render('marchand/security.html.twig', [
            'utilisateur' => $user,
            'form' => $form->createView()


        ]);

    }
}
 
 