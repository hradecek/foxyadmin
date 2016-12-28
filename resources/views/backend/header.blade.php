<div class="page-header-logo">
  <a href="#">
    <img src="{{ asset('img/logo-white.png') }}" alt="logo" class="page-logo-default"/>
  </a>
</div>
<div class="sidebar-toggler">
    <span class="sidebar-toggle-handler">
      <span>Menu</span>
    </span>
</div>
<div class="page-header-top-menu">
  <ul class="nav navbar-nav pull-right">
    <li class="dropdown dropdown-user">
      <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
         data-close-others="true">
        <img class="img-circle" src="{{ asset('img/avatar-default.svg', 'user') }}" alt="{{ trans('users::user.alternative') }}"/>
        <span class="username">Admin</span>
        <i class="fa fa-angle-down"></i>
      </a>
    </li>
  </ul>
</div>
