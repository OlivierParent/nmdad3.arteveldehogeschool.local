/**
 * @author    Olivier Parent
 * @copyright Copyright © 2015-2016 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app')
        .constant('config', {
            api: {
                protocol: 'http',
                host    : 'www.nmdad3.arteveldehogeschool.local',
                path    : '/app_dev.php/api/v1/'
            }
        });
})();
