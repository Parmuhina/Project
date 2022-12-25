<?php

namespace App\Models;

class SymbolObject
{
    private int $id;
    private string $username;
    private int $cash;
    private ?string $symbol;
    private ?float $price;
    private ?float $value;
    private ?float $paymount;
    private ?int $userId;

    public function __construct(
        int $id,
        string $username,
        int $cash,
        ?string $symbol,
        ?float $price,
        ?float $value,
        ?float $paymount,
        ?int $userId
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->cash = $cash;
        $this->symbol = $symbol;
        $this->price = $price;
        $this->value = $value;
        $this->paymount = $paymount;
        $this->userId=$userId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getCash(): int
    {
        return $this->cash;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function getPaymount(): ?float
    {
        return $this->paymount;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

}