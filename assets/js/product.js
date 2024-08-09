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
})

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

                return;
            }

            console.log(response);
        }
    });
}