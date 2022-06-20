<?php

namespace App\Entity;

use App\Entity\Editeur;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MangaRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MangaRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Manga
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Editeur::class, inversedBy: 'mangas',)]
    #[ORM\JoinColumn(nullable: true)]
    private $editeur;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\Column(type: 'string', length: 255)]
    private $nomDuCreateur;

    #[ORM\Column(type: 'string', length: 255)]
    private $dessin;

    #[ORM\Column(type: 'string', length: 255)]
    private $statut;

    #[ORM\OneToMany(mappedBy: 'manga', targetEntity: Chapitre::class)]
    private $chapitres;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'datetime_immutable')]
    private $debut;

    #[ORM\ManyToOne(targetEntity: Genres::class, inversedBy: 'manga')]
    private $genres;

    #[ORM\PrePersist]
    public function prePersist()
    {
        if(empty($this->debut))
        {
            $this->debut = new DateTimeImmutable();
        }
    }

    public function __construct()
    {
        $this->chapitres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNomDuCreateur(): ?string
    {
        return $this->nomDuCreateur;
    }

    public function setNomDuCreateur(string $nomDuCreateur): self
    {
        $this->nomDuCreateur = $nomDuCreateur;

        return $this;
    }

    public function getDessin(): ?string
    {
        return $this->dessin;
    }

    public function setDessin(string $dessin): self
    {
        $this->dessin = $dessin;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
    
    /**
     * @return Collection|Chapitre[]
     */
    public function getChapitres(): Collection
    {
        return $this->chapitres;
    }

    public function addChapitre(Chapitre $chapitre): self
    {
        if (!$this->chapitres->contains($chapitre)) {
            $this->chapitres[] = $chapitre;
            $chapitre->setManga($this);
        }

        return $this;
    }

    public function removeChapitre(Chapitre $chapitre): self
    {
        if ($this->chapitres->removeElement($chapitre)) {
            // set the owning side to null (unless already changed)
            if ($chapitre->getManga() === $this) {
                $chapitre->setManga(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDebut(): ?\DateTimeImmutable
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeImmutable $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getGenres(): ?Genres
    {
        return $this->genres;
    }

    public function setGenres(?Genres $genres): self
    {
        $this->genres = $genres;

        return $this;
    }
}
