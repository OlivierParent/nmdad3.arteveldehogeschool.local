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
        'ngResource',
        // Modules
        'app.blog',
        'app.common',
        'app.camera',
        'app.database',
        'app.services'
    ]);
    angular.module('app.blog'    , []);
    angular.module('app.common'  , []);
    angular.module('app.camera'  , []);
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
        '$httpProvider',
        '$urlRouterProvider'
    ];

    function Config(
        // Angular
        $compileProvider,
        $httpProvider,
        $urlRouterProvider
    ) {
        // Allow 'app:' as protocol (for use in Hybrid Mobile apps)
        $compileProvider
            .aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|app):/)
            .imgSrcSanitizationWhitelist(/^\s*((https?|ftp|file|app):|data:image\/)/)
        ;

        // Enable CORS (Cross-Origin Resource Sharing)
        $httpProvider.defaults.useXDomain = true;
        delete $httpProvider.defaults.headers.common['X-Requested-With'];

        // Routes
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

    angular.module('app.blog')
        .controller('ArticleCtrl', ArticleCtrl);

    // Inject dependencies into constructor (needed when JS minification is applied).
    ArticleCtrl.$inject = [
        // Angular
        '$log',
        '$state',
        // Custom
        'UserArticleResourceFactory'
    ];

    function ArticleCtrl(
        // Angular
        $log,
        $state,
        // Custom
        UserArticleResourceFactory // ResourceFactory
    ) {

        // ViewModel
        // =========
        var vm = this;

        vm.title = 'New Article';
        vm.form = {
            article: {
                title: 'Title',
                body: 'Body text'
            }
        };
        vm.data = {
            article: {}
        };
        vm.article = {};

        console.log($state.current.name);
        switch ($state.current.name) {
            case 'blog_article_edit':
                editArticle();
                break;
            case 'blog_article_new':
                newArticle();
                break;
            default:
                break;
        }

        // Functions
        // =========

        // New Article
        // -----------

        function newArticle() {
            console.info("newArticle");
            vm.post = postArticle;
        }

        function postArticle() {

            $log.info(vm.article);

            var params = {
                    user_id: 2,
                    format: null
                },
                postData = {
                    article: vm.article
                };

            UserArticleResourceFactory
                .save(
                    params,
                    postData,
                    postArticleSuccess,
                    postArticleError
                );

        }

        function postArticleError(reason) {
            $log.error('postArticleError:', reason);
        }

        function postArticleSuccess(response) {
            $log.log('postArticleSuccess:', response);
            $state.go('blog');
        }

        // Edit Article
        // ------------

        function editArticle() {
            console.info("editArticle", $state.params.article_id);

            vm.article = getArticle();

            vm.put = putArticle;
        }

        function getArticle() {
            var params = {
                user_id: 2,
                article_id: $state.params.article_id
            };
            return UserArticleResourceFactory
                .get(
                    params,
                    getArticleSuccess,
                    getArticleError
                );
        }

        function getArticleError(reason) {
            $log.error('getArticleError:', reason);
        }

        function getArticleSuccess(response) {
            $log.log('getArticleSuccess:', response);
        }

        function putArticle() {

            $log.info(vm.article);

            var params = {
                    user_id: 2,
                    article_id: $state.params.article_id,
                    format: 'json'
                },
                putData = {
                    article: vm.article
                };

            UserArticleResourceFactory
                .update(
                    params,
                    putData,
                    putArticleSuccess,
                    putArticleError
                );
        }

        function putArticleError(reason) {
            $log.error('putArticleError:', reason);
        }

        function putArticleSuccess(response) {
            $log.log('putArticleSuccess:', response);
            $state.go('blog');
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

    angular.module('app.blog')
        .config(Routes);

    // Inject dependencies into constructor (needed when JS minification is applied).
    Routes.$inject = [
        // Angular
        '$stateProvider'
    ];

    function Routes(
        // Angular
        $stateProvider
    ) {
        $stateProvider
            .state('blog', {
                controller: 'BlogCtrl as vm',
                templateUrl: 'templates/blog/blog.view.html',
                url: '/blog'
            })
            .state('blog_article_edit', {
                controller: 'ArticleCtrl as vm',
                templateUrl: 'templates/blog/article-edit.view.html',
                url: '/blog/article/:article_id/edit'
            })
            .state('blog_article_new', {
                controller: 'ArticleCtrl as vm',
                templateUrl: 'templates/blog/article-new.view.html',
                url: '/blog/article/new'
            });
    }

})();
/**
 * @author    Olivier Parent
 * @copyright Copyright © 2015-2016 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app.blog')
        .controller('BlogCtrl', BlogCtrl);

    // Inject dependencies into constructor (needed when JS minification is applied).
    BlogCtrl.$inject = [
        // Angular
        '$log',
        '$state',
        // Custom
        'UserArticleResourceFactory'
    ];

    function BlogCtrl(
        // Angular
        $log,
        $state,
        // Custom
        UserArticleResourceFactory // ResourceFactory
    ) {
        // ViewModel
        // =========
        var vm = this;

        vm.title = 'Blog Demo';
        vm.articles = getArticles();

        vm.delete = deleteArticle;

        // Functions
        // =========
        function getArticles() {
            var params = {
                user_id: 2
            };
            return UserArticleResourceFactory
                .query(
                    params,
                    getArticlesSuccess,
                    getArticlesError
                );
        }

        function getArticlesError(reason) {
            $log.error('getArticlesError:', reason);
        }

        function getArticlesSuccess(response) {
            $log.log('getArticlesSuccess:', response);
        }

        function deleteArticle(article) {
            $log.info("deleteArticle", article);

            var params = {
                user_id: 2,
                article_id: article.id
            };

            return UserArticleResourceFactory
                .delete(
                    params,
                    deleteArticleSuccess,
                    deleteArticleError
                );
        }

        function deleteArticleError(reason) {
            $log.error('deleteArticleError:', reason);
        }

        function deleteArticleSuccess(response) {
            $log.log('deleteArticleSuccess:', response);
        }

    }

})();

/**
 * @author    Olivier Parent
 * @copyright Copyright © 2014-2015 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app.blog')
        .factory('UserArticleResourceFactory', UserArticleResourceFactory);

    // Inject dependencies into constructor (needed when JS minification is applied).
    UserArticleResourceFactory.$inject = [
        // Angular
        '$resource'
    ];

    function UserArticleResourceFactory(
        // Angular
        $resource
    ) {
        var url = 'http://www.nmdad3.arteveldehogeschool.local/api/v1/users/:user_id/articles/:article_id.:format';

        var paramDefaults = {
            user_id   : '@id',
            article_id: '@id',
            format    : 'json'
        };

        var actions = {
            //'query' : {
            //    method : 'GET',
            //    isArray: false
            //},
            'update': {
                method:'PUT'
            }
        };

        return $resource(url, paramDefaults, actions);
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
        .config(Routes);

    // Inject dependencies into constructor (needed when JS minification is applied).
    Routes.$inject = [
        // Angular
        '$stateProvider'
    ];

    function Routes(
        // Angular
        $stateProvider
    ) {
        $stateProvider
            .state('camera', {
                controller: 'CameraCtrl as vm',
                templateUrl: 'templates/camera/camera.view.html',
                url: '/camera'
            });
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
        .config(Routes);

    // Inject dependencies into constructor (needed when JS minification is applied).
    Routes.$inject = [
        // Angular
        '$stateProvider'
    ];

    function Routes(
        // Angular
        $stateProvider
    ) {
        $stateProvider
            .state('home', {
                controller: 'HomeCtrl as vm',
                templateUrl: 'templates/common/home.view.html',
                url: '/'
            });
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

        vm.links = [
            {
                'href' : '/blog',
                'label': 'Blog Demo'
            },
            {
                'href' : '/camera',
                'label': 'Camera Demo'
            },
            {
                'href' : '/database',
                'label': 'Database Demo'
            }
        ];
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
        .config(Routes);

    // Inject dependencies into constructor (needed when JS minification is applied).
    Routes.$inject = [
        // Angular
        '$stateProvider'
    ];

    function Routes(
        // Angular
        $stateProvider
    ) {
        $stateProvider
            .state('database', {
                    controller: 'DatabaseCtrl as vm',
                    templateUrl: 'templates/database/database.view.html',
                    url: '/database'
                });
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
        // https://blog.nraboy.com/2014/11/use-sqlite-instead-local-storage-ionic-framework/
        
        
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
