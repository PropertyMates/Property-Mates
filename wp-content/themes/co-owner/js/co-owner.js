const calculateSale = (listPrice, discount) => {
  listPrice = parseFloat(listPrice);
  discount = parseFloat(discount);
  var response = (listPrice * discount) / 100;
  return isNaN(response) && !isFinite(response) ? 0 : response.toFixed(0);
};

const calculateShare = (share, take_it, total_price) => {
  share = parseInt(share);
  take_it = parseInt(take_it);
  total_price = parseFloat(total_price);
  let result = (take_it * total_price) / share;
  return isNaN(result) && !isFinite(result) ? 0 : result.toFixed(0);
};

const swalCoOwnerDefault = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-orange btn-rounded",
    cancelButton: "btn btn-white btn-rounded mx-3",
  },
  buttonsStyling: false,
  reverseButtons: true,
});

const get_file_size = (_size, is_label = false) => {
  if (is_label) {
    let fSExt = ["Bytes", "KB", "MB", "GB"],
      i = 0;
    while (_size > 900) {
      _size /= 1024;
      i++;
    }
    return Math.round(_size * 100) / 100 + (is_label ? " " + fSExt[i] : "");
  } else {
    return parseFloat(_size / (1024 * 1024)).toFixed(2);
  }
};

jQuery(function ($) {
  $(".select2-multiple-taggable").select2({
    tags: true,
    tokenSeparators: [","],
  });

  $(".single-select2").each(function (index, element) {
    let options = {
      dropdownParent: $(this).parent(),
    };
    if ($(this).data("search") == false) {
      options.minimumResultsForSearch = Infinity;
    }

    $(element).select2(options);
  });

  $(".single-pr-select2")
    .each(function (index, element) {
      $(element).select2({
        tags: true,
        dropdownParent: $(this).parent(),
        containerCssClass: " pr-for-price",
        dropdownCssClass: " pr-for-price",
        createTag: function (params) {
          var term = $.trim(params.term);

          if (term === "") {
            return null;
          }

          let max = $(element).data("max") ? $(element).data("max") : 99.0;

          let value =
            parseFloat(max) < parseFloat(term)
              ? max
              : parseFloat(term).toFixed(0);

          if ($(element).find("option[value='" + term + "']").length > 0) {
            return null;
          }

          return {
            id: value,
            text: value + "%",
            newTag: true,
          };
        },
      });
    })
    .on("select2:open", function (e) {
      let self = $(this);
      if ($(this).hasClass("property-input-disable")) {
        $(".select2-search--dropdown").remove();
      }
      price_input = self.closest(".card").find("input.price.input-only-price");
      if (price_input.length > 0) {
        price_input.val(null);
      }
    });

  $(document).on(
    "keypress input",
    ".pr-for-price input.select2-search__field",
    function (e) {
      if (
        (event.which != 46 ||
          (event.which == 46 && $(this).val() == "") ||
          $(this).val().indexOf(".") != -1) &&
        (event.which < 48 || event.which > 57)
      ) {
        event.preventDefault();
      }
      let value = $(this).val();
      if (value > 99) {
        $(this).val(99);
      }
    }
  );

  $(document).on("click", ".go-to-href", function (e) {
    var href = $(this).data("href");
    if (href) {
      window.location.href = href;
    }
  });

  $(".js-select2-with-image").each(function (index, element) {
    $(element).select2({
      dropdownParent: $(this).parent(),
      templateResult: format_with_image,
    });
  });

  function format_with_image(option) {
    if (!option.id) {
      return option.text;
    }
    let html = "";
    let type = $(option.element).data("type");
    if (type == "connection") {
      let profile = $(option.element).data("profile");

      html =
        '<span class="me-1 list-thumb"><img src="' +
        profile +
        '" alt=""></span>';
    } else {
      html = '<span class="me-1">' + php_vars.svg.pool + "</span>";
    }
    var $option = $('<span class="d-flex">' + html + option.text + "</span>");
    return $option;
  }

  $("#user-preferred-location").on("select2:select", function (e) {
    let user_pref_location = $(this);
    var data = e.params.data;
    let value = data.id;
    if (value != "all") {
      $("#user-preferred-location option[value=all]")
        .prop("selected", false)
        .trigger("change");
    } else {
      user_pref_location.val(["all"]).trigger("change");
    }
  });
  $("#show-more-option").hide();
  $("#view-more-options").click(function () {
    $(".progress")
      .removeClass("d-none")
      .find(".progress-bar")
      .css("width", "100%");
    $("#view-less-options").show();
    $("#show-more-option").slideDown("slow");
    $(this).hide();
  });
  $("#view-less-options").click(function () {
    $(".progress").addClass("d-none").find(".progress-bar").css("width", "0%");
    $("#show-more-option").slideUp("slow");
    $(this).hide();
    $("#view-more-options").show();
  });
});

/* Set the width of the sidebar to 250px and the left margin of the page content to 250px */
function openNav() {
  document.getElementById("mySidebar").style.left = "0";
}

/* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
function closeNav() {
  document.getElementById("mySidebar").style.left = "-900px";
}
