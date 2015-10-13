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
    }

})();
