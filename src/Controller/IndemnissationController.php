<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\IndemnissationRefuseType;
use App\Form\IndemnissationAcceptType;
use App\Entity\Indemnissation;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reclamation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReclamationRepository;
use App\Repository\IndemnissationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MyGmailMailerService;
use App\Repository\CommandeRepository;
use App\Entity\Commande;
class IndemnissationController extends AbstractController
{
    private MyGmailMailerService $mailerService;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry, MyGmailMailerService $mailerService)
    {
        $this->managerRegistry = $managerRegistry;
        $this->mailerService = $mailerService;
    }

    #[Route('/tableIndelnissations', name: 'app_indemnissation_admin')]
    public function index_reclamation_admin(IndemnissationRepository $rep): Response
    {$list=$rep->findAll();
        return $this->render('indemnissation\IndemnissationBack.html.twig', 
          ['list' => $list, ]
        );
    }

    #[Route('/Indemnissation/IndemnissationRefuse/{reclamationId}', name: 'reclamation_refuse')]
        public function newIndemnissationRefuse(Request $request, int $reclamationId): Response {
           
            $entityManager = $this->managerRegistry->getManager();
    $reclamationRepository = $this->managerRegistry->getRepository(Reclamation::class);
    $reclamation = $reclamationRepository->find($reclamationId);
    $indemnissation = $reclamation->getIndemnissation();

    if (!$indemnissation) {
        $indemnissation = new Indemnissation();
       
       
       
        $reclamation->setIndemnissation($indemnissation);
        $entityManager->persist($reclamation);
    } else {
     
        $indemnissation->setMontant(0);
    }

   
    $entityManager->flush();
    $this->mailerService->sendEmail(
        "farah.adad2001@gmail.com",
        'New Compensation Added to Your Reclamation',
        $this->renderView('email/rec_email.html.twig', [
            'ind' => $indemnissation,'reclamation'=>$reclamation
        ])
    );
    return $this->redirectToRoute('app_reclamation_admin');
}
        
    
        

    
       

#[Route('/Indemnissation/IndemnissationAccept/{reclamationId}', name: 'reclamation_accepte')]
public function newIndemnissationAccepte(Request $request, int $reclamationId): Response
{
    $entityManager = $this->managerRegistry->getManager();
    $reclamationRepository = $this->managerRegistry->getRepository(Reclamation::class);
    $reclamation = $reclamationRepository->find($reclamationId);
    $command = $reclamation->getCommand();
    $indemnissation = $reclamation->getIndemnissation();
    

    if (!$indemnissation) {
        $indemnissation = new Indemnissation();
    }
        $indemnissation->setMontant($command->getInsValue());
    $form = $this->createForm(IndemnissationAcceptType::class, $indemnissation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      
        $reclamation->setIndemnissation($indemnissation);
        
       
        $entityManager->persist($reclamation);
        
    
        $entityManager->flush();
        $this->mailerService->sendEmail(
            $reclamation->getCommand()->getUser()->getEmail(),
            'compensation added',
            $this->renderView('email/rec_email.html.twig', [
                'ind' => $indemnissation,'reclamation'=>$reclamation
            ])
        );
     
        return $this->redirectToRoute('app_reclamation_admin');
    }

    return $this->render('indemnissation/addIndemnissationAccept.html.twig', [
        'form' => $form->createView(),
        'reclamation' => $reclamation
    ]);
}

#[Route('listn/delete/{id}', name: 'ind_delete')]
public function deleteInd(Request $req, IndemnissationRepository $indemnissationRepository, ReclamationRepository $reclamationRepository, $id, EntityManagerInterface $em): Response
{
    
    $indemnissation = $indemnissationRepository->find($id);
    if ($indemnissation) {
        
        $reclamation = $reclamationRepository->findOneBy(['indemnissation' => $indemnissation]);
        if ($reclamation) {
            
            $reclamation->setIndemnissation(null);
            $reclamation->setReponse("Currently being processed");
            $em->persist($reclamation);
        }

        $em->remove($indemnissation);
        $em->flush();
    }

    return $this->redirectToRoute('app_indemnissation_admin');
}
#[Route('indemnisation_show/{id}', name: 'indemnisation_show')]
public function showRec(Request $request, IndemnissationRepository $repository, $id): Response
{  $ind = $repository->find($id);
    
        
    return $this->render('indemnissation/show.html.twig', [
        'ind' => $ind,
    ]);
}






//User view: 

#[Route('indemnisationUser_show/{id}', name: 'indemnisationUser_show')]
public function showCOM(Request $request, IndemnissationRepository $repository, $id): Response
{  $ind = $repository->find($id);
    
        
    return $this->render('indemnissation/showIndsmnissationUser.html.twig', [
        'ind' => $ind,
    ]);
}
}