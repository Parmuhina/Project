<?php

namespace App\Models;

class RequestObject
{
    private string $name;
    private string $symbol;
    private ?int $maxSupply;
    private int $circulatingSupply;
    private string $lastUpdated;
    private float $price;
    private float $volume24h;
    private float $volumeChange24h;
    private float $percentChange1h;
    private float $percentChange24h;
    private string $logo;

    public function __construct(
        string $name,
        string $symbol,
        ?int $maxSupply,
        int $circulatingSupply,
        string $lastUpdated,
        float $price,
        float $volume24h,
        float $volumeChange24h,
        float $percentChange1h,
        float $percentChange24h,
        string $logo
    )
    {
        $this->name = $name;
        $this->symbol = $symbol;
        $this->maxSupply = $maxSupply;
        $this->circulatingSupply = $circulatingSupply;
        $this->lastUpdated = $lastUpdated;
        $this->price = $price;
        $this->volume24h = $volume24h;
        $this->volumeChange24h = $volumeChange24h;
        $this->percentChange1h = $percentChange1h;
        $this->percentChange24h = $percentChange24h;
        $this->logo=$logo;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getMaxSupply(): ?int
    {
        return $this->maxSupply;
    }

    public function getCirculatingSupply(): int
    {
        return $this->circulatingSupply;
    }

    public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getVolume24h(): float
    {
        return $this->volume24h;
    }

    public function getVolumeChange24h(): float
    {
        return $this->volumeChange24h;
    }

    public function getPercentChange1h(): float
    {
        return $this->percentChange1h;
    }

    public function getPercentChange24h(): float
    {
        return $this->percentChange24h;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }
}