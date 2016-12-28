{{--
  File:        master.blade.php
  Author:      Ivo Hradek <ivohradek@gmail.com>
  Description: Master layout for backend UI.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>@yield('title')</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  @include('backend.head')
</head>
<body>
  <div class="page-header navbar">
    @include('backend.header')
  </div>
  <div class="page-container">
    <div class="page-sidebar">
      @include('backend.sidebar')
    </div>
    <div class="page-content-wrapper">
      <div class="page-content">
        @yield('content')
      </div>
    </div>
  </div>
</body>
</html>
