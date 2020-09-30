<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use App\Repository\IslandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/cities", name="city_")
 */
class CityController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(IslandRepository $islandRepository)
    {
        $islands = $islandRepository->findAll();
        
        return $this->render('city/index.html.twig', [
            'islands' => $islands,
        ]);
    }

    /**
     *@Route("/creation/{id}", name="create_update")
     *
     * @param EntityManagerInterface $manager
     * @param City $city
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function createUpdate(EntityManagerInterface $manager,Request $request , City $city = null, $id = null)
    {
        $city = new City();

        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($city);
            $manager->flush();
            $this->addFlash("success", "Nouvelle ville ajouter avec succès");
            return $this->redirectToRoute('city_index');
        }
        
        return $this->render('city/createUpdate.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
