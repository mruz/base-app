(function($) {
    // Flash close
    $(".alert .close").click(function() {
        $(this).parent().fadeTo("slow", 0.00, function() {
            $(this).slideUp();
        });
        return false;
    });

    // Modal
    $(document).on('shown.bs.modal', function(e) {
        $('a[data-accept="modal"]').attr('href', e.relatedTarget.href);
    });
    $(document).on('hidden.bs.modal', '.modal', function() {
        $(this).removeData('bs.modal');
    });

    // Hook
    $(window).load(function() {
        var header = 55;
        var url = window.location;

        $("a").click(function() {
            var h = this.href.substr(0, this.href.indexOf(this.hash));
            var u = url.hash ? url.toString().substr(0, url.toString().indexOf(url.hash)) : url.toString();
            if (h == u) {
                url.hash = this.hash;
                $('html, body').animate({scrollTop: $(this.hash).offset().top - header}, "fast");
                return false;
            }
        });

        if (url.hash) {
            $("html, body").animate({scrollTop: $(url.hash).offset().top - header}, "fast");
        }
    });
})(jQuery);