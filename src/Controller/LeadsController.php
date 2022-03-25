<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Concessionnaire;
use App\Entity\Leads;
use App\Entity\Notes;
use App\Entity\Status;
use App\Form\AgentType;
use App\Form\LeadsType;
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
    public function index(LeadsRepository $leadsRepository, NotesRepository $notes): Response
    {
       $llead=$leadsRepository->findAll();
       // dd($llead);die;
        $mytab=[];
        foreach($llead as $leead)
        {
       $data = $leead->getId();
         if (isset($data)) 
         {
           
            $result= $notes->findNotesByLead($data);
           
            array_push($mytab,$result);
            //dump($result);die;

         }

       
        }
        dd($mytab);die;
        return $this->render('leads/index.html.twig', [
            'leads' => $leadsRepository->findAll(),
            'notes' => $mytab,
        ]);
    }

    #[Route('/new', name: 'leads_new', methods: ['GET', 'POST'])]
    public function new(LeadsRepository $leads,ModeleemailRepository $email,ModelesmsRepository $sms,Request $request, EntityManagerInterface $entityManager , UtilisateurRepository $u, AgentRepository $agent ,PartenaireRepository $partenaire , AdministrateurRepository $administrateur , ConcessionnaireRepository $concessionnaire , MarchandRepository $marcha): Response
    {
   
        $lead = new Leads();
      
        $form = $this->createForm(LeadsType::class, $lead);
     
      /*  foreach ($originalTags as $tag) {
            if (false === $task->getTags()->contains($tag)) {
                // remove the Task from the Tag
                $tag->getTasks()->removeElement($task);
                $em->persist($tag);
            }*/
       
        
        
       /* $part = $partenaire->findAll();

        
        $administrateur = $administrateur->findAll();
        $concessionnaire = $concessionnaire->findAll();
        $marchand = $marcha->findAll();
      

       
        $form->get('vendeur')->setData($agent);
        $form->get('partenaire')->setData($part);
        $form->get('administrateur')->setData($administrateur);
        $form->get('concessionnaire')->setData($concessionnaire);
        $form->get('marchand')->setData($marchand);
        $form->get('status')->setData($marchand);
     //   $form->get('status')->getData();*/
   /*  $emailleads = $email->findAll();
    foreach ($emailleads as $email) {
       // var_dump($email);die;
        $lead->removeEmaillead($email);
     

        
    } 
    $sms = $sms->findAll();
    foreach ($sms as $sm) {
        $lead->removeSmslead($sm);
        
    } */
   // $form->get('emailleads')->setData(NULL);
  //  $form->get('smsleads')->setData(NULL);
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

    #[Route('/{id}', name: 'leads_delete', methods: ['delete'])]
    public function delete(Request $request, Leads $lead, EntityManagerInterface $entityManager): Response
    {
       
            $entityManager->remove($lead);
            $entityManager->flush();
        

        return $this->redirectToRoute('leads_index', [], Response::HTTP_SEE_OTHER);
    }
}
