/**
 * Form Ações
 */
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
  $.get(`/product/stock/clone/${id}`, function (dd) {
    console.log(dd);
    $("#new").modal("show");
    $("#form #id_package").val(dd.id_package);
    $("#form #id_package").val(dd.id_package);
    $("#form #id_variety").val(dd.id_variety);
    $("#form #id_crop").val(dd.id_crop);
    $("#form #article_number").val(dd.article_number);
    $("#form #value").val(dd.value);
    $("#form #weight").val(dd.weight);
    $("#form #id").val(dd.id);
    $("#form h4").text("Update");
    $("#form p").text(``);
    $("#form button").text("Update");
    $("#form").attr("action", "/product/stock/clone/update");
  });
}

function deletar(id, id_products) {
  $.ajax({
    url: `/product/stock/clone/delete`,
    type: "delete",
    data: { id, id_products },
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

function deletarAll() {
  $.ajax({
    url: `/product/stock/clone/delete/all`,
    type: "delete",
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

$("#value").mask("###0.00", {
  reverse: true,
});

$.get(`/product/stock/clone/lists/all`, function (dd) {
  console.log(dd);
  var table = $("#root").tableSortable({
    data: dd,
    columns: {
      id_crop: "Crop",
      id_variety: "Variedade",
      product_sales_unit: "Produto + Unidade de venda",
      batch: "Lote",
      one_of_sale: "Un de venda",
      packaging_expiration: "Vencimento embalagem",
      treatments: "Tratamentos",
      sum_of_qty_in_vwh_local: "Sum of Qty in VWH Local",
      actions: "",
    },
    searchField: "#searchField",
    responsive: {
      1100: {
        columns: {
          id_crop: "Crop",
          id_variety: "Variedade",
          product_sales_unit: "Produto + Unidade de venda",
          batch: "Lote",
          one_of_sale: "Un de venda",
          packaging_expiration: "Vencimento embalagem",
          treatments: "Tratamentos",
          sum_of_qty_in_vwh_local: "Sum of Qty in VWH Local",
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
