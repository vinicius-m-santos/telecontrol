$(function() {
    notificationToastElement = $('#notification-toast');
    notificationToast = new bootstrap.Toast(notificationToastElement);

    $("#product_form").on("submit", function (event) {
        event.preventDefault();

        const params = {
            productId: $("#productId").val(),
            code: $("#code").val(),
            description: $("#description").val(),
            status: $("#status").val(),
            warrantyTime: $("#warrantyTime").val(),
        };
        
        saveProduct(params);
    })
        

    $("#product_delete_form").on("submit", function (event) {
        event.preventDefault();

        const params = {
            productId: $("#productDeleteId").val(),
        };
        
        deleteProduct(params);;
    })
})

function deleteProduct(params)
{
    $.ajax({
        type: "POST",
        url: "./App/Controllers/ProductController.php",
        data: {
            params: JSON.stringify(params),
            action: JSON.stringify($("#product_delete_form_action").val())
        },
        dataType: 'json',
        success: function(response){
            if (response.success) {
                notificationToastElement.find('.toast-body').html('Produto excluído!');
                notificationToastElement.addClass('bg-success');
                notificationToastElement.removeClass('bg-danger');
                setTimeout(() => {
                    window.location.reload();
                }, 1200);
            } else {
                if (response.message == "Product has service orders") {
                    notificationToastElement.find('.toast-body').html('O produto tem ordem de serviço vinculada!');
                } else {
                    notificationToastElement.find('.toast-body').html('Ocorreu um erro, tente novamente!');
                }
                notificationToastElement.addClass('bg-danger');
                notificationToastElement.removeClass('bg-success');
            }

            notificationToast.show();
            $("#productDeleteModal").modal('hide');
        }
    });
}

const productModal = document.getElementById('productModal');
if (productModal) {
  productModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const action = button.getAttribute('data-bs-action');
    $("#product_form_action").val(action);
    const productId = button.getAttribute('data-bs-product-id');
    if (productId) {
      loadProduct(productId);
    } else {
      $("#productId").val("");
      $("#code").val("");
      $("#description").val("");
      $("#status").val("");
      $("#warrantyTime").val("");
    }
  })
}

const productDeleteModal = document.getElementById('productDeleteModal');
if (productDeleteModal) {
    productDeleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const action = button.getAttribute('data-bs-action');
    $("#product_delete_form_action").val(action);
    const productDeleteId = button.getAttribute('data-bs-product-id');
    if (productDeleteId) {
      $("#productDeleteId").val(productDeleteId);
    }
  })
}

function saveProduct(params)
{
    $.ajax({
        type: "POST",
        url: "./App/Controllers/ProductController.php",
        data: {
            params: JSON.stringify(params),
            action: JSON.stringify($("#product_form_action").val())
        },
        dataType: 'json',
        success: function(response){
            if (response.success) {
                notificationToastElement.find('.toast-body').html('Produto salvo!');
                notificationToastElement.addClass('bg-success');
                setTimeout(() => {
                    window.location.reload();
                }, 1200);
            } else {
                notificationToastElement.find('.toast-body').html('Ocorreu um erro, tente novamente!');
                notificationToastElement.addClass('bg-danger');
            }

            notificationToast.show();
            $("#productModal").modal('hide');
        }
    });
}

function loadProduct(id)
{
    $.ajax({
        type: "POST",
        url: "./App/Controllers/ProductController.php",
        data: {
            action: JSON.stringify("load"),
            params: JSON.stringify({id: id})
        },
        dataType: 'json',
        success: function(response){
            if (response.success) {
                $("#productId").val(response.product.id);
                $("#code").val(response.product.code);
                $("#description").val(response.product.description);
                $("#status").val(response.product.status);
                $("#warrantyTime").val(response.product.warranty_time);
                $("#productModalLabel").text('Editar Produto');

                return;
            }

            console.log(response);
        }
    });
}