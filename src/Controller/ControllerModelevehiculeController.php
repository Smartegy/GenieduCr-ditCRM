<?php

namespace App\Controller;

use App\Repository\FabriquantRepository;
use App\Repository\ModeleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ControllerModelevehiculeController extends AbstractController
{
    #[Route('/controller/modelevehicule', name: 'app_controller_modelevehicule')]
    public function index(Request $request,ModeleRepository $modele,FabriquantRepository $fabricant): Response
    {
        $fab=$fabricant->findOneById(7);
        $listmodle=$modele->findByFabriquant($fab);
       // dd($listmodle);die;





        return $this->render('controller_modelevehicule/index.html.twig', [
            'controller_name' => 'ControllerModelevehiculeController',
            'listmodle' => $listmodle ,
        ]);
    }
}
