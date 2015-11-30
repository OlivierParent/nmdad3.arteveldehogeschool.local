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
        'UserArticleResourceFactory',
        'UserImageResourceFactory'
    ];

    function BlogCtrl(
        // Angular
        $log,
        $state,
        // Custom
        UserArticleResourceFactory,
        UserImageResourceFactory
    ) {
        // ViewModel
        // =========
        var vm = this;

        vm.title = 'Blog Demo';
        vm.articles = getArticles();
        vm.images = getImages();

        vm.delete = deleteArticle;

        // Functions
        // =========

        // Articles
        // --------
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

        function getArticlesError(error) {
            $log.error('getArticlesError:', error);
        }

        function getArticlesSuccess(resource, responseHeader) {
            $log.log('getArticlesSuccess:', resource, responseHeader());
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

        function deleteArticleError(error) {
            $log.error('deleteArticleError:', error);
        }

        function deleteArticleSuccess(resource, responseHeader) {
            $log.log('deleteArticleSuccess:', resource, responseHeader());
        }

        // Images
        // ------
        function getImages() {
            var params = {
                user_id: 2
            };
            return UserImageResourceFactory
                .query(
                    params,
                    getImagesSuccess,
                    getImagesError
                );
        }

        function getImagesError(error) {
            $log.error('getImagesError:', error);
        }

        function getImagesSuccess(resource, responseHeader) {
            $log.log('getImagesSuccess:', resource, responseHeader());
        }

    }

})();
