<?php
require_once './assets/head.php';

use App\Models\ServiceOrder;

$serviceOrder = new ServiceOrder();
/** @var ServiceOrder[] $allServiceOrders */
$allServiceOrders = $serviceOrder->getAllServiceOrders();
?>
<div class="container content mt-4">
        <h1 class="title">Ordens de Serviço</h1>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary mb-3 fw-bold" data-bs-toggle="modal" data-bs-target="#serviceOrderModal" data-bs-action="save">
                Nova Ordem de Serviço
            </button>
        </div>
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Número da Ordem</th>
                            <th>Nome do Consumidor</th>
                            <th>Data de Abertura</th>
                            <th>CPF Consumidor</th>
                            <th>Produto</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($allServiceOrders as $serviceOrder):
                        ?>
                        <tr>
                            <td><?= $serviceOrder->getOrderNumber() ?></td>
                            <td><?= $serviceOrder->getConsumerName() ?></td>
                            <td>
                                <?php
                                    $data = new DateTimeImmutable($serviceOrder->getOpeningDate());
                                    echo $data->format("d/m/Y");
                                ?>
                            </td>
                            <td><?= $serviceOrder->getConsumerCpf() ?></td>
                            <td>
                                <?php
                                    $productsName = [];
                                    foreach ($serviceOrder->getProducts() as $product){
                                        $productsName[] = $product->getCode();
                                    }

                                    echo implode(", ", $productsName);
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#serviceOrderModal" data-bs-action="save" data-bs-product-id="<?=$product->getId()?>">Edit</button>
                                <button class="btn btn-sm btn-danger fw-bold">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="serviceOrderModal" tabindex="-1" aria-labelledby="serviceOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceOrderModalLabel">Criar Ordem de Serviço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="service_order_form">
                <div class="modal-body">
                        <input type="hidden" id="service_order_form_action">
                        <input type="hidden" id="serviceOrderId">
                        <div class="mb-3">
                            <label for="orderNumber" class="form-label">Número da Ordem</label>
                            <input type="number" class="form-control" id="orderNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="openingDate" class="form-label">Data de Abertura</label>
                            <input type="date" class="form-control" id="openingDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="consumerName" class="form-label">Nome do Consumidor</label>
                            <input type="text" class="form-control" id="consumerName" required>
                        </div>
                        <div class="mb-3">
                            <label for="consumerCpf" class="form-label">CPF Consumidor</label>
                            <input type="text" class="form-control" id="consumerCpf" required>
                        </div>
                        <div class="mb-3">
                            <label for="products" class="form-label">Produto(s)</label>
                            <select class="form-select" id="products" multiple required>
                                <option selected>Selecione o(s) produto(s)</option>
                                <option value="1">Prod1</option>
                                <option value="2">Prod2</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="./assets/js/service_order.js"></script>
<?php require_once './assets/footer.php' ?>