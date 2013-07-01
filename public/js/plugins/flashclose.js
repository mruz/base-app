(function($){ 
    $(".alert .close").click(function() { 
        $(this).parent().fadeTo("slow", 0.00, function(){
            $(this).slideUp();
        }); return false; 
    });
})(jQuery);

