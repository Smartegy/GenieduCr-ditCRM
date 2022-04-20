<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Administrateur;
use App\Entity\Agent;
use App\Entity\Concessionnaire;
use App\Entity\Courriel;
use App\Entity\Leads;
use App\Entity\Marchand;
use App\Entity\Modeleemail;
use App\Entity\Notes;
use App\Entity\Partenaire;
use App\Entity\Status;
use App\Entity\Utilisateur;
use App\Entity\Vendeurr;
use App\Form\AgentType;
use App\Form\CourrielType;
use App\Form\LeadsType;
use App\Form\ModeleemailType;
use App\Repository\AdministrateurRepository;
use App\Repository\AgentRepository;
use App\Repository\LeadsRepository;
use App\Repository\ConcessionnaireRepository;
use App\Repository\CourrielRepository;
use App\Repository\MarchandRepository;
use App\Repository\ModeleemailRepository;
use App\Repository\ModelesmsRepository;
use App\Repository\NotesRepository;
use App\Repository\PartenaireRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
 use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\Mailer\MailerInterface;
 use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mime\Email;
use App\Service\SendMailService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
* @IsGranted("IS_AUTHENTICATED_FULLY")
*/
class SmsController extends AbstractController
{
    #[Route('/chat/messages', name: 'chat')]
    public function index(CourrielRepository $mailRepository): Response
    {
        $Listedesemails = $mailRepository->findAll() ; 

       // dd($Listedesemails) ; die ; 
        
        return $this->render('chat/private.html.twig', [
            'Liste' => $Listedesemails,
        ]);
    }

}