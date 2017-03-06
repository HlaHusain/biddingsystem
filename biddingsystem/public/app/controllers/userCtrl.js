/**
 * Created by hhamra on 3/6/2017.
 */
angular
    .module('boilerplate').controller("user",['$scope','BiddingService',function($scope,BiddingService)
{
    var promise=BiddingService.getBiddings();
    promise.then(function(data)
    {
        $scope.myData=data;
        console.log($scope.myData);
    });
    // $scope.myData=BiddingService.getBiddings();

    $scope.test=function(start,period,index){

        var start_date = new Date(start);

        //console.log("before adding hours"+start);
        start_date=Date.parse(start_date);
        period=period*60*60*1000;
        var end_date=start_date+period;
        //console.log(end_date);
        //console.log(start_date);
        // Set the date we're counting down to
        var countDownDate = new Date(end_date).getTime();
        // Update the count down every 1 second
        var x=$interval(function() {

            // Get todays date and time
            var now = new Date().getTime();

            // Find the distance between now an the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 *60 *60 * 24));
            var hours = Math.floor((distance % (1000 *60 *60 *24)) / (1000 *60 * 60));
            var minutes = Math.floor((distance % (1000  *60  *60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // var remaining = document.getElementById('remaining'+index);
            // console.log("********** ",'remaining'+days + "d " + hours + "h "+ minutes + "m " + seconds + "s ")
            // return 'remaining'+days + "d " + hours + "h "+ minutes + "m " + seconds + "s "
            // Output the result in an element with id="demo"
            // remaining.innerHTML  = days + "d " + hours + "h "+ minutes + "m " + seconds + "s ";
            // If the count down is over, write some text
            if (distance < 0) {
                clearInterval(x.then(function(data){console.log("|||||",data)}));
                remaining.innerHTML.innerHTML  == "EXPIRED";
            }
        }, 1000);
        console.log("x",x)

    }
// ..................................................................................
    $scope.inc = function(index) {
        var current=JSON.parse($scope.myData[index]["Current"]);
        var incrementUnit=JSON.parse($scope.myData[index]["Increment"]);
        var newCurrent=current+incrementUnit;
        $scope.myData[index]["Current"]=newCurrent;
        //window.alert(JSON.stringify($scope.myData[index]["increment"]));
    }

    $scope.dec = function(index) {
        var started=JSON.parse($scope.myData[index]["Started"]);
        var current=JSON.parse($scope.myData[index]["Current"]);

        var decrementUnit=JSON.parse($scope.myData[index]["Increment"]);
        var newCurrent=current-decrementUnit;
        if(newCurrent<started )
            window.alert("Foreclosed bid at a lower price than the initial price ");
        else
            $scope.myData[index]["Current"]=newCurrent;
    }

    $scope.bid = function(index) {
        confirm("you bid on "+$scope.myData[index]["Item"]+" with price "+$scope.myData[index]["Current"]+"$");

    }
    // ..................................................................................
}])