INSPIRO.pageloader = {
    
        init: function () {
            var $body = $("body");
            //Check if Animsition plugin is loaded
            if (typeof $.fn.animsition === 'undefined') {
                INSPIRO.elements.notification("Warning", "Pageloader plugin is missing, please add this code line <b> &lt;script src=&quot;plugins/pageloader/pageloader.js&quot;&gt;&lt;/script&gt;</b>, after <b>functions.js</b>", "danger")
                return true;
            }
            if (!$body.hasClass('no-page-loader')) {
                //Plugin Options
                var options = {
                        loading: $body.attr('data-loading') || true,
                        inClass: $body.attr('data-animation-in') || "fadeIn",
                        outClass: $body.attr('data-animation-out') || "fadeOut",
                        inDuration: $body.attr('data-speed-in') || 300,
                        outDuration: $body.attr('data-speed-out') || 300,
                        loaderIcon: $body.attr('data-icon') || 10,
                        loaderIconColor: $body.attr('data-icon-color') || null,
                        customMessage: $body.attr('data-loading-message')
                    },
                    iconColor = "",
                    loadingInnerHTML = "";
                //Generate loader icon based on elem.options.loading value
                switch (Number(options.loaderIcon)) {
                    case 1:
                        loadingInnerHTML = '<div class="material-icon"><div class="spinner"><div class="right-side"><div class="bar"></div></div><div class="left-side"><div class="bar"></div></div></div></div>';
                        iconColor = '.spinner .bar {border-color: ' + options.loaderIconColor + ';} .spinner .bar:after {background: ' + options.loaderIconColor + ';}';
                        break;
                    case 2:
                        loadingInnerHTML = '<div class="loader-inner ball-grid-pulse"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>';
                        iconColor = '.ball-grid-pulse > div {background: ' + options.loaderIconColor + '!important;}';
                        break;
                    case 3:
                        loadingInnerHTML = '<div class="loader01"></div>';
                        iconColor = '.loader01 {border-color: ' + options.loaderIconColor + ' transparent ' + options.loaderIconColor + ' ' + options.loaderIconColor + ';} .loader01::after {background: ' + options.loaderIconColor + ';}';
                        break;
                    case 4:
                        loadingInnerHTML = '<div class="loader-inner square-spin"><div></div></div>';
                        iconColor = '.square-spin > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 5:
                        loadingInnerHTML = '<div class="loader04"></div>';
                        iconColor = '.loader04:after {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 6:
                        loadingInnerHTML = '<div class="loader-inner ball-rotate"><div></div></div>';
                        iconColor = '.ball-rotate > div, .ball-rotate > div:after, .ball-rotate > div:before {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 7:
                        loadingInnerHTML = '<div class="loader-inner cube-transition"><div></div><div></div></div>';
                        iconColor = '.cube-transition > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 8:
                        loadingInnerHTML = '<div class="loader-inner ball-zig-zag"><div></div><div></div></div>';
                        iconColor = '.ball-zig-zag > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 9:
                        loadingInnerHTML = '<div class="loader-inner ball-triangle-path"><div></div><div></div><div></div></div>';
                        iconColor = '.ball-triangle-path > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 10:
                        loadingInnerHTML = '<div class="loader-inner line-scale"><div></div><div></div><div></div><div></div><div></div></div>';
                        iconColor = '.line-scale > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 11:
                        loadingInnerHTML = '<div class="loader-inner ball-scale-multiple"><div></div><div></div><div></div></div>';
                        iconColor = '.ball-scale-multiple > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 12:
                        loadingInnerHTML = '<div class="loader-inner ball-pulse-sync"><div></div><div></div><div></div></div>';
                        iconColor = '.ball-pulse-sync > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 13:
                        loadingInnerHTML = '<div class="loader-inner ball-beat"><div></div><div></div><div></div></div>';
                        iconColor = '.ball-beat > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 14:
                        loadingInnerHTML = '<div class="loader-inner line-scale-pulse-out-rapid"><div></div><div></div><div></div><div></div><div></div></div>';
                        iconColor = '.line-scale-pulse-out-rapid > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 15:
                        loadingInnerHTML = '<div class="loader-inner ball-scale-ripple-multiple"><div></div><div></div><div></div></div>';
                        iconColor = '.ball-scale-ripple-multiple > div {border-color: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 16:
                        loadingInnerHTML = '<div class="loader-inner ball-spin-fade-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>';
                        iconColor = '.ball-spin-fade-loader > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 17:
                        loadingInnerHTML = '<div class="loader-inner line-spin-fade-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>';
                        iconColor = '.line-spin-fade-loader > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 18:
                        loadingInnerHTML = '<div class="loader-inner pacman"><div></div><div></div><div></div><div></div><div></div></div>';
                        iconColor = '.pacman > div:nth-child(3), .pacman > div:nth-child(4), .pacman > div:nth-child(5), .pacman > div:nth-child(6)  {background: ' + options.loaderIconColor + ' !important;} .pacman > div:first-of-type, .pacman > div:nth-child(2) {border-color: ' + options.loaderIconColor + ' transparent ' + options.loaderIconColor + ' ' + options.loaderIconColor + '}';
                        break;
                    case 19:
                        loadingInnerHTML = '<div class="loader-inner ball-grid-beat"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>';
                        iconColor = '.ball-grid-beat > div {background: ' + options.loaderIconColor + ' !important;}';
                        break;
                    case 20:
                        loadingInnerHTML = '<div class="single9"></div>';
                        iconColor = '.single9:before {background-color: ' + options.loaderIconColor + ' !important;}';
                        break;
                    default:
                        loadingInnerHTML = '<div class="material-icon"><div class="spinner"><div class="right-side"><div class="bar"></div></div><div class="left-side"><div class="bar"></div></div></div></div>';
                        iconColor = '.spinner .bar {border-color: ' + options.loaderIconColor + ';} .spinner .bar:after {background: ' + options.loaderIconColor + ';}';
                        break;
                }
                if (options.loaderIconColor) {
                    $('head').append('<style type="text/css">' + iconColor + '</style>');
                }
                var innerHTMLcode = "";
                if (options.customMessage) {
                    innerHTMLcode = '<div class="loader">' + loadingInnerHTML + '</div>' + options.customMessage;
                } else {
                    innerHTMLcode = '<div class="loader">' + loadingInnerHTML + '</div>';
                }
                if ($('#wrapper').length > 0) {
                    $bodyInner = $('#wrapper');
                } else {
                    $bodyInner = $('.body-inner');
                }
                //Initializing jQuery Animsition plugin and passing the options
                $bodyInner.animsition({
                    inClass: options.inClass,
                    outClass: options.outClass,
                    inDuration: options.inDuration,
                    outDuration: options.outDuration,
                    loading: options.loading,
                    onLoadEvent: true,
                    loadingParentElement: 'body',
                    loadingClass: 'animsition-loading',
                    loadingInner: '<div class="loader">' + loadingInnerHTML + '</div>',
                    linkElement: '#page-menu a, #mainMenu a:not([target="_blank"]):not([href*="#"]):not([data-lightbox]):not([href^="mailto"]):not([href^="tel"]):not([href^="sms"]):not([href^="call"]), .animsition-link, .slide-link'
                });
                //Disable page loader on page js errors
                var t = setTimeout(function () {
                    if ($(".animsition-loading").length) {
                        $body.addClass("no-page-loader");
                        $(".animsition-loading").hide();
                    }
                }, 10000);
            }
        }
};
//Load Functions on document ready
$(document).ready(function () {
    INSPIRO.pageloader.init();
});