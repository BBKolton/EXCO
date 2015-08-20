$(window).on("hashchange", function () {
    window.scrollTo(window.scrollX, window.scrollY - 100);
});

//TODO make it scroll

/*$('#sidenavbar').affix();

$('#sidenavbar').on('affix.bs.affix', function (e) {
    var $this = $(this),
        affix = $this.data('bs.affix'),
        offset = affix.options.offset,
        offsetBottom = offset.bottom;

    if (typeof offset != 'object') {
        offsetBottom = offset;
    }

    if (typeof offsetBottom == 'function') {
        offsetBottom = offset.bottom($this);
    }

    if ($this.outerHeight() + $this.offset().top + offsetBottom === Math.max($(document).height(), $(document.body).height())) {
        e.preventDefault();
    }
});*/