
  /* const id_customer_category = $(
    "#formStepOne #id_category option:selected"
  ).val();
  const payment_type = $("#formStepOne #payment_type option:selected").val();
  $("#formStepOne #id_payment_term").html("");

  const id_payment_term_condition = $(
    "#formStepOne #id_payment_term_condition"
  ).val();

  $.get(
    `/credit-term/list/${id_customer_category}/${payment_type}`,
    function (dd) {
      console.log(dd);

      if (dd == null) {
        $.toast({
          text: "There is no Payment Term registered for this customer category in the system! Register before placing an order",
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "error",
        });
      }

      $("#formStepOne #id_payment_term").append(
        `<option value="0"> Select</option>`
      );
      $.map(dd, function (element, index) {
        $("#formStepOne #id_payment_term").append(
          `<option value="${element.id}" ${
            parseInt(id_payment_term_condition) === element.id ? "selected" : ""
          }> ${element.deadline}</option>`
        );
      });
    }
  ); */
});

/* /* *
 * Step 1 - Definição Cliente
 */
$(`#getOrderStepOne`).hide();
$(".selectSearch").select2();

$("#id_payment_term").on("change", function () {
  $(`#getOrderStepOne`).prop("disabled", false);
  $(`#getOrderStepOne`).show();
});
//Customer
$("#id_customer").on("change", function () {
  let id = $(this).val();
  $.get(`/order-to-order/client/${id}`, function (dd) {
    console.log(dd);
    $(`#variety`).prop("disabled", false);
    $("#form #input_name").val(dd.name);
    $("#cliente_id").val(id);
    $(`#id_category option[value=${dd.id_category_customer}]`).prop(
      "selected",
      true
    );
    $(`#payment_type`).prop("disabled", false);
  });
});

//Payment Type
$("#payment_type").on("change", function () {
  $("#payment_type").css("border", "1px solid #ced4da");
  if (parseInt($(this).val()) === 1) {
    $(".cash_payment").show();
    $(".cash_payment_type").removeClass("col-md-7").addClass("col-md-4");
    $("#cash_payment").val($("#cash_payment_old").val());
    $(`#id_payment_term, #delivery`).prop("disabled", false);
  } else if (parseInt($(this).val()) === 2) {
    $(".cash_payment_type").removeClass("col-md-4").addClass("col-md-7");
    $(".cash_payment").hide();
    $(`#id_payment_term`).prop("disabled", false);
  } else {
  }

  const id_customer_category = $("#id_category option:selected").val();
  const payment_type = $("#payment_type option:selected").val();
  $("#id_payment_term").html("");
  $.get(
    `/credit-term/list/${id_customer_category}/${payment_type}`,
    function (dd) {
      console.log(dd);

      if (dd == null) {
        $.toast({
          text: "There is no Payment Term registered for this customer category in the system! Register before placing an order",
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "error",
        });
      }

      $("#id_payment_term").append(`<option value="0"> Select</option>`);
      $.map(dd, function (element, index) {
        $("#id_payment_term").append(
          `<option value="${element.id}"> ${element.deadline}</option>`
        );
      });
    }
  );

  if (parseInt($(this).val()) === 7) {
    $("#dinMod").show();
  } else {
    $("#dinMod").hide();
  }
});

/**
 *
 * Cadastro de produtos com desconto normal fa categoria
 *
 * */
