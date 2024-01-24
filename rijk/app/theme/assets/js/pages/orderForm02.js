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
$(".selectSearch").select2();

//Payment Type
$("#payment_type").on("change", function () {
  $("#cash_payment_table").html("");

  $("#id_payment_term, #delivery, #delivery_address, #cash_payment")
    .val(0)
    .trigger("change");

  $("#payment_type").css("border", "1px solid #ced4da");
  if (parseInt($(this).val()) === 1) {
    $(".cash_payment").show();
    $("#cash_payment").val($("#cash_payment_old").val());
    $(`#id_payment_term`).prop("disabled", false);
  } else if (parseInt($(this).val()) === 2) {
    $(".cash_payment").hide();
    $(`#id_payment_term`).prop("disabled", false);
    $("#cash_payment").val(0);
  } else {
    $(".cash_payment").hide();
    $("#cash_payment").val(0);
  }

  const id_customer_category = $("#id_customer_category").val();
  const payment_type = $("#payment_type option:selected").val();
  $("#id_payment_term").html("");
  $.get(
    `/credit-term/list/${id_customer_category}/${payment_type}`,
    function (dd) {
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

$("#bonus_order").on("change", function () {
  if (parseInt($(this).val()) === 1) {
  }
});

$("#variety").on("change", function () {
  const id = $(this).val();

  $.ajax({
    url: `/product/list/${id}`,
    type: "get",
    beforeSend: function (dd) {
      $("#form button.btn").hide();
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
      } else {
        $("#product").html(``);
        $("#product").append(`<option value="0">Select</option>`);
        $.map(dd, function (element, index) {
          $("#product").append(
            `<option value="${element.id}"> ${element.batch} - ${element.name}</option>`
          );
        });

        $(`#product, #id_stock, #quantity, #variation`).prop("disabled", false);
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

$(`#btn_prod`).prop("disabled", true);
$("#quantity").on("focusout", function () {
  const quantity = $(this).val();
  const id = $(`#variety option`).filter(":selected").val();
  $.ajax({
    url: `/discount/list/${id}`,
    type: "get",
    beforeSend: function (dd) {
      $("#form button.btn").hide();
      $("#discount").html(`<option class="env">Please wait loading</option>`);
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
        $("#discount").html(``);
        $("#discount").append(`<option value="0">Select</option>`);
        $.map(dd, function (element, index) {
          if (parseInt(quantity) >= parseInt(element.value)) {
            $("#discount").append(
              `<option value="${element.percentage}"> > ${element.value}</option>`
            );
          }
          $(`#discount, #btn_prod`).prop("disabled", false);
        });
      }
    },
  });
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
  $("#id_payment_term, #delivery, #delivery_address").val(0).trigger("change");
});

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
    $(`#additional_discount, #payment_type`).prop("disabled", false);
  });
});

$("#id_payment_term").on("change", function () {
  $("#cash_payment_table").html("");
  if (parseInt($(this).val()) > 0) {
    // const cash_payment = $("#cash_payment option:selected").val();
    // const payment_type = $("#payment_type option:selected").val();
    // const id_payment_term = $("#id_payment_term option:selected").val();
    // const id_client = $("#id_customer").val();
    // const id_order = $("#id").val();
    // $.ajax({
    //   url: `/order-to-order/order-payment-type`,
    //   type: "post",
    //   data: {
    //     id_client,
    //     id_order,
    //     cash_payment,
    //     payment_type,
    //     id_payment_term,
    //   },
    //   success: function (dd) {
    //     console.log(dd);
    //   },
    //   error: function (dd) {},
    // });

    $(`#delivery`).prop("disabled", false);
  } else {
    $(`#delivery`).prop("disabled", true);
  }
});

$("#product").on("change", function () {
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

$("#bonus_order").on("change", function () {
  console.log(this);
  if (parseFloat($(this).val()) === 1) {
    $(".bonus").show();
  } else {
    $(".bonus").hide();
  }
});

$("#id_stock").on("change", function () {
  if (parseInt($(this).val()) > 0) {
    $(`#quantity`).prop("disabled", false);
  } else {
    $(`#quantity`).prop("disabled", true);
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
      console.log(dd);
      $("#cart").trigger("reset");
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
  $(`#quotation`).val("");
  $("#enviaForm").trigger("click");
});

$("#getOrderQuotation").click(function () {
  $(`#quotation`).val(12);
  $("#enviaForm").trigger("click");
});

$("#form").submit(function (e) {
  var formData = new FormData(this);

  const order_date = $(`#order_date`).val();
  const payment_type = $(`#payment_type option`).filter(":selected").val();
  const delivery = $(`#delivery option`).filter(":selected").val();
  const delivery_address = $(`#delivery_address option`)
    .filter(":selected")
    .val();

  const id_payment_term = $(`#id_payment_term option`)
    .filter(":selected")
    .val();

  const condition =
    parseInt(delivery) === 3
      ? order_date.length > 9 &&
        parseInt(delivery) > 0 &&
        parseInt(id_payment_term) > 0
      : order_date.length > 9 &&
        parseInt(delivery) > 0 &&
        parseInt(delivery_address) > 0 &&
        parseInt(id_payment_term);
  if (condition) {
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
  } else {
    $.toast({
      text: "Oops! fill in the mandatory fields",
      showHideTransition: "fade",
      position: "top-right",
      loader: false,
      icon: "error",
    });
  }
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
  if (parseInt($(this).val()) > 0) {
    let total_value_novo = 0;
    const id = $(this).val();
    const total = $("#totalParc").val();
    const totalWeight = $("#totalWeight").val();
    const idOrder = $("#id").val();
    console.log(totalWeight);
    const delivery = $(`#delivery option`).filter(":selected").val();
    const delivery_address = $(`#delivery_address option`)
      .filter(":selected")
      .val();
    $.get(
      `/order-to-order/freight-calculation/${id}/${totalWeight}/${idOrder}`,
      function (dd) {
        console.log(dd);
        if (parseInt(delivery) === 2) {
          let porcentagem_fin = parseFloat(dd.taxa.replace(",", "."));
          let resultado_fin =
            parseFloat(dd.valor.replace(",", ".")) * (porcentagem_fin / 100);
          total_freight_novo =
            resultado_fin + parseFloat(dd.valor.replace(",", "."));

          $("#freight_value").html(`+ ${formatter.format(total_freight_novo)}`);
          $("#freight").val(total_freight_novo);
          $("#tax").val(dd.id_taxa);

          let valor_fin = parseFloat(total_freight_novo) + parseFloat(total);

          total_value_novo = valor_fin;

          // if (parseFloat(dd.cash_payment) > 0) {
          //   let value_price = resultado_fin + valor_fin;
          //   total_value_novo =
          //     value_price - value_price * (parseFloat(dd.cash_payment) / 100);

          //   $("#cash_payment_table").html(
          //     `<td style="color: red;">Discount Cash payment:</td><td align="right"><div style="color: red;">- ${formatter.format(
          //       value_price * (parseFloat(dd.cash_payment) / 100)
          //     )}</div></td> `
          //   );
          // }
          //console.log(total_value_novo);
          //$("#icms_value").html(`${formatter.format(resultado_fin)}`);
          $("#tax_value").val(resultado_fin);
          $("#total_value").html(
            `<strong> ${formatter.format(total_value_novo)}</strong>`
          );

          $("#total_value_db").val(total_value_novo);
        } else {
          let valor_fin = parseFloat(total);
          //let porcentagem_fin = parseFloat(dd.taxa);
          //let resultado_fin = valor_fin * (porcentagem_fin / 100);
          //total_value_novo = resultado_fin + valor_fin;
          total_value_novo = valor_fin;

          // if (parseFloat(dd.cash_payment) > 0) {
          //   let value_price = resultado_fin + valor_fin;
          //   total_value_novo =
          //     value_price - value_price * (parseFloat(dd.cash_payment) / 100);

          //   $("#cash_payment_table").html(
          //     `<td style="color: red;">Discount Cash payment:</td><td align="right"><div style="color: red;">- ${formatter.format(
          //       value_price * (parseFloat(dd.cash_payment) / 100)
          //     )}</div></td> `
          //   );
          // } else {
          //   total_value_novo = resultado_fin + valor_fin;
          // }

          $("#freight").val(0);
          $("#tax").val(dd.id_taxa);
          $("#tax_value").val(resultado_fin);

          $("#freight_value").html(`+ R$ 0.00`);
          $("#icms_value").html(`${formatter.format(resultado_fin)}`);
          $("#total_value").html(
            `<strong> ${formatter.format(total_value_novo)}</strong>`
          );
          $("#total_value_db").val(total_value_novo);
        }
      }
    );
  }
});

$("#delivery").on("change", function () {
  $("table #freight").removeClass("hide");
  if (parseInt($(this).val()) > 0) {
    if (parseInt($(this).val()) === 3) {
      $(".delivery_address_label").hide();
      let total_value_novo = 0;
      const id = $("#id_customer_state").val();
      const total = $("#totalParc").val();
      const totalWeight = $("#totalWeight").val();
      const idOrder = $("#id").val();
      const delivery = $(`#delivery option`).filter(":selected").val();
      const delivery_address = $(`#delivery_address option`)
        .filter(":selected")
        .val();
      $.get(
        `/order-to-order/freight-calculation/${id}/${totalWeight}/${idOrder}/retirada`,
        function (dd) {
          if (parseInt(delivery) === 2) {
            let porcentagem_fin = parseFloat(dd.taxa.replace(",", "."));
            let resultado_fin =
              parseFloat(dd.valor.replace(",", ".")) * (porcentagem_fin / 100);
            total_freight_novo =
              resultado_fin + parseFloat(dd.valor.replace(",", "."));

            $("#freight_value").html(
              `+ ${formatter.format(total_freight_novo)}`
            );
            $("#freight").val(total_freight_novo);

            $("#tax").val(dd.id_taxa);

            // let valor_fin = parseFloat(dd.valor) + parseFloat(total);
            // let porcentagem_fin = parseFloat(dd.taxa);
            // let resultado_fin = valor_fin * (porcentagem_fin / 100);
            // total_value_novo = resultado_fin + valor_fin;

            // if (parseFloat(dd.cash_payment) > 0) {
            //   let value_price = resultado_fin + valor_fin;
            //   total_value_novo =
            //     value_price - value_price * (parseFloat(dd.cash_payment) / 100);

            //   $("#cash_payment_table").html(
            //     `<td style="color: red;">Discount Cash payment:</td><td align="right"><div style="color: red;">- ${formatter.format(
            //       value_price * (parseFloat(dd.cash_payment) / 100)
            //     )}</div></td> `
            //   );
            // } else {
            //   total_value_novo = resultado_fin + valor_fin;
            // }

            $("#icms_value").html(`${formatter.format(resultado_fin)}`);
            $("#tax_value").val(resultado_fin);

            $("#total_value").html(
              `<strong> ${formatter.format(total_value_novo)}</strong>`
            );
            $("#total_value_db").val(total_value_novo);
          } else {
            let valor_fin = parseFloat(total);
            let porcentagem_fin = parseFloat(dd.taxa);

            let resultado_fin = valor_fin * (porcentagem_fin / 100);
            total_value_novo = resultado_fin + valor_fin;

            $("#freight").val(0);
            $("#tax").val(dd.id_taxa);
            $("#tax_value").val(total_value_novo);

            $("#freight_value").html(`+ R$ 0.00`);
            $("table #freight").addClass("hide");
            $("#total_value").html(
              `<strong> ${formatter.format(total_value_novo)}</strong>`
            );
            $("#total_value_db").val(total_value_novo);
          }
        }
      );
    } else {
      $(".delivery_address_label").show();

      $(`#delivery_address`).prop("disabled", false);
      $("#delivery_address").prop("selectedIndex", 0);
    }
  }
});

function deletar(id) {
  $.ajax({
    url: `/order-to-order/delete`,
    type: "delete",
    data: { id },
    success: function (dd) {
      //console.log(dd);
      $.toast({
        text: dd,
        showHideTransition: "fade",
        position: "top-right",
        loader: false,
        icon: "success",
      });
      setTimeout(function () {
        window.location.reload();
      }, 500);
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
