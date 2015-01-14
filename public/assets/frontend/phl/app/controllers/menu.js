"use strict";

/**
 * Controller "TopMenuController"
 *
 * @dependencies $scope global variables
 * @dependencies $location window location
 * @dependencies $translate angular-translate.min.js
 * @dependencies $translatePartialLoader angular-translate-loader-partial.min.js
 *
 */
phl.controller('TopMenuController', ['$scope', '$location', '$translatePartialLoader', function($scope, $location, $translatePartialLoader) {

    // add language support to this controller

    $translatePartialLoader.addPart('menu');

    $scope.isActive = function (route) {

        return route === $location.url();
    }
}]);
