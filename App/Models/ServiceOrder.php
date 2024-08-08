<?php

namespace App\Models;

use App\Models\Model;

class ServiceOrder extends Model
{
    private string $orderNumber;
    private string $openingDate;
    private string $consumerName;
    private string $consumerCpf;
    private array $products;

    /**
     * Get service order number
     *
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    /**
     * Set service order number
     *
     * @param string $orderNumber
     * @return $this
     */
    public function setOrderNumber($orderNumber): self
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    /**
     * Get service order opening date
     *
     * @return string
     */
    public function getOpeningDate(): string
    {
        return $this->openingDate;
    }

    /**
     * Set service order opening date
     *
     * @param string $openingDate
     * @return $this
     */
    public function setOpeningDate($openingDate): self
    {
        $this->openingDate = $openingDate;
        return $this;
    }

    /**
     * Get service order consumer
     *
     * @return string
     */
    public function getConsumerName(): string
    {
        return $this->consumerName;
    }

    /**
     * Set service order consumer name
     *
     * @param string $consumerName
     * @return $this
     */
    public function setConsumerName($consumerName): self
    {
        $this->consumerName = $consumerName;
        return $this;
    }

    /**
     * Get service order consumer cpf
     *
     * @return string
     */
    public function getConsumerCpf(): string
    {
        return $this->consumerCpf;
    }

    /**
     * Set service order consumer cpf
     *
     * @param string $consumerCpf
     * @return $this
     */
    public function setConsumerCpf($consumerCpf): self
    {
        $this->consumerCpf = $consumerCpf;
        return $this;
    }

    /**
     * Get service order products
     *
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Add service order product
     *
     * @param int $id
     * @return $this
     */
    public function addProduct($id): self
    {
        array_push($this->products, $id);
        return $this;
    }

    /**
     * Remove service order product
     *
     * @param int $id
     * @return $this
     */
    public function removeProduct($id): self
    {
        if ($index = array_search($id, $this->getProducts())) {
            unset($index);
        }

        return $this;
    }
}