<?php

namespace App\Service;

use App\Entity\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha384;

class MercureCookieGenerator {

    private $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function generate(User $user)
    {
        $token = ( new Builder() )
            ->set('mercure', ['subscribe' => "http://monsite.com/restaurant/{$user->getId()}"])
            ->sign(new Sha384(), $this->secretKey)
            ->getToken();
        return "mercureAuthorization={$token}; Path=/hub; HttpOnly;";
    }

}