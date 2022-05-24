<?php

namespace App\Controller;

use App\Model\Personne;
use App\Repository\PersonneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewController extends AbstractController
{
    #[Route('/view', name: 'app_view')]
    public function index(): Response
    {    $person = new Personne('John', 'Doe', 23);
        $name = 'bassirou ngom';
        $tabFruit = ['banana', 'pomme', 'fraise'];
        return $this->render('view/index.html.twig', [
            'controller_name' => 'ViewController',
            'nom' => $name,
            'classe' => 'GLAR3 2022',
            'tab' => $tabFruit,
            'p' => $person,
        ]);
    }
    #[Route('/personnes', name: 'app_save_person')]
    public function savePerson(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $person = new \App\Entity\Personne();
        $person->setNom("Diallo")
            ->setPrenom("mamadou")
            ->setAge(50)
            ->setSexe('M');
        $em->persist($person);
        $em->flush();
        return new Response('user crée avec lid =>'.$person->getId());

    }

    #[Route('/liste', name: 'app_gte_person')]
    public function getPerson(PersonneRepository $personneRepository): Response
    {
        $allPerson = $personneRepository->findAll();
        dd($allPerson);
        return new Response('user crée avec lid =>');

    }
}
