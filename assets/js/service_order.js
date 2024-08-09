$(function() {
    notificationToastElement = $('#notification-toast');
    notificationToast = new bootstrap.Toast(notificationToastElement);

    $("#service_order_form").on("submit", function (event) {
        event.preventDefault();

        const params = {
            serviceOrderId: $("#serviceOrderId").val(),
            orderNumber: $("#orderNumber").val(),
            openingDate: $("#openingDate").val(),
            consumerName: $("#consumerName").val(),
            consumerCpf: $("#consumerCpf").val(),
            products: $("#products").val(),
        };
        
        saveServiceOrder(params);
    })

    $("#client_delete_form").on("submit", function (event) {
        event.preventDefault();

        const params = {
            serviceOrderId: $("#serviceOrderDeleteId").val(),
        };
        
        deleteServiceOrder(params);
    })
})

function deleteServiceOrder(params)
{
    $.ajax({
        type: "POST",
        url: "./App/Controllers/ServiceOrderController.php",
        data: {
            params: JSON.stringify(params),
            action: JSON.stringify($("#service_order_delete_form_action").val())
        },
        dataType: 'json',
        success: function(response){
            if (response.success) {
                notificationToastElement.find('.toast-body').html('Ordem de Serviço excluída!');
                notificationToastElement.addClass('bg-success');
                notificationToastElement.removeClass('bg-danger');
                setTimeout(() => {
                    window.location.reload();
                }, 1200);
            } else {
                notificationToastElement.find('.toast-body').html('Ocorreu um erro, tente novamente!');
                notificationToastElement.addClass('bg-danger');
                notificationToastElement.removeClass('bg-success');
            }

            notificationToast.show();
            $("#serviceOrderDeleteModal").modal('hide');
        }
    });
}

function saveServiceOrder(params)
{
    $.ajax({
        type: "POST",
        url: "./App/Controllers/ServiceOrderController.php",
        data: {
            params: JSON.stringify(params),
            action: JSON.stringify($("#service_order_form_action").val())
        },
        dataType: 'json',
        success: function(response){
            if (response.success) {
                notificationToastElement.find('.toast-body').html('Ordem de Serviço salva!');
                notificationToastElement.addClass('bg-success');
                notificationToastElement.removeClass('bg-danger');
                setTimeout(() => {
                    window.location.reload();
                }, 1200);
            } else {
                notificationToastElement.addClass('bg-danger');
                notificationToastElement.removeClass('bg-success');
                notificationToastElement.find('.toast-body').html('Ocorreu um erro, tente novamente!');
            }

            notificationToast.show();
            $("#serviceOrderModal").modal('hide');
        }
    });
}

const serviceOrderModal = document.getElementById('serviceOrderModal');
if (serviceOrderModal) {
  serviceOrderModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const action = button.getAttribute('data-bs-action');
    $("#service_order_form_action").val(action);
    const serviceOrderId = button.getAttribute('data-bs-service-order-id');
    if (serviceOrderId) {
      loadServiceOrder(serviceOrderId);
    } else {
      $("#serviceOrderId").val("");
      $("#orderNumber").val("");
      $("#openingDate").val("");
      $("#consumerName").val("");
      $("#consumerCpf").val("");
      $("#products").val(null).trigger('change');
    }
  })
}

const serviceOrderDeleteModal = document.getElementById('serviceOrderDeleteModal');
if (serviceOrderDeleteModal) {
    serviceOrderDeleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const action = button.getAttribute('data-bs-action');
    $("#service_order_delete_form_action").val(action);
    const serviceOrderDeleteId = button.getAttribute('data-bs-service-order-id');
    if (serviceOrderDeleteId) {
      $("#serviceOrderDeleteId").val(serviceOrderDeleteId);
    }
  })
}

function loadServiceOrder(id)
{
    $.ajax({
        type: "POST",
        url: "./App/Controllers/ServiceOrderController.php",
        data: {
            action: JSON.stringify("load"),
            params: JSON.stringify({id: id})
        },
        dataType: 'json',
        success: function(response){
            if (response.success) {
                $("#serviceOrderId").val(response.service_order.id);
                $("#orderNumber").val(response.service_order.order_number);
                $("#openingDate").val(response.service_order.opening_date);
                $("#consumerName").val(response.service_order.consumer_name);
                $("#consumerCpf").val(response.service_order.consumer_cpf);
                $("#products").val(response.service_order.products).trigger('change');
                $("#serviceOrderModalLabel").text('Editar Ordem de Serviço');

                return;
            }

            console.log(response);
        }
    });
}