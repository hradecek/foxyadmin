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
@include('backend.header')
<div class="page-container">
  @include('backend.sidebar')

  <div class="page-content-wrapper">
    <div class="page-content">
      <h1 class="page-content-title">
        Sample Text <small>sample text</small>
      </h1>
      <div class="row">
        <div class="col-md-12">
          <div class="row">Sample</div>
          <div class="row">Row</div>
          <div class="row">Content</div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
