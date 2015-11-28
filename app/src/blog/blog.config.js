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
                cache: false, // false will reload on every visit.
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