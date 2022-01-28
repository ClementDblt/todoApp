<?php

namespace App\Entity;

use App\Repository\TodoListsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TodoListsRepository::class)]
class TodoLists
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $nbOfTasks;

    #[ORM\Column(type: 'integer')]
    private $nbOfValidatedTasks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNbOfTasks(): ?int
    {
        return $this->nbOfTasks;
    }

    public function setNbOfTasks(int $nbOfTasks): self
    {
        $this->nbOfTasks = $nbOfTasks;

        return $this;
    }

    public function getNbOfValidatedTasks(): ?int
    {
        return $this->nbOfValidatedTasks;
    }

    public function setNbOfValidatedTasks(int $nbOfValidatedTasks): self
    {
        $this->nbOfValidatedTasks = $nbOfValidatedTasks;

        return $this;
    }
}
