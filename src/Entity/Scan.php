<?php

namespace App\Entity;

use App\Repository\ScanRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScanRepository::class)]
class Scan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: chapitre::class, inversedBy: 'scans')]
    #[ORM\JoinColumn(nullable: false)]
    private $chapitre;

    #[ORM\Column(type: 'integer')]
    private $numero;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChapitre(): ?chapitre
    {
        return $this->chapitre;
    }

    public function setChapitre(?chapitre $chapitre): self
    {
        $this->chapitre = $chapitre;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }
}
