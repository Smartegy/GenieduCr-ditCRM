<?php

namespace App\Controller;

use App\Entity\Vendeurr;
use App\Entity\Utilisateur;
use App\Form\EditVendeurType;
use App\Form\VendeurrType;
use App\Repository\VendeurrRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\SecUtilisateurType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;


class VendeurController extends AbstractController
{
    public function __construct(VendeurrRepository $vendeurrRepository,

    )
    {
        $this->VendeurrRepository = $vendeurrRepository;

    }

    #[Route('/vendeur', name: 'vendeur')]
    public function index(VendeurrRepository $repository): Response
    {
        $vendeur = $repository -> findAll();
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
        $form->handleRequest($request);

        $user = new Utilisateur();


        //dd($form->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            // if($form->isSubmitted()){


            // encode the plain password
            $vendeurr->getUtilisateur()->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('utilisateur')->get('password')->getData()
                )
            );

            $modif = $vendeurr->getId() !== null;

            $objectManager->persist($vendeurr);
            $objectManager->flush();
            $this->addFlash("success", ($modif) ? "La modification a été effectuée" : "L'ajout a été effectuée");
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
            $this->addFlash("success", ($modif) ? "La modification a été effectuée" : "L'ajout a été effectuée");
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
}
