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
            $log.info('newArticle');
            vm.post = postArticle;
        }

        function postArticle() {
            $log.info('postArticle:', vm.article);

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

        function postArticleError(error) {
            $log.error('postArticleError:', error);
        }

        function postArticleSuccess(resource, responseHeader) {
            $log.log('postArticleSuccess:', resource, responseHeader);
            $state.go('blog');
        }

        // Edit Article
        // ------------

        function editArticle() {
            console.info('editArticle:', $state.params.article_id);

            vm.article = getArticle();

            vm.put = putArticle;
        }

        function getArticle() {
            $log.info('getArticle');
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
            $log.info('putArticle:', vm.article);

            var params = {
                    user_id: 2,
                    article_id: $state.params.article_id,
                    format: 'json'
                },
                putData = {
                    article: {
                        id: $state.params.article_id,
                        title: vm.article.title,
                        body: vm.article.body,
                    }
                };

            UserArticleResourceFactory
                .update(
                    params,
                    putData,
                    putArticleSuccess,
                    putArticleError
                );
        }

        function putArticleError(error) {
            $log.error('putArticleError:', error);
        }

        function putArticleSuccess(resource, responseHeader) {
            $log.log('putArticleSuccess:', resource, responseHeader());
            $state.go('blog');
        }

    }

})();
