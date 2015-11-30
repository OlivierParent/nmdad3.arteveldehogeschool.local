/**
 * @author    Olivier Parent
 * @copyright Copyright © 2014-2015 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app.blog')
        .factory('UserImageResourceFactory', UserImageResourceFactory);

    // Inject dependencies into constructor (needed when JS minification is applied).
    UserImageResourceFactory.$inject = [
        // Angular
        '$resource',
        // Custom
        'UriFactory'
    ];

    function UserImageResourceFactory(
        // Angular
        $resource,
        // Custom
        UriFactory
    ) {
        var url = UriFactory.getApi('users/:user_id/images/:image_id.:format');

        var paramDefaults = {
            user_id : '@id',
            image_id: '@id',
            format  : 'json'
        };

        var actions = {
            'file': {
                method: 'POST'
            }
        };

        return $resource(url, paramDefaults, actions);
    }

})();
