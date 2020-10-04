<?php

namespace App\Controller;

use App\Entity\DeliveryMan;
use App\Form\DeliveryManType;
use App\Repository\DeliveryManRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
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
    public function new(Request $request, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $searchUserform = $this->createFormBuilder()
            ->add('phone', SearchType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Entrez le numéro de téléphone'
                ]])
            ->getForm();

        $searchUserform->handleRequest($request);

        if ($searchUserform->isSubmitted() && $searchUserform->isValid()) {

            $data = $searchUserform->getData();

            $user = $userRepository->findOneByPhone($data['phone']);
            $tryToFind = true;
        }
        
        $deliveryMan = new DeliveryMan();
        $deliveryManForm = $this->createForm(DeliveryManType::class, $deliveryMan);
        $deliveryManForm->handleRequest($request);
        
        if ($deliveryManForm->isSubmitted() && $deliveryManForm->isValid()) {
            $user = $userRepository->findOneByPhone($deliveryManForm->getData()->phone);
            $deliveryMan->setUser($user);
            
            $user->addRole("ROLE_DELIVERY_MAN");

            $manager->persist($user);
            $manager->persist($deliveryMan);
            $manager->flush();
            
            return $this->redirectToRoute('delivery_man_index');
        }

        return $this->render('delivery_man/new.html.twig', [
            'searchUserform' => $searchUserform->createView(),
            'deliveryManForm' => isset($deliveryManForm) ? $deliveryManForm->createView() : null,
            'user' => isset($user) ? $user : null,
            'tryToFind' => isset($tryToFind) ? true : false,
        ]);
    }
}
