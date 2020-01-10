jQuery(function () {
    // выход
    var $form = $('#layout-exit-form').css('display', 'none');
    $('<a>', {
        href: '#',
        html: $form.find('button').html(),
        click: function () {
            $form.submit();
            return false;
        }
    }).insertAfter($form);

    // лечим checkbox'ы zircos'а
    $('.checkbox').each(function () {
        var $this = $(this);
        $this.find('input').detach().insertBefore($this.find('label'));
    });

    // лечим оступ в футоре
    $(window).on("scroll", function () {
        var scrollHeight = $(document).height();
        var scrollPosition = $(window).height() + $(window).scrollTop();
        if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
            $(window).trigger('resize');
        }
    });
});