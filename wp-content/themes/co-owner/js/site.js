 /*For Signup*/
 var register_temp_email_id =''; 
 var edit_list_obj= {};
 
 var email_verified_code_error=false;
 function getSearchParams(k){
 var p={};
 location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
 return k?p[k]:p;
}
	
	function autoTab(field1, len, field2) {
		if (document.getElementById(field1).value.length == len) {
			document.getElementById(field2).focus();
			}
	}	
	
 /*For Signup end*/	
jQuery(function ($) {
  $(function () {
	
    jQuery(".remove-my-parent").parent().parent().remove();

    $('[data-toggle="tooltip"]').tooltip();

    $(document).on("change", "#property-view select", function (e) {
      $("#property-view").submit();
    });

    if (php_vars.alert === "property_mark_as_completed") {
      toastr["success"]("Property Mark As Completed Successfully.");
      remove_url_segment("alert");
    } else if (php_vars.alert === "you_cannot_edit_this_property") {
      toastr["error"]("You cannot edit this property.");
      remove_url_segment("alert");
    } else if (php_vars.alert === "property_not_found") {
      toastr["error"]("Property Not Found.");
      remove_url_segment("alert");
    } else if (php_vars.alert === "your_account_is_inactive") {
      toastr["error"]("Your account is deactivated.");
      remove_url_segment("alert");
    } else if (php_vars.alert === "your_account_linked") {
      toastr["success"]("Your account is linked successfully.");
      remove_url_segment("alert");
    } else if (
      php_vars.alert === "your_account_already_linked_to_other_account"
    ) {
      toastr["error"]("Your account is already linked to another account.");
      remove_url_segment("alert");
    } else if (php_vars.alert === "your_account_already_linked") {
      toastr["error"]("Your account already linked.");
      remove_url_segment("alert");
    } else if (
      $.inArray(php_vars.alert, ["error", "success", "warning", "info"]) >= 0 &&
      php_vars.hasOwnProperty("alert_message")
    ) {
      toastr[php_vars.alert](php_vars.alert_message);
      remove_url_segment("alert");
      remove_url_segment("alert_message");
    } else if (
      php_vars.query.hasOwnProperty("alert") &&
      php_vars.query.hasOwnProperty("group_id") &&
      php_vars.query.hasOwnProperty("id")
    ) {
      let url =
        window.location.origin +
        "/messages/?is_pool=true&with=" +
        php_vars.query.group_id;
      let message =
        "New member have been added to your Pool.\n" +
        "<br><br>" +
        "Also chat room has been created. Go to messages and enjoy pool group messaging\n" +
        "<br><br>" +
        "<a href='" +
        url +
        "'>Go to Pool - Chat Room</a>";
      toastr.success(message);
      remove_url_segment("alert");
      remove_url_segment("group_id");
    } else if (
      php_vars.query.hasOwnProperty("alert") &&
      php_vars.query.alert === "new_member_added"
    ) {
      let message = "New member have been added to your Pool.";
      toastr.success(message);
      remove_url_segment("alert");
    }

    $("#notification-dropdown").on("show.bs.dropdown", function () {
      let self = $(this);
      let html =
        '<li class="notification-spinner">' +
        '<div class="d-flex justify-content-center message py-3">' +
        '<div class="d-flex">' +
        '<div class="spinner-border" role="status"></div>' +
        "</div>" +
        "</div>" +
        "</li>";
      self.parent().find("ul").html(html);
      co_owner_ajax(
        { action: "get_my_notifications", is_block: false },
        function (response) {
          self.parent().find("ul").html(response.html);
        }
      );
    });

    $(".notification-dropdown").on("click", function (event) {
      let self = event.target;
      event.stopPropagation();
      if ($(self).is("a")) {
        if (
          $(self).hasClass("notify-reject-action") ||
          $(self).hasClass("notify-accept-action")
        ) {
          change_user_connection_status(self);
        } else if ($(self).hasClass("load-more-notifications")) {
          let li = $(self).parent("li");
          let notification_spinner =
            '<div class="d-flex justify-content-center message py-3"><div class="d-flex"><div class="spinner-border" role="status"></div></div></div>';
          li.html(notification_spinner);
          let page = $(self).data("current-page");

          co_owner_ajax(
            { action: "get_my_notifications", is_block: false, page: page + 1 },
            function (response) {
              li.closest("ul").append(response.html);
              li.closest("ul").find(".no-any-notification").remove();
              li.remove();
            }
          );
        }
      }
    });

    $(document).on("focus", "input", function (e) {
      let notification_dropdown = $(".notification-dropdown");
      if (notification_dropdown.is(":visible")) {
        $("#notification-dropdown").dropdown("toggle");
      }
    });

    if (
      php_vars.sessions.hasOwnProperty("open_subscription_modal") &&
      php_vars.sessions.open_subscription_modal == true &&
      $("#subscribed-modal").length > 0
    ) {
      //$("#subscribed-modal").modal("show");
    }

    if (
      php_vars.user_id != 0 &&
      window.location.origin != "http://co-owner.loc"
    ) {
      setInterval((e) => {
        co_owner_ajax(
          {
            action: "get_notification_count",
            is_block: false,
          },
          function (response) {
            if (response.status) {
              let notification = $("#notification-dropdown").find(
                ".notification-alert-dot"
              );
              if (response.all > 0 && !notification.hasClass("orange-circle")) {
                notification.addClass("orange-circle");
              }
            }
          }
        );
      }, 5000);
    }

    $(document).click(function (e) {
      let self = $(e.target);
      if (self.closest(".navbar-collapse").length == 0) {
        $("header").find(".navbar-collapse").collapse("hide");
      }
    });
  });

  $(function () {
    if (
      php_vars.query.hasOwnProperty("action") &&
      php_vars.query.action == "subscription"
    ) {
      if (!php_vars.is_admin) {
        $("#plan-modal").modal("show");
      }
      remove_url_segment("action");
    }
    if (
       php_vars.steps_complete==400
    ) {
      if (!php_vars.is_admin) {
        $("#property_goal").modal("show");
      }
      //remove_url_segment("register");
    }	
	
	
    $(".action-pricing").on("click", function (e) {
      e.preventDefault();
      $("#plan-modal").modal("show");
    });

    if (
      php_vars.query.hasOwnProperty("subscription") &&
      php_vars.query.subscription == "success"
    ) {
      remove_url_segment("subscription");
    }

    if (php_vars.query.hasOwnProperty("co_owner_action")) {
      remove_url_segment("co_owner_action");
    }
    let subscription_cancel = $(
      "#s2member-pro-stripe-cancellation-form-submission-section-title"
    );
    if (subscription_cancel.length > 0) {
      subscription_cancel.html("Click to cancel your subscription.");
    }
    let submit_btn = $("#s2member-pro-stripe-cancellation-submit");
    if (submit_btn.length > 0) {
      submit_btn.html("Cancel subscription.");
    }
  });

  var australiaCenter = { lat: -25.274398, lng: 133.775136 };

  toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: false,
    progressBar: false,
    positionClass: "toast-bottom-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
  };

  setJqueryDefaultValidationSettings();

  let subscription_plans = ".subscription-plans-co-owners";
  if ($(subscription_plans).length > 0) {
    let count = $(subscription_plans).find(".plan-card").length;
    $(subscription_plans).owlCarousel({
      loop: false,
      margin: 32,
      nav: true,
      dots: false,
      stageClass: "owl-stage d-flex",
      responsive: {
        0: { items: 1 },
        768: { items: count },
        1000: { items: count },
        1400: { items: count },
      },
    });
  }

  document.addEventListener(
    "wpcf7mailsent",
    function (event) {
      location = window.location.origin + "/contact-us/?submitted=true";
    },
    false
  );

  $(document).on("click", '[href="#how-it-work-video"]', function (e) {
    e.preventDefault();
    $("#staticBackdrop").modal("show");
  });

  $(document).on("hide.bs.modal", "#staticBackdrop", function (e) {
    var video = $(this).find("video").get(0);
    if (video) video.pause();
  });

  $(document).on("focusout input", ".room-counter input", function (e) {
    if ($(this).val().length == 0) {
      $(this).val(0);
    }
  });

  $(document).on("click", '[href="#"]', function (e) {
    e.preventDefault();
  });

  $(document).on("input", ".input-username", function (e) {
    $(this).val($(this).val().replace(" ", ""));
  });

  $(document).on(
    "keypress input",
    ".input-only-number,.room-counter input",
    function (e) {
      if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57)) {
        return false;
      }
      var input = $(this);
      var value = input.val() ? input.val() : 0;
      let max = input.data("max");
      if (max !== undefined && max < value) {
        $(this).val(max);
        event.preventDefault();
        return false;
      }
    }
  );

  $(document)
    .on("keypress input", ".input-only-price", function (event) {
      if (
        (event.which != 46 ||
          (event.which == 46 && $(this).val() == "") ||
          $(this).val().indexOf(".") != -1) &&
        (event.which < 48 || event.which > 57)
      ) {
        event.preventDefault();
      }
      let max = $(this).data("max");

      if (max !== undefined && max < $(this).val()) {
        $(this).val(max);
        event.preventDefault();
      }
    })
    .on("input", function (event) {
      let max = $(this).data("max");
      if (max !== undefined && max < $(this).val()) {
        $(this).val(max);
        event.preventDefault();
      }
    });

  $(document).on("input", ".count-character", function (e) {
    $(this).parent("div").find(".character-length").html($(this).val().length);
  });

  $(document).on("click", ".room-counter .counter-minus", function (e) {
    var input = $(this).parent(".room-counter").find("input");
    var value = input.val() ? input.val() : 0;
    if (value - 1 >= 0) {
      input.val(parseInt(value) - parseInt(1));
    }
  });

  $(document).on("click", ".room-counter .counter-plus", function (e) {
    var input = $(this).parent(".room-counter").find("input");
    var value = input.val() ? input.val() : 0;
    if (value >= 99) {
      input.val(99);
    } else {
      input.val(parseInt(value) + parseInt(1));
    }
  });

  $(document).on("focusout", ".room-counter input", function (e) {
    var input = $(this);
    var value = input.val() ? input.val() : 0;
    input.val(value);
  });

  $(document).on("change", ".property-share-input", function (e) {
    var propertyShareInput = $(this).val();
    var propertyValueInput = $($(this).data("property-value-input"));
    var calculatedValueInput = $($(this).data("calculated-value-input"));

    if (propertyValueInput.length > 0 && calculatedValueInput.length > 0) {
      if (
        propertyValueInput.is(":visible") === true &&
        calculatedValueInput.is(":visible") === true
      ) {
        let calculated = calculateSale(
          propertyValueInput.val(),
          propertyShareInput
        );
        calculatedValueInput.val(calculated);
      } else {
        calculatedValueInput.val(null);
      }
    }
  });

  $(document).on("input", ".property-value-input", function (e) {
    var propertyValueInput = $(this).val();
    var propertyShareInput = $($(this).data("property-share-input"));
    var calculatedValueInput = $($(this).data("calculated-value-input"));

    if (propertyShareInput.length > 0 && calculatedValueInput.length > 0) {
      if (
        propertyShareInput.is(":visible") === true &&
        calculatedValueInput.is(":visible") === true
      ) {
        var share = propertyShareInput.val();
        share = share == null ? 0 : share;
        var calculated = calculateSale(propertyValueInput, share);
        calculatedValueInput.val(calculated);
      } else {
        propertyShareInput.val(null);
        calculatedValueInput.val(null);
      }
    }
  });



  $(document).on("click", ".mark-as-complete-my-property", function (e) {
    let box = $(this).closest(".property-box");
    let id = $(this).data("id");
    let status = $(this).hasClass("complete") ? "complete" : "in-complete";
    if (id) {
      swalCoOwnerDefault
        .fire({
          title: "Are you sure?",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, Update it!",
          cancelButtonText: "Cancel!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            co_owner_ajax(
              {
                id: id,
                action: "property_mark_as_status",
                status: status,
              },
              function (response) {
                swalCoOwnerDefault
                  .fire(
                    response.status ? "Great!" : "Oops...",
                    response.message,
                    response.status ? "success" : "warning"
                  )
                  .then((result) => {
                    if (php_vars.page === "my-listings") {
                      box.fadeOut(500, () => box.remove());
                      // if (status == 'complete') {
                      //     box.find('.property-completed').show();
                      //     box.find('.complete.mark-as-complete-my-property').hide();
                      //     box.find('.incomplete.mark-as-complete-my-property').show();
                      // } else {
                      //     box.find('.property-completed').hide();
                      //     box.find('.complete.mark-as-complete-my-property').show();
                      //     box.find('.incomplete.mark-as-complete-my-property').hide();
                      // }
                    } else {
                      if (response.status) {
                        window.location.reload();
                      }
                    }
                  });
              }
            );
          }
        });
    }
  });

  $(document).on("click", ".duplicate-my-property", function (e) {
    let box = $(this).closest(".property-box");
    let id = $(this).data("id");
    if (id) {
      co_owner_ajax(
        {
          id: id,
          action: "make_duplicate_property",
        },
        function (response) {
          swalCoOwnerDefault
            .fire(
              response.status ? "Great!" : "Oops...",
              response.message,
              response.status ? "success" : "warning"
            )
            .then((result) => {
              if (response.status) {
                window.location.reload();
              }
            });
        }
      );
    }
  });

  $(document).on(
    "click",
    ".make-property-like,.make-property-dislike",
    function (e) {
      let like_or_not = !!$(this).hasClass("make-property-like");
      let activeAndDislikeClasses = "active make-property-dislike";
      let inactiveAndLikeClasses = "make-property-like";
      let id = $(this).data("id");
      let action = $(this).hasClass("people")
        ? "make_people_like_dislike"
        : "make_property_like_dislike";
      if (id) {
        let self = $('.property-thumb-top a[data-id="' + id + '"]');
        $.ajax({
          url: php_vars.ajax_url,
          method: "POST",
          data: {
            id: id,
            like: like_or_not,
            action: action,
            ajax_nonce: php_vars.ajax_nonce,
          },
          success(response) {
            response = JSON.parse(response);
            if (response.status) {
              self
                .addClass(
                  like_or_not ? activeAndDislikeClasses : inactiveAndLikeClasses
                )
                .removeClass(
                  like_or_not ? inactiveAndLikeClasses : activeAndDislikeClasses
                );
            } else {
              if (response.message) {
                toastr["error"](response.message);
              }
            }
          },
        });
      }
    }
  );

  $(document).on(
    "click",
    ".add-to-shortlist,.remove-to-shortlist",
    function (e) {
      let self = $(this);
      let is_like = self.hasClass("add-to-shortlist");
      let id = self.data("id");
      let action = self.hasClass("person")
        ? "make_people_like_dislike"
        : "make_property_like_dislike";
      if (id) {
        $.ajax({
          url: php_vars.ajax_url,
          method: "POST",
          data: {
            id: id,
            like: is_like,
            action: action,
            ajax_nonce: php_vars.ajax_nonce,
          },
          success(response) {
            response = JSON.parse(response);
            if (response.status) {
              self.html(is_like ? "Remove From Shortlist" : "Shortlist");
              self.addClass(
                is_like ? "remove-to-shortlist" : "add-to-shortlist"
              );
              self.removeClass(
                !is_like ? "remove-to-shortlist" : "add-to-shortlist"
              );
            } else {
              if (response.message) {
                toastr["error"](response.message);
              }
            }
          },
        });
      }
    }
  );

  $(document).on("input", ".next-focus", function (e) {
    let next_focus = $(this).data("next-focus");
    if (next_focus.length > 0) {
      $(next_focus).focus();
    }
  });

  $(document).on("change", ".property-share-selection", function () {
    let available_share = $(this).data("property-available-share");
    let available_price = $(this).data("property-available-price");
    let calculated_input = $($(this).data("calculated-input"));
    let pr = $(this).val();
    if (calculated_input.length > 0 && available_price && available_share) {
      let calculated_value = 0.0;
      if (pr) {
        calculated_value = calculateShare(available_share, pr, available_price);
      }
      calculated_input.val(calculated_value);
    }
  });

  $(document).on("click", ".remove-group-member", function (e) {
    let self = $(this);
    let member_id = self.data("id");
    let group_id = self.data("group-id");
    if (member_id && group_id) {
      swalCoOwnerDefault
        .fire({
          title: "Are you sure?",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, Remove it!",
          cancelButtonText: "Cancel!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            co_owner_ajax(
              {
                user_id: member_id,
                group_id: group_id,
                action: "remove_from_group_connection",
              },
              function (response) {
                toastr[response.status ? "success" : "error"](response.message);
                if (response.status && php_vars.page === "property-details") {
                  self.closest(".member-box").fadeOut(1000, () => {
                    self.remove();
                    window.location.reload();
                  });
                }
              }
            );
          }
        });
    }
  });

  $(document).on(
    "change",
    ".inner-price-cal .price.input-only-price",
    function () {
      let inputShare = $(this).closest(".inner-price-cal").find("select.share");
      inputShare.val(null).change();
    }
  );

  $(document).on("click", ".calculate-price", function (e) {
    let inputPrice = $(this).closest(".inner-price-cal").find("input.price");
    let inputShare = $(this).closest(".inner-price-cal").find("select.share");
    let price = inputPrice.val();
    let share = inputShare.val();
    if (price || share) {
      let availableShare = $(this).data("available-share");
      let availablePrice = $(this).data("available-price");

      if (parseInt(price) > 0 && (share == null || share == "")) {
        let calculatedShare =
          (parseFloat(price) * parseFloat(availableShare)) /
          parseFloat(availablePrice);
        if (!isNaN(calculatedShare)) {
          calculatedShare = calculatedShare.toFixed(2);
          if (
            inputShare.find("option[value='" + calculatedShare + "']").length ==
            0
          ) {
            let newOption = new Option(
              calculatedShare + "%",
              calculatedShare,
              true,
              true
            );
            inputShare.append(newOption);
          }
          inputShare.val(calculatedShare).change();
        }
      } else {
        let calculatedPrice =
          (parseFloat(share) * parseFloat(availablePrice)) /
          parseFloat(availableShare);
        if (!isNaN(calculatedPrice)) {
          if (parseFloat(calculatedPrice).toString().indexOf(".") >= 0) {
            calculatedPrice = parseFloat(calculatedPrice).toFixed(0);
          }
          inputPrice.val(calculatedPrice);
        }
      }
    }
  });

  $(document).on("show.bs.modal", "#property-connection-modal", function (e) {
    let inputShare = $(".inner-price-cal").find("select.share").val();
    let availableShare = $("#person-connection-form").data("available-share");
    $("#property-share-options")
      .val(
        inputShare && inputShare <= availableShare ? parseInt(inputShare) : null
      )
      .change();
  });

  $(document).on("input", "input,textarea,select", function () {
    let self = $(this);
    if (self.closest("form").length > 0 && self.hasClass("--ignore") == false) {
      $(self.closest("form")).validate().element(this);
    }
  });

  $(document).on("click", ".delete-group-action", function (e) {
    let self = $(this);
    let connection_id = $(this).data("id");
    swalCoOwnerDefault
      .fire({
        title: "Are you sure?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Delete it!",
        cancelButtonText: "Cancel!",
      })
      .then((result) => {
        if (result.isConfirmed) {
          co_owner_ajax(
            {
              group_id: connection_id,
              action: "remove_group_and_connection",
            },
            function (response) {
              if (response.status) {
                if (php_vars.page == "my-connections") {
                  self.closest(".member-card").parent("div").remove();
                  toastr.success(response.message);
                } else {
                  window.location.href = response.link;
                }
              } else {
                toastr.error(response.message);
              }
            }
          );
        }
      });
  });

  $(document).on("click", ".leave-group-action", function (e) {
    let self = $(this);
    let group_id = $(this).data("id");
    let user_id = $(this).data("user-id");
    swalCoOwnerDefault
      .fire({
        title: "Are you sure?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Leave it!",
        cancelButtonText: "Cancel!",
      })
      .then((result) => {
        if (result.isConfirmed) {
          co_owner_ajax(
            {
              is_leave: true,
              user_id: user_id,
              group_id: group_id,
              action: "remove_from_group_connection",
            },
            function (response) {
              if (response.status) {
                if (php_vars.page == "my-connections") {
                  self.closest(".member-card").parent("div").remove();
                  toastr.success(response.message);
                } else {
                  window.location.href = response.link;
                }
              } else {
                toastr.error(response.message);
              }
            }
          );
        }
      });
  });

  $(document).on("click", ".user-remove-action", function (e) {
    let self = $(this);
    let user_id = $(this).data("id");
    let connection_id = $(this).data("connection-id");
    swalCoOwnerDefault
      .fire({
        title: "Are you sure?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Remove it!",
        cancelButtonText: "Cancel!",
      })
      .then((result) => {
        if (result.isConfirmed) {
          co_owner_ajax(
            {
              user_id: user_id,
              connection_id: connection_id,
              action: "remove_user_connection",
            },
            function (response) {
              if (response.status) {
                if (php_vars.page == "my-connections") {
                  self.closest(".member-card").parent("div").remove();
                  toastr.success(response.message);
                } else {
                  window.location.href = response.link;
                }
              } else {
                toastr.error(response.message);
              }
            }
          );
        }
      });
  });

  $(document).on("click", ".check-login-toaster-alert", function (e) {
    if (
      php_vars.hasOwnProperty("user_id") &&
      (php_vars.user_id == 0 || php_vars.user_id == null)
    ) {
      toastr.error("You are not logged in. Please login");
    }
  });

  user_shield_tooltip();

  if (php_vars.is_front_page) {
    var propertyFilterBox = $("#front-page-property-filter-box");
    if (propertyFilterBox.length > 0) {
      propertyFilterBox.validate({
        ignore: ".ignore-validate,:not([name])",
        rules: {
          p_price: { required: true },
        },
      });
    }
    var peopleFilterBox = $("#front-page-people-filter-box");
    if (peopleFilterBox.length > 0) {
      peopleFilterBox.validate({
        ignore: ".ignore-validate,:not([name])",
        rules: {
          p_budget: { required: true },
        },
      });
    }

    $("#user-selling").on("change", function (e) {
      var type = $(this).val();
      var fullPropertyBox = $(".banner-main .full-property-input");
      var propertyValueInput = $(".banner-main .property-value-input");
      var calculatedValueInput = $(".banner-main .calculated-value-input");

      if (type === "full_property") {
        $("#sell #calculated-value").removeAttr("name");
        $("#sell #property-value").attr("name", "p_budget");
        propertyValueInput.prop("name", "p_budget");
        calculatedValueInput.removeAttr("name");
        fullPropertyBox
          .addClass("d-none")
          .find("input")
          .addClass("ignore-validate");
      } else {
        $("#sell #property-value").removeAttr("name");
        $("#sell #calculated-value").attr("name", "p_budget");
        propertyValueInput.removeAttr("name");
        calculatedValueInput.prop("name", "p_budget");
        fullPropertyBox
          .removeClass("d-none")
          .find("input")
          .removeClass("ignore-validate");
      }
    });

    if ($(".properties-need-co-owners").length > 0) {
      $(".properties-need-co-owners").owlCarousel({
        loop: false,
        margin: 32,
        nav: true,
        dots: false,
        stageClass: "owl-stage d-flex",
        responsive: {
          0: { items: 1 },
          768: { items: 2 },
          1000: { items: 3 },
          1400: { items: 4 },
        },
      });
    }
	if ($(".our-community-owlslider").length > 0) {
      $(".our-community-owlslider").owlCarousel({
        loop: false,
        margin: 32,
        nav: true,
        dots: false,
        stageClass: "owl-stage d-flex",
        responsive: {
          0: { items: 1 },
          768: { items: 2 },
          1000: { items: 3 },
          1400: { items: 3 },
        },
      });
    }

    if ($(".people-looking-for-properties").length > 0) {
      $(".people-looking-for-properties").owlCarousel({
        loop: false,
        margin: 32,
        nav: true,
        dots: false,
        stageClass: "owl-stage d-flex",
        responsive: {
          0: { items: 1 },
          768: { items: 2 },
          1000: { items: 3 },
          1400: { items: 4 },
        },
      });
    }

    if ($(".checkout-the-pools-already-created").length > 0) {
      $(".checkout-the-pools-already-created").owlCarousel({
        loop: false,
        margin: 32,
        nav: true,
        dots: false,
        stageClass: "owl-stage d-flex",
        responsive: {
          0: { items: 1 },
          600: { items: 1 },
          1000: { items: 1 },
          1200: { items: 2 },
        },
      });
    }

    if ($(".property-shares-sliders").length > 0) {
      $(".property-shares-sliders").owlCarousel({
        loop: false,
        margin: 32,
        nav: true,
        dots: false,
        stageClass: "owl-stage d-flex",
        responsive: {
          0: { items: 1 },
          768: { items: 2 },
          1000: { items: 3 },
          1400: { items: 4 },
        },
      });
    }

    if ($(".why-choose-us").length > 0) {
      $(".why-choose-us").owlCarousel({
        loop: true,
        margin: 22,
        nav: true,
        dots: false,
        responsive: {
          0: {
            items: 1,
          },
          768: {
            items: 2,
          },
          1000: {
            items: 2,
          },
          1300: {
            items: 3,
          },
        },
      });
    }

    $(document).on("change", ".property-shares-price", function (e) {
      let price = $(this).val();
      redirect_home_to_property_list(price, null);
    });

    $(document).on("click", ".property-shares-view-property", function (e) {
      let price = $(".property-shares-price").val();
      let state = $(this).data("state");
      redirect_home_to_property_list(price, state);
    });

    $(document).on("click", ".is-your-property-alert", function (e) {
      toastr.error("This is your property.");
    });
  }

  /*For Signup*/
  if (php_vars.page === "login") {
    $("form").validate({
      rules: {
        user_login: { required: true },
        user_password: { required: true },
      },
    });
  } else if (php_vars.page === "register") {
    if (php_vars.query.length == 0) {
      $("#plan-modal").modal("show");
    }

    $("#co-owner-user-register").validate({
      ignore: "",
      rules: {
        _user_plan_type: { required: true },
        first_name: { required: true },
        last_name: { required: true },
        email: { required: true },
        verify_code_1: { required: false, number: true },
        verify_code_2: { required: false, number: true },
        verify_code_3: { required: false, number: true },
        verify_code_4: { required: false, number: true },
      /*  mobile: { required: true }, */
        password: {
          required: true,
          remote: {
            url: php_vars.ajax_url,
            type: "post",
            data: {
              action: "php_password_validation",
              password: function () {
                return $(
                  '#co-owner-user-register :input[name="password"]'
                ).val();
              },
            },
          },
        },
        terms_and_condition: { required: true },
      },
      messages: {
       verify_code_1: { required: "Please Verify Code." },
        verify_code_2: { required: "Please Verify Code." },
        verify_code_3: { required: "Please Verify Code." },
        verify_code_4: { required: "Please Verify Code." },
        email: {
          remote: function () {
            let error = $('input[name="email"]').data("error");
            return error ? error : "Email is invalid.";
          },
        },
        password: {
          remote:
            "Password should be at least 8 to 16 characters in length and should include at least one upper case letter, one number, and one special character.",
        },
        _user_plan_type: { required: "Please select your plan." },
      },
      submitHandler: function (form) {
        check_is_verified_email();
		
        let email = $("#user-email").val().trim();
        let verified_user_email = $(".verify-user-email").data("email");
        if (
          verified_user_email === email ||
          verified_user_email !== undefined
        ) { 
          var from_data = $(form).serializeArray();
          from_data.push({ name: "action", value: "co_owner_user_register" });
          from_data.push({ name: "ajax_nonce", value: php_vars.ajax_nonce });
          $.ajax({
            url: php_vars.ajax_url,
            method: "POST",
            data: from_data,
            beforeSend() {
              $.blockUI();
            },
            success(response) {
              $.unblockUI();
              response = JSON.parse(response);
              if (response.status === true) {
                let parms =
                  "username=" +
                  response.username +
                  "&password=" +
                  response.password;
						   /*
							window.location.href =
							  window.location.origin + "?co_owner_auto_login&" + parms;
			*/
                    jQuery('#property_goal').modal('show');
				


              } else {
                show_alerts(response.message, "danger");
              }
            },
            error() {
              $.unblockUI();
            },
          });
       } 
        return false;
      },
    });

	
	/*For Signup
		var typingTimer;                //timer identifier
		var doneTypingInterval = 1000;  //time in ms, 5 second for example
		var $input = $('#user-email');

		//on keyup, start the countdown
		$input.on('keyup', function () {
		  clearTimeout(typingTimer);
		  typingTimer = setTimeout(doneTyping, doneTypingInterval);
		});

		//on keydown, clear the countdown 
		$input.on('keydown', function () {
		  clearTimeout(typingTimer);
		});

		//user is "finished typing," do something
		function doneTyping () {
			  var  email = $("#user-email").val().trim();
			  console.log('input hit');
			  jQuery('.verified-user-email').hide();
			check_is_verified_email();
		}	
		*/
	
/* For Signup */
	    $("#user-email").focusout(function () {
		var  email = $("#user-email").val().trim();		   
        check_is_verified_email();
		 if(!$('.email-verify-code-input').hasClass('d-none')){
			  $(".verified-user-email").addClass("d-none");
		 }
		
    });

    $(".user-mobile-no").focusout(function () {
      check_is_verified_mobile($(".user-mobile-no"));
    });

    $(".verify-user-email").on("click", function () {

      var email = $("#user-email").val().trim();
      
      var self = $(this);

      if (
        email &&
        (self.data("verified") === undefined || self.data("verified") === false)
      ) {
        var verify_code_1 = $("#verify_code_1").val().trim();
        var verify_code_2 = $("#verify_code_2").val().trim();
        var verify_code_3 = $("#verify_code_3").val().trim();
        var verify_code_4 = $("#verify_code_4").val().trim();

        if (verify_code_1 && verify_code_2 && verify_code_3 && verify_code_4) {
          var code =
            verify_code_1.toString() +
            verify_code_2.toString() +
            verify_code_3.toString() +
            verify_code_4.toString();
          $.ajax({
            url: php_vars.ajax_url,
            method: "POST",
            data: {
              action: "verify_user_email_code",
              email: email,
              code: code,
            },
            beforeSend() {
              // $.blockUI();
              //self.html(php_vars.svg.verifying);
              self.addClass("disabled");
            },
            success(response) {
              $.unblockUI();
              self.removeClass("disabled");
              response = JSON.parse(response);
              if (response.status === true) {
                $("#verify-code-input").addClass("d-none");
                self.data("verified", true);
                $('[name="email_verified_"]').val("true");
                $(".verified-user-email").removeClass("d-none");
                $(".verified-user-email").show();
                $(".verified-user-email").html(
                  php_vars.svg.verified + " Verified"
                );
				email_verified_code_error = false;
              } else {
                $('[name="email_verified_"]').val("false");
				var doc_val_check = "";
				$('#verify_code_1').val(doc_val_check);
				$('#verify_code_2').val(doc_val_check);
				$('#verify_code_3').val(doc_val_check);
				$('#verify_code_4').val(doc_val_check);
				if(!email_verified_code_error){
					toastr.error(response.message);
					email_verified_code_error = true;
				}
                
				
                $("#verify-code-input").removeClass("d-none");
                self.data("verified", false);
                self.html("Verify");
                $(".verified-user-email").addClass("d-none");
              }
            },
            error() {
               $.unblockUI();
            },
          });
        }
      }
	  return false;
    });

    $(document).on(
      "input change keyup keypress keydown",
      "#verify_code_1, #verify_code_2, #verify_code_3, #verify_code_4",
      function () {
        var verify_code_1 = $("#verify_code_1").val().trim();
        var verify_code_2 = $("#verify_code_2").val().trim();
        var verify_code_3 = $("#verify_code_3").val().trim();
        var verify_code_4 = $("#verify_code_4").val().trim();

        if (verify_code_1 && verify_code_2 && verify_code_3 && verify_code_4) {
          $(".verify-user-email").removeClass("disabled");
		  email_verified_code_error = false;
		  $(".verify-user-email").trigger('click');
        } else {
          $(".verify-user-email").addClass("disabled");
		  
        }
      }
    );
	
/*For SignUp*/
    $(".resend-verification-code").on("click", function () {
      send_verification_code();
    });

    $(document).on("click", ".resend-mobile-verification-code", function (e) {
      let input = $(".user-mobile-no");
      input.parent().find(".verify-user-mobile").data("mobile", null);
      check_is_verified_mobile(input);
    });

    $("#terms_and_condition").on("click", function () {
      var is_disabled = !$(this).is(":checked");
      $("#user_login_attempt").attr("disabled", is_disabled);
    });

    $(document).on("click", ".verify-user-mobile", function (e) {
      var mobile = $("#mobile-no").val().trim();
      var self = $(this);

      if (
        mobile &&
        (self.data("verified") === undefined || self.data("verified") === false)
      ) {
        var verify_code_1 = $("#mobile_verify_code_1").val().trim();
        var verify_code_2 = $("#mobile_verify_code_2").val().trim();
        var verify_code_3 = $("#mobile_verify_code_3").val().trim();
        var verify_code_4 = $("#mobile_verify_code_4").val().trim();
        if (verify_code_1 && verify_code_2 && verify_code_3 && verify_code_4) {
          var code =
            verify_code_1.toString() +
            verify_code_2.toString() +
            verify_code_3.toString() +
            verify_code_4.toString();
          //self.html(php_vars.svg.verifying);
          self.addClass("disabled");
          co_owner_ajax(
            {
              action: "verify_user_mobile_verification_code",
              mobile: mobile,
              code: parseInt(code),
              is_block: false,
            },
            function (response) {
              self.removeClass("disabled");
              if (response.status === true) {
                $(".user-mobile-no-verify-code-input").hide();
                self.data("verified", true);
                $(".verified-user-mobile").show();
                $('[name="mobile_verified_"]').val("true");
                setTimeout(() => {
                  $("#mobile_verify_code_1").focus();
                }, 30);
              } else {
                toastr.error(response.message);
                $(".user-mobile-no-verify-code-input").show();
                self.data("verified", false);
                self.html("Verify");
              }
            }
          );
        }
      }
    });

    $(document).on(
      "input change keyup keypress keydown",
      "#mobile_verify_code_1, #mobile_verify_code_2, #mobile_verify_code_3, #mobile_verify_code_4",
      function () {
        var verify_code_1 = $("#mobile_verify_code_1").val().trim();
        var verify_code_2 = $("#mobile_verify_code_2").val().trim();
        var verify_code_3 = $("#mobile_verify_code_3").val().trim();
        var verify_code_4 = $("#mobile_verify_code_4").val().trim();

        if (verify_code_1 && verify_code_2 && verify_code_3 && verify_code_4) {
          $(".verify-user-mobile").removeClass("disabled");
        } else {
          $(".verify-user-mobile").addClass("disabled");
        }
      }
    );

    $(document).on(
      "click",
      ".trial-subscription, .standard-subscription, .professional-subscription",
      function (e) {
        let plan = $(this).hasClass("trial-subscription")
          ? "trial"
          : $(this).hasClass("standard-subscription")
          ? "standard"
          : "professional";

        let spinner =
          '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ms-2">Loading...</span>';
        let plan_view_box = $("#plan-html-view");
        plan_view_box.find("a").html(spinner);
        $("#plan-modal").modal("hide");
        co_owner_ajax(
          {
            plan: plan,
            action: "get_plan_info_by_type",
            is_block: false,
          },
          function (response) {
            if (response.status) {
              $('[name="_user_plan_type"]').val(response.plan.slug);
              $("#_user_plan_type-error").remove();
              plan_view_box.html(response.html);
            } else {
              $('[name="_user_plan_type"]').val(null);
              toastr.error(response.message);
            }
          }
        );
      }
    );
  } else if (php_vars.page === "forgot-password") {
    $("#user-forgot-password").validate({
      rules: {
        email: {
          required: true,
          remote: {
            url: php_vars.ajax_url,
            type: "post",
            data: {
              action: "php_only_email_validation",
              email: function () {
                return $('input[name="email"]').val();
              },
            },
            dataFilter: function (data) {
              data = JSON.parse(data);
              $('input[name="email"]').data("error", data.message);
              return data.status;
            },
          },
        },
      },
      messages: {
        email: {
          remote: function () {
            let error = $('input[name="email"]').data("error");
            return error ? error : "Email is invalid.";
          },
        },
      },
      submitHandler: function (form) {
        var from_data = $(form).serializeArray();
        from_data.push({ name: "action", value: "user_forgot_password" });
        from_data.push({ name: "ajax_nonce", value: php_vars.ajax_nonce });
        $.ajax({
          url: php_vars.ajax_url,
          method: "POST",
          data: from_data,
          beforeSend() {
            $.blockUI();
          },
          success(response) {
            $.unblockUI();
            response = JSON.parse(response);
            show_alerts(
              response.message,
              response.status === true ? "success" : "danger"
            );
            if (response.status === true) {
              $('input[name="email"]').val(null);
            }
          },
          error() {
            $.unblockUI();
          },
        });
      },
    });
  } else if (php_vars.page === "reset-password") {
    $("#user-reset-password").validate({
      ignore: ":not([name])",
      rules: {
        password: {
          required: true,
          remote: {
            url: php_vars.ajax_url,
            type: "post",
            data: {
              action: "php_password_validation",
              password: function () {
                return $('input[name="password"]').val();
              },
            },
          },
        },
        password_confirm: {
          required: true,
          equalTo: "#password",
        },
      },
      messages: {
        password: {
          remote:
            "Password should be at least 8 to 16 characters in length and should include at least one upper case letter, one number, and one special character.",
        },
        password_confirm: {
          equalTo: "Password and Confirm password are not same.",
        },
      },
      submitHandler: function (form) {
        var from_data = $(form).serializeArray();
        from_data.push({ name: "action", value: "user_reset_password" });
        from_data.push({ name: "ajax_nonce", value: php_vars.ajax_nonce });
        $.ajax({
          url: php_vars.ajax_url,
          method: "POST",
          data: from_data,
          beforeSend() {
            $.blockUI();
          },
          success(response) {
            $.unblockUI();
            response = JSON.parse(response);
            show_alerts(
              response.message,
              response.status === true ? "success" : "danger"
            );
            if (response.status === true) {
              $(form).get(0).reset();
              $(".reset-password-box").remove();
              $(".login-user-box").show();
            }
          },
          error() {
            $.unblockUI();
          },
        });
        return;
      },
    });
  } else if (php_vars.page === "create-a-property-listing") {
    let actionHeadingOne = $("#action-heading-one");
    let actionHeadingTwo = $("#action-heading-two");
    let actionHeadingThree = $("#action-heading-three");
    let actionHeadingFour = $("#action-heading-four");

    let formOne = $("form#property-one");
    let formTwo = $("form#property-two");
    let formThree = $("form#property-three");
    let formFour = $("form#property-four");

    let nextHeadingOne = $("#next-heading-one");
    let nextHeadingTwo = $("#next-heading-two");
    let nextHeadingThree = $("#next-heading-three");
    let submitCoOwnerPropertyForm = $("#property-form-submit");
    let savePropertyPublish = $(".submit-co-owner-property-form");
    let savePropertyPreview = $("#preview-button");

    let resumable = new Resumable({
      target: "#",
      fileType: ["png", "jpg", "jpeg"],
      maxFiles: 10,
    });
    resumable.assignBrowse($("#browse-button"));
    resumable.assignDrop($("#resumable-drop-container"));
    resumable.on("fileAdded", function (file, event) {
      var reader = new FileReader();
      reader.onload = function (event) {
        var html =
          '<div class="col-md-4 col-sm-12 col-12 pb-3 images-preview">' +
          '<div class="property-up-main w-100 d-block">' +
          '<img src="' +
          event.target.result +
          '" class="img-fluid">' +
          "</div>" +
          '<a href="#" data-unique_identifier="' +
          file.uniqueIdentifier +
          '" class="text-danger remove-browse-image">Remove</a>';
        ("</div>");
        $(".pl-preview-images-box").prepend(html);
		/* Added new code for enable update listing button  */
		$('.submit-co-owner-property-form').removeClass('btn_list_disabled ');
      };
      reader.readAsDataURL(file.file);
    });
    $(document).on("click", ".remove-browse-image", function (e) {
      let file = resumable.getFromUniqueIdentifier(
        $(this).data("unique_identifier")
      );
      resumable.removeFile(file);
      $(this).parent(".images-preview").remove();
    $('.submit-co-owner-property-form').removeClass('btn_list_disabled ');
	  
    });

    $("#enable_pool0").on("change", function (e) {
      var is_checked = $(this).is(":checked");
      $("#enable_pool").prop("checked", is_checked);
    });

    $("#enable_pool").on("change", function (e) {
      var is_checked = $(this).is(":checked");
      $("#enable_pool0").prop("checked", is_checked);
    });

    $(".get-market-value-of-this-property").on("click", function (e) {
      let self = $(this);
      var address = get_property_full_address();
      if (address) {
        if (
          self.data("loading") == false ||
          self.data("loading") == undefined
        ) {
          self.data("loading", true);
          self
            .parent()
            .find(".preview-market-value-of-this-property")
            .html(null)
            .hide();
          self.html(
            '<div class="mt-1 spinner-border text-secondary" role="status"></div>'
          );
          co_owner_ajax(
            {
              address: address,
              is_block: false,
              action: "get_property_price_by_address",
            },
            function (response) {
              self.data("loading", false);
              if (response.status) {
                $(".view-market-value-of-this-property").html(response.html);
              } else {
                $(".view-market-value-of-this-property").html("");
                toastr["error"](response.message);
              }
              self.html("Get real market value of this property");
            }
          );
        }
      } else {
        toastr["error"]("Something went wrong please try again.");
      }
    });

    $(
      "form#property-one,form#property-two,form#property-three,form#property-four"
    ).on("reset", function (event) {
      var element = 'select[name="_pl_state"]';
      if ($(this).find(element).length) {
        reset_select2(element);
      }
      element = 'select[name="_pl_age_year_built"]';
      if ($(this).find(element).length) {
        reset_select2(element);
      }
      element = 'select[name="_pl_manually_features"]';
      if ($(this).find(element).length) {
        reset_select2(element);
      }
    });

    change_category_visible_or_hidden_input();
    $("input[name='_pl_property_category']").on("change", function (e) {
      change_category_visible_or_hidden_input();
    });

    change_selling_visible_or_hidden_input();
    $("input[name='_pl_interested_in_selling']").on("change", function (e) {
      change_selling_visible_or_hidden_input();
    });

    $("input[name='_pl_currently_on_leased']").on("change", function (e) {
      if ($(this).val() === "Yes") {
        $(".row.pl-rent-per-month").show();
        $(".row.pl-rent-per-month input").removeClass("ignore-validate");
      } else {
        $(".row.pl-rent-per-month").hide();
        $(".row.pl-rent-per-month input").addClass("ignore-validate").val(0);
      }
    });

    formOne.validate({
      ignore: ".ignore-validate,:not([name])",
      rules: {
        _pl_posted_by: { required: true },
        _pl_property_category: { required: true },
        _pl_property_type: { required: true },
      },
    });

    var imageRules = {
      required: true,
      extension: "jpeg|jpg|png",
    };
    var is_edit = $('[name="property_id"]');
    $.validator.addMethod(
      "check_image",
      function (value, element) {
        return resumable.files.length > 0 ||
          (is_edit.length > 0 && is_edit.val() !== "")
          ? true
          : false;
      },
      "Please select a image."
    );

    formTwo.validate({
      ignore: ".ignore-validate,:not([name])",
      rules: {
        _pl_heading: { required: true },
        _pl_descriptions: { required: true },
        _pl_address: { required: true },
        _pl_suburb: { required: true },
        _pl_street_no: { required: true },
        _pl_postcode: { required: true },
        _pl_street_name: { required: true },
        _pl_state: { required: true },
        _pl_images: {
          check_image: true,
        },
      },
      messages: {
        _pl_images: {
          required: "Please select minimum one image.",
        },
      },
    });

    formThree.validate({
      ignore: ".ignore-validate,:not([name])",
      rules: {
        _pl_land_area: { required: true },
        _pl_building_area: { required: true },
        _pl_age_year_built: { required: true },
        _pl_bedroom: { required: true },
        _pl_bathroom: { required: true },
        _pl_parking: { required: true },
        "_pl_property_features[]": { required: true },
        "_pl_manually_features[]": { required: false },
      },
      messages: {
        "_pl_property_features[]": {
          required: "Please select minimum one property feature.",
        },
      },
    });

    formFour.validate({
      ignore: ".ignore-validate,:not([name]),:hidden",
      rules: {
        _pl_interested_in_selling: { required: true },
        _pl_this_property_is: { required: true },
        _pl_currently_on_leased: { required: true },
        _pl_rent_per_month: { required: true, number: true },
        _pl_property_market_price: {
          required: true,
          maxlength: 20,
          number: true,
        },
        _pl_i_want_to_sell: { required: true },
        _pl_calculated: { required: true },
      },
    });

    nextHeadingOne.click(function () {
      check_form_and_set_progress(formOne, actionHeadingTwo, 25);
    });

    nextHeadingTwo.click(function () {
      check_form_and_set_progress(formTwo, actionHeadingThree, 50);
    });

    nextHeadingThree.click(function () {
      check_form_and_set_progress(formThree, actionHeadingFour, 75);
    });

    submitCoOwnerPropertyForm.click(function (e) {
      let is_valid = false;
      is_valid = check_form_and_set_progress(formOne, actionHeadingTwo, 25);
      if (!is_valid) {
        return;
      }
      is_valid = check_form_and_set_progress(formTwo, actionHeadingThree, 50);
      if (!is_valid) {
        return;
      }
      is_valid = check_form_and_set_progress(formThree, actionHeadingFour, 75);
      if (!is_valid) {
        return;
      }
      is_valid = check_form_and_set_progress(formFour, null, 100);
      if (!is_valid) {
        return;
      }

      var allData = $(
        "form#property-one,form#property-two,form#property-three,form#property-four"
      ).serializeArray();
      var formData = new FormData();

      for (var x = 0; x < resumable.files.length; x++) {
        formData.append("_pl_images[]", resumable.files[x].file);
      }

      $.each(allData, function (key, value) {
        formData.append(value.name, value.value);
      });
	  

      formData.append("action", "insert_property");
      formData.append("ajax_nonce", php_vars.ajax_nonce);
	  
	   /* #changed 11*/
		$('.img-fluid').each(function() {
			 formData.append("post_images[]", $(this).attr('src'));
		}); 
	   /* #changed 11*/
     

      formData.append("is_preview", $(this).data("is-preview"));

      $.ajax({
        url: php_vars.ajax_url,
        method: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend() {
          $.blockUI();
        },
        success(response) {
          $.unblockUI();
          response = JSON.parse(response);
          if (response.status === true) {
            formTwo.get(0).reset();
            formTwo.get(0).reset();
            formThree.get(0).reset();
            formFour.get(0).reset();
            $(".images-preview").remove();
            $('[for="residential-btn"]').trigger("click");

            let target = actionHeadingOne.data("bs-target");
            $(target).collapse("show");

            show_alerts(response.message, "success");
            setProgressBar(0);
            scroll_to_div();

            if (response.is_edit && response.redirect) {
              window.location.href = response.redirect;
            }
          } else {
            show_alerts(response.message, "danger");
            scroll_to_div();
          }
        },
        error() {
          $.unblockUI();
        },
      });
    });

    savePropertyPublish.on("click", function (e) {
      submitCoOwnerPropertyForm.data("is-preview", false).trigger("click");
    });
    savePropertyPreview.on("click", function (e) {
      submitCoOwnerPropertyForm.data("is-preview", true).trigger("click");
    });

    $(".delete-property-image").click(function (e) {
      let index = $(this).data("index");
      let property_id = $(this).data("property-id");
      let self = $(this);
      if (index != null && property_id) {
        swalCoOwnerDefault
          .fire({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Delete it!",
            cancelButtonText: "Cancel!",
          })
          .then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: php_vars.ajax_url,
                method: "POST",
                data: {
                  index: index,
                  property_id: property_id,
                  action: "delete_property_image",
                  ajax_nonce: php_vars.ajax_nonce,
                },
                beforeSend() {
                  $.blockUI();
                },
                success(response) {
                  response = JSON.parse(response);
                  if (response.status) {
					   jQuery('.submit-co-owner-property-form').removeClass('btn_list_disabled'); 
                    self.parent("div").remove();
                  }
                  toastr[response.status ? "success" : "error"](
                    response.message
                  );
                  $.unblockUI();
                  $(".delete-property-image").each(function (index, itm) {
                    $(itm).data("index", index);
                  });
                },
                error() {
                  $.unblockUI();
                },
              });
            }
          });
      }
    });

    $("select.property-input-disable").find("option:not(:selected)").remove();
    $(document).on(
      "click change input",
      ".property-input-disable",
      function (e) {
        e.preventDefault();
      }
    );

    $(".select2-property-address-api")
      .select2({
        minimumInputLength: 3,
        placeholder: "Search Property Address.",
        multiple: true,
        maximumSelectionLength: 1,
        dropdownCssClass: "s2-property-address-api",
        ajax: {
          url: php_vars.ajax_url,
          method: "post",
          data: function (params) {
            return {
              search: params.term,
              ajax_nonce: php_vars.ajax_nonce,
              action: "get_property_addresses",
            };
          },
          processResults: function (data) {
            data = JSON.parse(data);
            return {
              results: data.items,
            };
          },
        },
      })
      .on("select2:select", function (e) {
        let data =
          e.hasOwnProperty("params") && e.params.hasOwnProperty("data")
            ? e.params.data
            : null;
        if (data) {
          let self = $(this);
          let box = self.closest(".address-management");
          box.find('[name="_pl_unit_no"]').val(data.unitNumber);
          box.find('[name="_pl_street_no"]').val(data.streetNumber);
          box.find('[name="_pl_street_name"]').val(data.streetName);
          box.find('[name="_pl_suburb"]').val(data.suburb);
          box.find('[name="_pl_postcode"]').val(data.postCode);
          box.find('[name="_pl_state"]').val(data.state).change();
        }
      });

    $(document).on("click", ".add-manually-property-address", function (e) {
      let self = $(this);
      let box = self.closest(".address-management");
      let address_manually = box.find('[name="_pl_address_manually"]');
      let manually_box = box.find(".address-manually");
      let suggest_box = box.find(".address-by-suggest");

      if (address_manually.val() == "true") {
        manually_box.hide();
        suggest_box.slideDown();
        address_manually.val("false");
        suggest_box.find("select").removeClass("ignore-validate");
        manually_box.find("input,select").addClass("ignore-validate");
        self.html("Add Manually");
      } else {
        self.html("Add By Suggestion");
        manually_box.show();
        suggest_box.slideUp();
        address_manually.val("true");
        manually_box.find("select").val(null).change();
        manually_box.find('input[type="text"]').val(null);
        manually_box.find("input,select").removeClass("ignore-validate");
        suggest_box
          .find("select")
          .addClass("ignore-validate")
          .val(null)
          .change();
      }
    });

    $(document).on(
      "click",
      ".only-display-suburb-1,.only-display-suburb-2",
      function () {
        let self = $(this);
        let is_checked = self.prop("checked");
        let element = self.hasClass("only-display-suburb-1")
          ? $(".only-display-suburb-2")
          : $(".only-display-suburb-1");
        element.prop("checked", is_checked);
      }
    );
  } else if (
    php_vars.page === "property-list" ||
    php_vars.page === "shortlist" ||
		  php_vars.page === "property-search"  ||
    php_vars.page === "pool-property-list"
  ) {
    var properties = [];
    if ($("#google-map-view").length) {
      $(".property-info-box").each(function (index, box) {
        properties.push($(this).data("id"));
      });

      var googleMap = new google.maps.Map(
        document.getElementById("google-map-view"),
        {
          zoom: 4,
          center: australiaCenter,
        }
      );
      var bounds = new google.maps.LatLngBounds();
    }

    if (properties.length > 0) {
      $.ajax({
        url: php_vars.ajax_url,
        method: "POST",
        data: {
          ajax_nonce: php_vars.ajax_nonce,
          action: "get_property_address",
          properties: properties,
        },
        success(response) {
          response = JSON.parse(response);
          if (response.status === true && response.data.length) {
            let map = new GoogleGeocode();
            for (i = 0; i < response.data.length; i++) {
              let id = response.data[i].id;
              map.geocode(response.data[i].address, function (data) {
                if (data != null) {
                  put_marker_on_google(
                    googleMap,
                    bounds,
                    data.latitude,
                    data.longitude,
                    id
                  );
                }
                if (response.data.length == 1) {
                  googleMap.setZoom(15);
                }
              });
            }
          }
        },
      });
    }
  } else if (php_vars.page === "create-a-person-listing") {
    let personPreview = $("#person-preview-button");
    let personPublish = $(".person-publish-button");

    let formOne = $("form#person-one");
    let formTwo = $("form#person-two");
    let formThree = $("form#person-three");
    let formFour = $("form#person-four");

    let actionHeadingOne = $("#action-heading-one");
    let actionHeadingTwo = $("#action-heading-two");
    let actionHeadingThree = $("#action-heading-three");
    let actionHeadingFour = $("#action-heading-four");

    let nextHeadingOne = $("#next-heading-one");
    let nextHeadingTwo = $("#next-heading-two");
    let nextHeadingThree = $("#next-heading-three");
    let submitCoOwnerPersonForm = $(".submit-co-owner-person-form");

    $("#enable_pool0").on("change", function (e) {
      var is_checked = $(this).is(":checked");
      $("#enable_pool").prop("checked", is_checked);
    });

    $("#enable_pool").on("change", function (e) {
      var is_checked = $(this).is(":checked");
      $("#enable_pool0").prop("checked", is_checked);
    });

    $("form#person-one").on("reset", function (event) {
      element = 'select[name="_user_preferred_location"]';
      if ($(this).find(element).length) {
        reset_select2(element);
      }
      element = 'select[name="_user_age_year_built"]';
      if ($(this).find(element).length) {
        reset_select2(element);
      }
      element = 'select[name="_user_manually_features"]';
      if ($(this).find(element).length) {
        reset_select2(element);
      }
    });

    formOne.validate({
      ignore: ":hidden,:not([name])",
      rules: {
        "_user_property_category[]": { required: true },
        "_user_preferred_location[]": { required: true },
        // "_user_property_type[]": { required: true },
        user_budget_price: { required: true },
      },
      messages: {
        "_user_property_category[]": {
          required: "Plese select property category.",
        },
        "_user_property_category[]": {
          required: "Location is required",
        },
        // "_user_property_type[]": { required: "Plese select property type." },
        user_budget_price: { required: "Plese select budget range." },
      },
    });

    // formOne.validate({
    //   ignore: ":not([name])",
    //   rules: {
    //     // _user_descriptions: { required: true, maxlength: 3000 },
    //     "_user_preferred_location[]": { required: true },
    //   },
    // });

    // formOne.validate({
    //   ignore: ".ignore,:not([name])",
    //   rules: {
    //     _user_land_area: { required: true },
    //     _user_building_area: { required: true },
    //     _user_age_year_built: { required: true },
    //     _user_bedroom: { required: true, number: true },
    //     _user_bathroom: { required: true, number: true },
    //     _user_parking: { required: true, number: true },
    //     "_user_property_features[]": { required: true },
    //   },
    //   messages: {
    //     "_user_property_features[]": {
    //       required: "Please select a minimum one property feature.",
    //     },
    //   },
    // });

    // nextHeadingOne.click(function () {
    //   check_form_and_set_progress(formOne, actionHeadingTwo, 25);
    // });

    // nextHeadingTwo.click(function () {
    //   let is_valid = check_form_and_set_progress(formOne, actionHeadingTwo, 25);
    //   if (is_valid) {
    //     check_form_and_set_progress(formTwo, actionHeadingThree, 50);
    //   }
    // });

    // nextHeadingThree.click(function () {
    //   let is_valid = check_form_and_set_progress(formOne, actionHeadingTwo, 25);
    //   if (is_valid) {
    //     is_valid = check_form_and_set_progress(formTwo, actionHeadingThree, 50);
    //     if (is_valid) {
    //       let is_validate = check_form_and_set_progress(
    //         formThree,
    //         actionHeadingFour,
    //         75
    //       );
    //       if (is_validate) {
    //         personPreview.show();
    //       }
    //     }
    //   }
    // });

    submitCoOwnerPersonForm.click(function () {
      let is_valid = false;
      is_valid = check_form_and_set_progress(formOne, null, 100);
      if (!is_valid) {
        return;
      }
      // is_valid = check_form_and_set_progress(formTwo, actionHeadingThree, 50);
      // if (!is_valid) {
      //   return;
      // }
      // is_valid = check_form_and_set_progress(formThree, actionHeadingFour, 75);
      // if (!is_valid) {
      //   return;
      // }
      // is_valid = check_form_and_set_progress(formFour, null, 100);
      // if (!is_valid) {
      //   return;
      // }

      var allData = $("form#person-one").serializeArray();
      var formData = new FormData();

      $.each(allData, function (key, value) {
        formData.append(value.name, value.value);
      });

      formData.append("action", "create_person_listing");
      formData.append("ajax_nonce", php_vars.ajax_nonce);

      let status = submitCoOwnerPersonForm.data("is-preview");
      formData.append("_user_listing_status", status ? 1 : 2);

      $.ajax({
        url: php_vars.ajax_url,
        method: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend() {
          $.blockUI();
        },
        success(response) {
          $.unblockUI();
          response = JSON.parse(response);
          if (response.status === true && response.hasOwnProperty("link")) {
            window.location.href = response.link;
          } else {
            show_alerts(
              response.message,
              response.status ? "success" : "danger"
            );
            scroll_to_div();
          }
        },
        error() {
          $.unblockUI();
        },
      });
    });

    $("#preview-person-modal")
      .on("show.bs.modal", function () {
        let previewSection = $("#preview-section");

        previewSection.find(".property-category").html(null);
        let categoryCheckbox = $(
          "input[name='_user_property_category[]']:checked"
        );
        categoryCheckbox.each(function (i, el) {
          previewSection
            .find(".property-category")
            .append(
              uc_first($(this).val()) +
                (categoryCheckbox.length - 1 === i ? "" : ", ")
            );
        });

        previewSection.find(".property-type").html(null);
        let typeCheckbox = $("input[name='_user_property_type[]']:checked");
        typeCheckbox.each(function (i, el) {
          previewSection
            .find(".property-type")
            .append(
              uc_first($(this).val()) +
                (typeCheckbox.length - 1 === i ? "" : ", ")
            );
        });

        previewSection
          .find(".property-descriptions")
          .val($("[name='_user_descriptions']").val());

        let userPreferredLocation = $(
          "[name='_user_preferred_location[]']"
        ).val();
        previewSection.find(".property-preferred-location").html(null);
        $(userPreferredLocation).each(function (i, el) {
          previewSection
            .find(".property-preferred-location")
            .append(
              uc_first(el) +
                (userPreferredLocation.length - 1 === i ? "" : ", ")
            );
        });

        let landArea = $("[name='_user_land_area']").val();
        previewSection.find(".land-area").html(landArea);

        let buildingArea = $("[name='_user_building_area']").val();
        previewSection.find(".building-area").html(buildingArea);

        let ageYearBuilt = $("[name='_user_age_year_built']").val();
        previewSection.find(".age-year-built").html(ageYearBuilt);

        let bedroom = $("[name='_user_bedroom']").val();
        previewSection.find(".bedroom").html(bedroom);

        let bathroom = $("[name='_user_bathroom']").val();
        previewSection.find(".bathroom").html(bathroom);

        let parking = $("[name='_user_parking']").val();
        previewSection.find(".parking").html(parking);

        previewSection.find(".property-features").html(null);
        let propertyFeatures = $(
          "input[name='_user_property_features[]']:checked"
        );
        propertyFeatures.each(function (i, el) {
          var html =
            '<div class="col-md-3 mb-2">' + uc_first($(this).val()) + "</div>";
          previewSection.find(".property-features").append(html);
        });

        let userManuallyFeatures = $(
          "[name='_user_manually_features[]']"
        ).val();
        $(userManuallyFeatures).each(function (i, el) {
          var html = '<div class="col-md-3 mb-2">' + uc_first(el) + "</div>";
          previewSection.find(".property-features").append(html);
        });

        $(".property-budget").html($('[name="_user_budget"]').val());
        $(".enable-pool").html(
          $('[name="_user_enable_pool"]').is(":checked") ? "Enable" : "Disable"
        );
        previewSection.show();
      })
      .on("hidden.bs.modal", function () {
        $("#preview-section").hide();
      });

    select_property_category_person_page();
    $('[name="_user_property_category[]"]').on("change", function (e) {
      select_property_category_person_page();
    });

    personPublish.on("click", function (e) {
      submitCoOwnerPersonForm.data("is-preview", true).trigger("click");
    });
    personPreview.on("click", function (e) {
      submitCoOwnerPersonForm.data("is-preview", false).trigger("click");
    });
  } 
