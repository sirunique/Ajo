    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="{{ asset('vali') }}/images/48.jpg" alt="User Image">
        <div>
          <p class="app-sidebar__user-name">{{auth::guard('investor')->user()->name}}</p>
          <p class="app-sidebar__user-designation">Frontend Developer</p>
        </div>
      </div>
      <ul class="app-menu">
        @if(auth::guard('investor')->check())
        <li><a class="app-menu__item active" href="{{ route('dashboard')}}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Thrift</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
              <li><a class="treeview-item" href="{{ route('group')}}"><i class="icon fa fa-circle-o"></i> Thrift</a></li>
            <li><a class="treeview-item" href="{{ route('createThrift')}}"><i class="icon fa fa-circle-o"></i> Create Thrift</a></li>
          </ul>
        </li>
        @endif

        @if(auth::guard('admin')->check())
        <li><a class="app-menu__item active" href="{{ route('admin.admin')}}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
        <li><a class="app-menu__item active" href="{{ route('admin.datacheckout')}}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Data</span></a></li>
        @endif
      </ul>
    </aside>