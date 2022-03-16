<?php

namespace App\Controller;

use App\Entity\Concessionnaire;
use App\Entity\Leads;
use App\Entity\Status;
use App\Form\LeadsType;
use App\Repository\AdministrateurRepository;
use App\Repository\AgentRepository;
use App\Repository\LeadsRepository;
use App\Repository\ConcessionnaireRepository;
use App\Repository\MarchandRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/leads')]
class LeadsController extends AbstractController
{
    #[Route('/', name: 'leads_index', methods: ['GET'])]
    public function index(LeadsRepository $leadsRepository): Response
    {
        return $this->render('leads/index.html.twig', [
            'leads' => $leadsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'leads_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , AgentRepository $agent ,PartenaireRepository $partenaire , AdministrateurRepository $administrateur , ConcessionnaireRepository $concessionnaire , MarchandRepository $marcha): Response
    {
    
        $lead = new Leads();
        $form = $this->createForm(LeadsType::class, $lead);
      
           
        
       /* $part = $partenaire->findAll();

        $agent = $agent->findAll();
        $administrateur = $administrateur->findAll();
        $concessionnaire = $concessionnaire->findAll();
        $marchand = $marcha->findAll();
      

        $form->get('agent')->setData($agent);
        $form->get('vendeur')->setData($agent);
        $form->get('partenaire')->setData($part);
        $form->get('administrateur')->setData($administrateur);
        $form->get('concessionnaire')->setData($concessionnaire);
        $form->get('marchand')->setData($marchand);
        $form->get('status')->setData($marchand);
     //   $form->get('status')->getData();*/
      
        


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $entityManager->persist($lead);
            $entityManager->flush();

            return $this->redirectToRoute('leads_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('leads/new.html.twig', [
           
            'lead' => $lead,
            'form' => $form,
        ]);
    }





    #[Route('/{id}', name: 'leads_show', methods: ['GET'])]
    public function show(Leads $lead): Response
    {
        return $this->render('leads/show.html.twig', [
            'lead' => $lead,
        ]);
    }

    #[Route('/{id}/edit', name: 'leads_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Leads $lead, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LeadsType::class, $lead);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('leads_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('leads/edit.html.twig', [
            'lead' => $lead,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'leads_delete', methods: ['POST'])]
    public function delete(Request $request, Leads $lead, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lead->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lead);
            $entityManager->flush();
        }

        return $this->redirectToRoute('leads_index', [], Response::HTTP_SEE_OTHER);
    }
}
