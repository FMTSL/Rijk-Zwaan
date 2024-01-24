/**
 * Slug
 */
$(".slug-set #name").blur(function () {
  const str = $(this).val();
  const parsed = str
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/\s+/g, "-")
    .toLowerCase();
  $("#form #slug").val(parsed);
});
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
        //$("#root").refresh(true);
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

        $("#root").refresh(true);
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
  $.get(`/product/${id}`, function (dd) {
    console.log(dd);
    $("#new").modal("show");
    $("#form #input_name").val(dd.name);
    $("#form #maturity").val(dd.maturity);

    $(`#form #id_crop option[value=${dd.id_crop}]`).prop("selected", true);

    $(`#form #checkbox`).prop("checked", dd.status);

    $(`#form #id_variety option[value=${dd.id_variety}]`).prop(
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
    $("#form #dtp_input2").val(dd.maturity);
    $("#form #batch").val(dd.batch);

    //Format Date PT-BR
    if (dd.maturity === "") {
      let data = new Date(dd.maturity);
      let dataFormatada = data.toLocaleDateString("pt-BR", { timeZone: "UTC" });
      $("#form .form_date").text(dataFormatada);
    }

    $("#form #id").val(dd.id);
    $("#form h4").text("Update Product");
    $("#form p").text(`Update Product ${dd.name}`);
    $("#form button").text("Update");
    $("#form").attr("action", "/product/update");
  });
}

function deletar(id) {
  $.ajax({
    url: `/product/delete`,
    type: "delete",
    data: { id },
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
$.get(`/products/lists/all`, function (dd) {
  var table = $("#root").tableSortable({
    data: dd,
    columns: {
      id_variety: "Variety",
      id_crop: "Crop",
      batch: "Batch",
      name: "Product",
      id_sales_unit: "Sales Unit",
      id_chemical_treatment: "Chemical treatment",
      maturity: "Expiry date",
      stock: "Quantity",
      status: "Status",
      actions: "",
    },
    searchField: "#searchField",
    responsive: {
      1100: {
        columns: {
          name: "Product",
          batch: "Batch",
          stock: "Quantity",
          status: "Status",
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
