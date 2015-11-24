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
