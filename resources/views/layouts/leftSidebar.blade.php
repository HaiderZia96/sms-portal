<!-- Sidebar Menu -->
<nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="{{route('admin')}}" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <!-- <i class="right fas fa-angle-left"></i> -->
              </p>
            </a>
          </li>
          @if(Auth::user()['role'] == 1)
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                User Groups
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('user_groups.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Groups</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('users.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('users.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Users</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Subscriber Manage
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('subscriber-list.create')}}" class="nav-link">
                  <i class="nav-icon fas fa-file"></i>
                  <p>Subscriber List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('subscriber.create')}}" class="nav-link">
                  <i class="nav-icon fas fa-file"></i>
                  <p>Subscriber</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{route('message.create')}}" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>Message</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Send Message
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('instant-message.create')}}" class="nav-link">
                  <i class="nav-icon fas fa-file"></i>
                  <p>Instant Message</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('campaign.create')}}" class="nav-link">
                  <i class="nav-icon fas fa-file"></i>
                  <p>Campaign</p>
                </a>
              </li>
            </ul>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>