<?php

namespace App\Controller;

use App\Entity\Vendeurr;
use App\Entity\Utilisateur;
use App\Form\EditVendeurType;
use App\Form\VendeurrType;
use App\Form\SecUtilisateurType;
use App\Repository\VendeurrRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;



/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class VendeurController extends AbstractController
{
    public function __construct(VendeurrRepository $vendeurrRepository,

    )
    {
        $this->VendeurrRepository = $vendeurrRepository;

    }

    #[Route('/vendeur', name: 'vendeur')]
    public function index(VendeurrRepository $repository,Security $security): Response
    {
        
     /** @var User $user */
     $user = $security->getUser();
     $userrole = $user->getRoles();
     $usernom= $user->getId();
   //  $vendeur = $repository ->findIdByUtilisateur($usernom);
    // dd($vendeur);die;
         
  if($userrole[0] == 'ROLE_ADMIN' )
  {
     $vendeur = $repository -> findAll();
    // dd($concessionnaires);die;

  }
  if($userrole[0] == 'ROLE_VENDEUR' )
  {
     $vendeur = $repository ->findIdByUtilisateur($usernom);
    
  }

     
        return $this->render('vendeur/index.html.twig', [
            'vendeur' => $vendeur,
        ]);
    }

    #[Route('/vendeur/add-vendeur', name: 'add_vendeur')]
    public function ajout_modification(Vendeurr $vendeurr = null, UserPasswordHasherInterface $userPasswordHasher, ObjectManager $objectManager, Request $request)
    {
        if (!$vendeurr) {
            $vendeurr = new Vendeurr();

        }
        $form = $this->createForm(VendeurrType::class, $vendeurr);
     
        $form->get('utilisateur')->get('roles')->setData(["ROLE_VENDEUR"]);
      
        
        $form->handleRequest($request);
        $user = new Utilisateur();
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $vendeurr->getUtilisateur()->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('utilisateur')->get('password')->getData()
                )
            );
            $modif = $vendeurr->getId() !== null;
              $this->addFlash('success', 'L\'ajout a été effectuée avec succeés');
            $objectManager->persist($vendeurr);
            $objectManager->flush();
           
            return $this->redirectToRoute('vendeur');


        }


        return $this->render('vendeur/ajoutVendeur.html.twig', [
            'vendeur' => $vendeurr,
            'form' => $form->createView(),
            'isModification' => $vendeurr->getId() !== null
        ]);
    }

    #[Route('/conslt-vendeur/{id}', name: 'consultation_vendeur', methods: 'GET|POST')]
    public function consultation(VendeurrRepository $repository, Vendeurr $vendeur): Response
    {


        $vendeur = $repository->findOneById($vendeur->getId());


        return $this->render('vendeur/consultation.html.twig', [
            'vendeur' => $vendeur

        ]);
    }

    #[Route('/modify-vendeur/{id}', name: 'modify_vendeur', methods: 'GET|POST')]
    public function modification(Vendeurr $vendeur = null, ObjectManager $objectManager, UserPasswordHasherInterface $userPasswordHasher, Request $request)
    {
        if (!$vendeur) {
            $vendeur = new Vendeurr();
        }
        $form = $this->createForm(EditVendeurType::class, $vendeur)->remove("password");
       
        $form->handleRequest($request);
        $user = new Utilisateur();
        if ($form->isSubmitted() && $form->isValid()) {

            $modif = $vendeur->getId() !== null;
            $objectManager->persist($vendeur);
            $objectManager->flush();
            $this->addFlash("success","Cet Utilisateur est modifié avec succès");
            return $this->redirectToRoute('vendeur');
        }

        return $this->render('vendeur/modification.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form->createView(),
            'isModification' => $vendeur->getId() !== null
        ]);
    }

    #[Route('/delete-vendeur/{id}', name: 'deletevendeur')]
    public function suppression(Vendeurr $vendeur, Request $request, ObjectManager $objectManager)
    {
        $objectManager->remove($vendeur);
        $objectManager->flush();
        return $this->redirectToRoute("vendeur");
    }

    #[Route('/secure-vendeur/{id}', name: 'secure_vendeur', methods:'GET|POST')]
    public function secure(UserPasswordHasherInterface $userPasswordHasher, ObjectManager $objectManager, Request $request, $id)
    {

        $vendeur = $this->VendeurrRepository->findOneById($id);

        $user= $vendeur->getUtilisateur();
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
            return $this->redirectToRoute("vendeur");
        }



        return $this->render('vendeur/security.html.twig', [
            'utilisateur' => $user,
            'form' => $form->createView()


        ]);

    }

}
