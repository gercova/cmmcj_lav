<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('storage/'.$enterprise[0]->logo) }}" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">{{ $enterprise[0]->nombre_comercial }}</span>
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
                <p class="d-block text-white profile" style="margin-bottom: 0px;">{{ auth()->user()->roles[0]->name }}</p>
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
                @can('dashboard')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard')? 'active' : '' }}">
                            <i class="nav-icon fa bi bi-speedometer"></i>
                            <p>Reportes</p>
                        </a>
                    </li>
                @endcan
                @can('documentos')
                    <li class="nav-item {{ request()->routeIs('hcl.*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('hcl.*') ? 'active' : '' }}">
                            <i class="nav-icon fa bi bi-journal-medical"></i>
                            <p>
                                Documentos
                                <i class="fas bi bi-arrow-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('historia_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('hcl.histories.home') }}" class="nav-link {{ request()->routeIs('hcl.histories.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Historias clínicas</p>
                                    </a>
                                </li>    
                            @endcan
                            @can('examen_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('hcl.exams.home') }}" class="nav-link {{ request()->routeIs('hcl.exams.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Exámenes</p>
                                    </a>
                                </li>
                            @endcan
                            @can('control_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('hcl.appointments.home') }}" class="nav-link {{ request()->routeIs('hcl.appointments.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Controles</p>
                                    </a>
                                </li>
                            @endcan
                            @can('informe_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('hcl.reports.home') }}" class="nav-link {{ request()->routeIs('hcl.reports.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Informes</p>
                                    </a>
                                </li>
                            @endcan
                            @can('riesgo_acceder')
                            <li class="nav-item">
                                <a href="{{ route('hcl.risks.home') }}" class="nav-link {{ request()->routeIs('hcl.risks.*') ? 'active' : '' }}">
                                    <i class="bi bi-chevron-right"></i>
                                    <p>Riesgos</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('mantenimiento')
                    <li class="nav-item {{ request()->routeIs('maintenance.*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
                            <i class="nav-icon fa bi bi-gear-fill"></i>
                            <p>
                                Mantenimiento
                                <i class="fas bi bi-arrow-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('categoria_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('maintenance.categories') }}" class="nav-link {{ request()->routeIs('maintenance.categories') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Categorías</p>
                                    </a>
                                </li>
                            @endcan
                            @can('presentacion_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('maintenance.presentations') }}" class="nav-link {{ request()->routeIs('maintenance.presentations') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Presentación fármaco</p>
                                    </a>
                                </li>
                            @endcan
                            @can('farmaco_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('maintenance.drugs') }}" class="nav-link {{ request()->routeIs('maintenance.drugs') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Fármacos</p>
                                    </a>
                                </li>
                            @endcan
                            @can('diagnostico_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('maintenance.diagnostics') }}" class="nav-link {{ request()->routeIs('maintenance.diagnostics') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Diagnósticos</p>
                                    </a>
                                </li>
                            @endcan
                            @can('ocupacion_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('maintenance.occupations') }}" class="nav-link {{ request()->routeIs('maintenance.occupations') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Ocupaciones</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('empresa')
                    <li class="nav-item {{ request()->routeIs('business.*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('business.*') ? 'active' : '' }}">
                            <i class="nav-icon fa bi bi-building-fill-gear"></i>
                            <p>
                                Negocio
                                <i class="fas bi bi-arrow-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('empresa_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('business.enterprise') }}" class="nav-link {{ request()->routeIs('business.enterprise') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Empresa</p>
                                    </a>
                                </li>
                            @endcan
                                <li class="nav-item">
                                    <a href="{{ route('business.posts') }}" class="nav-link {{ request()->routeIs('business.posts') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Post</p>
                                    </a>
                                </li>
                        </ul>
                    </li>
                @endcan
                @can('seguridad')
                    <li class="nav-item {{ request()->routeIs('security.*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('security.*') ? 'active' : '' }}">
                            <i class="nav-icon fa bi bi-shield-lock-fill"></i>
                            <p>
                                Seguridad
                                <i class="fas bi bi-arrow-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('modulo_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('security.modules') }}" class="nav-link {{ request()->routeIs('security.modules') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Módulos</p>
                                    </a>
                                </li>
                            @endcan
                            @can('permiso_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('security.permissions') }}" class="nav-link {{ request()->routeIs('security.permissions') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Permisos</p>
                                    </a>
                                </li>
                            @endcan
                            @can('especialidad_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('security.specialties') }}" class="nav-link {{ request()->routeIs('security.specialties') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Especialidades</p>
                                    </a>
                                </li>
                            @endcan
                            @can('usuario_acceder')
                                <li class="nav-item">
                                    <a href="{{ route('security.users.home') }}" class="nav-link {{ request()->routeIs('security.users.*') ? 'active' : '' }}">
                                        <i class="bi bi-chevron-right"></i>
                                        <p>Usuarios</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
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