<?php

namespace App\Controller;

use App\Entity\FilesLead;
use App\Form\FilesLeadType;
use App\Repository\FilesLeadRepository;
use App\Repository\LeadsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
* @IsGranted("IS_AUTHENTICATED_FULLY")
*/
#[Route('/files/lead')]
class FilesLeadController extends AbstractController
{
    #[Route('/', name: 'files_lead_index', methods: ['GET'])]
    public function index(FilesLeadRepository $filesLeadRepository): Response
    {
        return $this->render('files_lead/index.html.twig', [
            'files_leads' => $filesLeadRepository->findAll(),
        ]);
    }



    #[Route('/filess/{idlead}', name: 'files_index', methods: ['GET', 'POST'])]
    public function listfiles(Request $request, EntityManagerInterface $entityManager,FilesleadRepository $files,LeadsRepository $leadsRepository): Response
    {
        $question_id = $request->query->get('idlead');
       $data= $request->getPathInfo();
       $res= explode('/',$data,5);
        $param=$res[4];
       $paranfinal= intval($param);
 
        $result= $files->findNotesByLead($paranfinal );
        $fille = new Fileslead();
        $form = $this->createForm(FilesLeadType::class, $fille);
        $onelead=$leadsRepository->findOneById($paranfinal);

       $form->get('lead')->setData($onelead);

        
        $form->handleRequest($request);
      
        $files = $request->files;

      
        if ($form->isSubmitted()  && $form->isValid() ) {

          $fdataile = $form['nom']->getData();
        if($fdataile)
        {
            $name = $fdataile->getClientOriginalName();

            $lien = '/media/files/'.$name;
            $fdataile->move('../public/media/files', $name);
             

           $fille->setNom($name);
           $fille->setLien($lien);
  
  
          // dd($fille->getLien());die;

        }
         
        
        $this->addFlash("success", "un fichier a été ajouté avec succeès");
        $entityManager->persist($fille);
        $entityManager->flush();

      
            return $this->redirectToRoute('files_index',array('idlead' => $paranfinal));
        }
       // $form->get('lead')->setData($onelead); 

        return $this->renderForm('files_lead/index.html.twig', [
            'file' => $fille,
            'form' => $form,
            'files' => $result,
            'lead' => $onelead,
            'question_id' => $paranfinal,
        ]);
    
    }

    #[Route('/new', name: 'files_lead_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $filesLead = new FilesLead();
        $form = $this->createForm(FilesLeadType::class, $filesLead);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($filesLead);
            $entityManager->flush();

            return $this->redirectToRoute('files_lead_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('files_lead/new.html.twig', [
            'files_lead' => $filesLead,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'files_lead_show', methods: ['GET'])]
    public function show(FilesLead $filesLead): Response
    {
        return $this->render('files_lead/show.html.twig', [
            'files_lead' => $filesLead,
        ]);
    }

    #[Route('/{id}/edit', name: 'files_lead_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FilesLead $filesLead, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FilesLeadType::class, $filesLead);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('files_lead_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('files_lead/edit.html.twig', [
            'files_lead' => $filesLead,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/{idlead}', name: 'files_lead_delete')]
    public function delete(Request $request, FilesLead $filesLead, EntityManagerInterface $entityManager): Response
    {
        $idlead = $request->get('idlead');
        //($id);die;
            $entityManager->remove($filesLead);
            $entityManager->flush();
        

        return $this->redirectToRoute('files_index',array('idlead' => $idlead));
    }
}
