<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\City;
use App\Entity\Delivery;
use App\Entity\Island;
use App\Entity\Order;
use App\Entity\OrderArticlePack;
use App\Entity\Rating;
use App\Entity\Restaurant;
use App\Entity\Section;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Prices($faker));

        $islands = [
            'Grd Comore' => ['Moroni', 'Mitsamiouli', 'Iconi', 'Foumbouni', 'Mbéni', 'Mvouni'],
            'Anjouan' => ['Mutsamudu', 'Mirontsy', 'Ouani', 'Domoni'],
            'Moheli' => ['Fomboni', 'Wanani', 'Mirigoni']
        ];

        $places = [];
        $users = [];
        $articles = [];

        $prices = [];

        for ($p=500; $p <= 15000; $p+=50)
        { 
            $prices[] = $p;
        }

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

        for ($u = 0; $u < 50; $u++) {

            $user = new User();
            $user->setFullName($faker->firstName . ' ' . $faker->lastName)
                ->setPhone($faker->phoneNumber)
                ->setEmail($faker->freeEmail)
                ->setCreatedAt(new DateTime())
                ->setPassword($passwordHash);

            $manager->persist($user);
            $users[] = $user;
        }

        $j = 0;
        foreach ($islands as $island => $cities) {
            $isle = new Island();
            $isle->setName($island);

            $manager->persist($isle);
            
            foreach ($cities as $city) {
                $place = new City();
                $place->setName($city)
                    ->setIsland($isle);
                $manager->persist($place);
                $places[$j][] = $place;
            }
            $j +=1;
        }

        for ($r = 0; $r < mt_rand(8, 27); $r++) {

            $numIsland = $faker->randomElement([0,1,2]);

            $restaurant = new Restaurant();
            $restaurant->setName($faker->company)
                ->setPhone($faker->e164PhoneNumber)
                ->setEmail($faker->boolean ? $faker->companyEmail : '')
                ->setLocation($faker->randomElement($places[$numIsland]))
                ->setActivate($faker->boolean)
                ->setImageLogo('media/images/static/default.jpg')
                ->setSpeciality($faker->words(mt_rand(3, 5), true));

            $manager->persist($restaurant);

            for ($s = 0; $s < mt_rand(3, 6); $s++) {
                $section = new Section();
                $section->setName($faker->words(mt_rand(1, 4), true))
                    ->setImage('media/images/static/default.jpg')
                    ->setRestaurant($restaurant);
                $manager->persist($section);

                for ($a = 0; $a < mt_rand(3, 7); $a++) {
                    $ingredient = [];
                    for ($i = 0; $i < mt_rand(0, 7); $i++) {
                        $ingredient[] = $faker->word;
                    }

                    $article = new Article();
                    $article->setName($faker->words(mt_rand(1, 4), true))
                        ->setPrice($faker->price($faker->randomElement($prices)))
                        ->setImage('media/images/static/default.jpg')
                        ->setIngredient(implode(' , ', $ingredient))
                        ->setSection($section);

                    $manager->persist($article);

                    if ($faker->boolean(20)) {
                        $articles[] = $article;
                    }
                }
            }

            for ($o = 0; $o < mt_rand(0, 10); $o++) {
                $order = new Order();

                $order->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCustomer($faker->randomElement($users))
                    ->setStatus($faker->randomElement([0,1,2,3]))
                    ->setRestaurant($restaurant);

                for ($oap = 0; $oap < mt_rand(1, 5); $oap++) {

                    $orderArticlePack = new OrderArticlePack();
                    $orderArticlePack->setArticle($faker->randomElement($articles))
                        ->setQuantity(mt_rand(1, 4))
                        ->setCommand($order);
                    // $order->addOrderArticlePack($orderArticlePack);
                    $manager->persist($orderArticlePack);
                }

                //creation de la livraison

                $delivery = new Delivery();
                $delivery->setAddress($faker->address)
                         ->setCommand($order)
                         ->setCity($faker->randomElement($places[$numIsland]))
                         ->setLatitude($faker->randomFloat(6))
                         ->setLongitude($faker->randomFloat(6))
                         ;
        
                $manager->persist($delivery);

                $manager->persist($order);

                $rating = new Rating();

                $rating->setDeliveryManStars(mt_rand(1, 5))
                    ->setDeliveryManComment($faker->words(mt_rand(3, 7), true))
                    ->setRestaurantStars((mt_rand(1, 5)))
                    ->setRestaurantComment($faker->words(mt_rand(3, 7), true))
                    ->setOrderConcerned($order);
                $manager->persist($rating);
            }
        }

        $manager->flush();
    }
}
