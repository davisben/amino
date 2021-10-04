/**
 * @file
 * Javascript for the layout settings form.
 */

 ((Drupal) => {
  'use strict';

  Drupal.behaviors.aminoPageBuilderLayoutSettings = {
    attach: (context) => {
      var slider = document.getElementById('region-slider');

      if (slider) {
        if (slider.noUiSlider) {
          slider.noUiSlider.destroy();
        }

        var regions = document.querySelectorAll('input[name*="region_width"]');
        var regionSize = Math.floor(100 / regions.length);
        var start = [];

        regions.forEach(function(region, index) {
          var value = parseInt(region.value);
          if (isNaN(value)) {
            start.push(regionSize * index);
          }
          else {
            var prev = isNaN(start[index - 1]) ? 0 : start[index - 1];
            start.push(value + prev);
          }
        });

        if (start[0] === 0) {
          start.shift();
        }
        else {
          start.pop();
        }

        noUiSlider.create(slider, {
          start: start,
          step: 1,
          range: {
            'min': 0,
            'max': 100
          },
          format: {
            to: function (value) {
              return value;
            },
            from: function (value) {
              return Number(value);
            }
          },
        });

        slider.noUiSlider.on('update', function (values, handle) {
          if (regions.length === 2) {
            regions[0].value = values[0];
            regions[1].value = 100 - values[0];
          }
          else {
            if (handle === 0) {
              regions[0].value = values[0];
              regions[1].value = values[1] - values[handle];
            }
            else if (handle === regions.length - 2) {
              regions[handle].value = values[handle] - values[handle - 1];
              regions[handle + 1].value = 100 - values[handle];
            }
            else {
              regions[handle].value = values[handle] - values[handle - 1];
              regions[handle + 1].value = values[handle + 1] - values[handle];
            }
          }
        });
      }
    }
  };
})(Drupal);
