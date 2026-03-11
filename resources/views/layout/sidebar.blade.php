  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @php
      $dashboardRoute = 'welcome';
      $brandText = 'Educational Platform';
      if(Auth::guard('admin_web')->check()) {
          $dashboardRoute = 'admin.dashboard';
          $brandText = 'Admin Dashboard';
      } elseif(Auth::guard('instructor_web')->check()) {
          $dashboardRoute = 'instructor.dashboard';
          $brandText = 'Instructor Panel';
      } elseif(Auth::guard('student_web')->check()) {
          $dashboardRoute = 'student.dashboard';
          $brandText = 'Student Portal';
      }
    @endphp
    <a href="{{ route($dashboardRoute) }}" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{ $brandText }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          @php
            $user = Auth::guard('admin_web')->user() ?? Auth::guard('instructor_web')->user() ?? Auth::guard('student_web')->user();
          @endphp
          <a href="#" class="d-block">{{ $user ? $user->name : 'Guest' }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          @if(Auth::guard('admin_web')->check())
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Admin Dashboard</p>
            </a>
          </li>
          <li class="nav-header">MANAGEMENT</li>
          <li class="nav-item has-treeview {{ request()->is('admin/courses*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('admin/courses*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book"></i>
              <p>Courses <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->is('admin/courses') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>All Courses</p></a></li>
              <li class="nav-item"><a href="{{ route('admin.courses.pending') }}" class="nav-link {{ request()->is('admin/courses/pending') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Pending Courses</p></a></li>
            </ul>
          </li>
          <li class="nav-item has-treeview {{ request()->is('admin/instructors*') || request()->is('admin/students*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('admin/instructors*') || request()->is('admin/students*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Users <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="{{ route('admin.instructors.index') }}" class="nav-link {{ request()->is('admin/instructors*') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Instructors</p></a></li>
              <li class="nav-item"><a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->is('admin/students*') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Students</p></a></li>
            </ul>
          </li>
          @elseif(Auth::guard('instructor_web')->check())
          <li class="nav-item">
            <a href="{{ route('instructor.dashboard') }}" class="nav-link {{ request()->is('instructor/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>Instructor Dashboard</p>
            </a>
          </li>
          <li class="nav-header">MY CONTENT</li>
          <li class="nav-item">
            <a href="{{ route('instructor.courses.index') }}" class="nav-link {{ request()->is('instructor/courses*') ? 'active' : '' }}"><i class="nav-icon fas fa-book"></i><p>My Courses</p></a>
          </li>
          <li class="nav-item">
            <a href="{{ route('instructor.students.index') }}" class="nav-link {{ request()->is('instructor/students*') ? 'active' : '' }}"><i class="nav-icon fas fa-users"></i><p>My Students</p></a>
          </li>
          @elseif(Auth::guard('student_web')->check())
          <li class="nav-item">
            <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->is('student/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>Student Dashboard</p>
            </a>
          </li>
          <li class="nav-header">LEARNING</li>
          <li class="nav-item">
            <a href="{{ route('student.courses.index') }}" class="nav-link {{ request()->is('student/courses*') ? 'active' : '' }}"><i class="nav-icon fas fa-layer-group"></i><p>My Courses</p></a>
          </li>
          <li class="nav-item">
            <a href="{{ route('student.courses.browse') }}" class="nav-link {{ request()->is('student/browse-courses') ? 'active' : '' }}"><i class="nav-icon fas fa-search"></i><p>Browse All</p></a>
          </li>
          @endif

          <li class="nav-header">COMMUNICATION</li>
          <li class="nav-item">
            <a href="{{ route('chat.index') }}" class="nav-link {{ request()->is('chat*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-comments"></i>
              <p>Public Chat</p>
            </a>
          </li>

          <li class="nav-header">ACCOUNT</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>Profile</p>
            </a>
          </li>
          <li class="nav-item">
            @php
              $logoutRoute = '#';
              $formAction = '#';
              if(Auth::guard('admin_web')->check()) {
                  $logoutRoute = 'javascript:void(0)';
                  $formAction = route('admin.logout');
              } elseif(Auth::guard('instructor_web')->check()) {
                  $logoutRoute = 'javascript:void(0)';
                  $formAction = route('instructor.logout');
              } elseif(Auth::guard('student_web')->check()) {
                  $logoutRoute = 'javascript:void(0)';
                  $formAction = route('student.logout');
              }
            @endphp
            <a href="{{ $logoutRoute }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>

          <form id="logout-form" action="{{ $formAction }}" method="POST" style="display: none;">
              @csrf
          </form>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
