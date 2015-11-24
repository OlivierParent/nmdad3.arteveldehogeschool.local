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
