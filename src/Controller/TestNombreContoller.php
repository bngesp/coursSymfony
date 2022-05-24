<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestNombreContoller extends AbstractController
{

    /**
     * @Route("/test/number")
     */
    // ou  #[Route('/lucky/number')]
    public function number() : Response {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>votre nombre est: '.$number.'</body></html>'
        );
    }
    /**
     * @Route("/cours/home/{coursname?}", name="home_cours")
     */
    public function cours(Request $request) : Response {
        $cours = $request->attributes->get('coursname');
        var_dump($request->query->get('id'));
        return new Response(" le cours est : $cours");
    }
    /**
     * @Route("/home2/{coursname}", name="app_home")
     */
    public function redirectToCours($coursname){
        $url = $this->generateUrl("home_cours");
        echo "redirecting";
        return new RedirectResponse($url);
    }

    /**
     * @Route("/product", name="app_product")
     */
    public function saveProduct(ManagerRegistry $doctrine){
        $entityManager = $doctrine->getManager();
        $p = new Produit();
        $p->setNom("test")
            ->setDescription('un peu de text')
            ->setMontant(100);

        $entityManager->persist($p);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$p->getId());

    }
    /**
     * @Route("/products", name="app_product_get")
     */
    public function getProduct(ProduitRepository $produitRepository){
        $p = $produitRepository->findAll();

        return new Response('Saved new product with id ');

    }
    /**
     * @Route("/products/add", name="app_product_add")
     */
    public function addProduct(){
        //$f = new ProduitType();

        $form = $this->createFormBuilder((new Produit())->setNom("text")->setMontant(100)->setDescription("un texte"))
        ->add('nom', TextType::class)
        ->add('montant', NumberType::class)
        ->add('description', TextareaType::class)
        ->add('save', SubmitType::class, ['label' => 'Create Task'])
        ->getForm();
        return $this->render('bloc/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}