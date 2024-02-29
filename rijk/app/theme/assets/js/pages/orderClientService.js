function view(id) {
  $.get(`/client-service/${id}`, function (dd) {
    $("#new").modal("show");
    $("#order").html(dd);
  });
}

function gerarPDF(id, id_customer, order_number) {
  $.get(
    `/order-to-orders/client-service/logistics/pdf/${id}/${id_customer}/${order_number}`,
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

$.get(`/client-service/lists/all`, function (dd) {
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

function filterData() {
  var status = $("#statusFilter").val();
  var selectedDate = $("#dateFilter").val();
  var selectedSalesman = $("#salesmanFilter").val();

  var filteredData = dd;

  if (status && status !== "All") {
      filteredData = filteredData.filter(function (item) {
          return item.status.replace(/<\/?[^>]+(>|$)/g, "").toLowerCase() === status.toLowerCase();
      });
  }

  if (selectedDate) {
      filteredData = filteredData.filter(function (item) {
          var orderDate = item.created_at.split("/").reverse().join("-");
          return orderDate === selectedDate;
      });
  }

  if (selectedSalesman) {
      filteredData = filteredData.filter(function (item) {
          return item.id_salesman === selectedSalesman;
      });
  }

  return filteredData;
}


function updateTable() {
    var filteredData = filterData();

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
  }

  $("#statusFilter, #dateFilter, #salesmanFilter").on("change", function () {
      updateTable();
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

  // Initial table render
  updateTable();
});
