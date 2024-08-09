<?php
require_once './assets/head.php';

use App\Models\Product;

$product = new Product();
/** @var Product[] $allProducts */
$allProducts = $product->getAllProducts();
?>
<div class="container content mt-4">
        <h1 class="title">Produtos</h1>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary mb-3 fw-bold" data-bs-toggle="modal" data-bs-target="#productModal" data-bs-action="save">
                Novo Produto
            </button>
        </div>
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Situação</th>
                            <th>Tempo de Garantia</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($allProducts as $product):
                        ?>
                        <tr>
                            <td><?= $product->getCode() ?></td>
                            <td><?= $product->getDescription() ?></td>
                            <td><?= $product->getStatus() == 1 ? "Ativo" : "Inativo" ?></td>
                            <td><?= $product->getWarrantyTime() ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#productModal" data-bs-action="save" data-bs-product-id="<?=$product->getId()?>">Editar</button>
                                <button class="btn btn-sm btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#productDeleteModal" data-bs-action="delete" data-bs-product-id="<?=$product->getId()?>">Excluir</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Criar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="product_form">
                <div class="modal-body">
                        <input type="hidden" id="product_form_action">
                        <input type="hidden" id="productId">
                        <div class="mb-3">
                            <label for="code" class="form-label">Código</label>
                            <input type="text" class="form-control" id="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea rows="3" class="form-control" id="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Situação</label>
                            <select class="form-select" id="status" required>
                                <option value="" selected>Selecione a situação</option>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="warrantyTime" class="form-label">Tempo de Garantia</label>
                            <input type="text" class="form-control" id="warrantyTime" required>
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
    <div class="modal fade" id="productDeleteModal" tabindex="-1" aria-labelledby="productDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDeleteModalLabel">Excluir Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="product_delete_form">
                    <input type="hidden" id="product_delete_form_action">
                    <input type="hidden" id="productDeleteId">
                <div class="modal-body">
                    <p>Deseja realmente excluir o produto?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="./assets/js/product.js"></script>
<?php require_once './assets/footer.php' ?>