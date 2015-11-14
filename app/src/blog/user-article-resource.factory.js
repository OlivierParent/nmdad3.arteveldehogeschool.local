/**
 * @author    Olivier Parent
 * @copyright Copyright © 2014-2015 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () { 'use strict';

    angular.module('app.blog')
        .factory('UserArticleResourceFactory', UserArticleResourceFactory);

    // Inject dependencies into constructor (needed when JS minification is applied).
    UserArticleResourceFactory.$inject = [
        // Angular
        '$resource'
    ];

    function UserArticleResourceFactory(
        // Angular
        $resource
    ) {
        var url = 'http://www.nmdad3.arteveldehogeschool.local/api/v1/users/:user_id/articles/:article_id.:format';

        var paramDefaults = {
            user_id   : '@id',
            article_id: '@id',
            format    : 'json'
        };

        var actions = {
            //'update': {
            //    interceptor: {
            //        response: GoalResponseInterceptorFactory
            //    },
            //    isArray: false,
            //    method: 'PUT',
            //    transformResponse: GenericResponseTransformerFactory
            //}
        };

        return $resource(url, paramDefaults, actions);
    }

})();
