$(function() {
    notificationToastElement = $('#notification-toast');
    notificationToast = new bootstrap.Toast(notificationToastElement);

    $("#client_form").on("submit", function (event) {
        event.preventDefault();

        const params = {
            clientId: $("#clientId").val(),
            name: $("#name").val(),
            cpf: $("#cpf").val(),
            address: $("#address").val(),
        };
        
        saveClient(params);
    })
})

const clientModal = document.getElementById('clientModal');
if (clientModal) {
  clientModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const action = button.getAttribute('data-bs-action');
    $("#client_form_action").val(action);
    const clientId = button.getAttribute('data-bs-client-id');
    if (clientId) {
      loadClient(clientId);
    } else {
      $("#clientId").val("");
      $("#name").val("");
      $("#cpf").val("");
      $("#address").val("");
    }
  })
}

function saveClient(params)
{
    $.ajax({
        type: "POST",
        url: "./App/Controllers/ClientController.php",
        data: {
            params: JSON.stringify(params),
            action: JSON.stringify($("#client_form_action").val())
        },
        dataType: 'json',
        success: function(response){
            if (response.success) {
                notificationToastElement.find('.toast-body').html('Cliente salvo!');
                notificationToastElement.addClass('bg-success');
            } else {
                notificationToastElement.find('.toast-body').html('Ocorreu um erro, tente novamente!');
                notificationToastElement.addClass('bg-danger');
            }

            notificationToast.show();
            $("#clientModal").modal('hide');
        }
    });
}

function loadClient(id)
{
    $.ajax({
        type: "POST",
        url: "./App/Controllers/ClientController.php",
        data: {
            action: JSON.stringify("load"),
            params: JSON.stringify({id: id})
        },
        dataType: 'json',
        success: function(response){
            if (response.success) {
                $("#clientId").val(response.client.id);
                $("#name").val(response.client.name);
                $("#cpf").val(response.client.cpf);
                $("#address").val(response.client.address);

                return;
            }

            console.log(response);
        }
    });
}