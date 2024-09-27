<?php

namespace App\Entity;

use App\Repository\TaxBandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxBandRepository::class)]
class TaxBand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?float $lowerLimit = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?float $upperLimit = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $rate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLowerLimit(): ?float
    {
        return $this->lowerLimit;
    }

    public function setLowerLimit(float $lowerLimit): static
    {
        $this->lowerLimit = $lowerLimit;

        return $this;
    }

    public function getUpperLimit(): ?float
    {
        return $this->upperLimit;
    }

    public function setUpperLimit(?float $upperLimit): static
    {
        $this->upperLimit = $upperLimit;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }
}
