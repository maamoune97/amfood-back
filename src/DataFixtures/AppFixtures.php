<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\City;
use App\Entity\Island;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\OrderArticlePack;
use App\Entity\Rating;
use App\Entity\Restaurant;
use App\Entity\Section;
use App\Entity\TemporaryRestaurantPlainTextPassword;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Liior\Faker\Prices;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $faker->addProvider(new Prices($faker));

        $islands = [
            'Grd Comore' => ['Moroni', 'Mitsamiouli', 'Iconi', 'Foumbouni', 'Mbéni', 'Mvouni'] ,
            'Anjouan' => ['Mutsamudu', 'Mirontsy', 'Ouani', 'Domoni'] ,
             'Moheli' => ['Fomboni', 'Wanani', 'Mirigoni']
            ];
        
        $users = [];
        $articles = [];

        //creation user
        $user = new User();
        
        $passwordHash = $this->encoder->encodePassword($user, 'password');

        $user->setFullName('Maamoune Hassane')
             ->setPhone('0762811077')
             ->setCreatedAt(new DateTime())
             ->setEmail('maamoune97bv@gmail.com')
             ->setRoles(['ROLE_MANAGER', 'ROLE_ADMIN'])
             ->setPassword($passwordHash);

        $manager->persist($user);
        $users[] = $user;

        for ($u=0; $u < 20 ; $u++) { 
            
            $user = new User();
            $user->setFullName($faker->firstName . ' ' . $faker->lastName)
                 ->setPhone($faker->e164PhoneNumber)
                 ->setEmail($faker->freeEmail)
                 ->setCreatedAt(new DateTime())
                 ->setPassword($passwordHash);
            
            $manager->persist($user);
            $users[] = $user;
        }
        
        foreach ($islands as $island => $cities)
        {
            $isle = new Island();
            $isle->setName($island);
            
            $manager->persist($isle);

            foreach ($cities as $city)
            {
                $place = new City();
                $place->setName($city)
                      ->setIsland($isle)
                      ;
                $manager->persist($place);

                for ($r=0; $r < mt_rand(1,6); $r++)
                { 
                    $restaurant = new Restaurant();
                    $restaurant->setName($faker->company)
                               ->setPhone($faker->e164PhoneNumber)
                               ->setEmail($faker->boolean ? $faker->companyEmail : '')
                               ->setLocation($place)
                               ->setActivate($faker->boolean)
                               ->setImageLogo('default.jpg')
                               ->setSpeciality($faker->words(mt_rand(3,5), true))
                               ;
                    
                    $manager->persist($restaurant);

                    $menu = new Menu();
                    $menu->setRestaurant($restaurant)
                         ;
                    
                    $manager->persist($menu);

                    for ($s=0; $s < mt_rand(3, 6); $s++)
                    { 
                        $section = new Section();
                        $section->setName($faker->words(mt_rand(1,4), true))
                                ->setImage($faker->imageUrl(640, 480, 'food'))
                                ->setMenu($menu)
                                ;
                        $manager->persist($section);

                        for ($a=0; $a < mt_rand(3,7); $a++)
                        { 
                            $ingredient = [];
                            for ($i=0; $i < mt_rand(0, 7) ; $i++)
                            { 
                                $ingredient[] = $faker->word;
                            }
                            
                            $article = new Article();
                            $article->setName($faker->words(mt_rand(1, 4), true))
                                    ->setPrice($faker->price(1000, 5000, false, false))
                                    // ->setPrice($faker->rand(2, 200))
                                    ->setIngredient(implode(' , ', $ingredient))
                                    ->setSection($section)
                                    ;
                            
                            $manager->persist($article);

                            if ($faker->boolean(20)) {
                               $articles[] = $article; 
                            }
                            
                        }
                    }

                    for ($o=0; $o < mt_rand(0, 10); $o++)
                    {
                        $order = new Order();

                        $order->setCreatedAt(new DateTime())
                            ->setCustomer($faker->randomElement($users))
                            ->setStatus('0')
                            ->setRestaurant($restaurant)
                            ;

                            for ($oap=0; $oap < mt_rand(1,5); $oap++) {

                                $orderArticlePack = new OrderArticlePack();
                                $orderArticlePack->setArticle($faker->randomElement($articles))
                                                 ->setQuantity(mt_rand(1,4))
                                                 ->setCommand($order);
                                // $order->addOrderArticlePack($orderArticlePack);
                                $manager->persist($orderArticlePack);
                            }
                        $manager->persist($order);

                        $rating = new Rating();

                        $rating->setDeliveryManStars(mt_rand(1,5))
                               ->setDeliveryManComment($faker->words(mt_rand(3,7), true))
                               ->setRestaurantStars((mt_rand(1,5)))
                               ->setRestaurantComment($faker->words(mt_rand(3,7), true))
                               ->setOrderConcerned($order);
                        $manager->persist($rating);
                    }

                }
            }
            
        }

        $manager->flush();

    }
}
