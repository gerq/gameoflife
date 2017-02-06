(function (angular) {
  'use strict';
  angular.module('formFlickr', [])
    .controller('FlickrController', ['$scope', '$http', function ($scope, $http) {

      $scope.master = {};
      $scope.images = {};

      $scope.search = function (searchCriteria) {

        $scope.form.tags.$setUntouched();
        $scope.form.tags.$setValidity();

        if('' != document.getElementById('searchCriteria').value) {

          var loader  = document.getElementById('loader');
          loader.className = '';

          if("" != document.getElementById('pretags').value) {
            $scope.searchCriteria = {'tags': document.getElementById('searchCriteria').value};
          }

          var flickrAPI = "http://flickrapi.dev/flickr-search/q/" + encodeURIComponent($scope.searchCriteria.tags);
          $http.get(flickrAPI)
          .then(function(response){
            //console.log(response.data.result.photo);
            $scope.images = response.data.result.photo;
            loader.className = 'hidden';
          });
        }

        // reset form validation
        $scope.form.tags.$setValidity();
      };

      // reset form to initial state
      $scope.resetForm = function (form) {
        $scope.form.tags.$setValidity();
        $scope.images = {};
        $scope.searchCriteria = {};
      };

    }])
    .controller('PreTags', ['$scope', '$http', function ($scope, $http) {

      var keywords = "http://flickrapi.dev/keyword/";
      $http.get(keywords)
      .then(function(response){
        //console.log(response.data.result);
        $scope.pretags = response.data.result;
      });
/*
      $scope.pretags = [{
        value: 'gerq',
        label: 'gerq'
      }, {
        value: 'bmw',
        label: 'bmw'
      }];
*/
      //$scope.selected = $scope.pretags[0];

      $scope.hasChanged = function(selected) {
        //console.log(selected);
        $scope.searchCriteria.tags = selected.id;
        document.getElementById('searchCriteria').value = selected.id;
        document.getElementById('searchCriteria').click();
      }

    }])
    ;
})(window.angular);
