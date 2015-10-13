/**
 * @author    Olivier Parent
 * @copyright Copyright © 2015-2016 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    // Module declarations
    var app = angular.module('app', [
        'ionic',
        'ngCordova',
        // Modules
        'app.common',
        'app.camera',
        'app.database',
        'app.services'
    ]);
    angular.module('app.common', []);
    angular.module('app.camera', []);
    angular.module('app.database', []);
    angular.module('app.services', []);

    app.run(function($ionicPlatform) {
        $ionicPlatform.ready(whenReady);

        function whenReady() {
            console.log('read');
            // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
            // for form inputs).
            // The reason we default this to hidden is that native apps don't usually show an accessory bar, at
            // least on iOS. It's a dead giveaway that an app is using a Web View. However, it's sometimes
            // useful especially with forms, though we would prefer giving the user a little more room
            // to interact with the app.
            if (window.cordova && window.cordova.plugins.Keyboard) {
                cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
                cordova.plugins.Keyboard.disableScroll(true);
            }

            if (window.StatusBar) {
                // Set the statusbar to use the default style, tweak this to
                // remove the status bar on iOS or change it to use white instead of dark colors.
                StatusBar.styleDefault();
            }
        }

    });

})();

/**
 * @author    Olivier Parent
 * @copyright Copyright © 2015-2016 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app')
        .config(Config);

    // Inject dependencies into constructor (needed when JS minification is applied).
    Config.$inject = [
        // Angular
        '$compileProvider',
        '$httpProvider'
    ];

    function Config(
        // Angular
        $compileProvider,
        $httpProvider
    ) {
        // Allow 'app:' as protocol (for use in Hybrid Mobile apps)
        $compileProvider
            .aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|app):/)
            .imgSrcSanitizationWhitelist(/^\s*((https?|ftp|file|app):|data:image\/)/)
        ;

        // Enable CORS (Cross-Origin Resource Sharing)
        $httpProvider.defaults.useXDomain = true;
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
    }

})();

/**
 * @author    Olivier Parent
 * @copyright Copyright © 2015-2016 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app')
        .config(Routes);

    // Inject dependencies into constructor (needed when JS minification is applied).
    Routes.$inject = [
        // Angular
        '$stateProvider',
        '$urlRouterProvider'
    ];

    function Routes(
        // Angular
        $stateProvider,
        $urlRouterProvider
    ) {
        $stateProvider
            .state('home', {
                url: '/',
                templateUrl: 'templates/common/home.view.html'
            })
            .state('camera', {
                url: '/camera',
                templateUrl: 'templates/camera/camera.view.html'
            })
            .state('database', {
                url: '/database',
                templateUrl: 'templates/database/database.view.html'
            });
        $urlRouterProvider.otherwise('/');
    }

})();

/**
 * @author    Olivier Parent
 * @copyright Copyright © 2015-2016 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app.camera')
        .controller('CameraCtrl', CameraCtrl);

    // Inject dependencies into constructor (needed when JS minification is applied).
    CameraCtrl.$inject = [
        // Angular
        '$log',
        // Ionic
        '$ionicPlatform',
        // ngCordova
        '$cordovaCamera'
    ];

    function CameraCtrl(
        // Angular
        $log,
        // Ionic
        $ionicPlatform,
        // ngCordova
        $cordovaCamera
    ) {
        // ViewModel
        // =========
        var vm = this;

        vm.getPhoto = getPhoto;
        vm.lastPhoto = 'no photo';
        vm.title = 'Camera Demo';

        // Functions
        // =========
        function getPhoto() {
            var cameraOptions = {
                quality: 75,
                targetWidth: 320,
                targetHeight: 320,
                saveToPhotoAlbum: false
            };

            $cordovaCamera
                .getPicture(cameraOptions)
                .then(getPhotoSuccess, getPhotoError);
        }
        
        function getPhotoSuccess(imageUri) {
            $log.log(imageUri);
            vm.lastPhoto = imageUri;
        }

        function getPhotoError(error) {
            $log.error(error);
            vm.lastPhoto = 'error';
        }
    }

})();

/**
 * @author    Olivier Parent
 * @copyright Copyright © 2015-2016 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app.common')
        .controller('HomeCtrl', HomeCtrl);

    // Inject dependencies into constructor (needed when JS minification is applied).
    HomeCtrl.$inject = [
        // Angular
        '$log'
    ];

    function HomeCtrl(
        // Angular
        $log
    ) {
        // ViewModel
        // =========
        var vm = this;

        vm.title = 'Home';
    }

})();

/**
 * @author    Olivier Parent
 * @copyright Copyright © 2015-2016 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app.database')
        .controller('DatabaseCtrl', DatabaseCtrl);

    // Inject dependencies into constructor (needed when JS minification is applied).
    DatabaseCtrl.$inject = [
        // Angular
        '$log',
        // ngCordova
        '$cordovaSQLite'
    ];

    function DatabaseCtrl(
        // Angular
        $log,
        // ngCordova
        $cordovaSQLite
    ) {
        // Database
        var db = $cordovaSQLite.openDB({ name: "nmdad3.db" });
        
        // ViewModel
        // =========
        var vm = this;

        vm.title = 'Database Demo';
        
        testDatabase();
        
        // Functions
        // =========
        function testDatabase() {
            var query = "INSERT INTO test_table (data, data_num) VALUES (?,?)";
            var params = ["test", 100];
            
            $cordovaSQLite.execute(db, query, params)
                .then(databaseSuccess, databaseError);
        }
        
        function databaseSuccess(res) {
            $log.info("insertId: " + res.insertId);
        }
        
        function databaseError(err) {
            $log.error(err);
        }
    }

})();