else if (php_vars.page === "property-details" || php_vars.post_type=="property") {
    $("#property-images-modal").on("shown.bs.modal", function () {
      $(this).find("#carousel").flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 210,
        itemMargin: 12,
        asNavFor: "#slider",
      });

      $(this).find("#slider").flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carousel",
      });
    });
	


/* #change003 Google Map disabled here and added globally at the end of the file

    $("#property-map-view").each(function (index, item) {
      let address = $(item).data("address");
      let id = $(item).data("id");
      if (address) {
        let element = document.getElementById("property-map-view");
        let googleMap = new google.maps.Map(element, {
          zoom: 5,
          center: australiaCenter,
        });
        let bounds = new google.maps.LatLngBounds();
        let map = new GoogleGeocode();
        map.geocode(address, function (data) {
          if (data != null) {
            put_marker_on_google(
              googleMap,
              bounds,
              data.latitude,
              data.longitude,
              id
            );
            googleMap.setZoom(15);
          }
        });
      }
    });
	
	
	*/

    $(".get-real-market-value").click(function (e) {
      let self = $(this);
      let address = $(this).data("address");
      if (address) {
        if (
          self.data("loading") == false ||
          self.data("loading") == undefined
        ) {
          self.data("loading", true);
          self.html(
            '<div class="mt-1 spinner-border text-secondary" role="status"></div>'
          );
          co_owner_ajax(
            {
              address: address,
              is_block: false,
              action: "get_property_price_by_address",
            },
            function (response) {
              self.data("loading", false);
              if (response.status) {
                $(".preview-get-real-market-value").html(response.html);
              } else {
                toastr["error"](response.message);
                ".preview-get-real-market-value".html("");
              }
              self.html("Get real market value of this property");
            }
          );
        }
      } else {
        toastr["error"]("Something went wrong please try again.");
      }
    });
	

    let person_connection_form = $("#person-connection-form");

    $("#property-share-options").on("change", function (e) {
      let pr = $(this).val();
      let calculated_value = 0.0;
      let available_share = person_connection_form.data("available-share");
      let available_price = person_connection_form.data("available-price");
      if (pr) {
        calculated_value = calculateShare(available_share, pr, available_price);
      }
      $('[name="calculated_price"]').val(calculated_value);
    });

    person_connection_form.validate({
      ignore: ":hidden,:not([name])",
      rules: {
        property_id: { required: true },
        description: { required: true, maxlength: 3000 },
        interested_in: { required: true },
        calculated_price: { required: true },
      },
      submitHandler(form) {
        let data = $(form).serializeArray();
        let property_id = $(form).data("id");
        data.push({
          name: "action",
          value: "send_property_connection_request",
        });
        data.push({ name: "property_id", value: property_id });
        data.push({ name: "ajax_nonce", value: php_vars.ajax_nonce });
        $.ajax({
          url: php_vars.ajax_url,
          method: "POST",
          data: data,
          beforeSend() {
            $.blockUI();
          },
          success(response) {
            response = JSON.parse(response);
            if (response.status) {
              if (response.hasOwnProperty("url")) {
                window.location.href = response.url;
              }
              $(form).get(0).reset();
              $(form).find("select").change();
              $("#property-connection-modal").modal("hide");
            }
            toastr[response.status ? "success" : "error"](response.message);
            $.unblockUI();
          },
          error() {
            $.unblockUI();
          },
        });
      },
    });
	
	

    if (
      php_vars.query.hasOwnProperty("action") &&
      php_vars.query.action == "open_property_connection_modal"
    ) {
      $("#property-connection-modal").modal("show");
      remove_url_segment("action");
    }
	
