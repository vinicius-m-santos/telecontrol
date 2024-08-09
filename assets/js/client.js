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

    $("#client_delete_form").on("submit", function (event) {
        event.preventDefault();

        const params = {
            clientId: $("#clientDeleteId").val(),
        };
        
        deleteClient(params);
    })
})

function deleteClient(params)
{
    $.ajax({
        type: "POST",
        url: "./App/Controllers/ClientController.php",
        data: {
            params: JSON.stringify(params),
            action: JSON.stringify($("#client_delete_form_action").val())
        },
        dataType: 'json',
        success: function(response){
            if (response.success) {
                notificationToastElement.find('.toast-body').html('Cliente excluído!');
                notificationToastElement.addClass('bg-success');
                notificationToastElement.removeClass('bg-danger');
                setTimeout(() => {
                    window.location.reload();
                }, 1200);
            } else {
                if (response.message == "Client has service orders") {
                    notificationToastElement.find('.toast-body').html('O cliente possui ordens de serviço vinculadas');
                } else {
                    notificationToastElement.find('.toast-body').html('Ocorreu um erro, tente novamente!');
                }
                notificationToastElement.addClass('bg-danger');
                notificationToastElement.removeClass('bg-success');
            }

            notificationToast.show();
            $("#clientDeleteModal").modal('hide');
        }
    });
}

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

const clientDeleteModal = document.getElementById('clientDeleteModal');
if (clientDeleteModal) {
    clientDeleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const action = button.getAttribute('data-bs-action');
    $("#client_delete_form_action").val(action);
    const clientDeleteId = button.getAttribute('data-bs-client-id');
    if (clientDeleteId) {
      $("#clientDeleteId").val(clientDeleteId);
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
                notificationToastElement.removeClass('bg-danger');
                setTimeout(() => {
                    window.location.reload();
                }, 1200);
            } else {
                notificationToastElement.addClass('bg-danger');
                notificationToastElement.removeClass('bg-success');
                if (response.message == "Invalid cpf") {
                    notificationToastElement.find('.toast-body').html('CPF inválido');
                    notificationToast.show();
                    return;
                } else if (response.message == "Client already exists") {
                    notificationToastElement.find('.toast-body').html('Cliente já cadastrado');
                    notificationToast.show();
                    return;
                } else {
                    notificationToastElement.find('.toast-body').html('Ocorreu um erro, tente novamente!');
                }
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
                $("#clientModalLabel").text('Editar Cliente');

                return;
            }

            console.log(response);
        }
    });
}