//Variety
$("#commercialOrder #variety").on("change", function () {
  const id = $(this).val();

  $.ajax({
    url: `/product/list/${id}`,
    type: "get",
    beforeSend: function (dd) {
      $("#form button.btn").hide();
      $("#commercialOrder #id_stock").html(`<option class="env">Please wait loading</option>`);
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
        $("#product").html(``);
        $("#product").append(`<option value="0">Select</option>`);
        $.map(dd, function (element, index) {
          $("#product").append(
            `<option value="${element.id}"> ${element.package} - R$ ${element.value}</option>`
          );
        });
        $(`#product, #commercialOrder #id_stock, #quantity, #variation`).prop("disabled", false);
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
$("#commercialOrder #product").on("change", function () {
  $("#id_stock option").remove();
  $(`#id_stock`).prop("disabled", false);
  let id = $(this).val();
  $.get(`/order-to-order/product/${id}`, function (dd) {
    $(`.checkbox input`).prop("checked", false);
    $("#id_stock").html(``);
    $("#id_stock").append(`<option value="0">Select</option>`);
    $.map(dd, function (element, index) {
      $(`#id_stock`).prop("disabled", false);
      $("#id_stock").append(
        `<option value="${element.id}">${element.id_package} - R$ ${element.valor}</option>`
      );
    });
  });
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
  $.ajax({
    url: `/discount/list/${id}`,
    type: "get",
    beforeSend: function (dd) {
      $("#commercialOrder button.btn").hide();
      $("#commercialOrder #discount").html(
        `<option class="env">Please wait loading</option>`
      );
    },
    success: function (dd) {
      if (dd === 0) {
        $.toast({
          text: "No Variation found",
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "error",
        });
      } else {
        $("#commercialOrder #discount").html(``);
        $("#commercialOrder #discount").append(
          `<option value="0">Select</option>`
        );
        $.map(dd, function (element, index) {
          if (parseInt(quantity) >= parseInt(element.value)) {
            $("#commercialOrder #discount").append(
              `<option value="${element.percentage}"> > ${element.value}</option>`
            );
          }
          $(`#commercialOrder #discount, #commercialOrder #btn_prod`).prop(
            "disabled",
            false
          );
        });
      }
    },
  });
});

/**
 *
 * Cadastro de produtos com desconto normal fa categoria
 *
 * */
//Variety
$("#bonusOrder #variety").on("change", function () {
  const id = $(this).val();

  $.ajax({
    url: `/product/list/${id}`,
    type: "get",
    beforeSend: function (dd) {
      $("#bonusOrder button.btn").hide();
      $("#product").html(`<option class="env">Please wait loading</option>`);
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
        $("#bonusOrder #product").html(``);
        $("#bonusOrder #product").append(`<option value="0">Select</option>`);
        $.map(dd, function (element, index) {
          $("#bonusOrder #product").append(
            `<option value="${element.id}"> ${element.package} - R$ ${element.value}</option>`
          );
        });
        $(
          `#bonusOrder #product, #bonusOrder #id_stock, #bonusOrder #quantity, #bonusOrder #variation`
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
$("#bonusOrder #product").on("change", function () {
  $("#id_stock option").remove();
  $(`#id_stock`).prop("disabled", false);
  let id = $(this).val();
  $.get(`/order-to-order/product/${id}`, function (dd) {
    $(`.checkbox input`).prop("checked", false);
    $("#id_stock").html(``);
    $("#id_stock").append(`<option value="0">Select</option>`);
    $.map(dd, function (element, index) {
      $(`#id_stock`).prop("disabled", false);
      $("#id_stock").append(
        `<option value="${element.id}">${element.id_package} - R$ ${element.valor}</option>`
      );
    });
  });
});

//Packaging
$("#bonusOrder #id_stock").on("change", function () {
  if (parseInt($(this).val()) > 0) {
    $(`#bonusOrder #quantity`).prop("disabled", false);
  } else {
    $(`#bonusOrder #quantity`).prop("disabled", true);
  }
});

//Quantity in MX
$("#bonusOrder #quantity").on("focusout", function () {
  const quantity = $(this).val();
  const id = $(`#bonusOrder #variety option`).filter(":selected").val();
  $.ajax({
    url: `/discount/list/${id}`,
    type: "get",
    beforeSend: function (dd) {
      $("#bonusOrder button.btn").hide();
      $("#bonusOrder #discount").html(
        `<option class="env">Please wait loading</option>`
      );
    },
    success: function (dd) {
      if (dd === 0) {
        $.toast({
          text: "No Variation found",
          showHideTransition: "fade",
          position: "top-right",
          loader: false,
          icon: "error",
        });
      } else {
        $("#bonusOrder #discount").html(``);
        $("#bonusOrder #discount").append(`<option value="0">Select</option>`);
        $.map(dd, function (element, index) {
          if (parseInt(quantity) >= parseInt(element.value)) {
            $("#bonusOrder #discount").append(
              `<option value="${element.percentage}"> > ${element.value}</option>`
            );
          }
          $(`#bonusOrder #discount, #bonusOrder #btn_prod`).prop(
            "disabled",
            false
          );
        });
      }
    },
  });
});

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

//$('.cash_payment').hide();

$("#bonus_order").on("change", function () {
  if (parseInt($(this).val()) === 1) {
  }
});

$("#additional_discount").on("change", function () {
  let additional_discount = $(this).val();
  let desconto = $("#discount_form").val();
  let cash_payment =
    $("#cash_payment").val() == "Select" ? 0 : $("#cash_payment").val();
  let total = $("#totalParc").val();
  let novoTotal =
    parseFloat(desconto) +
    parseFloat(additional_discount) +
    parseFloat(cash_payment);
  let porcentagem = (parseFloat(total) * parseFloat(novoTotal)) / 100;
  $(`#desconto`).val(novoTotal);
  $(`#total`).val(formatter.format(total - porcentagem));
  $(`#total_form`).val(total - porcentagem);
});

$("#cash_payment").on("change", function () {
  let cash_payment = $(this).val();
  let desconto = $("#discount_form").val();
  let additional_discount =
    $("#additional_discount").val() == "Select"
      ? 0
      : $("#additional_discount").val();
  let total = $("#totalParc").val();
  let novoTotal =
    parseFloat(desconto) +
    parseFloat(additional_discount) +
    parseFloat(cash_payment);
  let porcentagem = (parseFloat(total) * parseFloat(novoTotal)) / 100;
  $(`#desconto`).val(novoTotal);
  $(`#total`).val(formatter.format(total - porcentagem));
  $(`#total_form`).val(total - porcentagem);
});

$("#salanova").on("change", function () {
  let id = $(this).val();
  if (parseInt(id) === 1) {
    $(`#id_customer`).prop("disabled", true);
  }
  $(`#id_salanova`).prop("disabled", false);
});

$("#id_salanova").on("change", function () {
  $(`#id_customer`).prop("disabled", false);
});

$("#bonus_order").on("change", function () {
  $(`#id_customer`).prop("disabled", false);
});

$("#id_payment_term").on("change", function () {
  $(`#bonus_order, #getOrder`).prop("disabled", false);
  $(`#getOrder`).show();
});

$("#bonus_order").on("change", function () {
  console.log(this);
  if (parseFloat($(this).val()) === 1) {
    $(".bonus").show();
  } else {
    $(".bonus").hide();
  }
});

/**
 * Form Ações
 */
$("#cart").submit(function (e) {
  var formData = new FormData(this);
  $.ajax({
    url: $("#cart").attr("action"),
    type: "post",
    data: formData,
    beforeSend: function () {
      $("#form button.btn").hide();
      $(".carrega").html(
        '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
      );
    },
    success: function (dd) {
      $("#cart").trigger("reset");
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
        carregaProdutos();
        valorPedido();
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

$("#getOrder").click(function () {
  $("#enviaForm").trigger("click");
});

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
        carregaProdutos();
        valorPedido();
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

function carregaProdutos() {
  let id = $("select#id_customer option").filter(":selected").val();
  $.ajax({
    url: `/order-to-order/products/${id}`,
    type: "GET",
    beforeSend: function () {
      $(".carrega").html(
        '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
      );
    },
    success: function (dd) {
      $("#productList").html(dd);
      $(".carrega").hide();
    },
  });
} 

function valorPedido() {
  let id = $("select#id_customer option").filter(":selected").val();
  $.ajax({
    url: `/order-to-order/products/total/${id}`,
    type: "GET",
    beforeSend: function () {
      $(".carrega").html(
        '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
      );
    },
    success: function (dd) {
      let desconto = $("#discount_form").val();
      let porcentagem = (parseFloat(dd.total) * parseFloat(desconto)) / 100;
      $("#total").val(formatter.format(parseFloat(dd.total) - porcentagem));
      $("#total_parc").val(formatter.format(dd.total));
      $("#totalParc").val(dd.total);
      $("#total_form").val(parseFloat(dd.total) - porcentagem);
    },
  });
}
 */