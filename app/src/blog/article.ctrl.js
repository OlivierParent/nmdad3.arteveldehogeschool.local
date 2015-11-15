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
        '$location',
        '$log',
        // Custom
        'UserArticleResourceFactory'
    ];

    function ArticleCtrl(
        // Angular
        $location,
        $log,
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
        }

        vm.article = {};

        vm.post = postUserArticles;

        // Functions
        // =========
        function postUserArticles() {

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
                    postUserArticlesSuccess,
                    postUserArticlesError
                );

        }

        function postUserArticlesError(reason) {
            $log.error('postUserArticlesError:', reason);
        }

        function postUserArticlesSuccess(response) {
            $log.log('postUserArticlesSuccess:', response);
            $location.path('/blog');
        }

    }

})();
