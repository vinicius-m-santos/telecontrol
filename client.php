<?php
require_once './assets/head.php';

use App\Models\Client;

$client = new Client();
/** @var Client[] $allClients */
$allClients = $client->getAllClients();
?>
<div class="container content mt-4">
        <h1 class="title">Clientes</h1>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary mb-3 fw-bold" data-bs-toggle="modal" data-bs-target="#clientModal" data-bs-action="save">
                Novo Cliente
            </button>
        </div>
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Endereço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($allClients as $client):
                        ?>
                        <tr>
                            <td><?= $client->getName() ?></td>
                            <td><?= $client->getCpf() ?></td>
                            <td><?= $client->getAddress() ?: 'Não informado' ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#clientModal" data-bs-action="save" data-bs-client-id="<?= $client->getId() ?>">Editar</button>
                                <button class="btn btn-sm btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#clientDeleteModal" data-bs-action="delete" data-bs-client-id="<?= $client->getId() ?>">Excluir</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientModalLabel">Cadastrar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="client_form">
                <div class="modal-body">
                        <input type="hidden" id="client_form_action">
                        <input type="hidden" id="clientId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="cpf" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="address" required>
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
    <div class="modal fade" id="clientDeleteModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientDeleteModalLabel">Excluir Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="client_delete_form">
                    <input type="hidden" id="client_delete_form_action">
                    <input type="hidden" id="clientDeleteId">
                <div class="modal-body">
                    <p>Deseja realmente excluir o cliente?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="./assets/js/client.js"></script>
<?php require_once './assets/footer.php' ?>