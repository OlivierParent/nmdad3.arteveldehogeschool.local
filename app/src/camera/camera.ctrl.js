/**
 * @author    Olivier Parent
 * @copyright Copyright © 2015-2016 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app.camera')
        .controller('CameraCtrl', CameraCtrl);

    // Inject dependencies into constructor (needed when JS minification is applied).
    CameraCtrl.$inject = [
        // Angular
        '$log',
        // Ionic
        '$ionicPlatform',
        // ngCordova
        '$cordovaCamera'
    ];

    function CameraCtrl(
        // Angular
        $log,
        // Ionic
        $ionicPlatform,
        // ngCordova
        $cordovaCamera
    ) {
        // ViewModel
        // =========
        var vm = this;

        vm.getPhoto = getPhoto;
        vm.lastPhoto = 'no photo';
        vm.title = 'Camera Demo';

        // Functions
        // =========
        function getPhoto() {
            var cameraOptions = {
                quality: 75,
                targetWidth: 320,
                targetHeight: 320,
                saveToPhotoAlbum: false
            };

            $cordovaCamera
                .getPicture(cameraOptions)
                .then(getPhotoSuccess, getPhotoError);
        }
        
        function getPhotoSuccess(imageUri) {
            $log.log(imageUri);
            vm.lastPhoto = imageUri;
        }

        function getPhotoError(error) {
            $log.error(error);
            vm.lastPhoto = 'error';
        }
    }

})();
