<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Option;
use App\Entity\OptionField;
use App\Entity\Section;
use App\Form\ArticleType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("admin/article", name="article_")
     */
class ArticleController extends AbstractController
{

    private $manager;
    private $fileUploader;

    public function __construct(EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $this->manager = $manager;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Article $article)
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/new/section/{id}", name="new")
     */
    public function new(Section $section, Request $request)
    {
        $article = new Article();
        $article->setSection($section);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $imageFile = $form->get("image")->getData();

        if ($form->isSubmitted() && $imageFile === null)
        {
            $form->get('image')->addError(new FormError("l'image de la séction est obligatoire!"));
        }

        if ($form->isSubmitted() && $form->isValid())
        {
            $fileName = $this->fileUploader->upload($imageFile, 'articles');

            $article->setImage($fileName);

            $this->manager->persist($article);
            $this->manager->flush();
            
            $this->addFlash('success','Nouveau article créer avec succès');

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Article $article, Request $request)
    {
        
        $oldImage = $article->getImage();

        $article->setImage(
            new File($article->getImage())
        );

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $imageFile = $form->get("image")->getData();

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($imageFile === null)
            {
                $article->setImage($oldImage);
            }
            else
            {
                $fileName = $this->fileUploader->upload($imageFile, 'articles');

                $filetoRemove = $article->getImage();

                $fileSystem = new Filesystem();
                $fileSystem->remove($filetoRemove);

                $article->setImage($fileName);
            }

            $this->manager->persist($article);
            $this->manager->flush();
            
            $this->addFlash('success','Article '. $article->getName() .' modifier avec succès');

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/remove", name="remove")
     */
    public function remove(Article $article)
    {
        try {
            $this->manager->remove($article);
            $this->manager->flush();
            $this->addFlash('success','Article '. $article->getName() .' supprimer avec succès');
        } catch (\Throwable $th) {
            $this->addFlash('danger', $article->getName().' possède des commandes, impossible de le supprimer');
        }
        return $this->redirectToRoute('section_show', [
            'id' => $article->getSection()->getId(),
        ]);
    }
}
