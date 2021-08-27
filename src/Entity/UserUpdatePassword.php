<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
/**
 * 
 */
/**
 * @ApiResource(
 *  collectionOperations={
 *  "UPDATE_PASSWOD" = {
 *      "method" = "POST",
 *      "path" = "/users/{id}/update-password",
 *      "controller" = "App\Controller\UpdatePasswordController",
 *      
 *      }
 * },
 * )
 */
class UserUpdatePassword
{
    /**
     * 
     */
    private $id;

    /**
     * 
     */
    private $old;

    /**
     * 
     */
    private $new;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOld(): ?string
    {
        return $this->old;
    }

    public function setOld(string $old): self
    {
        $this->old = $old;

        return $this;
    }

    public function getNew(): ?string
    {
        return $this->new;
    }

    public function setNew(string $new): self
    {
        $this->new = $new;

        return $this;
    }
}
