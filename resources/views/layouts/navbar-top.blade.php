<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle"
           href="#"
           data-toggle="dropdown"
           aria-haspopup="false"
           aria-expanded="false">
            <span class="h6">
                {{auth()->user()->primer_nombre}} {{auth()->user()->apellido_paterno}}
                <span class="small">( {{auth()->user()->email}} )</span>
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right mt-3 w-75" aria-labelledby="alertsDropdown">
            <a href="#" class="dropdown-item text-muted">
                <i class="fa fa-file"></i>
                <span>Shared Data</span>
            </a>
            <a href="#" class="dropdown-item text-muted">
                <i class="fa fa-cog"></i>
                <span>Settings</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{route('logout')}}" class="dropdown-item text-dark">
                <i class="fa fa-sign-out fa-fw"></i>
                <span>Logout</span>
            </a>
        </div>
    </li>
</ul>