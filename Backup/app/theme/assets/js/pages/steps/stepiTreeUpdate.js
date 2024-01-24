$(".selectSearch").select2();
$("#bonusOrder button.btn").hide();
/**
 *
 * Cadastro de produtos com desconto normal fa categoria
 *
 * */
//Variety
$("#bonusOrder #variety_bonus_order").on("change", function () {
  const id = $(this).val();
  $("#bonusOrder button.btn").hide();
  $.ajax({
    url: `/product/list/${id}`,
    type: "get",
    beforeSend: function (dd) {
      $("#bonusOrder button.btn").hide();
      $("#bonusOrder #id_stock_bonus_order").html(
        `<option class="env">Please wait loading</option>`
      );
    },
    success: function (dd) {
      if (dd === 0) {
        $.toast({
          text: "No products found in this Variety",
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "error",
        });
        //$("#product").html(``);
      } else {
        $("#bonusOrder #id_stock_bonus_order").html(``);
        $("#bonusOrder #id_stock_bonus_order").append(
          `<option value="0">Select</option>`
        );
        $.map(dd, function (element, index) {
          $("#bonusOrder #id_stock_bonus_order").append(
            `<option value="${element.id}"> ${element.package} - R$ ${element.value}</option>`
          );
        });
        $(
          `#bonusOrder #id_stock_bonus_order, #bonusOrder #id_stock_bonus_order, #bonusOrder #quantity_bonus_order`
        ).prop("disabled", false);
      }
    },
    error: function (dd) {
      $.toast({
        text: "No products found in this Variety",
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
//Batch + Product
$("#bonusOrder #product_bonus_order").on("change", function () {
  $("#bonusOrder #id_stock_bonus_order option").remove();
  $(`#bonusOrder #id_stock_bonus_order`).prop("disabled", false);
  let id = $(this).val();
  $.get(`/order-to-order/product/${id}`, function (dd) {
    $(`.checkbox input`).prop("checked", false);
    $("#bonusOrder #id_stock_bonus_order").html(``);
    $("#bonusOrder #id_stock_bonus_order").append(
      `<option value="0">Select</option>`
    );
    $.map(dd, function (element, index) {
      $(`#bonusOrder #id_stock_bonus_order`).prop("disabled", false);
      $("#bonusOrder #id_stock_bonus_order").append(
        `<option value="${element.id}">${element.id_package} - R$ ${element.valor}</option>`
      );
    });
  });
});

//Packaging
$("#bonusOrder #id_stock_bonus_order").on("change", function () {
  if (parseInt($(this).val()) > 0) {
    $(`#bonusOrder #quantity_bonus_order`).prop("disabled", false);
  } else {
    $(`#bonusOrder #quantity_bonus_order`).prop("disabled", true);
  }
});

//Quantity in MX
$("#bonusOrder #quantity_bonus_order").on("focusout", function () {
  const quantity = $(this).val();
  const id = $(`#bonusOrder #variety_bonus_order option`)
    .filter(":selected")
    .val();

  //$("#bonusOrder button.btn").show();
  $(`#bonusOrder #bonus_type_bonus_order`).prop("disabled", false);
});

$("#bonusOrder #bonus_type_bonus_order").on("change", function () {
  if (parseInt($(this).val()) > 0) {
    $("#bonusOrder button.btn").show();
  }
});

//Novo item no carrinho
$("#bonusOrder").submit(function (e) {
  var formData = new FormData(this);
  const bonus_type_bonus_order = $(`#bonusOrder #bonus_type_bonus_order option`)
    .filter(":selected")
    .val();

  if (parseInt(bonus_type_bonus_order) > 0) {
    $.ajax({
      url: $("#bonusOrder").attr("action"),
      type: "post",
      data: formData,
      beforeSend: function () {
        $("#bonusOrder button.btn").hide();
        $("#bonusOrder .carrega").html(
          '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
        );
      },
      success: function (dd) {
        console.log(dd);
        $("#bonusOrder").trigger("reset");
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
        const id = $("#id_order_bonus_order").val();
        const clientid = $("#client_id_bonus_order").val();
        $("#stepTreeUpdate #productList_bonus_order").html("");

        $.get(
          `/order-to-order-list/${id}/${clientid}/step-tree-products`,
          function (dd) {
            $("#stepTreeUpdate #productList_bonus_order").html(dd);
          }
        );
        $.get(`/order-to-order-total/${id}/${clientid}/total`, function (dd) {
          $("#valorTotal").html(dd);
        });
      },
      error: function (dd) {
        $("#bonusOrder button.btn").show();
        $("#bonusOrder .carrega").hide();
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
  }
});

$("#stepTreeUpdate #getOrder").click(function () {
  $("#stepTreeUpdate #enviaForm").trigger("click");
});

function carregaProdutos() {
  const id = $("#id_order_bonus_order").val();
  const clientid = $("#client_id_bonus_order").val();
  $("#stepTreeUpdate #productList_bonus_order").html("");

  $.get(
    `/order-to-order-list/${id}/${clientid}/step-tree-products`,
    function (dd) {
      $("#stepTreeUpdate #productList_bonus_order").html(dd);
    }
  );

  $.get(`/order-to-order-total/${id}/${clientid}/total`, function (dd) {
    $("#valorTotal").html(dd);
  });
}
