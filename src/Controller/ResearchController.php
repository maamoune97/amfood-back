<?php

namespace App\Controller;

use App\Entity\Research;
use App\Repository\CityRepository;
use App\Repository\RestaurantRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResearchController extends AbstractController
{ 
    private RestaurantRepository $restaurantRepo; 
    private CityRepository $cityRepo;
    
    public function __construct(RestaurantRepository $restaurantRepo, CityRepository $cityRepo)
    {
        $this->restaurantRepo = $restaurantRepo;
        $this->cityRepo = $cityRepo;
    }

    /**
     * @Route("api/research/{word}/in-city/{location}", name="research", methods={"GET"})
     *
     * @param string $word
     * @param int $location
     * @return void
     */
    public function __invoke(string $word, int $location): JsonResponse
    {
        $research = new Research();

        $city = $this->cityRepo->find($location);

        foreach ($this->restaurantRepo->findBy(['location' => $city]) as $restaurant)
        {
            //Add restaurant if his name match with word researched
            if ($restaurant->getName() == $word)
            {
                $research->addRestaurant($restaurant);
            }

            //Add specialtyRestaurant if his specialties contains word researched
            $specialities = explode(', ', $restaurant->getSpeciality());
            if (in_array($word, $specialities))
            {
                $research->addSpecialtyRestaurants($restaurant);    
            }

            //Add section if his name match with word researched
            foreach($restaurant->getSections() as $section)
            {
                if ($section->getName() == $word)
                {
                    $research->addSection($section);
                }

                //Add article if his name match with word researched
                foreach($section->getArticles() as $article)
                {
                    if ($article->getName() == $word)
                    {
                        $research->addArticle($article);
                    }
                }

            }
        }

        return $this->json($research, 200, [], ['groups' => 'research_read']);
    }
}
