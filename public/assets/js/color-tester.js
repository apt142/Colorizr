/* globals $, tinycolor */

$(document).ready(function () {
  'use strict';

  var buildTimer = null;
  var rebuildDelay = 200;

  var styleTimer = null;
  var rebuildStyleDelay = 500;

  var body = $('body');

  body.on('change', '#primary', function () {
    var color = tinycolor($(this).val());
    $('#sample-rgb').val(color.toHexString());

    clearTimeout(buildTimer);
    buildTimer = setTimeout(buildPalette, rebuildDelay);
  });

  body.on('keyup', '#sample-rgb', function () {
    var color = tinycolor($(this).val());

    if (color.isValid()) {
      $('#primary').val(color.toHexString());
      clearTimeout(buildTimer);
      buildTimer = setTimeout(buildPalette, rebuildDelay);
    }
  });

  body.on('blur', '#sample-rgb', function () {
    var color = tinycolor($(this).val());

    if (color.isValid()) {
      $(this).val(color.toHexString());
    }
  });

  body.on('click', '#build-action', function (e) {
    styleSheet.getStyleSheet();
    e.preventDefault();
  });

  body.on('change', 'input,select', function () {
    updateStyleSheet();
  });

  buildPalette();
  styleSheet.getStyleSheet();

  function buildPalette() {
    var color = tinycolor($('#sample-rgb').val());

    if (color.isValid()) {
      fetchRelatedColors(color.toHexString());
    }
  }

  function updateStyleSheet() {
    clearTimeout(styleTimer);
    styleTimer = setTimeout(
      function () {
        styleSheet.getStyleSheet();
      },
      rebuildStyleDelay
    );
  }

  function fetchRelatedColors(color) {
    color = color.replace('#', '');

    $.get('/theme/' + color, {}, function (data) {
      colorSwatch('.success-swatch', data.success);
      colorSwatch('.info-swatch', data.info);
      colorSwatch('.warning-swatch', data.warning);
      colorSwatch('.danger-swatch', data.danger);

      $('#success').val(data.success);
      $('#info').val(data.info);
      $('#warning').val(data.warning);
      $('#danger').val(data.danger);
    });

    function colorSwatch(className, color) {
      var textColor = '#ccc';
      $(className).css('background', color);
      $(className + ' .swatch-color-label').html(color);
      if (tinycolor(color).isDark()) {
        textColor = '#ddd';
      } else {
        textColor = '#111';
      }
      $(className + ' .swatch-color-label').css({color: textColor});
    }
  }

});


var styleSheet = {


  getStyleSheet: function() {
    'use strict';

    var _this = this;
    var params = {
      'primary': $('#primary').val(),
      'info':    $('#info').val(),
      'success': $('#success').val(),
      'warning': $('#warning').val(),
      'danger':  $('#danger').val(),

      'font-size': $('#font-size').val(),
      'font-family': $('#font-family').val(),
      'heading-family': $('#heading-family').val(),
      'text-color': $('#text-color').val(),
      'body-color': $('#body-color').val(),

      'border-radius': $('#border-radius').val()
    };

    $.post('/build/bootstrap', params, function(data) {
      _this.updateStylesheet(data);
    });
  },

  updateStylesheet: function(data) {
    // $('#bootstrap-link-old').attr('href', $('#bootstrap-link').attr('href'));

    $('#bootstrap-link').attr('href', '/' + data.file_path);
    $('#download-link').attr('href', '/' + data.file_path);
  }
};



