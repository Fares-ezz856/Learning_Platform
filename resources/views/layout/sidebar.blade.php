  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Admin Dashboard</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()?Auth::user()->name:'NA' }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-header">MANAGEMENT</li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Courses
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.courses.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Courses</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.courses.pending') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Courses</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.instructors.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Instructors</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.students.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Students</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-header">SETTINGS</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>Profile</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>

          <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
              @csrf
          </form>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
