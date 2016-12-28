<ul class="sidebar-menu">
  <li>
    <a href="javascript:;">
      <i class="fa fa-user"></i>
      <span class="title">{{ trans('users::user.users') }}</span>
    </a>
    <ul class="sidebar-sub-menu">
      <li>
        <a href="{{ route('auth.user.create') }}">
          <span class="title">{{ trans('users::user.new') }}</span>
        </a>
      </li>
    </ul>
  </li>
</ul>
