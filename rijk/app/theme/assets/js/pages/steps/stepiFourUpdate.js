$(".selectSearch").select2();
$("#salanova button.btn").hide();
/**
 *
 * Cadastro de produtos com desconto normal fa categoria
 *
 * */
//Variety
$("#stepFourUpdate #variety_sala_nova").on("change", function () {
  const id = $(this).val();
  $("#salanova button.btn").hide();
  $.ajax({
    url: `/product/list/${id}`,
    type: "get",
    beforeSend: function (dd) {
      $("#salanova button.btn").hide();
      $("#stepFourUpdate #id_stock_sala_nova").html(
        `<option class="env">Please wait loading</option>`
      );
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
        //$("#product").html(``);
      } else {
        $("#stepFourUpdate #id_stock_sala_nova").html(``);
        $("#stepFourUpdate #id_stock_sala_nova").append(
          `<option value="0">Select</option>`
        );
        $.map(dd, function (element, index) {
          $("#stepFourUpdate #id_stock_sala_nova").append(
            `<option value="${element.id}"> ${element.package} - R$ ${element.value}</option>`
          );
        });
        $(
          `#stepFourUpdate #id_stock_sala_nova, #stepFourUpdate #id_stock_sala_nova, #stepFourUpdate #quantity_sala_nova, #stepFourUpdate #variation_sala_nova, #stepFourUpdate #aditional_discount_sala_nova`
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
$("#stepFourUpdate #id_stock_sala_nova").on("change", function () {
  if (parseInt($(this).val()) > 0) {
    $(`#stepFourUpdate #quantity_sala_nova`).prop("disabled", false);
  } else {
    $(`#stepFourUpdate #quantity_sala_nova`).prop("disabled", true);
  }
});

//Quantity in MX
$("#stepFourUpdate #quantity_sala_nova").on("focusout", function () {
  const quantity = $(this).val();
  $(` #stepFourUpdate #sala_nova_sala_nova`).prop("disabled", false);
  //$("#salanova button.btn").show();
  $(`#stepFourUpdate #sala_nova_sala_nova`).prop("disabled", false);
});

$("#stepFourUpdate #sala_nova_sala_nova").on("change", function () {
  if (parseInt($(this).val()) > 0) {
    $("#salanova button.btn").show();
  }
});

//Novo item no carrinho
$("#salanova").submit(function (e) {
  var formData = new FormData(this);
  $.ajax({
    url: $("#salanova").attr("action"),
    type: "post",
    data: formData,
    beforeSend: function () {
      $("#salanova button.btn").hide();
      $("#stepFourUpdate .carrega").html(
        '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
      );
    },
    success: function (dd) {
      $("#salanova").trigger("reset");
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
      const clientid = $("#client_id_sala_nova").val();
      $("#stepFourUpdate #productList_sala_nova").html("");

      $.get(
        `/order-to-order-list/${id}/${clientid}/step-four-products`,
        function (dd) {
          $("#stepFourUpdate #productList_sala_nova").html(dd);
        }
      );
      $.get(`/order-to-order/${id}/${clientid}/total`, function (dd) {
        $("#valorTotal").html(dd);
      });
    },
    error: function (dd) {
      $("#salanova button.btn").show();
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

$("#getOrder").click(function () {
  $("#enviaForm").trigger("click");
});

function carregaProdutos() {
  const id = $("#id_order").val();
  const clientid = $("#client_id_sala_nova").val();
  $("#stepFourUpdate #productList_sala_nova").html("");

  $.get(
    `/order-to-order-list/${id}/${clientid}/step-four-products`,
    function (dd) {
      $("#stepFourUpdate #productList_sala_nova").html(dd);
    }
  );

  $.get(`/order-to-order-total/${id}/${clientid}/total`, function (dd) {
    $("#valorTotal").html(dd);
  });
}
