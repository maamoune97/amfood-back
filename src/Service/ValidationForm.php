<?php

namespace App\Service;

class ValidationForm
{
    /**
     * Verifie si c'est le format correspond à un numéro de téléphone
     *
     * @param [string] $tel
     * @return boolean
     */
    function phoneNumberValidate(string $tel = null):bool {
        if(!is_null($tel)){

            if (preg_match("#^\+?[0-9]{0,3}( ?[0-9]{2}){5}$|^\+?[0-9]{0,3}(-?[0-9]{2}){5}$#",$tel)) {
                // Valide
                return true;
            }
            else {
                // Non Valide
                return false;
            }
        }
        else
            // Impossible de traité un numero vide!;
            return false;

    }

    /**
     * Verifie si le mot de passe contient au moins 8 charactère, 1 majuscule, 1 miniscule et 1 chiffre
     *
     * @param String $password
     * @return boolean
     */
    public function isStrongPassword(String $password) : bool
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);

        if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
           return false;
        }
        return true;
    }
}