/* old position
    $(document).on(
      "click",
      ".confirm-to-property-mark-as-completed",
      function (e) {
        e.preventDefault();
        swalCoOwnerDefault
          .fire({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Do it!",
            cancelButtonText: "Cancel!",
          })
          .then((result) => {
            if (result.isConfirmed) {
              window.location.href = $(this).data("url");
            }
          });
      }
    );
	*/
	
  } else if (php_vars.page === "person-details") {
    let connection_button = $(".connect-to-person");
    let property_select2 = $(".property-select2");
    let property_share_options = $("#property-share-options");

    connection_button.click(function (e) {
      let href = $(this).attr("href");
      if (href === "#") {
        $("#person-connection-modal").modal("show");
      }
    });

    property_select2.on("change", function (e) {
      let property_id = $(this).val();
      let property_information = $(".property-information");
      let property_share_inputs = $(".property-share-inputs");

      if (property_id) {
        let property_data = $(this).find(":selected").data();
        let address = $(this).find(":selected").html();

        if (!property_data.availableShare > 0) {
          return;
        }

        property_information
          .find(".is-pool")
          .html(property_data.enablePool ? "Pool : " : null);
        property_information
          .find(".address")
          .html(address ? address.trim() : null);

        if (property_data.enable_pool === true) {
          property_information.find(".member-label").removeClass("d-none");
          property_information
            .find(".total-members")
            .html(property_data.members);
        } else {
          property_information.find(".member-label").addClass("d-none");
          property_information.find(".total-members").html(null);
        }

        property_information
          .find(".available-share")
          .html(property_data.availableShare);
        property_information
          .find(".available-price")
          .html(property_data.availablePrice.toFixed(0));

        if (property_data.enablePool) {
          property_share_inputs.removeClass("d-none");
          get_property_share_options_by_id(property_id, function (response) {
            property_share_options.html(response.html).change();
          });
        } else {
          property_share_inputs.addClass("d-none");
        }

        if (
          $(".property-information").is(":visible") === false &&
          property_data.availableShare > 0
        ) {
          $(".property-information").slideDown();
        }
      } else {
        property_information.slideUp();
        property_share_inputs.addClass("d-none");
      }
    });

    property_share_options.on("change", function (e) {
      let property_data = property_select2.find(":selected").data();
      let pr = $(this).val();
      let calculated_value = 0.0;
      if (pr) {
        calculated_value = calculateShare(
          property_data.availableShare,
          pr,
          property_data.availablePrice
        );
      }
      $('[name="calculated_price"]').val(calculated_value);
    });

    $("#person-connection-form").validate({
      ignore: ":hidden,:not([name])",
      rules: {
        property_id: { required: true },
        description: { required: true, maxlength: 3000 },
        interested_in: { required: true },
        calculated_price: { required: true },
      },
      submitHandler(form) {
        let data = $(form).serializeArray();
        let user_id = $(form).data("id");

        data.push({ name: "action", value: "send_person_connection_request" });
        data.push({ name: "user_id", value: user_id });
        data.push({ name: "ajax_nonce", value: php_vars.ajax_nonce });

        $.ajax({
          url: php_vars.ajax_url,
          method: "POST",
          data: data,
          beforeSend() {
            $.blockUI();
          },
          success(response) {
            response = JSON.parse(response);
            if (response.status) {
              $(form).get(0).reset();
              $(form).find("select").change();
              $("#person-connection-modal").modal("hide");
              if (
                response.hasOwnProperty("is_requested") &&
                response.hasOwnProperty("url") &&
                response.is_requested
              ) {
                window.location.href = response.url;
              }
            }
            toastr[response.status ? "success" : "error"](response.message);
            $.unblockUI();
          },
          error() {
            $.unblockUI();
          },
        });
        return false;
      },
    });

    $(document).on(
      "click",
      ".main-section .notify-reject-action,.main-section .notify-accept-action",
      function (e) {
        change_user_connection_status(this, function (response) {
          if (response.status) {
            $(".notify-reject-action").remove();
            $(".notify-accept-action").remove();
          }
        });
      }
    );
  } else if (php_vars.page === "messages") {
    let page = 1;
    let message_box = $("#message-display-box");
    $(".message-input").on("keypress", function (e) {
      if (e.keyCode === 13 && e.shiftKey === false) {
        $(".message-send-action").trigger("click");
      }
    });

    $(
      ".connection-block-action, .connection-accept-action, .connection-reject-action, .connection-unblock-action"
    ).on("click", function (e) {
      let status = $(this).hasClass("connection-block-action")
        ? "block"
        : $(this).hasClass("connection-unblock-action")
        ? "unblock"
        : $(this).hasClass("connection-accept-action")
        ? "accept"
        : "reject";
      let id = $(this).data("id");
      let with_user_id = status == "accept" ? $(this).data("with-id") : null;

      swalCoOwnerDefault
        .fire({
          title: "Are you sure?",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, " + status + " it!",
          cancelButtonText: "Cancel!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: php_vars.ajax_url,
              method: "POST",
              data: {
                status: status,
                id: id,
                action: "update_connection_status",
                ajax_nonce: php_vars.ajax_nonce,
              },
              beforeSend() {
                $.blockUI();
              },
              success(response) {
                response = JSON.parse(response);
                $.unblockUI();
                let $status =
                  status.charAt(0).toUpperCase() + status.slice(1) + "ed";
                swalCoOwnerDefault
                  .fire(
                    response.status ? $status : "Oops...",
                    response.message,
                    response.status ? "success" : "warning"
                  )
                  .then((result) => {
                    if (response.status) {
                      if (status == "accept") {
                        remove_url_segment("request");
                        window.location.href = response.link;
                      } else {
                        if (response.hasOwnProperty("already_connected_link")) {
                          window.location.href =
                            response.already_connected_link;
                        } else {
                          window.location.reload();
                        }
                      }
                    }
                  });
              },
              error() {
                $.unblockUI();
              },
            });
          }
        });
    });

    let resumable = new Resumable({
      target: "#",
      maxFiles: 5,
    });
    resumable.assignBrowse($(".sand-file-input"));
    let display_input_files = $(".display-input-files");
    let file_index = 1;
    resumable.on("fileAdded", function (file, event) {
      if (resumable.files.length > 5) {
        toastr.error("You can select a maximum of 5 files.");
      } else {
        display_input_files.append(
          "<h6 class='mb-0'>" +
            file_index +
            " - " +
            file.file.name +
            "<a href='#' data-id='" +
            file.uniqueIdentifier +
            "' class='float-end remove-message-file text-error'><small>Remove</small></a></h6>"
        );
        file_index = file_index + 1;
      }
    });
    $(document).on("click", ".remove-message-file", function (e) {
      let file = resumable.getFromUniqueIdentifier($(this).data("id"));
      resumable.removeFile(file);
      $(this).parent("h6").remove();
      file_index = file_index - 1;
    });

    let messageButton = $(".message-send-action");
    messageButton.data("sending", false);
    messageButton.on("click", function (e) {
      let self = $(this);
      let message_input = $(".message-input");
      let message = message_input.val();
      let id = php_vars.message.with;
      let is_group = php_vars.message.is_pool;
      if (message.trim().length > 2000) {
        toastr.error("Please enter no more than 2000 characters.");
        return;
      }

      if (message.trim().length > 0 || resumable.files.length > 0) {
        if (self.data("sending") === false) {
          let formData = new FormData();
          $.each(resumable.files, function (index, file) {
            formData.append("files[]", file.file);
          });
          let date = $(".message-date").last().data("date");
          formData.append("id", id);
          if (date != undefined && date != "" && date != null) {
            formData.append("date", date);
          }
          formData.append("is_group", is_group);
          formData.append("message", message);
          formData.append("action", "send_message");
          formData.append("ajax_nonce", php_vars.ajax_nonce);
          self.data("sending", true);
          $.ajax({
            url: php_vars.ajax_url,
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend() {
              self.attr("disabled", true);
              message_input.attr("readonly", true);
            },
            success(response) {
              response = JSON.parse(response);
              self.data("sending", false);
              if (response.status) {
                message_input.val(null);
                $("#message-display-box").append(response.html);
                if (response.files.length > 10) {
                  $("#files_and_links").find("ul").prepend(response.files);
                }
                $(".remove-message-file").trigger("click");
                if (response.file_error != true) {
                  toastr.error(response.file_error);
                }
              } else {
                toastr.error(response.message);
              }
              self.attr("disabled", false);
              message_input.removeAttr("readonly");
              scroll_to_bottom(message_box.parent());
              message_input.focus();
              remove_duplicate_date_in_message_box();
            },
            error() {
              self.attr("disabled", false);
              message_input.removeAttr("readonly");
            },
          });
        }
      } else {
        message_input.focus();
      }
    });

    if (
      php_vars.hasOwnProperty("message") &&
      php_vars.message.hasOwnProperty("with") && 
      php_vars.message.hasOwnProperty("is_pool")
    ) {
      get_conversation_message(page, function (response) {
        page = parseInt(page) + 1;
        message_box.append(response.html);
        scroll_to_bottom(message_box.parent());
      });
    }
	

    $(document).on("click", ".load-more-message", function (e) {
      $(this).remove();
      get_conversation_message(page, function (response) {
        page = parseInt(page) + 1;
        $("#message-display-box").prepend(response.html);
      });
    });


	
	function fetchMessage(is_pool){
	
		$.ajax({
			  url: php_vars.ajax_url,
			  method: "POST",
			  data: {
				id: 'false',
				is_group: is_pool,
				page: page,
				action: "get_conversations_message_extend",
				ajax_nonce: php_vars.ajax_nonce,
			  },
			  success(response) {
				response = JSON.parse(response);
				if (response.status) {
					page = parseInt(page) + 1;
					$("#message-display-box").prepend(response.html);
				  remove_duplicate_date_in_message_box();
				  user_shield_tooltip();
				}
			  },
			}); 	
	}
	
	console.log('php_vars.page'+php_vars.page);
	console.log('php_vars.query.with'+php_vars.query.with);
	
    if (php_vars.page=="messages" && typeof php_vars.query.with == "undefined" ) {
        fetchMessage(php_vars.query.is_pool);
    }	


    $(".clear-connection-chat,.clear-group-chat").on("click", function (e) {
      let id = $(this).data("id");
      let is_group = $(this).hasClass("clear-group-chat");
      swalCoOwnerDefault
        .fire({
          title: "Are you sure?",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, Clear it!",
          cancelButtonText: "Cancel!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            co_owner_ajax(
              {
                id: id,
                is_group: is_group,
                action: "clear_conversations_chat",
              },
              function (response) {
                toastr[response.status ? "success" : "warning"](
                  response.message
                );
                if (response.status) {
                  $("#message-display-box").html(null);
                  $("#files_and_links").find("ul").html(null);
                }
              }
            );
          }
        });
    });

    $(".add-group-members").on("click", function (e) {
      $("#members-modal").modal("show");
    });

    $(
      ".user-block-action, .user-accept-action, .user-reject-action, .user-unblock-action"
    ).on("click", function (e) {
      let status = $(this).hasClass("user-block-action")
        ? "block"
        : $(this).hasClass("user-unblock-action")
        ? "unblock"
        : $(this).hasClass("user-accept-action")
        ? "accept"
        : "reject";
      let id = $(this).data("id");
      let with_user_id = status == "accept" ? $(this).data("id") : null;

      swalCoOwnerDefault
        .fire({
          title: "Are you sure?",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, " + status + " it!",
          cancelButtonText: "Cancel!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            co_owner_ajax(
              {
                status: status,
                id: id,
                action: "block_connected_user",
              },
              function (response) {
                if (response.status) {
                  window.location.href = response.link;
                } else {
                  toastr.error(response.message);
                }
              }
            );
          }
        });
    });

    $(document).on("click", ".remove-group-connection", function (e) {
      let connection_id = $(this).data("connection-id");
      let user_id = $(this).data("id");
      swalCoOwnerDefault
        .fire({
          title: "Are you sure?",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, Remove it!",
          cancelButtonText: "Cancel!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            co_owner_ajax(
              {
                user_id: user_id,
                group_id: connection_id,
                action: "remove_from_group_connection",
              },
              function (response) {
                if (response.status) {
                  window.location.href = response.link;
                } else {
                  toastr.error(response.message);
                }
              }
            );
          }
        });
    });

    $(document).on(
      "click",
      ".block-group-connection,.unblock-group-connection",
      function (e) {
        let self = $(this);
        let action = $(this).hasClass("block-group-connection")
          ? "block"
          : "unblock";
        let connection_id = $(this).data("connection-id");
        let user_id = $(this).data("id");
        swalCoOwnerDefault
          .fire({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, " + uc_first(action) + " it!",
            cancelButtonText: "Cancel!",
          })
          .then((result) => {
            if (result.isConfirmed) {
              co_owner_ajax(
                {
                  user_id: user_id,
                  group_id: connection_id,
                  status: action,
                  action: "update_status_from_group_connection",
                },
                function (response) {
                  if (response.status) {
                    if (action == "block") {
                      self.addClass("unblock-group-connection");
                      self.removeClass("block-group-connection");
                      self.html("Unblock");
                    } else {
                      self.html("Block");
                      self.removeClass("unblock-group-connection");
                      self.addClass("block-group-connection");
                    }
                    toastr.success(response.message);
                  } else {
                    toastr.error(response.message);
                  }
                }
              );
            }
          });
      }
    );

    $(document).on("click", ".contact-search-close", function (e) {
      $(".contact-search").val(null).trigger("keyup");
    });

    $(document).on("keyup", ".contact-search", function (e) {
      var value = $(this).val().toLowerCase();
      $(".search-filter-connection").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });

    let assignMemberToAnotherGroup = $("form#assign-member-to-another-group");
    $(document).on("click", ".assign-another-pool", function (e) {
      let id = $(this).data("id");
      assignMemberToAnotherGroup.find('[name="member_id"]').val(id);
      $("#add-member-to-another-pool").modal("show");
    });

    $(document).on("change", ".select-another-group", function (e) {
      let property_id = $(this).find(":selected").data("property-id");
      let propertyShareSelect2Group = $("#property-share-select2-group");
      let showPropertyInfo = $("#show-property-info-group");
      if (property_id) {
        co_owner_ajax(
          {
            id: property_id,
            with_info: true,
            action: "get_property_share_options",
          },
          function (response) {
            if (response.status) {
              propertyShareSelect2Group.html(response.html);
              if (propertyShareSelect2Group.find("option").length === 1) {
                toastr.error("The property doesn't have portions");
              } else {
                propertyShareSelect2Group.data(
                  "property-available-share",
                  response.total_share
                );
                propertyShareSelect2Group.data(
                  "property-available-price",
                  response.total_price
                );
                showPropertyInfo.html(response.info).show();
              }
            }
          }
        );
      } else {
        propertyShareSelect2Group.html(null);
        $('[name="calculated_price"]').val(null);
        showPropertyInfo.html(null).hide();
      }
    });

    assignMemberToAnotherGroup.validate({
      rules: {
        member_id: { required: true },
        property_id: { required: true },
        interested_in: { required: true },
        calculated_price: { required: true },
        description: { required: true, maxlength: 3000 },
      },
      messages: {
        member_id: "Please select a member.",
        property_id: "Please select a pool.",
        interested_in: "Please select a share %.",
        calculated_price: "Please calculate a price.",
        description: "Please Add a comment.",
      },
      submitHandler(form) {
        let data = {
          description: $(form).find('[name="description"]').val(),
          interested_in: $(form).find('[name="interested_in"]').val(),
          property_id: $(form).find('[name="property_id"]').val(),
          member_id: $(form).find('[name="member_id"]').val(),
          action: "add_member_on_pool",
        };

        co_owner_ajax(data, function (response) {
          if (response.status) {
            $(form).get(0).reset();
            $(form).find("select").change();
            toastr.success(response.alert_message);
            $("#add-member-to-another-pool").modal("hide");
          } else {
            toastr.error(response.message);
          }
        });
      },
    });

    /* For All Connections */
    $(document).on(
      "click",
      ".all-connections-contact-search-close",
      function (e) {
        $(".all-connections-contact-search").val(null).trigger("keyup");
      }
    );

    $("#all-connections-modal").on("hidden.bs.modal", function (e) {
      $(".all-connections-contact-search").val(null).trigger("keyup");
    });

    $(document).on("keyup", ".all-connections-contact-search", function (e) {
      var value = $(this).val().toLowerCase();
      $(".all-connection-search-filter-connection").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });
    /* For All Connections */

    $("#compose-message-form").validate({
      rules: {
        contact_for_compose_message: { required: true },
        compose_message: { required: true, maxlength: 2000 },
      },
      messages: {
        contact_for_compose_message: "Please select a contact.",
        compose_message: "Please Type Message.",
      },
      submitHandler(form) {
        let is_type = $(form)
          .find('[name="contact_for_compose_message"]')
          .find(":selected")
          .data("type");
        let data = {
          id: $(form).find('[name="contact_for_compose_message"]').val(),
          message: $(form).find('[name="compose_message"]').val(),
          is_group: is_type == "group",
          action: "send_message",
        };

        co_owner_ajax(data, function (response) {
          if (response.status) {
            $(form).get(0).reset();
            $(form).find("select").change();
            $("#compose-message-modal").modal("hide");
            if (
              php_vars.message.hasOwnProperty("with") &&
              data.id == php_vars.message.with
            ) {
              $("#message-display-box").append(response.html);
              scroll_to_bottom(message_box.parent());
              remove_duplicate_date_in_message_box();
            } else {
              toastr.success("Message sent successfully.");
            }
          } else {
            toastr.error(response.message);
          }
        });
      },
    });

    $(document).on("click", "a.preview-message-image", function (e) {
      e.preventDefault();
      let url = $(this).prop("href");
      $("#preview-images-modal").modal("show");
      $("#preview-images-modal").find("#preview_image").prop("src", url);
    });

    $(document).on("click", ".load-more-files", function (e) {
      let self = $(this);
      if (self.data("loading") == false || self.data("loading") == undefined) {
        self.data("loading", true);
        let is_group = self.data("is-group");
        let chat_with_connected = self.data("chat-with-connected");
        let current_page = self.data("current-page");
        let clear_chat_date = self.data("clear-chat-date");
        let chat_with = self.data("chat-with");
        let length = self.closest("ul").find("li").length - 1;
        co_owner_ajax(
          {
            is_group: is_group,
            chat_with_connected: chat_with_connected,
            page: current_page + 1,
            clear_chat_date: clear_chat_date,
            chat_with: chat_with,
            action: "load_more_files",
            is_block: false,
            length: length,
          },
          function (response) {
            self.data("loading", false);
            self.closest("ul").append(response.html);
            self.parent("li").remove();
          }
        );
      }
    });

    $(document).on("click", ".report-message", function (e) {
      let self = $(this);
      let id = self.data("id");
      if (self.data("loading") == false || self.data("loading") == undefined) {
        swalCoOwnerDefault
          .fire({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Report it!",
            cancelButtonText: "Cancel!",
          })
          .then((result) => {
            if (result.isConfirmed) {
              self.data("loading", true);
              co_owner_ajax(
                {
                  id: id,
                  action: "report_message",
                  is_block: false,
                },
                function (response) {
                  self.data("loading", false);
                  toastr[response.status ? "success" : "error"](
                    response.message
                  );
                  if (response.status) {
                    self.closest(".message-report-dropdown").remove();
                  }
                }
              );
            }
          });
      }
    });

    if (
      $("#open-nav-on-mobile").is(":visible") &&
      php_vars.query.hasOwnProperty("aside") &&
      php_vars.query.aside
    ) {
      $("#open-nav-on-mobile").click();
    }
  } else if (php_vars.page === "my-account") {
    let resumable = new Resumable({
      target: "#",
      maxFiles: 1,
      fileType: ["png", "jpg", "jpeg"],
    });
    resumable.assignBrowse($("#user-profile-browse").get(0));
    resumable.assignDrop($("#user-profile-drop-box").get(0));
    resumable.on("fileAdded", function (file) {
      var reader = new FileReader();
      reader.onload = function (event) {
        $("#user-profile").prop("src", event.target.result);
        $(".remove-user-image").data("id", file.uniqueIdentifier).show();
        $(".remove-user-image-temp").show();
		 $(".remove-user-image").hide();
      };
      reader.readAsDataURL(file.file);
    });

 $(".remove-user-image-temp").on("click", function (e) {
	  $("#user-profile").prop("src", $("#user-profile").data("default"));
	  $(this).hide();
	 return false;
 });	 
    $(".remove-user-image").on("click", function (e) {
      var id = $(this).data("id");
      var file = resumable.getFromUniqueIdentifier(id);
      resumable.removeFile(file);
      $("#user-profile").prop("src", $("#user-profile").data("old"));
    //  $(".remove-user-image").hide();
	  $.ajax({
		 type: "POST",
		 url: php_vars.ajax_url,
		 data:{
			 user_id : id,
			 action: "remove_profile_image"
		 },
		 success:function(res){
			 if(res==200){
				 $('#user-profile').attr('src',php_vars.site_url+'/wp-content/themes/co-owner/images/person-icon-new.png');
				 $(".remove-user-image").hide();
			 }else{
				 
			 }			
		 }	 
	  });
	  
	  
	  
    });

    let my_account_info_form = $("#my-account-info");
    $(".submit-profile-info").on("click", function () {
      my_account_info_form.submit();
    });
    my_account_info_form.validate({
      rules: {
        first_name: { required: true, maxlength: 20 },
        last_name: { required: true, maxlength: 20 },
      },
      submitHandler(form) {
        var formData = new FormData();

        $.each($(form).serializeArray(), function (key, value) {
          formData.append(value.name, value.value);
        });

        if (resumable.files.length) {
          formData.append("profile", resumable.files[0].file);
        }
		/* #changed 11*/
        if (resumable.files.length) {
          formData.append("profile_data_img", $('#user-profile').attr('src'));
        }
        /* #changed 11*/
		 if (resumable.files.length) {		
				console.log(resumable.files[0]);
				console.log(resumable.files[0].file);
		 }
		
        formData.append("ajax_nonce", php_vars.ajax_nonce);
        co_owner_ajax_with_file(formData, function (response) {
          toastr[response.status ? "success" : "error"](response.message);
          if (response.status) {
            if (
              response.hasOwnProperty("profile_updated") &&
              response.profile_updated
            ) {
              $(".remove-user-image").hide();
              let id = $(".remove-user-image").data("id");
              let file = resumable.getFromUniqueIdentifier(id);
              resumable.removeFile(file);
            }
            $(".user-forst-name").html(response.name);
			location.reload();
          }
        });
        return false;
      },
    });

    $("#change-password-modal").on("show.bs.modal", function () {
      $(this).find(".p").val(null);
    });
    $("#change-password-form").validate({
      rules: {
        old_password: {
          required: true,
          remote: {
            url: php_vars.ajax_url,
            type: "post",
            data: {
              action: "user_is_old_password",
              password: function () {
                return $('input[name="old_password"]').val();
              },
            },
            dataFilter: function (data) {
              data = JSON.parse(data);
              return data.status;
            },
          },
        },
        new_password: {
          required: true,
          remote: {
            url: php_vars.ajax_url,
            type: "post",
            data: {
              action: "php_password_validation",
              password: function () {
                return $('input[name="new_password"]').val();
              },
            },
          },
        },
        new_password_confirm: {
          required: true,
          equalTo: "#new_password",
        },
      },
      messages: {
        old_password: {
          remote: "Old password is invalid.",
        },
        new_password: {
          remote:
            "Password should be at least 8 to 16 characters in length and should include at least one upper case letter, one number, and one special character.",
        },
      },
      submitHandler(form) {
        let data = $(form).serializeArray();
        co_owner_ajax(data, function (response) {
          swalCoOwnerDefault
            .fire(
              response.status ? "Great!" : "Oops...",
              response.message,
              response.status ? "success" : "warning"
            )
            .then((result) => {
              if (response.status) {
               
                $("#change-password-modal").modal("hide");
				/* change 005*/
				console.log('status triggerd');
				//$('.logoutme').trigger('click');
                window.location.href = response.redirect;
              }
            });
        });
      },
    });

    let document_file = new Resumable({
      target: "#",
      maxFiles: 5,
    });

    let preview_documents = $("#preview-user-documents");
    let document_send_request = $("#user_document_send_request");

    if ($("#user_upload_document_file").length > 0) {
      document_file.assignBrowse($("#user_upload_document_file").get(0));
    }
    document_file.on("fileAdded", function (file) {
      preview_documents.append(
        "<li> - " +
          file.file.name +
          "" +
          "<a href='#' class='text-danger remove-document' data-unique_identifier='" +
          file.file.uniqueIdentifier +
          "'>" +
          php_vars.svg.trash +
          "</a>" +
          "</li>"
      );
      document_send_request.show();
    });

    document_send_request.on("click", function (e) {
      if (document_file.files.length > 0) {
        var formData = new FormData();
        for (let x = 0; x < document_file.files.length; x++) {
          formData.append("document[]", document_file.files[x].file);
        }
        formData.append("action", "user_update_document_file");
        formData.append("ajax_nonce", php_vars.ajax_nonce);

        co_owner_ajax_with_file(formData, function (response) {
          toastr[response.status ? "success" : "error"](response.message);
          if (response.status) {
            $(".document-status-message").html(
              '<span class="alert alert-info px-2 py-1">Document submitted and pending for approval. You will be notified as soon as your ID is approved.</span>'
            );
            for (let x = 0; x < document_file.files.length; x++) {
              let file = document_file.getFromUniqueIdentifier(
                document_file.files[x].file.uniqueIdentifier
              );
              document_file.removeFile(file);
            }
            $("#preview-user-documents").html(response.html);
            document_send_request.hide();
          }
        });
      }
    });

    $(document).on("click", ".remove-document", function (e) {
      let file = document_file.getFromUniqueIdentifier(
        $(this).data("unique_identifier")
      );
      document_file.removeFile(file);
      $(this).parent("li").remove();
      if (document_file.files.length == 0) {
        document_send_request.hide();
      }
    });

    $(document).on("click", ".delete-document", function (e) {
      let index = $(this).data("index");
      let self = $(this);
      if (index != null) {
        swalCoOwnerDefault
          .fire({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Delete it!",
            cancelButtonText: "Cancel!",
          })
          .then((result) => {
            if (result.isConfirmed) {
              co_owner_ajax(
                {
                  index: index,
                  action: "delete_user_document",
                },
                function (response) {
                  if (response.status) {
                    self.closest("li").remove();
                  }
                  toastr[response.status ? "success" : "error"](
                    response.message
                  );
                }
              );
            }
          });
      }
    });

    $("#deactivate-or-delete-my-account-model")
      .on("show.bs.modal", function () {
        $(".confirm-to-action").show();
        $(".submit-to-action").hide();
      })
      .on("hidden.bs.modal", function () {
        let is_deleted = $(this).data("is_deleted");
        if (is_deleted !== undefined && is_deleted === true) {
          $("#leave-account-feedback-model").modal("show");
        }
      });

    $("button.confirm-to-action").on("click", function (e) {
      $(".confirm-to-action").hide();
      $(".submit-to-action").hide();
      let action = $(
        "#deactivate-or-delete-my-account-model input[type='radio']:checked"
      ).val();
      $(".submit-to-action." + action).slideDown(500);
      $(".submit-to-action.action").show();
      $("span#action").html(uc_first(action));
    });

    $("button.submit-to-action").on("click", function (e) {
      let self = $(this);
      if (self.hasClass("back")) {
        $(".submit-to-action").hide();
        $(".confirm-to-action:not(button)").slideDown(500);
        $("button.confirm-to-action").show();
      } else {
        let action = $(
          "#deactivate-or-delete-my-account-model input[type='radio']:checked"
        ).val();
        co_owner_ajax(
          {
            delete_action_type: action,
            action: "user_delete_my_account",
          },
          function (response) {
            toastr[response.status ? "success" : "error"](response.message);
            if (response.status) {
              $("#deactivate-or-delete-my-account-model").modal("hide");
              $(".deactivate-my-account-button").hide();
              $(".active-my-account-button").show();
              if (action === "deactivate") {
                $("#user-profile").addClass("deactivated-account");
              } else {
                $("#deactivate-or-delete-my-account-model").data(
                  "is_deleted",
                  true
                );
              }
            }
          }
        );
      }
    });

    $("#leave-account-feedback-model").on("hidden.bs.modal", function () {
      window.location.reload();
    });

    $("#leave-account-feedback-form").validate({
      rules: {
        leave_reason: { required: true },
        comment: {
          required: function (element) {
            return $('[name="leave_reason"]:checked').val() == "Other";
          },
          maxlength: 1000,
        },
      },
      submitHandler(form) {
        let data = $(form).serializeArray();
        co_owner_ajax(data, function (response) {
          toastr[response.status ? "success" : "error"](response.message);
          if (response.status) {
            $("#leave-account-feedback-model").modal("hide");
          }
        });
      },
    });

    $(".active-my-account-button").on("click", function (e) {
      swalCoOwnerDefault
        .fire({
          title: "Are you sure?",
          text: "Activate your account?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, Activate it",
          cancelButtonText: "Cancel!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            co_owner_ajax(
              { action: "active_user_account" },
              function (response) {
                toastr[response.status ? "success" : "error"](response.message);
                if (response.status) {
                  $("#user-profile").removeClass("deactivated-account");
                  $(".deactivate-my-account-button").show();
                  $(".active-my-account-button").hide();
                }
              }
            );
          }
        });
    });

    if (php_vars.query.hasOwnProperty("canceled")) {
      remove_url_segment("canceled");
    }
  } else if (php_vars.page === "my-account-verification") {
    $(".remove-social-account").on("click", function (e) {
      let self = $(this);
      let account = self.data("social-account");

      swalCoOwnerDefault
        .fire({
          title: "Are you sure?",
          text: "You disconnect account.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, Remove it",
          cancelButtonText: "Cancel!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            co_owner_ajax(
              {
                account: account,
                action: "remove_social_account",
              },
              function (response) {
                swalCoOwnerDefault.fire(
                  response.status ? "Great!" : "Oops...",
                  response.message,
                  response.status ? "success" : "warning"
                );
                if (response.status) {
                  self.closest(".row").find(".verify-message").hide();
                  self.parent().find(".link-social-account").show();
                  self.hide();
                }
              }
            );
          }
        });
    });
  } else if (php_vars.page === "my-notification-settings") {
    $(".all-settings input").on("change", function (e) {
      let input = $(this);
      let is_checked = input.is(":checked");
      let value = input.val();
      let row = input.closest(".setting");
      let name = input.attr("name");

      if (name === "_user_notify_when_have_new_notify_me") {
        if (value === "no_thanks" && is_checked) {
          row.find('input[value="daily"]').prop("checked", false);
          row.find('input[value="weekly"]').prop("checked", false);
          row.find('input[value="monthly"]').prop("checked", false);
        } else {
          let is_daily_checked = row
            .find('input[value="daily"]')
            .is(":checked");
          let is_weekly_checked = row
            .find('input[value="weekly"]')
            .is(":checked");
          let is_monthly_checked = row
            .find('input[value="monthly"]')
            .is(":checked");
          row
            .find('input[value="no_thanks"]')
            .prop(
              "checked",
              is_daily_checked === false &&
                is_weekly_checked === false &&
                is_monthly_checked === false
            );
        }
        var data = {
          action: "update_notification_settings",
          key: name,
          is_block: false,
          daily: row.find('input[value="daily"]').is(":checked"),
          weekly: row.find('input[value="weekly"]').is(":checked"),
          monthly: row.find('input[value="monthly"]').is(":checked"),
        };
      } else {
        if (value === "no_thanks" && is_checked) {
          row.find('input[value="email"]').prop("checked", false);
          row.find('input[value="mobile"]').prop("checked", false);
        } else {
          let is_email_checked = row
            .find('input[value="email"]')
            .is(":checked");
          let is_mobile_checked = row
            .find('input[value="mobile"]')
            .is(":checked");
          row
            .find('input[value="no_thanks"]')
            .prop(
              "checked",
              is_email_checked === false && is_mobile_checked === false
            );
        }
        var data = {
          action: "update_notification_settings",
          key: name,
          is_block: false,
          email: row.find('input[value="email"]').is(":checked"),
          mobile: row.find('input[value="mobile"]').is(":checked"),
        };
      }

      co_owner_ajax(data, function (response) {});
    });
  } else if (php_vars.page === "faq") {
    $(document).on("click", ".contact-search-close", function (e) {
      $(".search-input").val(null).trigger("keyup");
    });

    $(document).on("keyup", ".search-input", function (e) {
      var value = $(this).val().toLowerCase();
      $(".search-filter-faqs").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });

    $("#faq-feedback-form").validate({
      ignore: "",
      rules: {
        rating_1: { required: true },
        rating_2: { required: true },
        rating_2: { required: true },
        rating_comment: { required: true, maxlength: 1000 },
      },
      submitHandler(form) {
        let formData = $(form).serializeArray();
        co_owner_ajax(formData, function (response) {
          if (response.status) {
            $(form).get(0).reset();
            $("#faq-feedback").modal("hide");
            $("#thank-you-feedback").modal("show");
          } else {
            toastr.error(response.message);
          }
        });
      },
    });

    $(function () {
      setTimeout(() => {
        if (php_vars.query.hasOwnProperty("search")) {
          $(".search-filter-faqs").filter(function () {
            if (
              $(this)
                .text()
                .toLowerCase()
                .indexOf(php_vars.query.search.toLowerCase()) > -1
            ) {
              $(this).find(".accordion-button").click();
            }
          });
        }
      }, 1000);
    });

	
  } else if (php_vars.page === "contact-us") {
    remove_url_segment("submitted");
  }
  
     $("#property-images-modal").on("shown.bs.modal", function () {
      $(this).find("#carousel").flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 210,
        itemMargin: 12,
        asNavFor: "#slider",
      });

      $(this).find("#slider").flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carousel",
      });
    }); 

  if (
    php_vars.page === "my-account" ||
    php_vars.page === "my-account-verification"
  ) {
    let email_form = $("#edit-email-form");

    $(".resend-email-verification-code").on("click", function () {
      email_form.data("mail-sended", false);
      let data = {
        action: "sent_edit_email_verification_code",
        email: email_form.find('input[name="email"]').val(),
      };
      co_owner_ajax(data, function (response) {
        if (response.status) {
          $("#temp-email").html(data.email);
          $("#email-verify-code-input").show();
          email_form.find('button[type="submit"]').html("Verify");
          email_form.data("mail-sended", true);
        } else {
          $("#email-error").html(response.message).show();
        }
      });
    });

    let validate = email_form.validate({
      ignore: ":hidden,:not([name])",
      rules: {
        email: {
          required: true,
          remote: {
            url: php_vars.ajax_url,
            type: "post",
            data: {
              action: "php_email_validation",
              email: function () {
                return $('input[name="email"]').val();
              },
              ignore: true,
            },
            dataFilter: function (data) {
              data = JSON.parse(data);
              $('input[name="email"]').data("error", data.message);
              return data.status;
            },
          },
        },
        email_code_1: { required: true, number: true },
        email_code_2: { required: true, number: true },
        email_code_3: { required: true, number: true },
        email_code_4: { required: true, number: true },
      },
      messages: {
        email: {
          remote: function () {
            let error = $('input[name="email"]').data("error");
            return error ? error : "Email is invalid.";
          },
        },
        email_code_1: {
          required: () => {
            $("#email_verify_code_error").html("Enter your code.");
            return "";
          },
        },
        email_code_2: {
          required: () => {
            $("#email_verify_code_error").html("Enter your code.");
            return "";
          },
        },
        email_code_3: {
          required: () => {
            $("#email_verify_code_error").html("Enter your code.");
            return "";
          },
        },
        email_code_4: {
          required: () => {
            $("#email_verify_code_error").html("Enter your code.");
            return "";
          },
        },
      },
      success: function (label, element) {
        if (validate.numberOfInvalids() === 0) {
          $("#email_verify_code_error").html(null);
        }
      },
      submitHandler: function (form) {
        let mail_sended = email_form.data("mail-sended");
        let email = email_form.find('input[name="email"]').val();
        if (mail_sended === false) {
          let data = {
            action: "sent_edit_email_verification_code",
            email: email,
          };
          co_owner_ajax(data, function (response) {
            if (response.status) {
              $("#temp-email").html(data.email);
              $("#email-verify-code-input").show();
              email_form.find('button[type="submit"]').html("Verify");
              email_form.data("mail-sended", true);
              $("#email-error").html(null).hide();
            } else {
              $("#email-error").html(response.message).show();
            }
          });
          email_form.valid();
        } else {
          let data = $(form).serializeArray();
          data.push({
            name: "action",
            value: "verify_email_verification_code",
          });
          $("#email-error").html(null).hide();
          co_owner_ajax(data, function (response) {
            if (response.status) {
              $("[name='user_email']").val(email);
              let alert_modal = $("#alert-model");
              alert_modal.modal("show");
              alert_modal
                .find("#message")
                .html("Email Id Updated Successfully");
              setTimeout((e) => {
                alert_modal.modal("hide");
              }, 5000);
              $("#edit-email-modal").modal("hide");
              $(".email-verified-symbol").html(php_vars.svg.verified);
              $("#temp-email").html(null);
              $("#email-verify-code-input").hide();
              $(".email-verify-code-input").find("input").val(null);
              email_form.find('button[type="submit"]').html("Continue");
              email_form.data("mail-sended", false);
            } else {
              $("#email-error").html(response.message).show();
            }
          });
        }
        return false;
      },
    });

    let edit_mobile = $("#edit-mobile-form");

    $(document).on("click", ".resend-mobile-verification-code", function (e) {
      $(".mobile-verify-code-input").find("input").val("-");
      edit_mobile.data("message-sended", false);
      edit_mobile.submit();
    });

    edit_mobile.validate({
      ignore: ":hidden,:not([name])",
      rules: {
        mobile: { required: true },
        mobile_code_1: { required: true },
        mobile_code_2: { required: true },
        mobile_code_3: { required: true },
        mobile_code_4: { required: true },
      },
      submitHandler(form) {
        let message_sended = edit_mobile.data("message-sended");
        let mobile = edit_mobile.find('input[name="mobile"]').val();
        if (message_sended === false) {
          let data = {
            action: "sent_edit_mobile_verification_code",
            mobile: mobile,
          };
          co_owner_ajax(data, function (response) {
            if (response.status) {
              $("#temp-mobile").html(data.mobile);
              $("#mobile-verify-code-input").show();
              edit_mobile.find('button[type="submit"]').html("Verify");
              edit_mobile.data("message-sended", true);
              $(".mobile-verify-code-input").find("input").val(null);
            } else {
              $("#mobile-error").html(response.message).show();
            }
          });
          edit_mobile.valid();
        } else {
          let data = $(form).serializeArray();
          data.push({
            name: "action",
            value: "verify_mobile_verification_code",
          });
          $("#mobile-error").html(null).hide();
          co_owner_ajax(data, function (response) {
            if (response.status) {
              let alert_modal = $("#alert-model");
              alert_modal.modal("show");
              alert_modal
                .find("#message")
                .html("Phone Number Updated Successfully");
              setTimeout((e) => {
                alert_modal.modal("hide");
              }, 5000);
              $("#edit-mobile-modal").modal("hide");
              $(".mobile-verified-symbol").html(php_vars.svg.verified);
              $('[name="_mobile"]').val(mobile);
              $("#temp-mobile").html(null);
              $("#mobile-verify-code-input").hide();
              $(".mobile-verify-code-input").find("input").val(null);
              edit_mobile.find('button[type="submit"]').html("Continue");
              edit_mobile.data("message-sended", false);
              if ($(".mobile-verified-error").length) {
                $(".mobile-verified-error").remove();
              }
            } else {
              $("#mobile-error").html(response.message).show();
            }
          });
        }
      },
    });

    $(document).on("show.bs.modal", "#edit-email-modal", function (e) {
      let self = $(this);
      let email = self.find("#email");
      email.val(email.data("oldemail")).removeClass("is-invalid");
      self.find(".text-error").html(null);
      $("#temp-email").html(null);
      $("#email-verify-code-input").hide();
      $(".email-verify-code-input").find("input").val(null);
      email_form.find('button[type="submit"]').html("Continue");
      email_form.data("mail-sended", false);
    });

    $(document).on("show.bs.modal", "#edit-mobile-modal", function (e) {
      let self = $(this);
      let mobile = self.find("#mobile");
      mobile.val(mobile.data("oldmobile")).removeClass("is-invalid");
      self.find(".text-error").html(null);
      $("#temp-mobile").html(null);
      $("#mobile-verify-code-input").hide();
      $(".mobile-verify-code-input").find("input").val(null);
      edit_mobile.find('button[type="submit"]').html("Continue");
      edit_mobile.data("message-sended", false);
    });
  }

  if (php_vars.page === "messages" || php_vars.page === "property-details") {
    $(".search-input").on("keyup", function () {
      var value = $(this).val().toLowerCase();
      $(".search-filter-member").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });

    $(".input-group-text.close").click(function () {
      $(".search-input").val(null).trigger("keyup");
    });

    $(".add-member-to-pool").click(function (e) {
      let member_id = $("input[name='selected_member']:checked").val();
      if (member_id) {
        get_connection_info_by_user_id(member_id, function (response) {
          if (response.status) {
            let share = parseInt(response.data.interested_in);
            let is_exists =
              $(".property-share-selection option[value=" + share + "]")
                .length > 0;
            $(".property-share-selection")
              .val(is_exists && share > 0 ? share : "")
              .change();
          }
        });

        $("#add-member-to-pool").modal("show");
      } else {
        toastr["error"]("Please select a member.");
      }
    });

    $("#add-new-member-form").validate({
      rules: {
        interested_in: { required: true },
        calculated_price: { required: true },
        description: { required: true },
      },
      submitHandler(form) {
        let data = $(form).serializeArray();
        let member_id = $("input[name='selected_member']:checked").val();

        data.push({ name: "action", value: "add_member_on_pool" });
        data.push({ name: "ajax_nonce", value: php_vars.ajax_nonce });
        data.push({ name: "member_id", value: member_id });
        data.push({ name: "page", value: php_vars.page });

        $.ajax({
          url: php_vars.ajax_url,
          method: "POST",
          data: data,
          beforeSend() {
            $.blockUI();
          },
          success(response) {
            response = JSON.parse(response);
            if (response.status) {
              $(form).get(0).reset();
              if (php_vars.page === "messages") {
                $("#members-modal").modal("hide");
              } else if (php_vars.page === "property-details") {
                $("#my-members-modal").modal("hide");
              }
              $("#add-member-to-pool").modal("hide");
              window.location.href = response.url;
            } else {
              toastr["error"](response.message);
            }
            $.unblockUI();
          },
          error() {
            $.unblockUI();
          },
        });
      },
    });
  }

  if (php_vars.page === "my-listings" || php_vars.page === "person-details") {
    $(document).on("click", ".delete-my-person-list", function (e) {
      swalCoOwnerDefault
        .fire({
          title: "Are you sure?",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, Delete it!",
          cancelButtonText: "Cancel!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            co_owner_ajax(
              {
                action: "update_person_list_as_delete",
              },
              function (response) {
                if (response.status && php_vars.page === "person-details") {
                  window.location.href = response.link;
                } else if (response.status) {
                  $(".person-list-box").fadeOut(1000, function () {
                    $(this).remove();
                  });
                  $(".create-person-list-link").show();
                  toastr[response.status ? "success" : "error"](
                    response.message
                  );
				  location.reload();
                } else {
                  toastr[response.status ? "success" : "error"](
                    response.message
                  );
                }
              }
            );
          }
        });
    });

    $(document).on(
      "click",
      ".hide-my-person-list,.show-my-person-list",
      function (e) {
        let self = $(this);
        let status = $(this).hasClass("hide-my-person-list") ? 2 : 1;
        swalCoOwnerDefault
          .fire({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText:
              "Yes, " + (status == 1 ? "Show" : "Hide") + " it!",
            cancelButtonText: "Cancel!",
          })
          .then((result) => {
            if (result.isConfirmed) {
              co_owner_ajax(
                {
                  listing_status: status,
                  action: "update_person_list_meta",
                },
                function (response) {
                  toastr[response.status ? "success" : "error"](
                    response.message
                  );
                  if (response.status) {
                    self
                      .closest(".card")
                      .find(".user-listing-status-button")
                      [status == 1 ? "show" : "hide"]();
                    self.removeClass(
                      status == 1
                        ? "show-my-person-list"
                        : "hide-my-person-list"
                    );
                    self.addClass(
                      status != 1
                        ? "show-my-person-list"
                        : "hide-my-person-list"
                    );
                    self.html(
                      status != 1 ? "Show My Listing" : "Hide My Listing"
                    );
                  }
                }
              );
            }
          });
      }
    );
  }

  
   

    $(".view-full-description").on("click", function (e) {
	 let description = $(".description-box");
    let small = description.find(".description-small");
    let full = description.find(".description-full");
      let self = $(this);
      if (!full.is(":visible")) {
        small.slideUp();
        full.slideDown("slow");
        self.html("Read less");
      } else {
        full.slideUp();
        small.slideDown("slow");
        self.html("Read more");
      }
    });
  

  function check_form_and_set_progress(form, heading = null, pr = null) {
    if (form.valid()) {
      if (pr) {
        setProgressBar(pr);
      }
      if (heading) {
        let target = heading.data("bs-target");
        $(target).collapse("show");
        scroll_to_div(target);
      }
      return true;
    }
    return false;
  }

  function change_user_connection_status(self, callback = null) {
    let status = $(self).hasClass("notify-accept-action") ? "accept" : "reject";
    let id = $(self).data("id");

    swalCoOwnerDefault
      .fire({
        title: "Are you sure?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, " + uc_first(status) + " it!",
        cancelButtonText: "Cancel!",
      })
      .then((result) => {
        if (result.isConfirmed) {
          co_owner_ajax(
            {
              status: status,
              id: id,
              action: "update_connection_status",
            },
            function (response) {
              if (callback) {
                callback(response);
              }
              if (!response.status) {
                toastr.error(response.message);
              }

              if (response.status) {
                window.location.href = response.link;
              }
            }
          );
        }
      });
  }

  function select_property_category_person_page() {
    var arr = $('[name="_user_property_category[]"]:checked')
      .map(function () {
        return this.value;
      })
      .get();
    let commercialBox = $(".commercial");
    let residentialBox = $(".residential");

    if ($.inArray("commercial", arr) >= 0) {
      commercialBox.show();
    } else {
      commercialBox.hide();
      $(".commercial input").prop("checked", false);
    }

    if ($.inArray("residential", arr) >= 0) {
      residentialBox.show();
      residentialBox.find("input").removeClass("ignore");
      residentialBox.find("textarea").removeClass("ignore");
      residentialBox.find("select").removeClass("ignore");
    } else {
      residentialBox.hide();
      $(".residential input").prop("checked", false);
      $(".residential").find(".room-counter").find("input").val(0);
      residentialBox.find("input").addClass("ignore");
      residentialBox.find("textarea").addClass("ignore");
      residentialBox.find("select").addClass("ignore");
    }

    if (arr.length === 0) {
      $(".residential-commercial").hide();
    } else {
      $(".residential-commercial").show();
    }
  }

  function co_owner_ajax_with_file(data, callback) {
    if (!data.has("ajax_nonce")) {
      formData.append("ajax_nonce", php_vars.ajax_nonce);
    }
    let is_block = data.has("is_block") ? data.get("is_block") : true;
    if (data.has("is_block")) {
      data.delete("is_block");
    }
    $.ajax({
      url: php_vars.ajax_url,
      method: "POST",
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend() {
        if (is_block) {
          $.blockUI();
        }
      },
      success(response) {
        if (is_block) {
          $.unblockUI();
        }
        response = JSON.parse(response);
        callback(response);
      },
      error() {
        if (is_block) {
          $.unblockUI();
        }
      },
    });
  }

  function co_owner_ajax(data, callback) {
    if (
      data.hasOwnProperty(0) &&
      data[0].hasOwnProperty("name") &&
      data[0].hasOwnProperty("value")
    ) {
      data.push({ name: "ajax_nonce", value: php_vars.ajax_nonce });
    } else {
      data.ajax_nonce = php_vars.ajax_nonce;
    }

    let is_block =
      data.hasOwnProperty("is_block") === false ||
      (data.hasOwnProperty("is_block") && data.is_block !== false);
    $.ajax({
      url: php_vars.ajax_url,
      method: "POST",
      data: data,
      beforeSend() {
        if (is_block) {
          $.blockUI();
        }
      },
      success(response) {
        if (is_block) {
          $.unblockUI();
        }
        response = JSON.parse(response);
        callback(response);
      },
      error() {
        if (is_block) {
          $.unblockUI();
        }
      },
    });
  }

  function uc_first(string = "") {
    return string.toLowerCase().replace(/\b[a-z]/g, function (letter) {
      return letter.toUpperCase();
    });
  }

  function get_connection_info_by_user_id(id, callback) {
    $.ajax({
      url: php_vars.ajax_url,
      method: "POST",
      data: {
        id: id,
        action: "get_connection_info_by_user_id",
        ajax_nonce: php_vars.ajax_nonce,
      },
      success(response) {
        response = JSON.parse(response);
        callback(response);
      },
    });
  }

  function get_conversation_message(page = 1, callback) {
    let is_pool =
      php_vars.hasOwnProperty("message") &&
      php_vars.message.hasOwnProperty("is_pool")
        ? php_vars.message.is_pool
        : false;
    let with_user =
      php_vars.hasOwnProperty("message") &&
      php_vars.message.hasOwnProperty("with")
        ? php_vars.message.with
        : $(".is-request-box").length > 0
        ? $(".is-request-box").data("user-id")
        : null;
    $.ajax({
      url: php_vars.ajax_url,
      method: "POST",
      data: {
        id: with_user,
        is_group: is_pool,
        page: page,
        action: "get_conversations_message",
        ajax_nonce: php_vars.ajax_nonce,
      },
      success(response) {
        response = JSON.parse(response);
        if (response.status) {
          callback(response);
          remove_duplicate_date_in_message_box();
          user_shield_tooltip();
        }
      },
    });
  }

  function remove_duplicate_date_in_message_box() {
    let date = null;
    $(".message-date").each(function (index, item) {
      let itemDate = $(item).data("date");
      if (date == itemDate) {
        $(item).remove();
      } else {
        date = itemDate;
      }
    });
  }

  function get_property_share_options_by_id(id, callback) {
    $.ajax({
      url: php_vars.ajax_url,
      method: "POST",
      data: {
        id: id,
        action: "get_property_share_options",
        ajax_nonce: php_vars.ajax_nonce,
      },
      success(response) {
        response = JSON.parse(response);
        callback(response);
      },
    });
  }

  function redirect_home_to_property_list(price = null, state = null) {
   /* var url = window.location.origin; */
    var url = php_vars.site_url;
    url += "/pool-property";
    if (price && state) {
      url += "/?p_price=" + price + "&p_state=" + state;
    } else if (price && state == null) {
      url += "/?p_price=" + price;
    } else if (price == null || (price === "" && state)) {
      url += "/?p_state=" + state;
    }
    window.location.href = url;
  }

  function setJqueryDefaultValidationSettings() {
    $.validator.setDefaults({
      normalizer: function (value) {
        return $.trim(value);
      },
      debug: false,
      errorClass: "text-error",
      errorElementClass: "is-invalid",
      validElementClass: "is-valid",
      highlight: function (element, errorClass, validClass) {
        if ($(element).is("select")) {
          $(element)
            .parent("div.w-100")
            .addClass(this.settings.errorElementClass)
            .removeClass(errorClass);
        } else {
          $(element)
            .addClass(this.settings.errorElementClass)
            .removeClass(errorClass);
        }
      },
      unhighlight: function (element, errorClass, validClass) {
        if ($(element).is("select")) {
          $(element)
            .parent("div.w-100")
            .removeClass(this.settings.errorElementClass)
            .removeClass(errorClass);
        } else {
          $(element)
            .removeClass(this.settings.errorElementClass)
            .removeClass(errorClass);
        }
      },
    });
  }

  function GoogleGeocode() {
    geocoder = new google.maps.Geocoder();
    this.geocode = function (address, callbackFunction) {
      geocoder.geocode({ address: address }, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
          var result = {};
          result.latitude = results[0].geometry.location.lat();
          result.longitude = results[0].geometry.location.lng();
          callbackFunction(result);
        }
      });
    };
  }

  function put_marker_on_google(map, bounds, lat, long, property_id = null) {
    let latLong = new google.maps.LatLng(lat, long);
    const marker = new google.maps.Marker({
      position: latLong,
      map: map,
      animation: google.maps.Animation.DROP,
    });
    bounds.extend(latLong);
    map.fitBounds(bounds);
    let infowindow = null;
    marker.addListener("click", () => {
      if (infowindow) {
        infowindow.close();
        infowindow = null;
      }

      co_owner_ajax(
        {
          id: property_id,
          is_block: false,
          action: "get_maps_property_view",
        },
        function (response) {
          if (response.status) {
            infowindow = new google.maps.InfoWindow({
              content: response.html,
            });
            infowindow.open(map, marker);

            setTimeout(() => {
              $(".first-image-").remove();
              $("#owl-slider").owlCarousel({
                loop: true,
                nav: false,
                items: 1,
                autoWidth: true,
                autoplay: true,
                responsive: {
                  0: {
                    items: 1,
                  },
                  600: {
                    items: 3,
                  },
                  1000: {
                    items: 5,
                  },
                },
              });
            }, 500);
          }
        }
      );
    });
  }

  function check_is_verified_mobile(mobileInput) {
    let mobile = mobileInput.val().trim();
    let verify_button = $(".verify-user-mobile");
    let verified_user_email = verify_button.data("mobile");
    let verification_input_box = $(".user-mobile-no-verify-code-input");
    let mobile_error = $("#mobile-no-error");

    if (
      mobile &&
      (verified_user_email !== mobile || verified_user_email === undefined)
    ) {
      verify_button.html("Verify");
      verify_button.data("verified", false);
      verify_button.data("mobile", null);
      verify_button.hide();
      verification_input_box.hide();
      $('[name="mobile_verified_"]').val("false");
      mobileInput.parent("div").removeClass("input-group");
      verification_input_box.find("input").val(null);
      send_mobile_verification_code(
        mobileInput,
        verify_button,
        verification_input_box,
        mobile_error
      );
    }
  }

  function send_mobile_verification_code(
    mobileInput,
    verify_button,
    verification_input_box,
    mobile_error
  ) {
    let mobile = mobileInput.val().trim();
    if (verify_button.data("verified") === false) {
      co_owner_ajax(
        {
          mobile: mobile,
          action: "send_mobile_verification_code",
        },
        function (response) {
          if (response.status) {
            mobile_error.html(null).hide();
            mobileInput.removeClass("is-invalid");
            verify_button.show();
            verify_button.data("mobile", mobile);
            verification_input_box.show();
            mobileInput.parent("div").addClass("input-group");
            verification_input_box.find("#temp-mobile").html(mobile);
            verification_input_box.find(" :input:first").focus();
          } else {
            mobile_error.html(response.message).show();
            mobileInput.addClass("is-invalid");
          }
        }
      );
    }
  }
