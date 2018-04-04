
<ul hidden class="navbar-nav ml-auto">
    <li class="nav-item dropdown" hidden>
        <a class="nav-link dropdown-toggle mr-lg-2" id="messagesDropdown" href="#" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-envelope"></i>
            <span class="d-lg-none">Messages
              <span class="badge badge-pill badge-primary">12 New</span>
            </span>
            <span class="indicator text-primary d-none d-lg-block">
              <i class="fa fa-fw fa-circle"></i>
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
            <h6 class="dropdown-header">New Messages:</h6>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
                <strong>David Miller</strong>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">Hey there! This new version of SB Admin is pretty
                    awesome! These messages clip off when they reach the end of the box so they don't
                    overflow over to the sides!
                </div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
                <strong>Jane Smith</strong>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">I was wondering if you could meet for an appointment at
                    3:00 instead of 4:00. Thanks!
                </div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
                <strong>John Doe</strong>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">I've sent the final files over to you for review. When
                    you're able to sign off of them let me know and we can discuss distribution.
                </div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item small" href="#">View all messages</a>
        </div>
    </li>
    <li class="nav-item dropdown" hidden>
        <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
            <span class="d-lg-none">Alerts
              <span class="badge badge-pill badge-warning">6 New</span>
            </span>
            <span class="indicator text-warning d-none d-lg-block">
              <i class="fa fa-fw fa-circle"></i>
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">New Alerts:</h6>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <span class="text-success">
                <strong>
                  <i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
              </span>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">This is an automated server response message. All
                    systems are online.
                </div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <span class="text-danger">
                <strong>
                  <i class="fa fa-long-arrow-down fa-fw"></i>Status Update</strong>
              </span>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">This is an automated server response message. All
                    systems are online.
                </div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <span class="text-success">
                <strong>
                  <i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
              </span>
                <span class="small float-right text-muted">11:21 AM</span>
                <div class="dropdown-message small">This is an automated server response message. All
                    systems are online.
                </div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item small" href="#">View all alerts</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle mr-lg-2" id="" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-user"></i>
            <b>{{auth()->user()->username}}</b>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
            <router-link class="dropdown-item" :to="{name:'user-profile'}">
                <small>Profile</small>
            </router-link>
            <div class="dropdown-divider"></div>
            <a href="{{url("/logout")}}" class="dropdown-item text-danger">
                <i class="fa fa-fw fa-sign-out"></i><b>Logout</b>
            </a>
        </div>
    </li>
</ul>


<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle"
           href="#"
           data-toggle="dropdown"
           aria-haspopup="false"
           aria-expanded="false">
            <span class="h6">
                {{auth()->user()->primer_nombre}} {{auth()->user()->apellido_paterno}}
                <span class="small text-muted">( {{auth()->user()->email}} )</span>
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right mt-3 w-75" aria-labelledby="alertsDropdown">
            {{--<a href class="dropdown-item text-muted">--}}
                {{--<i class="fa fa-user fa-fw"></i>--}}
                {{--<span>Profile User</span>--}}
            {{--</a>--}}
            {{--<a href class="dropdown-item text-muted">--}}
                {{--<i class="fa fa-cogs fa-fw"></i>--}}
                {{--<span>Preferences</span>--}}
            {{--</a>--}}
            {{--<a href class="dropdown-item text-muted">--}}
                {{--<i class="fa fa-tasks fa-fw"></i>--}}
                {{--<span>Theme</span>--}}
            {{--</a>--}}
            {{--<div class="dropdown-divider"></div>--}}
            <a href="{{route('logout')}}" class="dropdown-item text-danger">
                <i class="fa fa-sign-out fa-fw"></i>
                <span>Logout</span>
            </a>
        </div>
    </li>
</ul>