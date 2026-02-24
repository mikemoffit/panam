jQuery(function ($) {
  $(document).on('click', '.panam-media-upload', function (e) {
    e.preventDefault();

    const $wrap = $(this).closest('.panam-media-field');
    const $hidden = $wrap.find('.panam-media-id');
    const $remove = $wrap.find('.panam-media-remove');
    const $preview = $wrap.parent().find('.panam-media-preview');

    const frame = wp.media({
      title: 'Select Site Logo',
      button: { text: 'Use this logo' },
      multiple: false
    });

    frame.on('select', function () {
      const attachment = frame.state().get('selection').first().toJSON();
      $hidden.val(attachment.id);
      $remove.prop('disabled', false);

      if (attachment.url) {
        $preview.html(
          '<div class="panam-media-preview-box">' +
            '<img class="panam-media-preview-img" src="' + attachment.url + '" alt="">' +
          '</div>'
        );
      } else {
        $preview.empty();
      }
    });

    frame.open();
  });

  $(document).on('click', '.panam-media-remove', function (e) {
    e.preventDefault();

    const $wrap = $(this).closest('.panam-media-field');
    $wrap.find('.panam-media-id').val('');
    $(this).prop('disabled', true);
    $wrap.parent().find('.panam-media-preview').empty();
  });
});

jQuery(function ($) {
  if ($.fn.wpColorPicker) {
    $('.panam-color-field').wpColorPicker();
  }
});