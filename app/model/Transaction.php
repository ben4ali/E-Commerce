<?php

namespace model;

class Transaction
{
    private int $id;
    private int $userId;
    private int $billingAddress;
    private int $shippingAddress;
    private string $paymentMethod;
    private float $total;

    public function __construct(int $id, int $userId, int $billingAddress, int $shippingAddress, string $paymentMethod, float $total)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
        $this->paymentMethod = $paymentMethod;
        $this->total = $total;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBillingAddress(): int
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(int $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    public function getShippingAddress(): int
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(int $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    /**
     * Calcul le total avec les taxes de 14.975%, la réalité est que nous devrions utiliser
     * un API pour les taxes selon l'adresse de l'utilisateur mais pour des raisons de
     * simplicité, nous utilisons exclusivement celles du Quebec.
     * @return float
     */
    public function calculate(): float
    {
        return $this->total * (5.0 + 9.975);
    }

}
