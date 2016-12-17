{{--
  File:        head.blade.php
  Author:      Ivo Hradek <ivohradek@gmail.com>
  Description: Contains global <head/> content.
--}}
<meta charset="utf-8" />
<title>__TITLE__</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<!-- Global styles -->
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all"/>
<link rel="stylesheet" type="text/css" href="{{ asset('global/plugins/font-awesome/css/font-awesome.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('global/plugins/bootstrap/css/bootstrap.min.css') }}"/>
<!-- Theme styles -->
<link rel="stylesheet" type="text/css" href="{{ asset('global/themes/css/components.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('global/themes/css/blue.css') }}"
<!-- Layout styles -->
<link rel="stylesheet" type="text/css" href="{{ asset('layout/css/layout.css') }}"/>