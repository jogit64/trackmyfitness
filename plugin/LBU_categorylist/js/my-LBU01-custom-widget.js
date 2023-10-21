jQuery(document).ready(function ($) {
  var widgetId = "my-LBU01-custom-widget";

  $(document).on(
    "change",
    "#" + widgetId + ' .elementor-control-icon_spacing input[type="range"]',
    function () {
      var value = $(this).val();
      $("#" + widgetId + " .ma-classe-css-personnalisable li a .icon").css(
        "margin-right",
        value + "px"
      );
    }
  );
});
