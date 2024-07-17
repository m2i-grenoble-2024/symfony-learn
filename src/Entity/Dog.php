<?php

namespace App\Entity;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class Dog {
    private ?int $id;
    #[NotBlank]
    // #[Length(min: 2)] //On peut avoir autant de validateur qu'on veut sur une même propriété
    private string $name;
    #[NotBlank]
    private string $breed;
    #[Range(max:'now')]
    private DateTimeImmutable $birthdate;

    public function __construct(string $name,string $breed, DateTimeImmutable $birthdate, ?int $id = null ) {
        $this->id = $id;
        $this->name = $name;
        $this->breed = $breed;
        $this->birthdate = $birthdate;
    }

    //Avec cette version, pas la peine de déclarer les propriétés en premier
    /*
     public function __construct(
        private string $name,
        private string $breed, 
        private DateTime $birthdate, 
        private?int $id = null ) {
        
    }
        */

    public function getId(): ?int {
        return $this->id;
    }    
    public function getName(): string {
        return $this->name;
    }  
    public function getBreed(): string {
        return $this->breed;
    }  
    public function getBirthdate(): DateTimeImmutable {
        return $this->birthdate;
    }      
    public function setId(?int $id){
        $this->id = $id;
    }    
    public function setName( string $name){
        $this->name = $name;
    }  
    public function setBreed( string $breed){
        $this->breed = $breed;
    }  
    public function setBirthdate( DateTimeImmutable $birthdate){
        $this->birthdate = $birthdate;
    }    
}