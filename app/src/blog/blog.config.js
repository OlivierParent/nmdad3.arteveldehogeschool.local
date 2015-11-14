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
            });
    }

})();