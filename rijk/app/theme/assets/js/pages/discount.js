$("#value").mask("#.##0", {
  reverse: true,
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
  $.get(`/discount/${id}`, function (dd) {
    console.log(dd);
    $("#new").modal("show");
    $("#form #value").val(dd.value);
    $("#form #percentage").val(dd.percentage);
    $("#form #id_variety").val(dd.id_variety);
    $("#form #id").val(dd.id);
    $("#form h4").text("Update Discount");
    $("#form p").text(`Update Discount > ${dd.value}`);
    $("#form button").text("Update");
    $("#form").attr("action", "/discount/update");
  });
}

function deletar(id) {
  $.ajax({
    url: `/discount/delete`,
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

$.get(`/discount/lists/all`, function (dd) {
  var table = $("#root").tableSortable({
    data: dd,
    columns: {
      value: "Value",
      percentage: "Percentage",
      id_variety: "Variety",
      actions: "",
    },
    searchField: "#searchField",
    responsive: {
      1100: {
        columns: {
          value: "Value",
          percentage: "Percentage",
          id_variety: "Variety",
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
