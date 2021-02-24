<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\ValidationForm;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * @Route("/admin/restaurants", name="restaurant_")
 */
class RestaurantController extends AbstractController
{
    private $validationForm;
    private $manager;
    private $fileUploader;

    public function __construct(ValidationForm $validationForm, EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $this->validationForm = $validationForm;
        $this->manager = $manager;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(RestaurantRepository $restaurantRepository, PaginatorInterface $paginator, Request $request)
    {
        $restaurants = $paginator->paginate($restaurantRepository->findAllQuery(), $request->query->getInt('page', 1), 10);

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }

    /**
     * Undocumented function
     * @Route("/show/{id}", name="show")
     * @param Restaurant $restaurant
     * @return Response
     */
    public function show(Restaurant $restaurant)
    {
        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant
        ]);
    }

   /**
    * create new restaurant
    *
    *@Route("/new", name="new")
    *
    * @param EntityManagerInterface $manager
    * @param Request $request
    * @param UserPasswordEncoderInterface $encoder
    * @return void
    */
    public function new(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $restaurant = new Restaurant();
        
        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && ! $this->validationForm->phoneNumberValidate($form->get('phone')->getData()))
        {
            $form->get('phone')->addError(new FormError("Entrez un numéro de téléphone au format valide"));
        }
        
        $imageLogoFile = $form->get("imageLogo")->getData();

        if ($form->isSubmitted() && $imageLogoFile === null)
        {
            $form->get('imageLogo')->addError(new FormError("l'image du réstaurant est obligatoire!"));
        }
        
        if ($form->isSubmitted() && $form->isValid())
        {                        
            //Upload image logo
            $imageLogoName = $this->fileUploader->upload($imageLogoFile, 'restaurants');
                
            $restaurant->setImageLogo($imageLogoName);

            $this->manager->persist($restaurant);
            $this->manager->flush();
            
            $this->addFlash('success','Nouveau réstaurant créer avec succès');
            return $this->redirectToRoute('restaurant_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('restaurant/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * edit restaurant
     *
     * @Route("/edit/{id}", name="edit")
     * 
     * @param Restaurant $restaurant
     * @param Request $request
     * @return void
     */
    public function edit(Restaurant $restaurant, Request $request, UserRepository $userRepository)
    {
        $oldImageLogo = $restaurant->getImageLogo();

        $restaurant->setImageLogo(
            new File($this->getParameter('uploads_image_directory').'/restaurants\/'.$restaurant->getImageLogo())
            );
        
        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && ! $this->validationForm->phoneNumberValidate($form->get('phone')->getData()))
        {
            $form->get('phone')->addError(new FormError("Entrez un numéro de téléphone au format valide"));
        }
        
        $imageLogoFile = $form->get("imageLogo")->getData();
        
        if ($form->isSubmitted() && $form->isValid())
        {   
            if ($imageLogoFile === null)
            {
                $restaurant->setImageLogo($oldImageLogo);
            }
            else
            {
                $imageLogoName = $this->fileUploader->upload($imageLogoFile, 'restaurants');
                
                $filetoRemove = $restaurant->getImageLogo();

                $fileSystem = new Filesystem();
                $fileSystem->remove($filetoRemove);

                $restaurant->setImageLogo($imageLogoName);
            }
            

            $users = $userRepository->findByRestaurant($restaurant->getId());
            foreach ($users as $user) {
                $user->setRestaurant(null);
                $this->manager->persist($user);
            }

            $this->manager->persist($restaurant);
            $this->manager->flush();
            
            $this->addFlash('success','Réstaurant modifier avec succès');
            return $this->redirectToRoute('restaurant_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('restaurant/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Remove restaurant
     *
     * @Route("/remove/{id}", name="remove", methods={"POST"})
     * 
     * @param Restaurant $restaurant
     * @param Request $request
     * @return void
     */
    public function remove(Restaurant $restaurant) : Response
    {
        try {
            $this->manager->remove($restaurant);
            $this->manager->flush();
            return $this->redirectToRoute('restaurant_index');
        } catch (NotFoundResourceException $th) {
            return $this->createNotFoundException("Impossible de supprmer ce restaurant");
        }
    }
}
