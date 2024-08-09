<?php

namespace App\Models;

use App\Models\Model;

class Product extends Model
{
    private string $id = "";
    private string $code = "";
    private string $description = "";
    private string $status = "";
    private string $warrantyTime = "";

    public function __construct(
        string $code = '',
        string $description = '', 
        string $status = '', 
        string $warrantyTime = '',
        string $id = ''
    )
    {
        $this->code = $code;
        $this->description = $description;
        $this->status = $status;
        $this->warrantyTime = $warrantyTime;
        $this->id = $id;

        parent::__construct();
    }

    /**
     * Get product id
     * 
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set product id
     * 
     * @param string $id
     * @return $this
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

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
     * @return string|bool
     */
    public function save(): string|bool
    {
        if (empty($this->getId())) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    /**
     * Get all products
     *
     * @return array
     */
    public function getAllProducts(): array
    {
        $result = $this->getConnection()->select(["*"], 'product');
        if (count($result) === 0) {
            return $result;
        }

        return $this->hydrateList($result);
    }


    /**
     * Hydrates product list
     *
     * @param array $data
     * @return array
     */
    public function hydrateList($data): array
    {
        $hydratedList = [];
        foreach ($data as $item) {
            $hydratedList[] = new Product(
                $item['code'], 
                $item['description'], 
                $item['status'], 
                $item['warranty_time'],
                $item['id']
            );
        }

        return $hydratedList;
    }

    /**
     * Loads product by id
     *
     * @param string $id
     * @return $this
     */
    public function load(string $id): self
    {
        $result = $this->getConnection()->select(["*"], 'product', "id = :id", ['id' => $id]);
        if (count($result) === 0) {
            return $this;
        }

        $product = array_pop($result);
        $this->setCode($product['code']);
        $this->setDescription($product['description']);
        $this->setStatus($product['status']);
        $this->setWarrantyTime($product['warranty_time']);
        $this->setId($product['id']);
        return $this;
    }

    /**
     * Insert product
     * 
     * @return string|false
     */
    public function insert(): string|false
    {
        return $this->getConnection()->insert([
            'code' => $this->getCode(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus(),
            'warranty_time' => $this->getWarrantyTime()
        ], 'product');
    }

    /**
     * Updates product
     *
     * @return bool
     */
    public function update(): bool
    {
        return $this->getConnection()->update([
            'code' => $this->getCode(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus(),
            'warranty_time' => $this->getWarrantyTime()
        ], 'product', "id = :id", ['id' => $this->getId()]);
    }

    /**
     * Deletes product
     *
     * @return void
     */
    public function delete()
    {
        $this->getConnection()->delete('product', ['id' => $this->getId()]);
    }

    /**
     * Returns product as array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus(),
            'warranty_time' => $this->getWarrantyTime(),
            'id' => $this->getId()
        ];
    }
}