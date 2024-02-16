function view(id) {
  $.get(`/order/${id}`, function (dd) {
    $("#new").modal("show");
    $("#order").html(dd);
  });
}

function gerarPDF(id, id_customer, order_number) {
  $.get(
    `/order-to-orders/logistics/pdf/${id}/${id_customer}/${order_number}`,
    function (dd) {
      let dialog = window.open("", "", "width=800,height=600");
      dialog.document.write(
        `<html><head><title>PDF</title></head></html><body>${dd}</body></html>`
      );
      dialog.document.close();
      dialog.print();
    }
  );
  return false;
}

$.get(`/orders/lists/all`, function (dd) {
  var options = {
    data: dd,
    columns: {
      order_number: "Order Number",
      created_at: "Date",
      hora: "Hour",
      id_customer: "Customer",
      category: "Category",
      value: "Gross value",
      value_total: "Net value",
      status: "Status",
      id_salesman: "Sales Representative",
      actions: "",
    },
    searchField: "#searchField",
    responsive: {
      1100: {
        columns: {
          order_number: "Order Number",
          order_date: "Date",
          id_customer: "Customer",
          category: "Category",
          value: "Gross value",
          value_total: "Net value",
          status: "Status",
          id_salesman: "Sales Representative",
          actions: "",
        },
      },
    },
    sorting: true,
    rowsPerPage: 20,
    pagination: true,
    onPaginationChange: function (nextPage, setPage) {
      setPage(nextPage);
    },
  };

  var table = $("#root").tableSortable(options);
  table.refresh(table._dataset.sort("id", table._dataset.sortDirection.DESC));

  var statusMapping = {
    "Pending Approval": "Aguardando Aprovação",
    "Order Approved": "Pedido Aprovado",
    "Received": "Recebido",
    "Sorting in stock": "Triagem em Estoque",
    "In Process": "Em Processamento",
    "Waiting for NF": "Aguardando NF",
    "Sent": "Enviado",
    "Out for delivery": "Em Entrega",
    "Delivered": "Entregue",
    "Cancelled": "Cancelado",
    "Quotation": "Cotação"
  };

  //filtro de status
  $("#statusFilter").on("change", function () {
    console.log("Status filter changed");
    var status = $(this).val();
    var filteredData = dd;
    if (status !== "All") {
      filteredData = dd.filter(function (item) {
        // Comparar o status sem tags HTML e em letras minúsculas
        return item.status.replace(/<\/?[^>]+(>|$)/g, "").toLowerCase() === status.toLowerCase();
      });
    }
    console.log("Status values in dd:", dd.map(item => item.status));
    console.log("Filtered data:", filteredData);

    // Atualiza a tabela com os dados filtrados
    table.refresh(filteredData);

    // Cria o HTML da tabela apenas com as colunas desejadas
    var tableHtml = "<tr>";

    filteredData.forEach(function (item) {
      tableHtml += "<tr>";
      tableHtml += "<td>" + item.order_number + "</td>";
      tableHtml += "<td>" + item.created_at + "</td>";
      tableHtml += "<td>" + item.hora + "</td>";
      tableHtml += "<td>" + item.id_customer + "</td>";
      tableHtml += "<td>" + item.category + "</td>";
      tableHtml += "<td>" + item.value + "</td>";
      tableHtml += "<td>" + item.value_total + "</td>";
      tableHtml += "<td>" + item.status + "</td>";
      tableHtml += "<td>" + item.id_salesman + "</td>";
      tableHtml += "<th>" + item.actions + "</th>";
    });

    $("#root table tbody").html(tableHtml);
  });

  $("#changeRows").on("change", function () {
    table.updateRowsPerPage(parseInt($(this).val(), 10));
  });

  $("#rerender").click(function () {
    table.refresh(true);
  });

  $("#distory").click(function () {
    table.distroy();
  });

  $("#refresh").click(function () {
    table.refresh();
  });

  $("#setPage2").click(function () {
    table.setPage(1);
  });

  //filtro por data
  $("#dateFilter").on("change", function () {
    console.log("Date filter changed");
    var selectedDate = $(this).val();
    console.log("Selected date:", selectedDate);

    var filteredData = dd;
    if (selectedDate) {
      filteredData = dd.filter(function (item) {
        // Formatando a data do objeto retornado para o mesmo formato do selectedDate
        var orderDate = item.created_at.split("/").reverse().join("-");
        return orderDate === selectedDate;
      }).sort(function (a, b) {
        // Ordena os dados com base no created_at
        return new Date(a.created_at) - new Date(b.created_at);
      });
    }
    console.log("Filtered data:", filteredData);

    // Cria o HTML da tabela apenas com as colunas desejadas
    var tableHtml = "<tr>";

    filteredData.forEach(function (item) {
      tableHtml += "<tr>";
      tableHtml += "<td>" + item.order_number + "</td>";
      tableHtml += "<td>" + item.created_at + "</td>";
      tableHtml += "<td>" + item.hora + "</td>";
      tableHtml += "<td>" + item.id_customer + "</td>";
      tableHtml += "<td>" + item.category + "</td>";
      tableHtml += "<td>" + item.value + "</td>";
      tableHtml += "<td>" + item.value_total + "</td>";
      tableHtml += "<td>" + item.status + "</td>";
      tableHtml += "<td>" + item.id_salesman + "</td>";
      tableHtml += "<th>" + item.actions + "</th>";
    });

    $("#root table tbody").html(tableHtml);
  });

  //filtro por Sales Representative
  $("#salesmanFilter").on("input", function () {
    // console.log("Sales Representative filter changed");
    // var inputSalesman = $(this).val().trim().toLowerCase();
    // console.log("Input Sales Representative:", inputSalesman);

    // var filteredData = dd;
    // if (inputSalesman) {
    //   filteredData = dd.filter(function (item) {
    //     // Verifica se o nome do Sales Representative contém o texto digitado
    //     return item.id_salesman.toLowerCase().includes(inputSalesman);
    //   }).sort(function (a, b) {
    //     // Ordena os dados com base no created_at
    //     return new Date(a.created_at) - new Date(b.created_at);
    //   });
    // }
    // console.log("Filtered data:", filteredData);
    // table.refresh(filteredData);
    console.log("Salesman filter changed");
    var selectedSalesman = $(this).val();
    console.log("Selected salesman:", selectedSalesman);
    
    var filteredData = dd;
    if (selectedSalesman) {
        filteredData = dd.filter(function (item) {
            // Verifica se o vendedor do pedido é igual ao vendedor selecionado
            return item.id_salesman === selectedSalesman;
        }).sort(function(a, b) {
            // Ordena os dados com base no created_at
            return new Date(a.created_at) - new Date(b.created_at);
        });
    }
    console.log("Filtered data:", filteredData);
    table.refresh(filteredData);

    var tableHtml = "<tr>";

    filteredData.forEach(function (item) {
      tableHtml += "<tr>";
      tableHtml += "<td>" + item.order_number + "</td>";
      tableHtml += "<td>" + item.created_at + "</td>";
      tableHtml += "<td>" + item.hora + "</td>";
      tableHtml += "<td>" + item.id_customer + "</td>";
      tableHtml += "<td>" + item.category + "</td>";
      tableHtml += "<td>" + item.value + "</td>";
      tableHtml += "<td>" + item.value_total + "</td>";
      tableHtml += "<td>" + item.status + "</td>";
      tableHtml += "<td>" + item.id_salesman + "</td>";
      tableHtml += "<th>" + item.actions + "</th>";
    });

    $("#root table tbody").html(tableHtml);
  });
});
