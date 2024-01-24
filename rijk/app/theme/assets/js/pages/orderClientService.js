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
      net_value: "Net value",
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

          net_value: "Net value",
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
});
