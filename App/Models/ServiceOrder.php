<?php

namespace App\Models;

use App\Models\Model;
use Exception;

class ServiceOrder extends Model
{
    private string $orderNumber = '';
    private string $openingDate = '';
    private string $consumerId = '';
    private string $consumerName = '';
    private string $consumerCpf = '';
    private array $products = [];
    private string $id = '';

    public function __construct(
        string $orderNumber = '',
        string $openingDate = '',
        string $consumerId = '',
        string $consumerName = '',
        string $consumerCpf = '',
        string $id = '',
        array $products = []
    )
    {
        $this->orderNumber = $orderNumber;
        $this->openingDate = $openingDate;
        $this->consumerId = $consumerId;
        $this->consumerName = $consumerName;
        $this->consumerCpf = $consumerCpf;
        $this->id = $id;
        $this->products = $products;

        parent::__construct();
    }

    /**
     * Get service order id
     * 
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set service order id
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
     * Get service order consumer id
     * 
     * @return string
     */
    public function getConsumerId(): string
    {
        return $this->consumerId;
    }

    /**
     * Set service order consumer id
     * 
     * @param string $consumerId
     * @return $this
     */
    public function setConsumerId($consumerId): self
    {
        $this->consumerId = $consumerId;
        return $this;
    }

    /**
     * Get service order consumer name
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
     * Set service order products
     *
     * @param array $products
     * @return self
     */
    public function setProducts($products): self
    {
        foreach ($products as $product)
        {
            $this->addProduct($product);
        }

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
        $products = $this->getProducts();
        array_push($products, $id);
        $this->products = $products;
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

    /**
     * Finds service orders by client cpf
     *
     * @param string $cpf
     * @return array
     */
    public function findClientByCpf($cpf): array
    {
        $client = new Client();
        return $client->findByCpf($cpf);
    }

    /**
     * Finds service orders by client id
     *
     * @param string $id
     * @return array
     */
    public function findByClientId($id): array
    {
        return $this->getConnection()->select(
            ['*'], 
            'service_order', 
            "consumer_id = :id", 
            ['id' => $id]
        );
    }

    /**
     * Finds service orders by product id
     *
     * @param string $id
     * @return array
     */
    public function findByProductId($id): array
    {
        return $this->getConnection()->select(
            ['*'], 
            'service_order_product', 
            "product_id = :id", 
            ['id' => $id]
        );
    }

    /**
     * Creates client by cpf
     *
     * @param string $cpf
     * @return integer|false
     */
    private function createClientByCpf($cpf, $name): int|false
    {
        $client = new Client();
        return $client->createByCpf($cpf, $name);
    }

    /**
     * Saves model data to database
     *
     * @return string|bool
     */
    public function save()
    {
        if (empty($this->getId())) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    public function update()
    {
        $client = $this->findClientByCpf($this->getConsumerCpf());
        if (count($client) > 0) {
            $this->setConsumerId($client['id']);
        } else {
            $clientId = $this->createClientByCpf(
                $this->getConsumerCpf(),
                $this->getConsumerName()
            );
            $this->setConsumerId($clientId);
        }

        try {
            $this->getConnection()->beginTransaction();
    

            $this->getConnection()->delete(
                'service_order_product', 
                'service_order_id = :service_order_id', 
                ['service_order_id' => $this->getId()]
            );

            $this->getConnection()->update([
                    'order_number' => $this->getOrderNumber(),
                    'opening_date' => $this->getOpeningDate(),
                    'consumer_id' => $this->getConsumerId(),
                    'consumer_name' => $this->getConsumerName(),
                    'consumer_cpf' => $this->getConsumerCpf()
                ], 
                'service_order',
                "id = :id",
                ['id' => $this->getId()]
            );

            $products = $this->getProducts();
            foreach ($products as $product) {
                $this->saveProduct($product->getId(), $this->getId());
            }

            $this->getConnection()->commit();
        } catch (Exception $e) {
            $this->getConnection()->rollback();
            throw $e;
        }
    }

    public function insert()
    {
        $client = $this->findClientByCpf($this->getConsumerCpf());
        if (count($client) > 0) {
            $this->setConsumerId($client['id']);
        } else {
            $clientId = $this->createClientByCpf(
                $this->getConsumerCpf(),
                $this->getConsumerName()
            );
            $this->setConsumerId($clientId);
        }

        try {
            $this->getConnection()->beginTransaction();
    
            $serviceOrderId = $this->getConnection()->insert([
                'order_number' => $this->getOrderNumber(),
                'opening_date' => $this->getOpeningDate(),
                'consumer_id' => $this->getConsumerId(),
                'consumer_name' => $this->getConsumerName(),
                'consumer_cpf' => $this->getConsumerCpf()
            ], 'service_order');

            $products = $this->getProducts();
            foreach ($products as $product) {
                $this->saveProduct($product->getId(), $serviceOrderId);
            }

            $this->getConnection()->commit();
        } catch (Exception $e) {
            $this->getConnection()->rollback();
            throw $e;
        }
    }

    /**
     * Saves service order product
     *
     * @param string $productId
     * @param string $serviceOrderId
     * @return void
     */
    private function saveProduct(string $productId, string $serviceOrderId): void
    {
        $this->getConnection()->insert([
            'service_order_id' => $serviceOrderId,
            'product_id' => $productId
        ], 'service_order_product');
    }

    /**
     * Get all service orders
     *
     * @return array
     */
    public function getAllServiceOrders(): array
    {
        $result = $this->getConnection()->select(["*"], 'service_order');
        if (count($result) === 0) {
            return $result;
        }

        return $this->hydrateList($result);
    }

    private function hydrateList($data)
    {
        $hydratedList = [];
        foreach ($data as $item) {
             $serviceOrder = new ServiceOrder(
                $item['order_number'],
                $item['opening_date'],
                $item['consumer_id'],
                $item['consumer_name'],
                $item['consumer_cpf'],
                $item['id']
            );

            $products = $this->loadRelatedProducts($item['id']);
            $serviceOrder->setProducts($products);

            $hydratedList[] = $serviceOrder;
        }

        return $hydratedList;
    }

    /**
     * Load related products
     * 
     * @return void
     */
    private function loadRelatedProducts($serviceOrderId)
    {
        $result = $this->getConnection()->select(
            ['*'], 
            'service_order_product',
            "service_order_id = :service_order_id",
            ['service_order_id' => $serviceOrderId]
        );
        
        if (count($result) === 0) {
            return $result;
        }

        $relatedProducts = [];
        foreach ($result as $serviceOrderProduct) {
            $product = new Product();
            $relatedProducts[] = $product->load($serviceOrderProduct['product_id'])->toArray();

        }
        
        return $product->hydrateList($relatedProducts);
    }

    /**
     * Loads product by id
     *
     * @param string $id
     * @return $this
     */
    public function load(string $id): self
    {
        $result = $this->getConnection()->select(["*"], 'service_order', "id = :id", ['id' => $id]);
        if (count($result) === 0) {
            return $this;
        }

        $serviceOrder = array_pop($result);
        $this->setOrderNumber($serviceOrder['order_number']);
        $this->setOpeningDate($serviceOrder['opening_date']);
        $this->setConsumerId($serviceOrder['consumer_id']);
        $this->setConsumerName($serviceOrder['consumer_name']);
        $this->setConsumerCpf($serviceOrder['consumer_cpf']);
        $this->setId($serviceOrder['id']);

        $products = $this->loadRelatedProducts($serviceOrder['id']);
        $this->setProducts($products);

        return $this;
    }

    /**
     * Returns service order as array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'order_number' => $this->getOrderNumber(),
            'opening_date' => $this->getOpeningDate(),
            'consumer_id' => $this->getConsumerId(),
            'consumer_name' => $this->getConsumerName(),
            'consumer_cpf' => $this->getConsumerCpf(),
            'id' => $this->getId(),
            'products' => $this->getProductsIds()
        ];
    }

    /**
     * Return products ids
     *
     * @return array
     */
    private function getProductsIds(): array
    {
        $products = $this->getProducts();
        $productsIds = [];
        foreach ($products as $product) {
            $productsIds[] = $product->getId();
        }
        return $productsIds;
    }

    /**
     * Deletes service order
     *
     * @return bool
     */
    public function delete($id): bool
    {   
        try {
            $this->getConnection()->beginTransaction();
            $this->getConnection()->delete(
                'service_order_product', 
                'service_order_id = :service_order_id', 
                ['service_order_id' => $id]
            );
    
            $this->getConnection()->delete('service_order', 'id = :id', ['id' => $id]);
            $this->getConnection()->commit();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}