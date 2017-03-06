/**
 * Created by hhamra on 3/6/2017.
 */


angular
    .module('boilerplate')
    .service('BiddingService',function($http, $q){
        var deferred=$q.defer();

        var bidding_data;
        $http.get('api/v1/products/Uindex').then(function(data)
        {
            deferred.resolve(data);
        });

        this.getBiddings=function(){
            console.log ('you called getBiddings()');
            return deferred.promise;
        }

    })