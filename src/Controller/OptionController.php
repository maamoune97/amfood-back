<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Option;
use App\Form\OptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/option", name="option_")
 */
class OptionController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }



    /**
     * @Route("/edit/{option}", name="edit")
     */
    public function edit(Option $option, Request $request)
    {

        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach ($option->getOptionFields() as $optionField)
            {
                $optionField->setMyOption($option);
                $this->manager->persist($optionField);
            }
            $this->manager->persist($option);
            $this->manager->flush();

            return $this->redirectToRoute('article_show', ['id'  => $option->getArticle()->getId() ] );
        }

        return $this->render('option/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/new/article/{article}", name="new")
     */
    public function new(Article $article, Request $request)
    {
        $option = new Option();

        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $option->setArticle($article);

            foreach ($option->getOptionFields() as $optionField)
            {
                $optionField->setMyOption($option);
                $this->manager->persist($optionField);
            }
            $this->manager->persist($option);
            $this->manager->flush();

            return $this->redirectToRoute('article_show', ['id'  => $article->getId() ] );
        }

        return $this->render('option/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Remove option
     * @Route("/remove/{id}", name="remove")
     * @param Option $option
     * @return Response
     */
    public function remove(Option $option)
    {
        try {
            $this->manager->remove($option);
            $this->manager->flush();
            $this->addFlash('success','Option '. $option->getName() .' supprimer avec succès');
        } catch (\Throwable $th) {
            $this->addFlash('danger', "L'option ".$option->getName() .' possède des commandes, impossible de le supprimer');
        }
        return $this->redirectToRoute('article_show', [
            'id' => $option->getArticle()->getId(),
        ]);
    }
}
