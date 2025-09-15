<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('storage/'.$enterprise->logo_miniatura) }}" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">{{ $enterprise->nombre_comercial }}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->profile_photo_url }}" class="img-circle elevation-2" id="profile-img">
            </div>
            <div class="info">
                <!-- info usuario -->
                <a href="#" class="d-block summary-names">{{ auth()->user()->formatted_name }}</a>
                <p class="d-block text-white profile" style="margin-bottom: 0px;"></p>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="nav-icon fa bi bi-house"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard')? 'active' : '' }}">
                            <i class="nav-icon fa bi bi-speedometer"></i>
                            <p>Reportes</p>
                        </a>
                    </li>
                

                    <li class="nav-item {{ request()->routeIs('emr.*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('emr.*') ? 'active' : '' }}">
                            <i class="nav-icon fa bi bi-journal-medical"></i>
                            <p>
                                Documentos
                                <i class="fas bi bi-arrow-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            
                                <li class="nav-item">
                                    <a href="{{ route('emr.histories.home') }}" class="nav-link {{ request()->routeIs('emr.histories.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Historias clínicas</p>
                                    </a>
                                </li>    
                            

                                <li class="nav-item">
                                    <a href="{{ route('emr.exams.home') }}" class="nav-link {{ request()->routeIs('emr.exams.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Exámenes</p>
                                    </a>
                                </li>
                            
                            
                        </ul>
                    </li>
               

                    <li class="nav-item {{ request()->routeIs('maintenance.*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
                            <i class="nav-icon fa bi bi-gear-fill"></i>
                            <p>
                                Mantenimiento
                                <i class="fas bi bi-arrow-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">


                                <li class="nav-item">
                                    <a href="{{ route('maintenance.drugs') }}" class="nav-link {{ request()->routeIs('maintenance.drugs') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Fármacos</p>
                                    </a>
                                </li>
                            

                                <li class="nav-item">
                                    <a href="{{ route('maintenance.diagnostics') }}" class="nav-link {{ request()->routeIs('maintenance.diagnostics') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Diagnósticos</p>
                                    </a>
                                </li>
                            

                                <li class="nav-item">
                                    <a href="{{ route('maintenance.occupations') }}" class="nav-link {{ request()->routeIs('maintenance.occupations') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Ocupaciones</p>
                                    </a>
                                </li>
                            
                        </ul>
                    </li>
                

                    <li class="nav-item {{ request()->routeIs('business.*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('business.*') ? 'active' : '' }}">
                            <i class="nav-icon fa bi bi-building-fill-gear"></i>
                            <p>
                                Negocio
                                <i class="fas bi bi-arrow-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            
                                <li class="nav-item">
                                    <a href="{{ route('business.enterprise') }}" class="nav-link {{ request()->routeIs('business.enterprise') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Empresa</p>
                                    </a>
                                </li>
                            
                        </ul>
                    </li>
                

                    <li class="nav-item {{ request()->routeIs('security.*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('security.*') ? 'active' : '' }}">
                            <i class="nav-icon fa bi bi-shield-lock-fill"></i>
                            <p>
                                Seguridad
                                <i class="fas bi bi-arrow-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            
                                

                                <li class="nav-item">
                                    <a href="{{ route('security.permissions') }}" class="nav-link {{ request()->routeIs('security.permissions') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Permisos</p>
                                    </a>
                                </li>
                            

                                <li class="nav-item">
                                    <a href="{{ route('security.users.home') }}" class="nav-link {{ request()->routeIs('security.users.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Usuarios</p>
                                    </a>
                                </li>

                        </ul>
                    </li>
                
                <li class="nav-item">
                    <a href="" class="nav-link exit-system">
                        <i class="nav-icon fa bi bi-power"></i>
                        <p>Salir</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>