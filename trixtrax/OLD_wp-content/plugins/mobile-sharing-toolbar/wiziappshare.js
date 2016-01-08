jQuery.noConflict();

function lightbox(){

	// jQuery wrapper (optional, for compatibility only)
	(function($) {

		// add lightbox/shadow <div/>'s if not previously added
		$('#wiziappshare-lightbox-shadow').click(closeLightbox);


		// move the lightbox to the current window top + 100px
                $("body").undelegate('.wiziappshare-toolbar','click',lightbox);
                $("body").delegate('.wiziappshare-toolbar','click',closeLightbox);

		// display the lightbox
                var height = Math.max($(document).height(), $(window).height());
		$('#wiziappshare-lightbox-shadow').css('height', height + 'px');
		$('#wiziappshare-lightbox-shadow').show();
		$('.wiziappshare-menu').show();

	})(jQuery); // end jQuery wrapper

}

// close the lightbox
function closeLightbox(){

	// jQuery wrapper (optional, for compatibility only)
	(function($) {

		// hide lightbox/shadow <div/>'s
                $("body").delegate('.wiziappshare-toolbar','click',lightbox);
		$('#wiziappshare-lightbox-shadow').hide();
		$('.wiziappshare-menu').hide();


	})(jQuery); // end jQuery wrapper

}

function wiziapp_pageshow( event, ui ) {
    (function( $ ) {

        $(".wiziappshare-static-menu").map(function () {
            var currParent = $(this).parent();
	    var moveIt = $(this).remove();
	    currParent.append(moveIt);
        });

        $('.wiziappshare-toolbar').hide();
        $('.wiziappshare_show').parents().filter('[data-role=page]').map(function () {
            if($(this).is(':visible')) {
				var title = $(this).find("wiziapp-post-title").text();
                $('.wiziappshare-toolbar').show();
                $(this).find('.wiziappshare_show').map(function () {
					var url = encodeURIComponent($(this).text());
                    $('#wiziappshare-mail').attr("href", "mailto:?subject="+encodeURIComponent(title)+"&body=" + url);
                    $('#wiziappshare-google').attr("href", "https://plus.google.com/share?url=" + url);
                    $('#wiziappshare-linkedin').attr("href", "http://www.linkedin.com/shareArticle?url=" + url);
                    $('#wiziappshare-facebook').attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + url);
                    $('#wiziappshare-twitter').attr("href", "https://twitter.com/intent/tweet?source=webclient&text=" + url);
                });
            }
        });
     })(jQuery);
}

(function( $ ) {
  $(function() {
        $(".wiziappshare-static-menu").map(function () {
            var currParent = $(this).parent();
	    var moveIt = $(this).remove();
	    currParent.append(moveIt);
        });

        $("body").delegate('.wiziappshare-toolbar','click',lightbox);
        $("body").delegate('[data-role=page]','isVisible', wiziapp_pageshow);
        if( $('#wiziapp-theme-style-css').length || $('.wiziapp-header').length || $("script:contains('WiziappAccessPoint')").length)         // use this if you are using class to check
	{
        	$("body").delegate('[data-role=page]','pageshow', wiziapp_pageshow);
        	wiziapp_pageshow();
	}
  });
})(jQuery);


