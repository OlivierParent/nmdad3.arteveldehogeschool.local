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
        '$log'
    ];

    function BlogCtrl(
        // Angular
        $log
    ) {
        // ViewModel
        // =========
        var vm = this;

        vm.title = 'Blog Demo'

        // Functions
        // =========

    }

})();
