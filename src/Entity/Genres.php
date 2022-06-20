<?php

namespace App\Entity;

use App\Repository\GenresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenresRepository::class)]
class Genres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'genres', targetEntity: Manga::class)]
    private $manga;

    public function __construct()
    {
        $this->manga = new ArrayCollection();
    }

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

    /**
     * @return Collection|Manga[]
     */
    public function getManga(): Collection
    {
        return $this->manga;
    }

    public function addManga(Manga $manga): self
    {
        if (!$this->manga->contains($manga)) {
            $this->manga[] = $manga;
            $manga->setGenres($this);
        }

        return $this;
    }

    public function removeManga(Manga $manga): self
    {
        if ($this->manga->removeElement($manga)) {
            // set the owning side to null (unless already changed)
            if ($manga->getGenres() === $this) {
                $manga->setGenres(null);
            }
        }

        return $this;
    }
}
