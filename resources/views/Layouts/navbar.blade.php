<!--<nav class="main-header navbar navbar-expand navbar-white navbar-light">-->
<nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item" id="toggleMenuIcon">
            <a class="nav-link" data-widget="pushmenu" href="{{ route('home') }}" role="button"><i class="bi bi-list"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!--<li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                <i class="bi bi-list-columns-reverse"></i> Eventos
            </a>
        </li>-->
        @if(auth()->user()->roles[0]->name == 'Administrador')
            <li class="nav-item">
                <a href="{{ route('activity-logs.today') }}" class="nav-link">
                    <i class="nav-icon fas fa-history"></i> Bitácora
                </a>
            </li>
        @endif
    </ul>
</nav>
<!-- /.navbar -->
