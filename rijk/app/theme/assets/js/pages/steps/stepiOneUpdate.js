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

//Envia e atualiza os dados do cliente no pedido
$("#formStepOne").submit(function (e) {
  var formData = new FormData(this);
  $.ajax({
    url: $("#formStepOne").attr("action"),
    type: "post",
    data: formData,
    beforeSend: function () {
      $("#formStepOne button.btn").hide();
      $("#formStepOne .carrega").html(
        '<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>'
      );
    },
    success: function (dd) {
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
        $("#formStepOne button.btn").show();
        $("#formStepOne .carrega").hide();
        $(".stepOneUpdate a ").removeClass("active");
        $(".stepOneUpdate .content").slideUp(200);

        $(".stepTwoUpdate a ").addClass("active");
        $(".stepTwoUpdate .content").slideDown(200);
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
        $("#formStepOne button.btn").show();
        $("#formStepOne .carrega").hide();
      }
    },
    error: function (dd) {
      $("#formStepOne button.btn").show();
      $("#formStepOne .carrega").hide();
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
