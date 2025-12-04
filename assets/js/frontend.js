jQuery(document).ready(function ($) {
  "use strict";

  const form = $("#lhf-registration-form");

  form.on("submit", function (event) {
    let isValid = true;

    // Remove previous errors
    form.find(".lhf-error").removeClass("lhf-error");
    $(".lhf-validation-message").remove();

    // Check each required field
    form.find("[required]").each(function () {
      if ($(this).is(":radio") || $(this).is(":checkbox")) {
        const name = $(this).attr("name");
        if ($(`input[name="${name}"]:checked`).length === 0) {
          isValid = false;
          // Find the group parent to highlight
          $(this)
            .closest(".lhf-radio-group, .lhf-checkbox-group")
            .addClass("lhf-error");
        }
      } else if ($(this).val().trim() === "") {
        isValid = false;
        $(this).addClass("lhf-error");
      }
    });

    if (!isValid) {
      // Prevent the form from submitting
      event.preventDefault();
      // Add a general error message at the top
      form.prepend(
        '<div class="lhf-alert lhf-alert-error lhf-validation-message">Please fill out all required fields marked with an *.</div>'
      );
      // Scroll to the first error message
      $("html, body").animate(
        {
          scrollTop: form.offset().top - 100,
        },
        500
      );
    }
  });

  // Remove error class when the user starts correcting it
  form.on("input change", ".lhf-error", function () {
    $(this).removeClass("lhf-error");
  });
});
