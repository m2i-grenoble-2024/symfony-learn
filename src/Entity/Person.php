<?php

namespace App\Entity;

class Person {
    private ?int $id;
    private ?string $name;
    /**
     * Pas obligé d'avoir cette propriété dans la Person, surtout si on gère avec
     * des DogRepository::findByPerson et PersonRepository::findByDog, elle n'est
     * utile que si on fait des requêtes avec jointures dans nos repo
     * @var array<Dog>
     */
    private array $dogs = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of dogs
     *
     * @return array<Dog>
     */
    public function getDogs()
    {
        return $this->dogs;
    }

    public function addDog(Dog $dog):self {
        $this->dogs[] = $dog;
        return $this;
    }

    /**
     * Set the value of dogs
     *
     * @param array<Dog> $dogs
     */
    public function setDogs($dogs): self
    {
        $this->dogs = $dogs;

        return $this;
    }
}