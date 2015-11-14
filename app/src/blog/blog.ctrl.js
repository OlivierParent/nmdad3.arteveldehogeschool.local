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
        // Custom
        'UserArticleResourceFactory'
    ];

    function BlogCtrl(
        // Angular
        $log,
        // Custom
        UserArticleResourceFactory // ResourceFactory
    ) {
        // ViewModel
        // =========
        var vm = this;

        vm.title = 'Blog Demo';

        vm.articles = getUserArticles();

        // Functions
        // =========
        function getUserArticles() {

            var params = {
                user_id: 2
            };

            return UserArticleResourceFactory
                .query(
                    params,
                    getUserArticlesSuccess,
                    getUserArticlesError
                );
        }

        function getUserArticlesError(reason) {
            $log.error('getUserArticlesError:', reason);
        }

        function getUserArticlesSuccess(response) {
            $log.log('getUserArticlesSuccess:', response);
        }


    }

})();
