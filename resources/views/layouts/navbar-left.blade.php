<ul class="navbar-nav navbar-sidenav" id="exampleAccordion" style="overflow-y: auto;overflow-x: hidden">
    <li hidden class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
    <router-link :to="'home'" class="nav-link">
    <i class="fa fa-fw fa-dashboard"></i>
    <span class="nav-link-text">Dashboard</span>
    </router-link>
    </li>
    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Reports">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMulti"
           data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-file-text"></i>
            <span class="nav-link-text">Reportes</span>
        </a>
        <ul class="sidenav-second-level collapse" id="collapseMulti">
            <li>
                <router-link :to="'report'" class="nav-link">
                    <i class="fa fa-fw fa-area-chart"></i>
                    <span class="nav-link-text">Por Hora</span>
                </router-link>
            </li>
        </ul>
    </li>
    <li hidden class="nav-item" data-toggle="tooltip" data-placement="right">
        <a href class="nav-link">
            <i class="fa fa-fw fa-tasks"></i>
            <span class="nav-link-text">Tasks</span>
        </a>
    </li>
    <li hidden class="nav-item" data-toggle="tooltip" data-placement="right">
        <a href class="nav-link">
            <i class="fa fa-fw fa-calendar"></i>
            <span class="nav-link-text">Shedule</span>
        </a>
    </li>
    <li hidden class="nav-item" data-toggle="tooltip" data-placement="right" title="Menu Levels">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMulti"
           data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-sitemap"></i>
            <span class="nav-link-text">Menu Levels</span>
        </a>
        <ul class="sidenav-second-level collapse" id="collapseMulti">
            <li>
                <a href="#">Second Level Item</a>
            </li>
            <li>
                <a href="#">Second Level Item</a>
            </li>
            <li>
                <a href="#">Second Level Item</a>
            </li>
            <li>
                <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMulti2">Third
                    Level</a>
                <ul class="sidenav-third-level collapse" id="collapseMulti2">
                    <li>
                        <a href="#">Third Level Item</a>
                    </li>
                    <li>
                        <a href="#">Third Level Item</a>
                    </li>
                    <li>
                        <a href="#">Third Level Item</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</ul>
<ul class="navbar-nav sidenav-toggler">
    <li class="nav-item">
        <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-bars"></i>
        </a>
    </li>
</ul>