/* For SignUp */
  function check_is_verified_email() {
    var emailinput = $("#user-email");
    var email = emailinput.val().trim();
    var verify_user_email = $(".verify-user-email");
    var verified_user_email = verify_user_email.data("email");
     console.log('Inner hit',email);
     console.log(verified_user_email);
  
    if (
      email &&
      (verified_user_email !== email || verified_user_email === undefined)
    ) {
      verify_user_email.html("Verify");
      verify_user_email.data("verified", false);
      emailinput.parent("div").removeClass("input-group");
      verify_user_email.addClass("disabled");
      $('[name="email_verified_"]').val("false");
      $(".email-verify-code-input").find("input").val(null);
	   $(".verified-user-email").addClass("d-none");
      send_verification_code();
	  
	  
    } else {
      if (email === verified_user_email) {
		  if(email!=''){
			  $(".verified-user-email").removeClass("d-none"); 
		  }
		    
        return false;
      }
      $("#user-email-error").html("Email is required.");
      return false;
    }
  }

  /*For Signup*/
  function send_verification_code() {
    var verify_user_email = $(".verify-user-email");
    var email_validation_error = $("#user-email-error");
    email_validation_error.html("");
    var email = $("#user-email").val().trim();
    var email_box = $("#user-email").parent("div");
	email_verified_code_error = false;

    $("#verify-code-input").find("#temp-email").html(email);
    $.ajax({
      url: php_vars.ajax_url,
      method: "POST",
      data: {
        action: "verify_user_email",
        email: email,
      },
      beforeSend() {
        $.blockUI();
      },
      success(response) {
        $.unblockUI();
        response = JSON.parse(response);
        if (response.status === true) {
          $("#verify-code-input")
            .removeClass("d-none")
            .find("#temp-email")
            .html(email);
			
			 $("#verify-code-input").show();
          verify_user_email.data("email", email);
          verify_user_email.addClass("disabled");
          email_box.addClass("input-group");
		  verify_user_email.hide();
          setTimeout(() => {
            $("#verify_code_1").focus();
          }, 30);
		  /* Validate to show otp errors many times*/
		  
        } else {
          email_box.removeClass("input-group");
          $("#verify-code-input").addClass("disabled");
          verify_user_email.html("Verify");
          verify_user_email.data("verified", false);
          verify_user_email.addClass("disabled");
         
		  if(response.verify_status==200){
			   $(".verified-user-email").removeClass("d-none");
			   $(".verified-user-email").show();
			   $("#verify-code-input").hide();
			  verify_user_email.data("email", email);
			  $('.email-verify-code-input').addClass('d-none');
		  }else{
			   email_validation_error.html(response.message);
			  email_validation_error.show();
			   verify_user_email.data("email", '');
		  }
		  
          
          if (response.is_deleted == 3 && php_vars.page == "register") {
            let url = window.location.origin + "/contact-us";
            swalCoOwnerDefault.fire({
              title: "Oops!",
              html:
                'Your account is currently deleted please <a href="' +
                url +
                '" class="text-orange">contact</a> to admin.',
              icon: "warning",
              cancelButtonText: "Okay",
            });
          }
        }
      },
      error() {
        $.unblockUI();
      },
    });
  }



  function send_verification_code_old() {
    var verify_user_email = $(".verify-user-email");
    var email_validation_error = $("#user-email-error");
    email_validation_error.html("");
    var email = $("#user-email").val().trim();
    var email_box = $("#user-email").parent("div");

    $("#verify-code-input").find("#temp-email").html(email);
    $.ajax({
      url: php_vars.ajax_url,
      method: "POST",
      data: {
        action: "verify_user_email",
        email: email,
      },
      beforeSend() {
        $.blockUI();
      },
      success(response) {
        $.unblockUI();
        response = JSON.parse(response);
        if (response.status === true) {
          $("#verify-code-input")
            .removeClass("d-none")
            .find("#temp-email")
            .html(email);
          verify_user_email.data("email", email);
          verify_user_email.addClass("disabled");
          email_box.addClass("input-group");
          setTimeout(() => {
            $("#verify_code_1").focus();
          }, 30);
        } else {
          email_box.removeClass("input-group");
          $("#verify-code-input").addClass("disabled");
          verify_user_email.html("Verify");
          verify_user_email.data("verified", false);
          verify_user_email.addClass("disabled");
          email_validation_error.html(response.message);
          email_validation_error.show();
          if (response.is_deleted == 3 && php_vars.page == "register") {
            let url = window.location.origin + "/contact-us";
            swalCoOwnerDefault.fire({
              title: "Oops!",
              html:
                'Your account is currently deleted please <a href="' +
                url +
                '" class="text-orange">contact</a> to admin.',
              icon: "warning",
              cancelButtonText: "Okay",
            });
          }
        }
      },
      error() {
        $.unblockUI();
      },
    });
  }

  function scroll_to_div(element, minus = 210) {
    $("html, body").animate(
      {
        scrollTop: element ? $(element).offset().top - minus : 0,
      },
      250
    );
  }

  function scroll_to_bottom(element) {
    element.scrollTop(element.get(0).scrollHeight);
  }

  function setProgressBar(pr) {
    if (pr >= 75) {
      $("#preview-button").show();
    } else {
      $("#preview-button").hide();
    }

    if (pr > 0) {
      $(".progress")
        .removeClass("d-none")
        .find(".progress-bar")
        .css("width", (pr <= 100 ? pr : 100) + "%");
    } else {
      $(".progress")
        .addClass("d-none")
        .find(".progress-bar")
        .css("width", "0%");
    }
  }

  function reset_select2(element) {
    if ($(element).length) {
      $(element).val(null).change();
    }
  }

  function change_category_visible_or_hidden_input() {
    let propertyCategory = $(
      "input[name='_pl_property_category']:checked"
    ).val();
    let hiddenInputBox = null;
    let visibleInputBox = null;

    if (propertyCategory === "commercial") {
      visibleInputBox = $(".input-commercial");
      hiddenInputBox = $(".input-residential");
    } else {
      visibleInputBox = $(".input-residential");
      hiddenInputBox = $(".input-commercial");
    }
    visibleInputBox.removeClass("d-none");
    visibleInputBox.find("input").removeClass("ignore-validate");
    visibleInputBox.find("textarea").removeClass("ignore-validate");
    visibleInputBox.find("select").removeClass("ignore-validate");

    hiddenInputBox.addClass("d-none");
    hiddenInputBox.find("input").addClass("ignore-validate");
    hiddenInputBox.find("textarea").addClass("ignore-validate");
    hiddenInputBox.find("select").addClass("ignore-validate");
    hiddenInputBox.find('input[type="radio"]').prop("checked", false).change();
    hiddenInputBox
      .find('input[type="checkbox"]')
      .prop("checked", false)
      .change();
    hiddenInputBox.find('input[type="text"]').val(null);
    hiddenInputBox.find(".room-counter").find("input").val(0);
    hiddenInputBox.find("textarea").val(null);
    hiddenInputBox.find("select").val(null).change();
  }

  function change_selling_visible_or_hidden_input() {
    let interestedInSelling = $(
      "input[name='_pl_interested_in_selling']:checked"
    ).val();
    var changeBoxValues = $(".input-portion-of-it");
    if (interestedInSelling === "full_property") {
      changeBoxValues.addClass("d-none");
      changeBoxValues.find("input").addClass("ignore-validate");
      changeBoxValues.find("select").addClass("ignore-validate");
    } else {
      changeBoxValues.removeClass("d-none");
      changeBoxValues.find("input").removeClass("ignore-validate");
      changeBoxValues.find("select").removeClass("ignore-validate");
    }
  }

  function get_property_full_address() {
    let address = null;
    let unit_no = $('[name="_pl_unit_no"]').val();
    let street_no = $('[name="_pl_street_no"]').val();
    let street_name = $('[name="_pl_street_name"]').val();
    let suburb = $('[name="_pl_suburb"]').val();
    let postcode = $('[name="_pl_postcode"]').val();
    let state = $('[name="_pl_state"]').val();
    address =
      unit_no +
      street_no +
      " " +
      street_name +
      " " +
      suburb +
      " " +
      state +
      " " +
      postcode;
    return address;
  }

  function remove_url_segment(parameter = null) {
    let url = window.location.toString();
    if (parameter) {
      var urlparts = url.split("?");
      if (urlparts.length >= 2) {
        let prefix = encodeURIComponent(parameter) + "=";
        let pars = urlparts[1].split(/[&;]/g);
        for (let i = pars.length; i-- > 0; ) {
          if (pars[i].lastIndexOf(prefix, 0) !== -1) {
            pars.splice(i, 1);
          }
        }
        url = urlparts[0] + (pars.join("&") !== "" ? "?" + pars.join("&") : "");
      }
    } else {
      if (url.indexOf("?") > 0) {
        url = url.substring(0, url.indexOf("?"));
      }
    }
    window.history.replaceState({}, document.title, url);
  }

  function show_alerts(message, type = "danger") {
    var html =
      '<div class="col-md-12">' +
      '<div class="alert alert-' +
      type +
      ' alert-dismissible mb-3 my-3 fade show">' +
      '<strong class="fs-4">' +
      (type === "danger" ? "Error!" : "Success!") +
      "</strong>" +
      '<ul class="mb-0">';
    $.each(message, function (index, message) {
      html += "<li>" + message + "</li>";
    });
    html +=
      "</ul>" +
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
      "</div>" +
      "</div>";
    $("#error-block").show().html(html);
  }

  function toast_with_position(
    message,
    type = "success",
    positionClass = "toast-top-right"
  ) {
    toastr.options.positionClass = positionClass;
    toastr[type](message);
    toastr.options.positionClass = "toast-bottom-right";
  }

  function add_round_dot_by_message(message) {
    if (
      message.hasOwnProperty("sender_user") &&
      message.hasOwnProperty("is_group") &&
      message.hasOwnProperty("group_id")
    ) {
      let orange_circle = '<span class="ms-auto orange-circle"></span>';
      let user = $(
        message.group_id
          ? ".group-" + message.group_id
          : ".user-" + message.sender_user
      );

      let already_orange_circle =
        php_vars.hasOwnProperty("message") &&
        php_vars.message.hasOwnProperty("with") &&
        php_vars.message.with ===
          (message.group_id ? message.group_id : message.sender_user);

      if (user.find(".orange-circle").length === 0 && !already_orange_circle) {
        user.find("a").append(orange_circle);
      }

      let menu_message = $(".is-message-menu").find(".message-alert-dot");
      if (!menu_message.hasClass("orange-circle") && !already_orange_circle) {
        menu_message.addClass("orange-circle");
      }
    }
  }

  function user_shield_tooltip() {
    $(".user-shield-tooltip").each(function (index, itm) {
      let self = $(this);
      if (
        self.data("is-tooltip") == false ||
        self.data("is-tooltip") == undefined
      ) {
        $(this).tooltip({
          title: "Trusted Account",
          placement: "bottom",
        });
        self.data("is-tooltip", true);
      }
    });
  }

  if (php_vars.hasOwnProperty("pusher") && php_vars.user_id > 0) {
    try {
      Pusher.logToConsole = false;
      if (
        php_vars.pusher.hasOwnProperty("cluster") &&
        php_vars.pusher.hasOwnProperty("instance_id")
      ) {
        const pusher = new Pusher(php_vars.pusher.instance_id, {
          cluster: php_vars.pusher.cluster,
          authEndpoint: "/?pusher_auth=true",
        });

        const private_channel = pusher.subscribe(
          "chat-message-" + php_vars.user_id
        );
        let notification_alert_dot = $("#notification-dropdown").find(
          ".notification-alert-dot"
        );

        private_channel.bind("new-message", function (result) {
          if (php_vars.user_status == 1) {
            add_round_dot_by_message(result.message);
            if (
              php_vars.hasOwnProperty("message") &&
              php_vars.message.hasOwnProperty("with") &&
              result.hasOwnProperty("html") &&
              php_vars.message.with === result.message.sender_user
            ) {
              if ($(".chat-message-" + result.message.id).length === 0) {
                $("#message-display-box").append(result.html);
                scroll_to_bottom($("#message-display-box").parent());
                if (result.files.length > 10) {
                  $("#files_and_links").find("ul").prepend(result.files);
                }
              }
            } else {
              let message = result.message.message;
              toastr["info"](message, result.message.display_name);
              if (!notification_alert_dot.hasClass("orange-circle")) {
                notification_alert_dot.addClass("orange-circle");
              }
            }
          }
        });

        const group_channel = pusher.subscribe("chat-group-message");
        group_channel.bind("new-message", function (result) {
          if (
            php_vars.user_status == 1 &&
            php_vars.user_id !== result.message.sender_user &&
            $.inArray(result.message.group_id, php_vars.joined_groups) >= 0
          ) {
            add_round_dot_by_message(result.message);
            if (
              php_vars.hasOwnProperty("message") &&
              php_vars.message.with === result.message.group_id &&
              php_vars.message.hasOwnProperty("with")
            ) {
              if ($(".chat-message-" + result.message.id).length === 0) {
                $("#message-display-box").append(result.html);
                scroll_to_bottom($("#message-display-box").parent());
                if (result.files.length > 10) {
                  $("#files_and_links").find("ul").prepend(result.files);
                }
              }
            } else {
              let message = result.message.message;
              toastr["info"](
                message,
                result.message.display_name + " (On Pool)"
              );
            }
          }
        });
      }
    } catch (e) {}
  }

  $(".LoginPasswordToggle").click(function () {
    let current_type = $(this).closest(".pro-eye").find("input");
    if (current_type.attr("type") === "password")
      current_type.attr("type", "text");
    else current_type.attr("type", "password");
    $(".login-password-toggle").toggle();
  });

  $(".pro-eye input").keyup(function () {
    if ($(this).val().length > 0)
      $(this).closest(".pro-eye").find(".LoginPasswordToggle").show();
    else $(this).closest(".pro-eye").find(".LoginPasswordToggle").hide();
  });
  
  
  if(php_vars.page=="create-a-person-listing" || php_vars.page=="create-a-property-listing"){
	 var clickedUrl=''; 
  $(document).on('click','.cancel_alert',function(e){
	       e.preventDefault();
		   var ele  = $('#cancel_alert').html();
		   var hasEditMode= getSearchParams('id');
		   console.log('hasEditMode',hasEditMode);
		   if(typeof hasEditMode === 'undefined'){		   
	      swalCoOwnerDefault
          .fire({
            title:"",
			html: ele,
			customClass:{
				container: 'cancel_alert_modal',
				
			},
			
            showCancelButton: true,
            confirmButtonText: "Leave this Page",
            cancelButtonText: "Stay on this Page",
          })
          .then((result) => {
			  console.log(result);
            if (result.isConfirmed) {
			if(clickedUrl){
				window.location.href = clickedUrl;
			}else{				
              window.location.href = php_vars.site_url+'/my-listings';
			}
            }
          });  
		   }else{
			   var url = jQuery(this).attr('href');
			   window.location.href = php_vars.site_url+'/my-listings';
		   }

  });
  
  
  $('.custom-logo-link').click(function(){
	   clickedUrl = $(this).attr('href');
	   var hasEditMode= getSearchParams('id');
		   console.log('hasEditMode',hasEditMode);
		   if(typeof hasEditMode === 'undefined'){ 	   
				$('.cancel_alert').trigger('click');
				return false;
		   }	  
	 
  });
  
    $('#navbarNavDropdown ul li a').click(function(){
		 clickedUrl = $(this).attr('href');
	  var hasEditMode= getSearchParams('id');
		   console.log('hasEditMode',hasEditMode);
		   if(typeof hasEditMode === 'undefined'){ 	 
				$('.cancel_alert').trigger('click');	
				return false;
		   }	
  });
  
  
  }
  
  
     $(document).on(
      "click",
      ".confirm-to-property-mark-as-completed",
      function (e) {
        e.preventDefault();
        swalCoOwnerDefault
          .fire({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Do it!",
            cancelButtonText: "Cancel!",
          })
          .then((result) => {
            if (result.isConfirmed) {
              window.location.href = $(this).data("url");
            }
          });
      }
    ); 
	
	  $(document).on("click", ".property-delete-listing", function (e) {
    let post_id = $(this).data("id");
    let post_box = $(this).closest(".property-box");
    if (post_id) {
      swalCoOwnerDefault
        .fire({
          title: "Are you sure?",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, Delete it!",
          cancelButtonText: "Cancel!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: php_vars.ajax_url,
              method: "POST",
              data: {
                id: post_id,
                action: "delete_property_listing",
                ajax_nonce: php_vars.ajax_nonce,
              },
              beforeSend() {
                $.blockUI();
              },
              success(response) {
                response = JSON.parse(response);
                if (response.status && php_vars.page === "my-listings") {
                  post_box.fadeOut(500, () => post_box.remove());
                }
                swalCoOwnerDefault
                  .fire(
                    response.status ? "Deleted!" : "Oops...",
                    response.message,
                    response.status ? "success" : "warning"
                  )
                  .then((result) => {
				  window.location.href = php_vars.site_url+'/my-listings';
                    if (response.status && php_vars.page != "my-listings") {
                     // window.location.href = window.location.origin;
                      window.location.href = php_vars.site_url+'/my-listings';
                    }
                  });
                $.unblockUI();
              },
              error() {
                $.unblockUI();
              },
            });
          }
        });
    }
  });
  
 /* #change003 Google map settings globally */  
  if($("#property-map-view").length > 0){

    $("#property-map-view").each(function (index, item) {
      let address = $(item).data("address");
      let id = $(item).data("id");
      if (address) {
        let element = document.getElementById("property-map-view");
        let googleMap = new google.maps.Map(element, {
          zoom: 5,
          center: australiaCenter,
        });
        let bounds = new google.maps.LatLngBounds();
        let map = new GoogleGeocode();
        map.geocode(address, function (data) {
          if (data != null) {
            put_marker_on_google(
              googleMap,
              bounds,
              data.latitude,
              data.longitude,
              id
            );
            googleMap.setZoom(15);
          }
        });
      }
    }); 
}
/*Google map settings globally end  */  

