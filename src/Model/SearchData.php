<?php
namespace App\Model;
use App\Entity\user;

class SearchData
{
    /** @var int */
    public $page = 1;

    /** @var string */
    public string $q = '';

    /** @var string */
    public string $user = '';

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
