<?php
require_once './assets/head.php';

use App\Models\Product;

$product = new Product();
$product->setCode('1123');
$product->setDescription('A long description right here');
$product->setStatus(1);
$product->setWarrantyTime("90 days");
$product->save();
?>

<div class="container-fluid">
    <div class="container-xl">
        <h1>Ordens de Serviço</h1>
    </div>
</div>

<?php require_once './assets/footer.php' ?>