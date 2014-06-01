<!DOCTYPE html>
<html lang="en" ng-app="FluentKitInstall">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{{ $title or 'FluentKit' }}}</title>
        <link href="content/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
        <!--[if lt IE 9]>
          <script src="content/vendor/html5shiv/html5shiv.min.js"></script>
          <script src="content/vendor/respond/respond.min.js"></script>
        <![endif]-->
        <script src="content/vendor/angular/angular.min.js"></script>
        <script>
            var FluentKitInstall = angular.module('FluentKitInstall', []).config(function($interpolateProvider){
                    $interpolateProvider.startSymbol('[[').endSymbol(']]');
                }
            ).constant("CSRF_TOKEN", '{{ csrf_token() }}');;
        </script>
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>
        <script src="content/vendor/jquery/jquery.min.js"></script>
        <script src="content/vendor/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>