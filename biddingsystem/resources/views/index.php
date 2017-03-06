<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" ng-csp="" ng-app="boilerplate" > <!--<![endif]-->

<head>

    <base href="/">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Bidding System</title>
    <meta name="description" content="Simple AngularJS Boilerplate to kick start your new project with SASS support and Gulp watch/build tasks">

    <!-- mobile meta -->
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

    <!--[if IE]>
    <link rel="shortcut icon" href="favicon.ico">
    <![endif]-->
    <!-- or, set /favicon.ico for IE10 win -->
    <meta name="msapplication-TileColor" content="#f01d4f">

    <!-- CSS -->
    <!-- build:css css/style.min.css -->
    <!-- <link rel="stylesheet" type="text/css" href="styles/style.css" /> -->
    <!-- endbuild -->

    <!--Bootstarp components  .......................... -->
<!--    <link rel="stylesheet" type="text/css" href="styles/bootstrap/bootstrap.min.css" />-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- endbuild -->

</head>

<body class="main-wrapper">
<!-- Navigation  -->
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#/home">Biddings SYS</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li ><a href="#/all-bids">Biddings</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#/all-bids">My Biddings<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#/my-opened-bids">Opened Biddings</a></li>
                        <li><a href="#/my-closed-bids">Closed Biddings</a></li>
                    </ul>
                </li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#/login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                <li><a href="#/signup"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>

            </ul>
        </div>
    </div>
</nav>

<!-- Main view for templates -->
<div data-ng-view="" class="container"></div>
<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid ">
            <ul class="nav navbar-nav ">

                <li  class="active text-center"><a href="#/all-bids">All Bids</a></li>
                <li class="text-center" ><a href="#/my-opened-bids">My Opened Bids</a></li>
                <li class="text-center" ><a  href="#/my-closed-bids">My Closed Bids</a></li>
            </ul>
        </div>
    </nav>

    <h2>All Bids  &#8352;</h2>
    <p>Table below view all biddings "Opened and closed biddings".</p>

    <div  ng-controller="user">

        <div class="row">
            <input class="form-control" type="text" value="" ng-model="search" placeholder="Search..."/>
        </div>
        </br>

        <div class="row">
            <div>
                <table   class=" table table-responsive" >

                    <thead class="thead-inverse">
                    <tr class="text-center" >

                        <th class="text-center">Item</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Remaining</th>
                        <th class="text-center">Participant</th>
                        <th class="text-center">Started</th>
                        <th class="text-center">Current</th>
                        <th class="text-center">Increment</th>
                    </tr>
                    </thead>

                    <tbody id="myTable">
                    <tr class="text-center"  ng-repeat="data in myData | filter : search   " ng-if="data.status != 'not started' " >

                        <td>{{data.Item}}</td>
                        <td>{{data.Description}}</td>
                        <td>{{test(data.Start_date,data.Period,$index)}}</td>
                        <td >{{data.Participant}}</td>
                        <td>{{data.Started}}</td>
                        <td class="text-center">
                            <button  ng-if="data.status == 'opened' " class="btn btn-success btn-xs" ng-click="inc($index)">&#10010;</button>
                            <button  ng-if="data.status == 'closed' " class="btn btn-success btn-xs" ng-click="inc($index)" disabled>&#10010;</button>
                            {{data.Current}}
                            <button ng-if="data.status == 'opened' " class="btn btn-danger btn-xs" ng-click="dec($index)">&#10134;</button>
                            <button ng-if="data.status == 'closed' " class="btn btn-danger btn-xs" ng-click="dec($index)" disabled>&#10134;</button>
                        </td>
                        <td>{{data.Increment}}</td>
                        <td><button ng-if="data.status == 'opened' " class="btn btn-info" ng-click="bid($index)">&#9998;Bid</button>
                            <button ng-if="data.status == 'closed' " class="btn btn-info" ng-click="bid($index)" disabled>&#9998;Bid</button>
                        </td>
                    </tr>

                    </tbody>

                </table>
            </div>

        </div>

        <!--  <script src="app/countdown.js"></script> -->

    </div>
</div>

</body>
<!--AngularJS-->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.12/angular.min.js"></script>
<script src="public/app/app.js"></script>

<!-- endbuild -->
<script type="text/javascript" src="public/app/services/BiddingsServices.js"></script>

<!-- Angular components -->
<!-- build:appcomponents js/appcomponents.js -->
<script type="text/javascript" src="app/app.js"></script>


<!-- Application sections -->
<!-- build:mainapp js/mainapp.js -->
<script type="text/javascript" src="public/app/factories/factory.js"></script>
<script type="text/javascript" src="public/app/controllers/userCtrl.js"></script>
<!-- <script type="text/javascript" src="app/components/controllers/testCtrl.js"></script>
-->

<!-- <script src="app/countdown.js"></script> -->
<!-- endbuild -->

<!-- templates from $templateCache -->
<!-- build:templates -->
<!-- endbuild -->

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='https://www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
    ga('create','UA-XXXXX-X','auto');ga('send','pageview');
</script>

</html>
