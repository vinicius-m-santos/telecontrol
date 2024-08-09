$(function() {
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
})

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
            console.log(response);
        }
    });
}

const serviceOrderModal = document.getElementById('serviceOrderModal');
if (serviceOrderModal) {
  serviceOrderModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const action = button.getAttribute('data-bs-action');
    $("#service_order_form_action").val(action);
  })
}