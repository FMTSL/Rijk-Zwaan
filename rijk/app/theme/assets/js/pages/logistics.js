$("#form").submit(function (e) {
  var formData = new FormData(this);
  $.ajax({
    url: $("#form").attr("action"),
    type: "post",
    data: formData,
    beforeSend: function () {
      $("#form button.btn").hide();
      $(".carrega").html(
        '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
      );
    },
    success: function (dd) {
      console.log(dd);
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
        }

        //valorPedido();
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
  $.get(`/order-to-order/${id}`, function (dd) {
    $("#new").modal("show");
    $("#form #input_name").val(dd.name);
    $(`#form #id_category option[value=${dd.id_category}]`).prop(
      "selected",
      true
    );
    $(`#form #id_variety option[value=${dd.id_variety}]`).prop(
      "selected",
      true
    );
    $(`#form #id_package option[value=${dd.stock_id_package}]`).prop(
      "selected",
      true
    );
    $(`#form #id_sales_unit option[value=${dd.id_sales_unit}]`).prop(
      "selected",
      true
    );
    $(
      `#form #id_chemical_treatment option[value=${dd.id_chemical_treatment}]`
    ).prop("selected", true);
    $(`#form #id_stock option[value=${dd.id_stock}]`).prop("selected", true);
    $("#form #batch").val(dd.batch);
    $("#form #stock_id").val(dd.stock_id);
    $("#form #quantity").val(dd.stock_quantity);
    $("#form #dtp_input2").val(dd.maturity);

    //Format Date PT-BR
    let data = new Date(dd.maturity);
    let dataFormatada = data.toLocaleDateString("pt-BR", { timeZone: "UTC" });
    $("#form .form_date").text(dataFormatada);

    $("#form #id").val(dd.id);
    $("#form h4").text("Update Product");
    $("#form p").text(`Update Product ${dd.name}`);
    $("#form button").text("Update");
    $("#form").attr("action", "/order-to-order/update");
  });
}

// function valorPedido() {
//   let id = $("select#id_customer option").filter(":selected").val();
//   $.ajax({
//     url: `/order-to-order/products/total/${id}`,
//     type: "GET",
//     beforeSend: function () {
//       $(".carrega").html(
//         '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
//       );
//     },
//     success: function (dd) {
//       let desconto = $("#discount_form").val();
//       let porcentagem = (parseFloat(dd.total) * parseFloat(desconto)) / 100;
//       $("#total").val(formatter.format(parseFloat(dd.total) - porcentagem));
//       $("#total_parc").val(formatter.format(dd.total));
//       $("#totalParc").val(dd.total);
//       $("#total_form").val(parseFloat(dd.total) - porcentagem);
//     },
//   });
// }

$("#id_customer").on("change", function () {
  let id = $(this).val();
  $.get(`/order-to-order/client/${id}`, function (dd) {
    $(`#variety`).prop("disabled", false);
    $("#form #input_name").val(dd.name);
    $("#cliente_id").val(id);
    $(`#id_category option[value=${dd.id_category_customer}]`).prop(
      "selected",
      true
    );
    $("#cash_payment_old").val(dd.category_cash_payment_discount);
    $("#category_basic_discount, #desconto, #discount_form").val(
      dd.category_basic_discount
    );
    $(`#additional_discount, #payment_type, #delivery_address`).prop(
      "disabled",
      false
    );

    $.map(dd.address, function (el, index) {
      $("#delivery_address").append(
        `<option value="${el.id}">${el.type} - ${el.address_1} ${el.address_2} | ${el.city}</option>`
      );
    });
  });
});

$("#delivery_address").on("change", function () {
  const id = $(this).val();
  const total = $("#totalParc").val();

  $.ajax({
    url: `/order-to-order/freight-calculation`,
    type: "post",
    data: { id },
    success: function (dd) {
      console.log(dd);
      $("#freight_value").html(`+ R$ ${dd.valor[0]}`);
      $("#freight").val(dd.valor[0]);
      $("#tax").val(dd.id_taxa);
      $("#icms_value").html(`+ ${dd.taxa} %`);

      //console.log(total);

      let total_value =
        parseFloat(dd.valor[0]) + parseFloat(dd.taxa) + parseFloat(total);
      console.log(parseFloat(total));
      $("#total_value").html(
        `<strong> ${formatter.format(total_value)}</strong>`
      );
    },
  });
  return false;
});

$("#delivery").on("change", function () {
  $(`#delivery_address`).prop("disabled", false);
});

function deletar(id) {
  $.ajax({
    url: `/order-to-order/delete`,
    type: "delete",
    data: { id },
    success: function (dd) {
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

$(".form_date").datetimepicker({
  language: "pt-BR",
  weekStart: 1,
  todayBtn: 1,
  autoclose: 1,
  todayHighlight: 1,
  startView: 2,
  minView: 2,
  forceParse: 0,
});

$("#gerarPDF").on("click", function () {
  var doc = new jsPDF();
  doc.addHTML(document.body, 10, 10);
  doc.save("a4.pdf");

  // $.get(
  //   `/order-to-orders/logistics/pdf/${id}/${id_customer}/${order_number}`,
  //   function (dd) {
  //     //console.log(dd);
  //     //   var doc = new jsPDF("landscape", "pt", "a4");
  //     //   doc.addHTML(dd, function () {
  //     //     doc.save("teste.pdf");
  //     //   });

  //     //   var doc = new jsPDF();
  //     //   doc.renderHTML(dd, 10, 10);
  //     //   doc.save("a4.pdf");

  //     doc.html(document.body, {
  //       callback: function (doc) {
  //         doc.save();
  //       },
  //       x: 10,
  //       y: 10,
  //     });
  //   }
  // );
  return false;
});

$.get(`/order-to-orders/logistics/lists/all`, function (dd) {
  var table = $("#root").tableSortable({
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
    rowsPerPage: 20,
    pagination: true,
    onPaginationChange: function (nextPage, setPage) {
      setPage(nextPage);
    },
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
});
