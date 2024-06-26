/**
 * Form Ações
 */
$("#form").submit(function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  
   for (var pair of formData.entries()) {
    console.log(pair[0]+ ', ' + pair[1]); 
  }

  $.ajax({
    url: $("#form").attr("action"),
    type: "post",
    data: formData,
    beforeSend: function () {
      console.log("Before Send");
      $("#form button.btn").hide();
      $(".carrega").html(
        '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
      );
    },
    success: function (dd) {
      console.log("Success:", dd); 
      $("#form").trigger("reset");
      if (dd.resp > 0) {
        $.toast({
          text: dd.mensagem,
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "success",
        });
        if (dd.redirect) {
          setTimeout(function () {
            window.location.href = dd.redirect;
          }, 500);
        } else {
          setTimeout(function () {
            window.location.reload();
          }, 500);
        }
        if (dd.modal) {
          $(`#${dd.modal}`).modal("hide");
        }
      } else {
        $.toast({
          text: dd.mensagem,
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "error",
        });
        if (dd.redirect) {
          setTimeout(function () {
            window.location.href = dd.redirect;
          }, 500);
        }
        $("button.btn").show();
        $(".carrega").hide();
      }
    },
    error: function (dd) {
      console.log("Error:", dd); 
      $("#form button.btn").show();
      $(".carrega").hide();
      $.toast({
        text: "Oops we had a problem!",
        showHideTransition: "fade",
        position: "top-right",
        loader: false,
        icon: "error",
      });
    },
    cache: false,
    contentType: false,
    processData: false,
    xhr: function () {
      var myXhr = $.ajaxSettings.xhr();
      if (myXhr.upload) {
        myXhr.upload.addEventListener("progress", function () {}, false);
      }
      return myXhr;
    },
  });
  return false;
});

function update(id) {
  console.log("Updating address with ID:", id); 
  $.get(`/customer-address/${id}`, function (dd) {
    $("#new").modal("show");
    $("#formAddress #id_address").val(dd.id);
    $("#formAddress #type").val(dd.type);
    $("#formAddress #address_1").val(dd.address_1);
    $("#formAddress #address_2").val(dd.address_2);
    $("#formAddress #zipcode").val(dd.zipcode);
    $("#formAddress #city").val(dd.city);
    $("#formAddress #id_state").val(dd.id_state);
    $("#formAddress #id_country").val(dd.id_country);

    $(`#formAddress #delivery_type option[value=${dd.delivery_type}]`).prop(
      "selected",
      true
    );
    $("#actionBtn").text("Update");
    $(".modal-content .card-title").text("Update Address");
    $("#formAddress").attr("action", "/customer-address/update");
  });
  return false;
}


function deletarEnd(id) {
  $.ajax({
    url: `/customer-address/delete`,
    type: "delete",
    data: { id },
    success: function (dd) {
      console.log("Success:", dd); 
      if (dd.resp > 0) {
        $.toast({
          text: dd.mensagem,
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "success",
        });
      } else {
        $.toast({
          text: dd.mensagem,
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "error",
        });
      }
    },
  });
  return false;
}

$(document).ready(function() {
  $.get(`/customers/lists/all`).then(function (dd) {
    var table = $("#root").tableSortable({
      data: dd,
      columns: {
        full_name: "Name",
        email: "Email",
        mobile: "Phone",
        cnpj: "CNPJ",
        special_client: "Special Client",
        euro: "Euro",
        actions: "",
      },
      searchField: "#searchField",
      responsive: {
        1100: {
          columns: {
            full_name: "Name",
            mobile: "Phone",
            special_client: "Special Client",
            euro: "Euro",
            actions: "",
          },
        },
      },
      rowsPerPage: 20,
      pagination: true,
      onPaginationChange: function (nextPage, setPage) {
        setPage(nextPage);
      },
    });

    // Filtro de euro
    $("#euroFilter").on("change", function() {
      var euroFilter = $(this).val();
      var filteredCustomers = dd;

      // Filtra os clientes com base no valor selecionado no filtro de euro
      if (euroFilter !== "") {
        filteredCustomers = dd.filter(function(customer) {
          return customer.euro.toString() === euroFilter;
        });
      }

      // Atualiza a tabela com os dados filtrados
      updateTable(filteredCustomers);
    });

    // Função para atualizar a tabela com os dados filtrados
    function updateTable(data) {
      var tableHtml = "<tr>";
      data.forEach(function(customer) {
        tableHtml += "<tr>";
        tableHtml += "<td>" + customer.full_name + "</td>";
        tableHtml += "<td>" + customer.email + "</td>";
        tableHtml += "<td>" + customer.mobile + "</td>";
        tableHtml += "<td>" + (customer.cnpj !== null ? customer.cnpj : '') + "</td>";
        tableHtml += "<td>" + customer.special_client + "</td>";
        tableHtml += "<td>" + (customer.euro ? 'Yes' : 'No') + "</td>";
        tableHtml += "<td>" + customer.actions + "</td>";
        tableHtml += "</tr>";
      });
      $("#root table tbody").html(tableHtml);
    }

    // Atualiza a tabela com todos os clientes ao carregar a página
    updateTable(dd);
  });

  $("#changeRows").on("change", function () {
    table.updateRowsPerPage(parseInt($(this).val(), 10));
  });

  $("#rerender").click(function () {
    table.refresh(true);
  });

  $("#destroy").click(function () {
    table.destroy();
  });

  $("#refresh").click(function () {
    table.refresh();
  });

  $("#setPage2").click(function () {
    table.setPage(1);
  });
});

function deletar(id) {
  $.ajax({
    url: `/customer/delete`,
    type: "delete",
    data: { id },
    success: function (dd) {
      console.log("Success:", dd); 
      if (dd.resp > 0) {
        $.toast({
          text: dd.mensagem,
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "success",
        });

        setTimeout(function () {
          window.location.reload();
        }, 500);
      } else {
        $.toast({
          text: dd.mensagem,
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "error",
        });
        if (dd.redirect) {
          setTimeout(function () {
            window.location.href = dd.redirect;
          }, 500);
        }
      }
    },
  });
  return false;
}
