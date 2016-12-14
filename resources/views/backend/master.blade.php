{{--
  File:        master.blade.php
  Author:      Ivo Hradek, <ivohradek@gmail.com>
  Description: Master layout for backend.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
  @include('backend.components.head')
</head>
<body>
{{-- @include('backend.components.header') --}}
<div class="clearfix"></div>
<div class="page-container">
  {{-- @include('backend.components.sidebar') --}}
  <div>
    <div>
      <h3>
        Blank Page Layout
        <small>blank page layout</small>
      </h3>
    </div>
  </div>
</div>
<div class="page-footer">
  {{-- @include('backend.components.footer') --}}
</div>
@include('backend.components.scripts')
</body>
</html>