/* Copy Paste OTP*/

const $inp = $(".passInput");
$inp.on({
  paste(ev) { // Handle Pasting
  
    const clip = ev.originalEvent.clipboardData.getData('text').trim();

	console.log('Clip Data');
	console.log(clip);
    // Allow numbers only
    if (!/\d{4}/.test(clip)) return ev.preventDefault(); // Invalid. Exit here
    // Split string to Array or characters
    const s = [...clip];
	console.log(s);
    // Populate inputs. Focus last input.
    $inp.val(i => s[i]).eq(3).focus(); 
  },
  input(ev) { // Handle typing
    
    const i = $inp.index(this);
    if (this.value) $inp.eq(i + 1).focus();
  },
  keydown(ev) { // Handle Deleting
    
    const i = $inp.index(this);
    if (!this.value && ev.key === "Backspace" && i) $inp.eq(i - 1).focus();
  }
});
/* Copy Paste OTP*/
  
  
  
  /*Croppie code*/
  
   $image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:200,
      height:200,
      type:'square' //circle
    },
    boundary:{
      width:300,
      height:300
    }
  });

  $('#upload_image').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });

  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
      $.ajax({
        url: php_vars.ajax_url,
        type: "POST",
        data:{"image": response,'action': 'croppie_profile_fnc'},
        success:function(data)
        {
          $('#uploadimageModal').modal('hide');
		   
         $('#user-profile').attr('src',data);
		 $('#user-profile').show();
      
        }
      });
    })
  });

  
  /*Croppie code*/
  
  
});

