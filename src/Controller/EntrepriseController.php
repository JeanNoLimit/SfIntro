<?php

namespace App\Controller;

use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //Récupère toutes les entreprises de la BDD
        // https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database
        $entreprises= $entityManager->getRepository(Entreprise::class)->findAll();
        
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
            
        ]);
    }

    #[Route('/entreprise/{id}', name: "show_entreprise")]
    
    public function show(Entreprise $entreprise): Response {
        
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
            
        ]);

    }

    public function add() {
        
    }



}
