<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon">
            <i class="fab"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Indosopha</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <!--@can('admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    @endcan
-->
   
    <li class="nav-item">
        <a class="nav-link" href="{{ route('user') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Welcome Dashboard</span></a>
    </li>
   

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Prospect
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.prospectcreation') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Create New Prospect</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.prospectcheckview') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Prospect Check</span></a>
            </li>   

    @can('admin')

    <!--
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
            aria-expanded="true" aria-controls="collapseOne">
            <i class="fas fa-fw fa-table"></i>
            <span>Prospect Validation</span>
        </a>
        <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('user.index') }}">User</a>
            </div>
        </div>
    </li>

-->
     


            <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.prospectvalidationview') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Prospect Validation</span></a>
            </li>
            <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.prospect.index') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Prospect Review</span></a>
            </li>
          
            <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.prospect.index') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Prospect Drop Request</span></a>
            </li>
            <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.prospect.index') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Prospect Update Request</span></a>
            </li>

    @elseCan('fs')
           
            <li class="nav-item">
             <a class="nav-link" href="{{ route('admin.prospect.index') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>My Prospect</span></a>
            </li>
            <li class="nav-item">
    @elseCan('am')
           
            <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.prospectvalidationview') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Approval Prospect</span></a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.prospect.index') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Prospect Review</span></a>
            </li>
            <li class="nav-item">

    @elseCan('nsm')
    <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.prospectvalidationview') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Approval Prospect</span></a>
            </li>
            <li class="nav-item">
             <a class="nav-link" href="{{ route('admin.prospect.index') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Review Prospect In My Region</span></a>
            </li>
            <li class="nav-item">

    @endcan

    <!--
    @can('admin')
   
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="{{ route('buttons') }}">Buttons</a>
                <a class="collapse-item" href="{{ route('cards') }}">Cards</a>
            </div>
        </div>
    </li>
    @endcan
    
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="{{ route('utilities-colors') }}">Colors</a>
                <a class="collapse-item" href="{{ route('utilities-borders') }}">Borders</a>
                <a class="collapse-item" href="{{ route('utilities-animations') }}">Animations</a>
                <a class="collapse-item" href="{{ route('utilities-other') }}">Other</a>
            </div>
        </div>
    </li>
-->

<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
   Mapping
</div>

<li class="nav-item">
        <a class="nav-link" href="">
            <i class="fas fa-fw fa-sitemap"></i>
            <span>Department </br>(Under Development)</span></a>
        
</li>

<li class="nav-item">
        <a class="nav-link" href="">
            <i class="fas fa-fw fa-user-md"></i>
            <span>Doctor </br>(Under Development)</span></a>
        
</li>




<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
   Tools
</div>

<li class="nav-item">
        <a class="nav-link" href="">
            <i class="fas fa-fw fa-archive"></i>
            <span>Market Data and Query (Under Development)</span></a>
        
</li>
<li class="nav-item">
        <a class="nav-link" href="">
            <i class="fas fa-fw fa-tools"></i>
            <span>Marketing Tools (Under Development)</span></a>
</li>




<!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Installbase Management
    </div>

    <!-- 
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item" href="{{ route('login') }}">Login</a>
                <a class="collapse-item" href="{{ route('register') }}">Register</a>
                <a class="collapse-item" href="{{ route('forgot-password') }}">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="{{ route('404-page') }}">404 Page</a>
                <a class="collapse-item" href="{{ route('blank-page') }}">Blank Page</a>
            </div>
        </div>
    </li>
Nav Item - Pages Collapse Menu -->



    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Install Base Data (Coming SOON)</span></a>
    </li>

    <!-- Nav Item - Tables
    <li class="nav-item">
        <a class="nav-link" href="{{ route('tables') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('profile') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Profile</span></a>
    </li> -->

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>