/*For Signup*/
if(php_vars.page=="register" || php_vars.steps_complete==400){
/* Registration Modal steps */
  var stepNumber=1;

   /* change 006 */
    var resumable = new Resumable({
      target: "#",
      maxFiles: 1,
      fileType: ["png", "jpg", "jpeg"],
    });
	  /* change 006 */
    resumable.assignBrowse(jQuery("#user-profile-browse").get(0));
    resumable.assignDrop(jQuery("#user-profile-drop-box").get(0));
    resumable.on("fileAdded", function (file) {
    var reader = new FileReader();
      reader.onload = function (event) {
		  
        jQuery("#user-profile").prop("src", event.target.result);
		 jQuery("#user-profile").show();
        jQuery(".remove-user-image").data("id", file.uniqueIdentifier).show();
      };
      reader.readAsDataURL(file.file);
    });
	
	  function saveModalRegisterData(){
    var formData =new FormData();
    formData.append('action', 'save_register_modal');
    formData.append('property_option', jQuery('.property_option:checked').val());
   console.log(resumable.files.length);
    if (resumable.files.length) {
          formData.append("profile", resumable.files[0].file);
		   /* #changed 11*/
		   formData.append("profile_data_img",  jQuery('#user-profile').attr('src'));
		    /* #changed 11*/
        }
		
   console.log(formData);
	jQuery('body').addClass('registerModalOverlay');
	jQuery('#registerModalWait').show();
	  jQuery.ajax({
		  type:"post",
		  url: php_vars.ajax_url,
		  data:formData,
		       processData: false,
            contentType: false,
		  success:function(res){
               response = JSON.parse(res);
			  if(response.status==200){
               jQuery('#registerModalWait').hide();
                 jQuery('#registerModalMessage').addClass('alert alert-success').text(response.message);
                 setTimeout(function(){
                   window.location.href = response.redirect;
				},500);
				
			  }else{
				  jQuery('#registerModalMessage').addClass('alert alert-danger').text(response.message);
			  }
		  }
	  });
	  
  }
  
	function registerManageSteps(stepNum){
		jQuery('#register_steps .register_step').hide();
		jQuery('.register_step'+stepNum).fadeIn();	
		console.log('stepNumber ='+stepNumber);
	}
	
  function regManageBtn(){
	  if(stepNumber == 1){
			jQuery('#register_back').hide();
			jQuery('#register_next').show();	
		}
	  else if(stepNumber == 1){
			jQuery('#register_back').hide();
			jQuery('#register_next').hide();	
		}		
		else{
			jQuery('#register_back').show();
			jQuery('#register_next').show();		
		}
  }	
  
  /* On load */
  registerManageSteps(stepNumber);
  
  jQuery('#registerModal').submit(function(){
	 saveModalRegisterData();
     return false;	 
  });
  
  
  jQuery('.property_option').click(function(){
	  var getValue= jQuery(this).val();
	   jQuery('#register_next').prop('disabled', false);
  });

  
	jQuery('body').on('click','#register_next',function(){

		// jQuery('#property_goal').modal('show');
		   stepNumber = stepNumber+1;
		   console.log('stepNumber ' +stepNumber);
		  if(stepNumber==3){
			  saveModalRegisterData(); 
              stepNumber = stepNumber-1;
		  }else{
			registerManageSteps(stepNumber);		  
			regManageBtn();	  
		  } 

		 var property_option = jQuery('input[name="property_option"]:checked').val();
		 
		 if(property_option == "buy_property"){
		  jQuery('.profile-selected').html("Buyer's Profile");
		 }else{
		 jQuery('.profile-selected').html("Seller's Profile");
		 }
		  return false;
    });	
	
	jQuery('body').on('click','#register_back',function(){
		   stepNumber = stepNumber-1;
          registerManageSteps(stepNumber);		  
          regManageBtn();
		// jQuery('#property_goal').modal('show');
		  return false;
    });		
	/* Registration Modal steps */
	
	/*
	    let resumable = new Resumable({
      target: "#",
      maxFiles: 1,
      fileType: ["png", "jpg", "jpeg"],
    });
    resumable.assignBrowse($("#user-profile-browse").get(0));
    resumable.assignDrop($("#user-profile-drop-box").get(0));
    resumable.on("fileAdded", function (file) {
      var reader = new FileReader();
      reader.onload = function (event) {
        $("#user-profile").prop("src", event.target.result);
        $(".remove-user-image").data("id", file.uniqueIdentifier).show();
      };
      reader.readAsDataURL(file.file);
    });
	
	On modal popup
	
	
	*/
	

	
}

