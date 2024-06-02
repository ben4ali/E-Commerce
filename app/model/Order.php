<?php

namespace model;

class Order
{
    private int $id;
    private int $user_id;
    private int $delivery_address_id;
    private int $transaction_id;
    private string $created_at;
    private string $order_status;
    private string $special_instruction;

    public function __construct(int    $id, int $user_id, int $delivery_address_id, int $transaction_id, string $created_at,
                                string $order_status, string $special_instruction)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->delivery_address_id = $delivery_address_id;
        $this->transaction_id = $transaction_id;
        $this->created_at = $created_at;
        $this->order_status = $order_status;
        $this->special_instruction = $special_instruction;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getDeliveryAddressId(): int
    {
        return $this->delivery_address_id;
    }

    public function setDeliveryAddressId(int $delivery_address_id): void
    {
        $this->delivery_address_id = $delivery_address_id;
    }

    public function getTransactionId(): int
    {
        return $this->transaction_id;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getOrderStatus(): string
    {
        return $this->order_status;
    }

    public function setOrderStatus(string $order_status): void
    {
        $this->order_status = $order_status;
    }

    public function getSpecialInstruction(): string
    {
        return $this->special_instruction;
    }

    public function setSpecialInstruction(string $special_instruction): void
    {
        $this->special_instruction = $special_instruction;
    }
}