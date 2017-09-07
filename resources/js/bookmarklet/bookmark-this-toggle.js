jQuery(document).ready(function ($) {
    var $showPressThisWrap = $('.js-show-pressthis-code-wrap');
    var $pressthisCode = $('.js-pressthis-code');

    $showPressThisWrap.on('click', function (event) {
        var $this = $(this);

        $this.parent().next('.js-pressthis-code-wrap').slideToggle(200);
        $this.attr('aria-expanded', $this.attr('aria-expanded') === 'false' ? 'true' : 'false');
    });

    // Select Press This code when focusing (tabbing) or clicking the textarea.
    $pressthisCode.on('click focus', function () {
        var self = this;
        setTimeout(function () {
            self.select();
        }, 50);
    });

    $('a.pressthis-bookmarklet').on('click', function(event){
        event.preventDefault();
    })

});