function loadFormFieldData(){

        jQuery("body").find("input").each(function() {

            var field= jQuery(this).attr('id');
            var fieldType= jQuery(this).attr('type');
            var fieldName= jQuery(this).attr('name');
			 var value= jQuery(this).val();
			if(fieldType=="radio"){
				  value = jQuery('input[name='+fieldName+']:checked').val();
				edit_list_obj[fieldName] = value;
			}
	
		   if(fieldType=="checkbox"){
				if(jQuery(this).is(':checked')){					 
					 fieldName =	field;	
                    edit_list_obj[fieldName] = value;					 
				}                
			}

		   if(fieldType=="text"){
								 
					
					edit_list_obj[fieldName] = value;					 
			              
			}			
			
            
        });
	
         jQuery("body").find("select").each(function() {

            var field= jQuery(this).attr('id');
            var fieldType= jQuery(this).attr('type');
            var fieldName= jQuery(this).attr('name');
			 var value= jQuery(this).val();
				  
			value =jQuery(this).find('option:selected').text();				  

			
            edit_list_obj[fieldName] = value;
			console.log('Select');
        });      

		var textarea1val = jQuery('#exampleFormControlTextarea1').val();
		 edit_list_obj['_pl_descriptions'] = textarea1val;
		
		
}
   var isThereAnyChange = false;  
