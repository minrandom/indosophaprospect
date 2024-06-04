<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('user') }}">
        <div class="sidebar-brand-icon">
            <i class="fab"></i>
        </div>
        <div class="sidebar-brand-text mx-3">ISBIS APPS</div>
    </a>

    <!-- Divider -->
    <!--<hr class="sidebar-divider my-0">-->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
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

    @can('dba')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSetup"
            aria-expanded="true" aria-controls="collapseSetup">
            <i class="fas fa-fw fa-toolbox"></i>
            <span>SETUP DATA</span>
        </a>
        <div id="collapseSetup" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Setup Hospital Menu</h6>
                <a class="collapse-item" href="{{ route('admin.hospital') }}">
            <i class="fas fa-fw fa-hospital"></i>
            <span>Hospital List</span></a>
             
        <a class="collapse-item" href="{{ route('admin.hospitalcreate') }}">
            <i class="fas fa fa-plus-square"></i>
            <span>Create Hospital</span></a>
            </div>
        </div>
    </li>
    @endcan
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseJadwal"
            aria-expanded="true" aria-controls="collapseJadwal">
            <i class="fas fa-fw fa-calendar"></i>
            <span>Jadwal dan Kehadiran</span>
        </a>
        <div id="collapseJadwal" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Jadwal Menu</h6>
                <a class="collapse-item" href="{{ route('schedule') }}">
            <i class="fas fa-fw fa-calendar"></i>
            <span>Jadwal Kegiatan</span></a>
          
       
          
                
        <a class="collapse-item" href="{{ route('kehadiran') }}">
            <i class="fas fa fa-address-card"></i>
            <span>Kehadiran</span></a>
            </div>
        </div>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProspect"
            aria-expanded="true" aria-controls="collapseProspect">
            <i class="fas fa-fw fa-handshake"></i>
            <span>PROSPECT</span>
        </a>
        <div id="collapseProspect" class="collapse" aria-labelledby="headingProspect" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Prospect Menu:</h6>
                <a class="collapse-item" href="{{ route('admin.prospectcreation') }}"><i class="fas fa-fw fa-file-alt"></i><span> Create New Prospect</span></a>
                <a class="collapse-item" href="{{ route('admin.prospecteventcreation') }}"><i class="fas fa-fw fa-file-alt"></i><span> Create Event Prospect</span></a>
                <a class="collapse-item" href="{{ route('admin.prospectcheckview') }}"><i class="fas fa-fw fa-tasks"></i><span> Prospect Check</span></a>
                @can('admin')  
                <a class="collapse-item" href="{{ route('admin.prospectvalidationview') }}"><i class="fas fa-fw fa-tasks"></i><span> Prospect Validation</span></a>
                <a class="collapse-item" href="{{ route('admin.prospect.index') }}"><i class="fas fa-fw fa-briefcase"></i><span> Prospect Review</span></a>
                <a class="collapse-item" href="{{ route('admin.prospect.index') }}"><i class="fas fa-fw fa-handshake-slash"></i><span> Drop Request</span></a>
                <a class="collapse-item" href="{{ route('admin.prospect.index') }}"><i class="fas fa-fw fa-pen"></i><span> Update Request</span></a>
                <a class="collapse-item" href="{{ route('pchart') }}"><i class="fas fa-fw fa-tasks"></i><span>Prospect Chart</span></a>
         
                @elsecan('fs')
                <a class="collapse-item" href="{{ route('admin.prospect.index') }}"><i class="fas fa-fw fa-tasks"></i><span>My Prospect</span></a>
                @elsecan('am')
                <a class="collapse-item" href="{{ route('admin.prospectvalidationview') }}"><i class="fas fa-fw fa-tasks"></i><span>Approval Prospect</span></a>
                <a class="collapse-item" href="{{ route('admin.prospect.index') }}"><i class="fas fa-fw fa-tasks"></i><span>Area Prospect Review</span></a>
         
                @elsecan('nsm')
                <a class="collapse-item" href="{{ route('admin.prospectvalidationview') }}"><i class="fas fa-fw fa-tasks"></i><span>Approval Prospect</span></a>
                <a class="collapse-item" href="{{ route('admin.prospect.index') }}"><i class="fas fa-fw fa-tasks"></i><span>Region Prospect Review</span></a>
                @elsecan('bu')
                <a class="collapse-item" href="{{ route('admin.prospect.index') }}"><i class="fas fa-fw fa-tasks"></i><span>BU Prospect Review</span></a>
                <a class="collapse-item" href="{{ route('pchart') }}"><i class="fas fa-fw fa-tasks"></i><span>Prospect Chart</span></a>
         

                @endcan
            </div>
        </div>


        
    </li>
    
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHospital"
            aria-expanded="true" aria-controls="collapseHospital">
            <i class="fas fa-fw fa-hospital"></i>
            <span>HOSPITAL</span>
        </a>
        <div id="collapseHospital" class="collapse" aria-labelledby="headingHospital" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Hospital Menu:</h6>
                <a class="collapse-item" href="{{ route('admin.deptvalidation') }}"><i class="fas fa-fw fa-tasks"></i><span> Department Validation</span></a>
                </div>
        </div>

        


        
    </li>

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