<?php

namespace App\Models;

use App\Models\Model;

class Client extends Model
{
    private string $name;
    private string $cpf;
    private string $address;

    /**
     * Get client name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set client name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get client cpf
     *
     * @return string
     */
    public function getCpf(): string
    {
        return $this->cpf;
    }

    /**
     * Set client cpf
     *
     * @param string $cpf
     * @return $this
     */
    public function setCpf($cpf): self
    {
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * Get client address
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Set client address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address): self
    {
        $this->address = $address;
        return $this;
    }
}