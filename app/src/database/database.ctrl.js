/**
 * @author    Olivier Parent
 * @copyright Copyright © 2015-2016 Artevelde University College Ghent
 * @license   Apache License, Version 2.0
 */
;(function () {
    'use strict';

    angular.module('app.database')
        .controller('DatabaseCtrl', DatabaseCtrl);

    // Inject dependencies into constructor (needed when JS minification is applied).
    DatabaseCtrl.$inject = [
        // Angular
        // '$log',
        // ngCordova
        // '$cordovaSQLite'
    ];

    function DatabaseCtrl(
        // Angular
        // $log,
        // ngCordova
        // $cordovaSQLite
    ) {
        // // Database
        // var db = $cordovaSQLite.openDB({ name: "nmdad3.db" });
        
        // // ViewModel
        // // =========
        // var vm = this;

        // vm.title = 'Database Demo';
        
        // // Functions
        // // =========
        // function testDatabase() {
        //     var query = "INSERT INTO test_table (data, data_num) VALUES (?,?)";
        //     var params = ["test", 100];
            
        //     $cordovaSQLite.execute(db, query, params)
        //         .then(databaseSuccess, databaseError);
        // }
        
        // function databaseSuccess(res) {
        //     // $log.info("insertId: " + res.insertId);
        // }
        
        // function databaseError(err) {
        // //    $log.error(err);
        // }
    }

})();
