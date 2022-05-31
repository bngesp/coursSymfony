<?php

namespace App\Controller;

use App\Model\Personne;
use App\Repository\PersonneRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
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
        $name = 'bassirou ngom';
        $tabFruit = ['banana', 'pomme', 'fraise'];
        return $this->render('view/index.html.twig', [
            'controller_name' => 'ViewController',
            'nom' => $name,
            'classe' => 'GLAR3 2022',
            'tab' => $tabFruit,
            'p' => $person,
        ]);
        //return new Response('user crée avec lid =>'.$person->getId());

    }

    #[Route('/liste/{page?1}', name: 'app_get_person')]
    public function getPerson( PersonneRepository $personneRepository, int $page): Response
    {
//        $allPerson = $personneRepository->findAll();
        $offset = 4 * ($page - 1);
        $allPerson = $personneRepository->findBy([], ['id'=> 'ASC'], 4, $offset);
        $nbPage = ceil($personneRepository->count([]) / 4);
        return $this->render(
            'person/list.personnes.html.twig',
            [
                "person" => $allPerson,
                "pages" => $nbPage,
                "current_page"=> $page
            ]
        );
    }

    #[Route('/personnes/{id}', name: 'app_get_one_person')]
    public function getOnePerson( int $id, PersonneRepository $personneRepository): Response
    {
        $p = $personneRepository->findOneBy(['id'=> $id]);
        return $this->render(
            'person/view.personnes.html.twig',
            [ "p" => $p]
        );
    }
    #[Route('/personne/search/{name}/{prenom}', name: 'app_search_person')]
    public function getOneByNamePerson(string $name, string $prenom, PersonneRepository $personneRepository): Response
    {
        $p = $personneRepository->getOneByNomAndPrenom($name, $prenom);
        return $this->render(
            'person/view.personnes.html.twig',
            [ "person" => $p]
        );
    }

    #[Route('/personne/search/dql/{name}/{prenom}', name: 'app_search_dsl_person')]
    public function getOneByNamePersonDQL(string $name, string $prenom, PersonneRepository $personneRepository): Response
    {
        $p = $personneRepository->getOneByNomAndPrenomDQL($name, $prenom);
        return $this->render(
            'person/view.personnes.html.twig',
            [ "person" => $p]
        );
    }

    #[Route('/personne/noms', name: 'app_search_names_person')]
    public function getNames(PersonneRepository $personneRepository): Response
    {
        $p = $personneRepository->getOnlyNames();
        return $this->render(
            'person/view.personnes.html.twig',
            [ "person" => $p]
        );
    }


    #[Route('/personne/age/{age}', name: 'app_age_person')]
    public function getPersonGreatThan(string $age, PersonneRepository $personneRepository): Response
    {
        $p = $personneRepository->getPersonsGreatThan($age);
        return $this->render(
            'person/view.personnes.html.twig',
            [ "person" => $p]
        );
    }

    #[Route('/query/{q}', name: 'app_query_person')]
    public function query(string $q, ManagerRegistry $managerRegistry): Response
    {

        $person = $managerRegistry->getRepository(PersonneRepository::class);
        $person->getOneByNomAndPrenom('','');
        return $this->render(
            'person/view.personnes.html.twig',
            [ "person" => $person]
        );
    }


}
