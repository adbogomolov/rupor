(function () {
    "use strict";

    var libVersion = {
        jQuery: '2.0.2',
        jQueryIe: '1.10.1',
        jQueryUi: '1.10.3',
        jQueryMigrate: '1.2.1',

        underscore: '1.4.4',
        backbone: '1.0.0'
    };

    /*============================================================================================*/
    // jQuery CDN paths
    var cdnJquery = 'http://yandex.st/jquery/' + libVersion.jQuery + '/jquery.min.js',
        cdnJqueryIe = 'http://yandex.st/jquery/' + libVersion.jQueryIe + '/jquery.min.js';

    // jQuery local paths
    var jQueryLocal = ['js/jquery-' + libVersion.jQuery + '.min.js'],
        jQueryLocalIe = ['js/jquery-' + libVersion.jQueryIe + '.min.js'];

//    var projectScript = homePage ? 'js/home.js' : 'js/scripts.js';
    var projectScript = 'js/scripts.js';

    var modernizrPath = 'js/modernizr.js',
        isLoaded = false;

    // other scripts
    var otherScripts = [
//            'https://cdnjs.cloudflare.com/ajax/libs/underscore.js/' + libVersion.underscore + '/underscore-min.js',
//            'https://cdnjs.cloudflare.com/ajax/libs/backbone.js/' + libVersion.backbone + '/backbone-min.js',
            'js/jquery-ui-1.10.3.custom.min.js',
            projectScript
        ],
        otherScriptsLocal = [ // local *.js files if CDN failed
//            'js/underscore-min.js',
//            'js/backbone-min.js',
            'js/jquery-ui-1.10.3.custom.min.js',
            projectScript
        ];

//    var homePage = document.body.id === 'home';
//    if ( !homePage ) {
//        otherScripts.unshift( 'http://yandex.st/jquery-ui/' + libVersion.jQueryUi + '/jquery-ui.min.js' );
//        otherScriptsLocal.unshift( 'js/jquery-ui.min.js' );
//    }

    getOtherScripts();
    // getModernizr();
    /*============================================================================================*/

    function getModernizr() {
        var head = document.getElementsByTagName( 'head' )[0];
        var script = document.createElement( 'script' );

//        script.setAttribute('type', 'text/javascript');
        script.setAttribute( 'src', modernizrPath );
        head.appendChild( script );

        script.onload = function () {
            if ( isLoaded ) {
                return false;
            }
            isLoaded = true;
            getOtherScripts();
        };

        script.onreadystatechange = function () { // ie < 9
            var self = this;
            if ( script.readyState === 'loaded' || script.readyState === 'complete' && !isLoaded ) {
                setTimeout( function () {
                    self.onload();
                }, 30 );
            }
        };
    }

    function getOtherScripts() {
        var scripts;

        window.DEBUGGING = false;

        if ( window.isMsIe < 9 ) { // include jquery under version 2
            jQueryLocal = jQueryLocalIe;
            cdnJquery = cdnJqueryIe;
        }

        if ( window.location.hostname.search( '.dev' ) > -1 ) { // dev
            window.DEBUGGING = true;
            scripts = jQueryLocal.concat( 'js/jquery-migrate-' + libVersion.jQueryMigrate + '.min.js', otherScriptsLocal );
            Modernizr.load( scripts );

        } else { // production
            Modernizr.load( [
                {
                    load: cdnJquery,
                    complete: function () {
                        scripts = ( !window.jQuery) ? jQueryLocal.concat( otherScriptsLocal ) : otherScripts;
                        Modernizr.load( scripts );
                    }
                }
            ] );
        }

    }

}());
