<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonneController extends AbstractController
{
    #[Route('/all/personnes', name: 'app_getAll_personne')]
    public function index(PersonneRepository $personneRepository): Response
    {
        return $this->render('personne/index.html.twig', [
            'persons' => $personneRepository->findAll(),
        ]);
    }

    #[Route('/add/personnes', name: 'app_add_personne')]
    public function addPersonne(Request  $request,ManagerRegistry $managerRegistry): Response
    {
        //dump($request);
        $em = $managerRegistry->getManager();
        if($request->get('save') !== null){
            $age = $request->get('age') ?? 0;
            $persone = new Personne();
            $persone->setNom($request->get('nom'))
                ->setPrenom($request->get('prenom'))
                ->setAge($age);
            $em->persist($persone);
            //dump($persone);
            $em->flush();
            $this->addFlash('success', 'Article Created! Knowledge is power!');
            return new RedirectResponse('/all/personnes');
        }
        return $this->render('personne/add.html.twig', [
            //'persons' => $personneRepository->findAll(),
        ]);
    }
}
