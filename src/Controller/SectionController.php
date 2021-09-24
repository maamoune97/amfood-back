<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Section;
use App\Form\SectionType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/section", name="section_")
 */
class SectionController extends AbstractController
{
    private $manager;
    private $fileUploader;

    public function __construct(EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $this->manager = $manager;
        $this->fileUploader = $fileUploader;
    }

    /**
     * Create new section
     * 
     * @Route("/new/restaurant/{id}/", name="new")
     * 
     * @param Restaurant $restaurant
     * @param Request $request
     * @return Response
     */
    public function new(Restaurant $restaurant, Request $request)
    {
        
        $section = new Section();
        $section->setRestaurant($restaurant);
                
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        $imageFile = $form->get("image")->getData();

        if ($form->isSubmitted() && $imageFile === null)
        {
            $form->get('image')->addError(new FormError("l'image de la séction est obligatoire!"));
        }

        if ($form->isSubmitted() && $form->isValid())
        {
            $fileName = $this->fileUploader->upload($imageFile, 'sections');

            $section->setImage($fileName);

            $this->manager->persist($section);
            $this->manager->flush();
            
            $this->addFlash('success','Nouveau séction créer avec succès');

            return $this->redirectToRoute('restaurant_show', ['id' => $restaurant->getId()]);
        }
        
        return $this->render('section/new.html.twig', [
            'form'  =>  $form->createView(),

        ]);
    }
    
    /**
     * Show section and his articles
     * @Route("/show/{id}", name="show")
     * @param Section $section
     * @return Response
     */
    public function show(Section $section)
    {
        return $this->render('section/show.html.twig', [
            'section' => $section,
        ]);
    }

    /**
     * Remove Section
     *@Route("/{id}/delete", name="delete")
     * @param Section $section
     * @return Response
     */
    public function remove(Section $section)
    {
        try {
            $this->manager->remove($section);
            $this->manager->flush();
            $this->addFlash('success','Section '. $section->getName() .' supprimer avec succès');
        } catch (\Throwable $th) {
            $this->addFlash('danger',$section->getName() .' possède des articles avec des commandes, impossible de le supprimer');
        }
        return $this->redirectToRoute('restaurant_show', [
            'id' => $section->getRestaurant()->getId(),
        ]);
    }

    /**
     * edit section
     * @Route("/{id}/edit", name="edit")
     *
     * @param Request $request
     * @param Section $section
     * @return Response
     */
    public function edit(Request $request, Section $section)
    {
        $oldImage = $section->getImage();

        $section->setImage(
            new File($section->getImage())
        );
        
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        $imageFile = $form->get("image")->getData();

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($imageFile === null)
            {
                $section->setImage($oldImage);
            }
            else
            {
                $newFileName = $this->fileUploader->upload($imageFile, 'sections');

                $filetoRemove = $section->getImage();

                $fileSystem = new Filesystem();
                $fileSystem->remove($filetoRemove);

                $section->setImage($newFileName);
            }

            $this->manager->persist($section);
            $this->manager->flush();
            
            $this->addFlash('success','Séction '. $section->getName() .' modifier avec succès');

            return $this->redirectToRoute('restaurant_show', ['id' => $section->getRestaurant()->getId()]);
        }
        
        return $this->render('section/edit.html.twig', [
            'form'  =>  $form->createView(),

        ]);
    }
    

}

