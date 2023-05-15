<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
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
    //Fonction pour ajouter un objet en BDD depuis le formulaire.
    // https://symfony.com/doc/current/doctrine.html#persisting-objects-to-the-database
    // On aura 3 arguments :
    // 1. entityManagerInterface pour communiquer avec la BDD
    // 2. L'objet que l'on veut rajotuer en BDD. Ici entreprise. On l'initialise à null car dans le cas d'un ajout l'objet n'existe pas encore. Cette méthode add sera également utilisé dans le cas d'un formulaire de modification, à la différence que les valeurs de l'objet entreprise seront déja renseigné dans le formulaire pour modification.
    // 3. request -> objet....

    #[Route('/entreprise/add', name: "add_entreprise")]
    //On peut créer plusieurs routes pour une même fonction
    #[Route('/entreprise/{id}/edit', name:"edit_entreprise")]

    public function add(EntityManagerInterface $entityManager, Entreprise $entreprise = null, Request $request): Response {


        if(!$entreprise) {
            $entreprise = new Entreprise();
        }
        //Création du formulaire à partir de EntrepriseType et l'objet entreprise
        $form = $this->createForm(EntrepriseType::class, $entreprise);

        //Analyse la requète du formulaire avec la fonction handleRequest.
        $form->handleRequest($request);

        //Traitement du formulaire. Si le formulaire est soumis (clique bouton submit) et que les champs sont bien valide (equivalent à filter input)
        if ($form->isSubmitted() && $form->isValid()) {
            //getData récupère les données de l'entreprise si elle existe. (vue edit)
           $entreprise = $form->getData();
           
           //Equivalent à prepare une requête sql
           $entityManager->persist($entreprise);
           //Equivalent à execute. flush > envoie des données en BDD. Insert into
           $entityManager->flush();

           return $this->redirectToRoute('app_entreprise');
        }

        //Vue pour afficher le formulaire
        return $this->render('entreprise/add.html.twig', [
            // createView pour généré visuellement le formulaire
            'formAddEntreprise' => $form->createView(),
            'edit' => $entreprise->getId(),
            'entreprise' => $entreprise
            
        ]);


    }

    #[Route('/entreprise/{id}/delete', name: "delete_entreprise")]
    public function delete(EntityManagerInterface $entityManager, Entreprise $entreprise){

        //Supprime en cascade automatiquement si les liens entre les tables ont bien été créés.
        $entityManager->remove($entreprise);
        $entityManager->flush();

        return $this->redirectToRoute('app_entreprise');
    }

    #[Route('/entreprise/{id}', name: "show_entreprise")]
    public function show(Entreprise $entreprise): Response {
        
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
            
        ]);

    }



}
