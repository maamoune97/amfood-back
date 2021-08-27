<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ValidationForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UpdatePasswordController extends AbstractController
{
    private EntityManagerInterface $manager;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
    }

    public function __invoke(User $user, Request $request, ValidationForm $validator)
    {
        $passwords = $request->get("data");

        $isValid = $this->encoder->isPasswordValid($user, $passwords->getOld());
        if ($isValid)
        {
            if ($validator->isStrongPassword($passwords->getNew()))
            {
                $hash = $this->encoder->encodePassword($user, $passwords->getNew());
                $user->setPassword($hash);
    
                $this->manager->persist($user);
                $this->manager->flush();
                return $this->json(['error' => false, 'errorCode' => null, "errorMessage" => ""]);
            }
            else
            {
                return $this->json(['error' => true, 'errorCode' => 2,"errorMessage" => "the password must be at least 8 characters long, 1 uppercase and 1 lowercase"]);
            }
        }
        else
        {
            return $this->json(['error' => true, 'errorCode' => 1, "errorMessage" => "Invalid Password"]);
        }

    }
}
