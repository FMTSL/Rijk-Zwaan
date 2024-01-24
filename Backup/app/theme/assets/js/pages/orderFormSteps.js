/**
 * Accordion Staps
 */
$(document).ready(function () {
  $(".set > a").on("click", function () {
    if ($(this).hasClass("active")) {
      $(this).removeClass("active");
      $(this).siblings(".content").slideUp(200);
      $(this)
        .siblings("i")
        .removeClass("fa-chevron-down")
        .addClass("fa-chevron-ups");
    } else {
      $(this)
        .siblings("i")
        .removeClass("fa-chevron-down")
        .addClass("fa-chevron-up");
      $(this)
        .find("i")
        .removeClass("fa-chevron-up")
        .addClass("fa-chevron-down");
      $(this).removeClass("active");
      $(this).addClass("active");
      $(this).siblings(".content").slideUp(200);
      $(this).siblings(".content").slideDown(200);
    }
  });
});

function deletar(id) {
  $.ajax({
    url: `/order-to-order/delete`,
    type: "delete",
    data: { id },
    success: function (dd) {
      console.log(dd);
      $.toast({
        text: dd,
        showHideTransition: "fade",
        position: "top-right",
        loader: false,
        icon: "success",
      });
      location.reload();
    },
  });
  return false;
}
