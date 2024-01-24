$(".selectSearch").select2();
$("#commercialOrder button.btn").hide();
/**
 *
 * Cadastro de produtos com desconto normal fa categoria
 *
 * */
//Variety
$("#commercialOrder #variety").on("change", function () {
  const id = $(this).val();
  $("#commercialOrder button.btn").hide();
  $.ajax({
    url: `/product/list/${id}`,
    type: "get",
    beforeSend: function (dd) {
      $("#form button.btn").hide();
      $("#id_stock").html(`<option class="env">Please wait loading</option>`);
    },
    success: function (dd) {
      console.log(dd);
      if (dd === 0) {
        $.toast({
          text: "No products found in this Variety",
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "error",
        });
      } else {
        $("#id_stock").html(``);
        $("#id_stock").append(`<option value="0">Select</option>`);

        $.map(dd, function (element, index) {
          $("#id_stock").append(
            `<option value="${element.id}"> ${element.package} - R$ ${element.value}</option>`
          );
        });
        $(
          `#product, #id_stock, #quantity, #variation, #aditional_discount`
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

//Packaging
$("#commercialOrder #id_stock").on("change", function () {
  if (parseInt($(this).val()) > 0) {
    $(`#quantity`).prop("disabled", false);
  } else {
    $(`#quantity`).prop("disabled", true);
  }
});

//Quantity in MX
$("#commercialOrder #quantity").on("focusout", function () {
  const quantity = $(this).val();
  const id = $(`#commercialOrder #variety option`).filter(":selected").val();
  const id_customers = $("#cliente_id").val();
  $.ajax({
    url: `/discount/list/${id}/${id_customers}`,
    type: "get",
    beforeSend: function (dd) {
      $("#commercialOrder #volume_condition").html(
        `<option class="env">Please wait loading</option>`
      );
    },
    success: function (dd) {
      console.log(dd);
      if (dd === 0) {
        $("#commercialOrder #volume_condition").html(``);
        $("#commercialOrder #volume_condition").append(
          `<option value="0">...</option>`
        );
        $(
          `#commercialOrder #aditional_discount, #commercialOrder #volume_condition`
        ).prop("disabled", false);
        $("#commercialOrder button.btn").show();
      } else {
        $("#commercialOrder #volume_condition").html(``);
        $("#commercialOrder #volume_condition").append(
          `<option value="0">Select</option>`
        );

        $.map(dd, function (element, index) {
          if (parseInt(element.special_client) === parseInt(1)) {
            $("#commercialOrder #volume_condition").append(
              `<option value="${element.percentage}"> > ${element.value}</option>`
            );
          } else {
            if (parseInt(quantity) >= parseInt(element.value)) {
              $("#commercialOrder #volume_condition").append(
                `<option value="${element.percentage}"> > ${element.value}</option>`
              );
            }
          }
          $("#commercialOrder button.btn").show();
        });

        $(
          `#commercialOrder #aditional_discount, #commercialOrder #volume_condition`
        ).prop("disabled", false);
      }
    },
  });
});

//Novo item no carrinho
$("#commercialOrder").submit(function (e) {
  var formData = new FormData(this);

  $.ajax({
    url: $("#commercialOrder").attr("action"),
    type: "post",
    data: formData,
    beforeSend: function () {
      $("#stepOneUpdate #enviaForm").hide();
      $("#commercialOrder button.btn").hide();
      $("#commercialOrder .carrega").html(
        '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
      );
    },
    success: function (dd) {
      $("#commercialOrder").trigger("reset");
      console.log(dd);
      if (dd.resp > 0) {
        $.toast({
          text: dd.mensagem,
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "success",
        });
        const id = $("#id_order").val();
        const clientid = $("#client_id").val();
        $("#stepTwoUpdate #productList").html("");

        $.get(
          `/order-to-order-list/${id}/${clientid}/step-two-products`,
          function (dd) {
            $("#stepTwoUpdate #productList").html(dd);
          }
        );
        $.get(`/order-to-order-total/${id}/${clientid}/total`, function (dd) {
          $("#valorTotal").html(dd);
        });
      } else {
        $("#stepOneUpdate #enviaForm").show();
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
        $("#commercialOrder button.btn").show();
        $("#commercialOrder .carrega").hide();
      }
    },
    error: function (dd) {
      $("#commercialOrder button.btn").show();
      $("#commercialOrder .carrega").hide();
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

$("#getOrder").click(function () {
  $("#enviaForm").trigger("click");
});

function carregaProdutos() {
  const id = $("#id_order").val();
  const clientid = $("#client_id").val();
  $("#stepTwoUpdate #productList").html("");

  $.get(
    `/order-to-order-list/${id}/${clientid}/step-two-products`,
    function (dd) {
      $("#stepTwoUpdate #productList").html(dd);
    }
  );
  $.get(`/order-to-order-total/${id}/${clientid}/total`, function (dd) {
    $("#valorTotal").html(dd);
  });
}
