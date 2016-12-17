{{--
  File:        master.blade.php
  Author:      Ivo Hradek <ivohradek@gmail.com>
  Description: Master layout for backend UI.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
  @include('backend.head')
</head>
<body>
<div class="page-container">
  @include('backend.sidebar')

  <div class="page-content">
    <h1 class="page-content-title">
      Sample Text <small>sample text</small>
    </h1>
  </div>
</div>
</body>
</html>
