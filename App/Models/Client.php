<?php

namespace App\Models;

use App\Models\Model;

class Client extends Model
{
    private string $name = '';
    private string $cpf = '';
    private string $address = '';
    private string $id = '';

    public function __construct(
        string $id = '',
        string $name = '',
        string $cpf = '',
        string $address = ''
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->cpf = $this->formatCPF($cpf);
        $this->address = $address;

        parent::__construct();
    }

    /**
     * Get client id
     * 
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set client id
     * 
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

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
        $this->cpf = $this->formatCPF($cpf);
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

    /**
     * Find client by cpf
     * 
     * @param string $cpf
     * @return array
     */
    public function findByCpf(string $cpf): array
    {
        $result = $this->getConnection()->select(
            [
                '*'
            ],
            'client',
            "cpf = :cpf",
            ['cpf' => $this->formatCPF($cpf)]
        );
        
        if (isset($result) && count($result) > 0) {
            return array_pop($result);
        }

        return [];
    }

    /**
     * Create client by cpf
     * 
     * @param string $cpf
     * @return string|false
     */
    public function createByCpf(string $cpf, string $name): string|false
    {
        return $this->getConnection()->insert(
            [
                'name' => $name,
                'cpf' => $this->formatCPF($cpf),
                'address' => $this->address ?: ''
            ],
            'client'
        );
    }

    /**
     * Format client cpf
     *
     * @param string $cpf
     * @return string
     */
    public function formatCPF($cpf): string
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        $formattedCpf = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    
        return $formattedCpf;
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
     * Insert client
     * 
     * @return string|false
     */
    public function insert(): string|false
    {
        return $this->getConnection()->insert([
            'name' => $this->getName(),
            'cpf' => $this->getCpf(),
            'address' => $this->getAddress()
        ], 'client');
    }

    /**
     * Updates client
     *
     * @return bool
     */
    public function update(): bool
    {
        return $this->getConnection()->update([
            'name' => $this->getName(),
            'cpf' => $this->getCpf(),
            'address' => $this->getAddress()
        ], 'client', "id = :id", ['id' => $this->getId()]);
    }

    /**
     * Deletes client
     *
     * @return void
     */
    public function delete()
    {
        $this->getConnection()->delete('client', ['id' => $this->getId()]);
    }

    /**
     * Loads client by id
     *
     * @param string $id
     * @return $this
     */
    public function load(string $id): self
    {
        $result = $this->getConnection()->select(["*"], 'client', "id = :id", ['id' => $id]);
        if (count($result) === 0) {
            return $this;
        }

        $client = array_pop($result);
        $this->setName($client['name']);
        $this->setCpf($client['cpf']);
        $this->setAddress($client['address']);
        $this->setId($client['id']);

        return $this;
    }

    /**
     * Get all clients
     *
     * @return array
     */
    public function getAllClients(): array
    {
        $result = $this->getConnection()->select(["*"], 'client');
        if (count($result) === 0) {
            return $result;
        }

        return $this->hydrateList($result);
    }

    /**
     * Hydrates client list
     *
     * @param array $data
     * @return array
     */
    public function hydrateList($data): array
    {
        $hydratedList = [];
        foreach ($data as $item) {
            $hydratedList[] = new Client(
                $item['id'],
                $item['name'], 
                $item['cpf'], 
                $item['address']
            );
        }

        return $hydratedList;
    }

    /**
     * Returns client as array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'cpf' => $this->getCpf(),
            'address' => $this->getAddress(),
            'id' => $this->getId()
        ];
    }
}