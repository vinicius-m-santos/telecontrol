<?php
    require_once './autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de OS</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<header class="container-fluid">
<nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex align-items-center bg-body-tertiary">
            <a href="" class="navbar-brand">
                <img src="https://placehold.co/100x75" alt="" class="d-inline-block">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" id="offcanvasNavbar">
                <div class="offcanvas-header">
                    <h5>Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body" id="navbarNav">
                    <ul class="navbar-nav flex-grow-1">
                        <li class="nav-item">
                        <a class="nav-link" href="service_order.php">Ordens de Serviço</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="client.php">Clientes</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="product.php">Produtos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>