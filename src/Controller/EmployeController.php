<?php

namespace App\Controller;

use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //Récupère toutes les entreprises de la BDD
        // findBy possède 2 arguments : équivaut à la requête SELECT * FROM employe WHERE ville = "Strasourg" ORDER BY nom ASC 
        // 1. Dans le premier tableau on indique des conditions, ici affiche les employés dans la ville= Strasbourg.
        //2. Dans le Deuxième tableau on indique l'ordre du tri (nom trié par ordre alphabétique).
        // C'est une requête Dql
        $employes= $entityManager->getRepository(Employe::class)->findBy([],["nom" => "ASC"]);
        
        return $this->render('employe/index.html.twig', [
            'employes' => $employes
            
        ]);
    }

    #[Route('/employe/{id}', name: "show_employe")]
        // Response (n'est pas obligatoire). Le type de ce qui est renvoyé par notre méthode. On fait une request et renvoie une réponse.
        //Objet Employe en paramètre. Il sera récupéré avec l'id renseigné dans la route. PAs besoin de faire une requête du type findBy comme exemple plus haut, car 
    public function show(Employe $employe): Response {
        // Param converter. Permet de récupérer un objet (ici employé) gràce à son id.
        return $this->render('employe/show.html.twig', [
            'employe' => $employe
            
        ]);

    }
}
