<?php

namespace App\Controller;

use App\Entity\DeliveryMan;
use App\Form\DeliveryManType;
use App\Repository\DeliveryManRepository;
use App\Repository\DeliveryRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/deliveryman", name="delivery_man_")
 */
class DeliveryManController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(DeliveryManRepository $deliveryManRepository)
    {
        return $this->render('delivery_man/index.html.twig', [
            'deliveryMen' => $deliveryManRepository->findAllSimplified()
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(DeliveryMan $deliveryMan)
    {
        return $this->render('delivery_man/show.html.twig', [
            'deliveryMan' => $deliveryMan
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $deliveryMan = new DeliveryMan();

        $form = $this->createForm(DeliveryManType::class, $deliveryMan);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $deliveryMan->setCreatedAt(new DateTime());
            
            $user = $deliveryMan->getUser();
            $user->setRoles(['ROLE_DELIVERY_MAN'])->setDeliveryMan($deliveryMan); 
            
            // dd($user);

            $manager->persist($user);
            $manager->persist($deliveryMan);
            $manager->flush();
            return $this->redirectToRoute('delivery_man_index');
        }

        return $this->render('delivery_man/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
