<?php

namespace App\Controller;

use App\Entity\Leads;
use App\Entity\OperationAchat;
use App\Form\OperationAchatType;
use App\Repository\LeadsRepository;
use App\Repository\OperationAchatRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    

    #[Route('operations/{idlead}', name: 'clients_operations', methods: ['GET', 'POST'])]
    public function operations(Request $request, EntityManagerInterface $entityManager,OperationAchatRepository $operation,LeadsRepository $leadsRepository): Response
    {

        $question_id = $request->getpathInfo();
         $res= explode('/',$question_id,3);
         $param=$res[2];
         $idlead= intval($param);
        $onelead=$leadsRepository->findOneById($idlead);
        $result=$operation->findOperationsByLead($idlead);

        $resultachat=$operation->findOperationsAchatByLead($idlead);
        $resultvente=$operation->findOperationsVenteByLead($idlead);
        $resultEchange=$operation->findOperationsEchangeByLead($idlead);
        //dd($resultEchange);die;
  
       //dd($result) ;die;
      /*  $operations = new OperationAchat();
        $form = $this->createForm(OperationAchatType::class, $operations);
        $form->get('leads')->setData($onelead);  
        $form->handleRequest($request);
        dd($form);die;
   
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($operations);
            $entityManager->flush();
           
            $uri = $request->getUri();
          
            

          
            return $this->redirectToRoute('clients_operations',array('idlead' => $idlead));
        }*/


        return $this->render('clientAchat/operation.html.twig', [
            'result' => $result ,
            'resultachat'=> $resultachat,
            'resultvente' => $resultvente,
            'resulteechange' => $resultEchange ,
            'lead' => $onelead,

            
        ]);
    }
    #[Route('transaction/{idlead}', name: 'clients_transaction', methods: ['GET', 'POST'])]
    public function transaction(Request $request, EntityManagerInterface $entityManager,OperationAchatRepository $operation,LeadsRepository $leadsRepository): Response
    {

        $question_id = $request->getpathInfo();
         $res= explode('/',$question_id,3);
         $param=$res[2];
         $idlead= intval($param);
        $onelead=$leadsRepository->findOneById($idlead); 
        $result=$operation->findOperationsByLead($idlead);
       //dd($result) ;die;
       $operations = new OperationAchat();
        $form = $this->createForm(OperationAchatType::class, $operations);
        $form->get('leads')->setData($onelead);  
        $form->handleRequest($request);
        //dd($form);die;
   
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($operations);
            $entityManager->flush();
           
           // $uri = $request->getUri();
          
            

          
            return $this->redirectToRoute('clients_transaction',array('idlead' => $idlead));
        }


        return $this->render('clientAchat/transaction.html.twig', [
           
            'lead' => $onelead,
            'form' => $form->createView(),

            
        ]);
    }
    


    #[Route('transaction/{idlead}', name: 'clientvente_transaction', methods: ['GET', 'POST'])]
    public function transactionvente(Request $request, EntityManagerInterface $entityManager,OperationAchatRepository $operation,LeadsRepository $leadsRepository): Response
    {

        $question_id = $request->getpathInfo();
         $res= explode('/',$question_id,3);
         $param=$res[2];
         $idlead= intval($param);
        $onelead=$leadsRepository->findOneById($idlead); 
        $result=$operation->findOperationsByLead($idlead);
       //dd($result) ;die;
       $operations = new OperationAchat();
        $form = $this->createForm(OperationAchatType::class, $operations);
        $form->get('leads')->setData($onelead);  
        $form->handleRequest($request);
        //dd($form);die;
   
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($operations);
            $entityManager->flush();
           
           // $uri = $request->getUri();
          
            

          
            return $this->redirectToRoute('clients_transaction',array('idlead' => $idlead));
        }


        return $this->render('client_vente/transaction.html.twig', [
           
            'lead' => $onelead,
            'form' => $form->createView(),

            
        ]);
    }
}
