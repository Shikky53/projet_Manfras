<?php

namespace App\Entity;

use App\Repository\ChapitreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapitreRepository::class)]
class Chapitre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Manga::class, inversedBy: 'chapitres')]
    #[ORM\JoinColumn(nullable: false)]
    private $manga;

    #[ORM\Column(type: 'integer')]
    private $numero;

    #[ORM\OneToMany(mappedBy: 'chapitre', targetEntity: Scan::class)]
    private $scans;

    public function __construct()
    {
        $this->scans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManga(): ?Manga
    {
        return $this->manga;
    }

    public function setManga(?Manga $manga): self
    {
        $this->manga = $manga;

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

    /**
     * @return Collection|Scan[]
     */
    public function getScans(): Collection
    {
        return $this->scans;
    }

    public function addScan(Scan $scan): self
    {
        if (!$this->scans->contains($scan)) {
            $this->scans[] = $scan;
            $scan->setChapitre($this);
        }

        return $this;
    }

    public function removeScan(Scan $scan): self
    {
        if ($this->scans->removeElement($scan)) {
            // set the owning side to null (unless already changed)
            if ($scan->getChapitre() === $this) {
                $scan->setChapitre(null);
            }
        }

        return $this;
    }

    public function getScansSort() 
    {
        $scans = $this->getScans();
        
        $scanInfo = [];

        foreach($scans as $scan)
        {
            $detail = [
                'numero' => $scan->getNumero(),
                'image' => $scan->getImage(),
            ];

            $scanInfo[] = $detail;
        }
        sort($scanInfo);

        return $scanInfo;
    }

    public function getFirstImageScan()
    {
        $scanInfo = $this->getScansSort();

        if(count($scanInfo) > 0)
        {
            $image = $scanInfo[0]['image'];

            return $image;
        }
    }
}
