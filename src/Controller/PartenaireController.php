<?php
namespace App\Controller;
use App\Entity\Partenaire;
use App\Entity\Utilisateur;
use App\Form\PartenaireType;
use App\Form\EditPartenaireType;
use App\Form\SecUtilisateurType;
use App\Repository\AgentRepository;
use App\Repository\TypemediaRepository;
use App\Repository\PartenaireRepository;
use Doctrine\Persistence\ObjectManager;
use App\Form\SecurityPartenaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;



/**
* @IsGranted("IS_AUTHENTICATED_FULLY")
*/
class PartenaireController extends AbstractController
{
    public function __construct(ObjectManager $om,
     PartenaireRepository $PartenaireRepository,
     AgentRepository $AgentRepository,
     TypemediaRepository $TypemediaRepository
    )
   {
      
       $this->om = $om;
       $this->PartenaireRepository = $PartenaireRepository;
       $this->AgentRepository = $AgentRepository;
       $this->TypemediaRepository = $TypemediaRepository;
    
   }
    #[Route('/partenaire', name: 'partenaire')]
    public function index(PartenaireRepository $repository ,Security $security): Response
    {
       
         /** @var User $user */
     $user = $security->getUser();
     $userrole = $user->getRoles();
   //  $usernom= $user->getNomutilisateur();
     $usernom= $user->getId();
  
////////////////////////////Role Admin//////////////////////
  if($userrole[0] == 'ROLE_ADMIN' )
  {
     $partenaires = $repository -> findAll();
    // dd($concessionnaires);die;

  }
  ////////////////////////////Role AGENT//////////////////////
  if($userrole[0] == 'ROLE_PARTENAIRE' )
  {
     $partenaires = $repository ->findIdByUtilisateur($usernom);
    
  }
        return $this->render('partenaire/index.html.twig', [
           
            'partenaires' => $partenaires ,
          
        ]);
    }
    #[Route('/partenaire/creation', name: 'creation_partenaire')]
    public function ajout_partenaire(Partenaire $partenaire = null, ObjectManager $objectManager, Request $request,UserPasswordHasherInterface $userPasswordHasher)
    {
        if(!$partenaire){
            $partenaire = new Partenaire();
        }
          $om=$this->om;
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->get('utilisateur')->get('roles')->setData(["ROLE_PARTENAIRE"]);
        $form -> handleRequest($request);
        $user= new Utilisateur();
 
     if($form->isSubmitted() && $form->isValid()){
        
        $partenaire->getUtilisateur()->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('utilisateur')->get('password')->getData()
                )
            );
         
           
           //Récupère l'image
            $media = $form->getData()->getMedia();
            if ($media) {
                //Récupère le fichier image
                $mediafile = $form->getData()->getMedia()->getImageFile();
                //Ajouter le nom
                $name = $mediafile->getClientOriginalName();
                //Déplacer le fichier
                $lien = '/media/logos/'.$name;
                $mediafile->move('../public/media/logos', $name);

                //Définit les valeurs
                $media->setNom($name);
                $media->setLien($lien);

                //Ajoute le type du média
                //$type = $this->TypemediaRepository->gettype('photo');

                //$media->setType($type);

            }
            $this->addFlash('success', 'L\'ajout a été effectuée avec succès');
            $objectManager->persist($partenaire);
            $objectManager->flush();
            return $this->redirectToRoute("partenaire");
       
        }
        return $this->render('partenaire/ajoutPartenaire.html.twig', [
           
            'form' => $form->createView(),
         
            
        ]);
    }


    #[Route('/partenaire/{id}', name: 'modification_partenaire', methods:'GET|POST')]
    public function modification_partenaire(Partenaire $partenaire = null, ObjectManager $objectManager, Request $request)
    {

        if(!$partenaire){
            $partenaire = new Partenaire();

        }
        $om=$this->om;




        $form = $this->createForm(EditPartenaireType::class, $partenaire)->remove("password");

      

        //On recupere le partenaire

        $form -> handleRequest($request);
        // $vendeurs = $form->getData('vendeurs');
        //($vendeurs);

        // dd($vendeurs);
        if($form->isSubmitted() && $form->isValid()){
         
          /*  $vendeurvalue = $form->get('vendeurs')->getData();
            if($vendeurvalue != null){
                $ven = $this->AgentRepository->fillVendeur($vendeurvalue->getId());
                $form->get('vendeurs')->setData($ven);
            }*/
            
            
           
            //Récupère l'image
            $media = $form->getData()->getMedia();
            
            //Récupère le fichier image
            $mediafile = $form->getData()->getMedia()->getImageFile();
            
            
            if ($mediafile) {
            //Ajouter le nom
            $name = $mediafile->getClientOriginalName();
            //Déplacer le fichier
            $lien = '/media/logos/'.$name;
            $mediafile->move('../public/media/logos', $name);
            
            //Définit les valeurs
            $media->setNom($name);
            $media->setLien($lien);

            //Ajoute le type du média
           
            /* $type = 'photo';*/
           
             }

            $objectManager->persist($partenaire);
            $objectManager->flush();
            $this->addFlash("success", "Cet Utilisateur est modifié avec succès");
            return $this->redirectToRoute("partenaire");
        }

        return $this->render('partenaire/modificationPartenaire.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form->createView(),
            'isModification' => $partenaire->getId() !== null,

        ]);
    }

    #[Route('/consulter-partenaire/{id}', name: 'consultation_partenaire', methods:'GET|POST')]
 public function consultation(Partenaire $partenaire): Response
 {
     
    
    $partenaire = $this->PartenaireRepository ->findOneById($partenaire->getId());
   // dd($partenaire);die;
   
     return $this->render('partenaire/consultation.html.twig', [
         'partenaire' => $partenaire,
   
      
     ]);
 }
    
 #[Route('/security-partenaires/{id}', name: 'security_partenaire', methods:'GET|POST')]
 public function secure(Partenaire $partenaire = null,UserPasswordHasherInterface $userPasswordHasher, ObjectManager $objectManager, Request $request, $id)
 {
 
             if(!$partenaire){
                 $partenaire = new Partenaire();
                 
                             }
             $partenaire = $this->PartenaireRepository->findOneById($id);
             $user= $partenaire->getUtilisateur();
        
             $form = $this->createForm(SecUtilisateurType::class,$user);
             $form -> handleRequest($request);
             

         
         
         //dd($form->getData());
         
         if($form->isSubmitted() && $form->isValid()){
            
             
                             // encode the plain password
                            
                                $user->setPassword(
                                    $userPasswordHasher->hashPassword(
                                        $user,
                                        $form->get('password')->getData()
                                    )
                                );
                             
                         
                                $this->addFlash('success', 'le mot de passe a été changé avec succès'); 
                         $objectManager->persist($partenaire);
                         $objectManager->flush();
                        
                         return $this->redirectToRoute("partenaire");
                     
             
             }
             
            
             
         return $this->render('partenaire/security.html.twig', [
             'utilisateur' => $user,
             'form' => $form->createView()
             
         
         ]);
 
 }
 #[Route('/delete-partenaire/{id}', name: 'delete_partenaire')]
 public function suppression(Partenaire $partenaire, Request $request,ObjectManager $objectManager){
   //  if($this->isCsrfTokenValid("SUP". $partenaire->getId(),$request->get('_token'))){
         $objectManager->remove($partenaire);
         $objectManager->flush();
         return $this->redirectToRoute("partenaire");
   //  }
 }
}