<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{{ $title or 'FluentKit Theme test' }}}</title>
    <?php Asset::activate('bootstrap');?>
    <?php Event::fire('head');?>
  </head>
  <body>
      
    @include('header')
      
    @yield('content')
      
    @include('footer')

  </body>
</html>