function onChangeFieldData(){
     
       if(edit_list_obj){	
        jQuery("body").find("input").each(function() {

            var field= jQuery(this).attr('id');
            var fieldType= jQuery(this).attr('type');
            var fieldName= jQuery(this).attr('name');
			 var value= jQuery(this).val();
			if(fieldType=="radio"){
				  value = jQuery('input[name='+fieldName+']:checked').val();
				  if(edit_list_obj[fieldName]!=value){
					 isThereAnyChange = true;
				  }
			}
	
		   if(fieldType=="checkbox"){
				if(jQuery(this).is(':checked')){					 
					 fieldName =	field;	
				 if(edit_list_obj[fieldName]!=value){
					 isThereAnyChange = true;
				  }	
				  
				}                
			}

		   if(fieldType=="text"){
			   
				  if(edit_list_obj[fieldName]!=value){
					 isThereAnyChange = true;
				  }			              
			}  
        });
	
         jQuery("body").find("select").each(function() {

            var field= jQuery(this).attr('id');
            var fieldType= jQuery(this).attr('type');
            var fieldName= jQuery(this).attr('name');
			 var value= jQuery(this).val();
				  
			value =jQuery(this).find('option:selected').text();				  
               console.log(edit_list_obj[fieldName]);
               console.log(value);
			   if(edit_list_obj[fieldName]!=value){
					 isThereAnyChange = true;
				  }
        });      

		var textarea1val = jQuery('#exampleFormControlTextarea1').val();
		 edit_list_obj['_pl_descriptions'] = textarea1val;
		   if(edit_list_obj['_pl_descriptions']!=textarea1val){
			 isThereAnyChange = true;
		  } 
		 
	   }		 
  if(isThereAnyChange){
	  jQuery('.submit-co-owner-property-form').removeClass('btn_list_disabled');
  }	else{
	  jQuery('.submit-co-owner-property-form').addClass('btn_list_disabled');
  }
  console.log( 'isThereAnyChange =' +isThereAnyChange);
		
}

 if(php_vars.page=="create-a-property-listing"){
//loadFormFieldData();

 function checkFormEdit(){
	 var formData = jQuery('form').serialize();
	 formData = formData+'&action=checkFormEdit';
	 jQuery.ajax({
		 type:"POST",
		 url:php_vars.ajax_url,
		 data:formData,
		 success: function(res){
			 console.log(res);
			 if(res > 0){
				 jQuery('.submit-co-owner-property-form').removeClass('btn_list_disabled');
			 }else{
				 jQuery('.submit-co-owner-property-form').addClass('btn_list_disabled'); 
			 }
		 }
		 
	 });
	 
 }
 jQuery('input[type=radio]').click(function(){
	  checkFormEdit();
 });
  jQuery('input[type=checkbox]').click(function(){
	  checkFormEdit();
 });
 
 jQuery('input[type=text]').keyup(function(){
	  checkFormEdit();
 });
  jQuery('textarea').keyup(function(){
	  checkFormEdit();
 });
   jQuery('select').change(function(){
	  checkFormEdit();
  });



 }	

/* #changed 11*/
jQuery('.post_now_job').click(function(){
	setTimeout(function(){
		  toastr["success"]("Property Added Successfully.");		
	},500);

});

/* #changed 11*/
jQuery('.toggle_property_comment').click(function(){
  jQuery(this).next().next().toggle();
});

	
