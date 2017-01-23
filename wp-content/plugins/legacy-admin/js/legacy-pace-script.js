/**
 * @Package: WordPress Plugin
 * @Subpackage: Legacy - White Label WordPress Admin Theme
 * @Since: Legacy 1.0
 * @WordPress Version: 4.0 or above
 * This file is part of Legacy - White Label WordPress Admin Theme Plugin.
 */


/*----------------------------------
 Page loader
 -----------------------------------*/

(function($) {
    Pace.on('start', function() {
        //$(".pace-progress-inner").html("Loading...");
        //$(".pace").append('<div class="contain"><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div><div class="item"></div></div>');
        //console.log("pace started");
    });
    Pace.on('hide', function() {
        $("#wpwrap").addClass("loaded");
        //$("#wrapper").removeClass("loading");
        //console.log("pace ended");
    });
})(jQuery);

