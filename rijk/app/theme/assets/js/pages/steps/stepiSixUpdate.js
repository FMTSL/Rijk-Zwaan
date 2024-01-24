$(".selectSearch").select2();
$("#aditionalDiscount button.btn").hide();
/**
 *
 * Cadastro de produtos com desconto normal fa categoria
 *
 * */
//Variety
$("#stepSixUpdate #variety_aditional_discount").on("change", function () {
  const id = $(this).val();
  $("#aditionalDiscount button.btn").hide();
  $.ajax({
    url: `/product/list/${id}`,
    type: "get",
    beforeSend: function (dd) {
      $("#aditionalDiscount button.btn").hide();
      $("#stepSixUpdate #id_stock_aditional_discount").html(
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
        $("#stepSixUpdate #id_stock_aditional_discount").html(``);
        $("#stepSixUpdate #id_stock_aditional_discount").append(
          `<option value="0">Select</option>`
        );
        $.map(dd, function (element, index) {
          $("#stepSixUpdate #id_stock_aditional_discount").append(
            `<option value="${element.id}"> ${element.package} - R$ ${element.value}</option>`
          );
        });
        $(
          `#stepSixUpdate #id_stock_aditional_discount, #stepSixUpdate #id_stock_aditional_discount, #stepSixUpdate #quantity_aditional_discount, #stepSixUpdate #variation_aditional_discount, 
          #stepSixUpdate`
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
$("#stepSixUpdate #product_aditional_discount").on("change", function () {
  $("#stepSixUpdate #id_stock_aditional_discount option").remove();
  $(`#stepSixUpdate #id_stock_aditional_discount`).prop("disabled", false);
  let id = $(this).val();
  $.get(`/order-to-order/product/${id}`, function (dd) {
    $(`#stepSixUpdate .checkbox input`).prop("checked", false);
    $("#stepSixUpdate #id_stock_aditional_discount").html(``);
    $("#stepSixUpdate #id_stock_aditional_discount").append(
      `<option value="0">Select</option>`
    );
    $.map(dd, function (element, index) {
      $(`#stepSixUpdate #id_stock_aditional_discount`).prop("disabled", false);
      $("#stepSixUpdate #id_stock_aditional_discount").append(
        `<option value="${element.id}">${element.id_package} - R$ ${element.valor}</option>`
      );
    });
  });
});

//Packaging
$("#stepSixUpdate #id_stock_aditional_discount").on("change", function () {
  if (parseInt($(this).val()) > 0) {
    $(`#stepSixUpdate #quantity_aditional_discount`).prop("disabled", false);
  } else {
    $(`#stepSixUpdate #quantity_aditional_discount`).prop("disabled", true);
  }
});

//Quantity in MX
$("#stepSixUpdate #quantity_aditional_discount").on("focusout", function () {
  const quantity = $(this).val();
  const id = $(`#stepSixUpdate #variety_aditional_discount option`)
    .filter(":selected")
    .val();
  const id_customers = $("#cliente_id").val();
  $.ajax({
    url: `/discount/list/${id}/${id_customers}`,
    type: "get",
    beforeSend: function (dd) {
      $("#stepSixUpdate #volume_condition_aditional_discount").html(
        `<option class="env">Please wait loading</option>`
      );
    },
    success: function (dd) {
      if (dd === 0) {
        $("#stepSixUpdate #volume_condition_aditional_discount").html(``);
        $("#stepSixUpdate #volume_condition_aditional_discount").append(
          `<option value="0">...</option>`
        );
        $(
          `#stepSixUpdate #aditional_discount_aditional_discount, #stepSixUpdate #volume_condition_aditional_discount, #stepSixUpdate #sala_nova_aditional_discount`
        ).prop("disabled", false);
        //$("#variety_aditional_discount button.btn").show();
      } else {
        console.log(dd);
        $("#stepSixUpdate #volume_condition_aditional_discount").html(``);
        $("#stepSixUpdate #volume_condition_aditional_discount").append(
          `<option value="0">Select</option>`
        );
        $.map(dd, function (element, index) {
          if (parseInt(element.special_client) === parseInt(1)) {
            $("#stepSixUpdate #volume_condition_aditional_discount").append(
              `<option value="${element.percentage}"> > ${element.value}</option>`
            );
          } else {
            if (parseInt(quantity) >= parseInt(element.value)) {
              $("#stepSixUpdate #volume_condition_aditional_discount").append(
                `<option value="${element.percentage}"> > ${element.value}</option>`
              );
            }
          }
          $(
            `#stepSixUpdate #aditional_discount_aditional_discount, #stepSixUpdate #volume_condition_aditional_discount, #stepSixUpdate #sala_nova_aditional_discount`
          ).prop("disabled", false);
          //$("#variety_aditional_discount button.btn").show();
        });
      }
    },
  });
});

$("#stepSixUpdate #aditional_discount_aditional_discount").on(
  "change",
  function () {
    if (parseInt($(this).val()) > 0) {
      $("#aditionalDiscount button.btn").show();
    }
  }
);

//Novo item no carrinho
$("#aditionalDiscount").submit(function (e) {
  var formData = new FormData(this);
  $.ajax({
    url: $("#aditionalDiscount").attr("action"),
    type: "post",
    data: formData,
    beforeSend: function () {
      $("#variety_aditional_discount button.btn").hide();
      $("#stepSixUpdate .carrega").html(
        '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
      );
    },
    success: function (dd) {
      $("#aditionalDiscount").trigger("reset");
      console.log(dd);
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
      const id = $("#id_order").val();
      const clientid = $("#client_id").val();
      $("#stepSixUpdate #productList_aditional_discount").html("");

      $.get(
        `/order-to-order-list/${id}/${clientid}/step-six-products`,
        function (dd) {
          $("#stepSixUpdate #productList_aditional_discount").html(dd);
        }
      );
      $.get(`/order-to-order-total/${id}/${clientid}/total`, function (dd) {
        $("#valorTotal").html(dd);
      });
    },
    error: function (dd) {
      $("#variety_aditional_discount button.btn").show();
      $("#stepSixUpdate .carrega").hide();
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

$("#stepSixUpdate #getOrder").click(function () {
  $("#stepSixUpdate #enviaForm").trigger("click");
});

function carregaProdutos() {
  const id = $("#id_order").val();
  const clientid = $("#client_id").val();
  $("#stepSixUpdate #productList_aditional_discount").html("");

  $.get(
    `/order-to-order-list/${id}/${clientid}/step-six-products`,
    function (dd) {
      $("#stepSixUpdate #productList_aditional_discount").html(dd);
    }
  );
  $.get(`/order-to-order-total/${id}/${clientid}/total`, function (dd) {
    $("#valorTotal").html(dd);
  });
}
