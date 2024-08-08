<?php

namespace App\Models;

use App\Models\Model;

class Product extends Model
{
    private string $code = "";
    private string $description = "";
    private string $status = "";
    private string $warrantyTime = "";

    /**
     * Get product code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set product code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get product description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set product description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get product status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set product status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get product warranty time
     *
     * @return string
     */
    public function getWarrantyTime(): string
    {
        return $this->warrantyTime;
    }

    /**
     * Set product warranty time
     *
     * @param string $warrantyTime
     * @return $this
     */
    public function setWarrantyTime($warrantyTime): self
    {
        $this->warrantyTime = $warrantyTime;
        return $this;
    }

    /**
     * Saves model data to database
     *
     * @return void
     */
    public function save()
    {
        parent::getConnection()->insert([
            'code' => $this->getCode(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus(),
            'warranty_time' => $this->getWarrantyTime()
        ], 'product');
    }
}