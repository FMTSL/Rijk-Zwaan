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
  $(".envClassPit").addClass("hide");
  $(".envClass").removeClass("hide");

  $.get(`/customer-category/${id}`, function (dd) {
    console.log(dd);
    $("#new").modal("show");
    $("#form #input_name").val(dd.name);
    $("#form #basic_discount").val(dd.basic_discount);
    $("#form #cash_payment_discount").val(dd.cash_payment_discount);
    $("#form #goal_discount").val(dd.goal_discount);
    $("#form #goal_introduction").val(dd.goal_introduction);
    $("#form #id").val(dd.id);
    $("#form h4").text("Update Category");
    $("#form p").text(`Update Category ${dd.name}`);
    $("#form button").text("Update");
    $("#form").attr("action", "/customer-category/update");
  });
}

function updateCredit(id) {
  $("#new").modal("show");
  $(".envClass").addClass("hide");
  $(".envClassPit").removeClass("hide");
  $("#form").attr("action", "/customer-category/update");
  $(".checkbox").attr("data-id-cat", id);
  $.get(`/customer-category-deadline/${id}`, function (dd) {
    $(`.checkbox input`).prop("checked", false);
    $.map(dd, function (element, index) {
      $(`#deadline_${element}`).prop("checked", true);
    });
  });
}

$(".checkbox input").change(function () {
  if ($(this).is(":checked")) {
    const id_category = $(".checkbox").attr("data-id-cat");
    const id_credit_deadline = $(this).val();
    $.ajax({
      url: `/customer-category-deadline/new`,
      type: "post",
      data: { id_credit_deadline, id_category },
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
  } else {
    const id_category = $(".checkbox").attr("data-id-cat");
    const id_credit_deadline = $(this).val();
    $.ajax({
      url: `/customer-category-deadline/delete`,
      type: "delete",
      data: { id_credit_deadline, id_category },
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
  }
});
//envClassPit

function deletar(id) {
  $.ajax({
    url: `/customer-category/delete`,
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
$.get(`/customer-category/lists/all`, function (dd) {
  var table = $("#root").tableSortable({
    data: dd,
    columns: {
      name: "Name",
      code: "Code",
      basic_discount: "Discount basic",
      cash_payment_discount: "Discount in advance",
      goal_discount: "Discount sales targets",
      goal_introduction: "1st year bonus",
      actions: "",
    },
    searchField: "#searchField",
    responsive: {
      1100: {
        columns: {
          name: "Name",
          code: "Code",
          basic_discount: "Discount basic",
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
