<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/'.$enterprise->logo_miniatura) }}">
    <title>@yield('title', $enterprise->nombre_comercial)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">
    <!-- JQuery UI -->
    <link href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- DataTables -->
    <link href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <!-- Theme style -->
    <link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
    <!-- JTable -->
    <link href="{{ asset('jtable/themes/lightcolor/gray/jtable.min.css') }}" rel="stylesheet">
    <!-- iCheck for checkboxes and radio inputs -->
    <link href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Color Picker -->
    <link href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
    <!-- Select2 -->
    <link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <!-- Slim Select -->
    <link href="{{ asset('css/slimselect.min.css') }}" rel="stylesheet">
    <!-- SweetAlert 2 -->
    <link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icon -->
    <link href="{{ asset('css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <!-- Hightcharts -->
    <!--<script src="{{ asset('plugins/highcharts/highcharts.js') }}"></script>
	<script src="{{ asset('plugins/highcharts/dashboards.js') }}"></script>
    <script src="{{ asset('plugins/highcharts/layout.js') }}"></script>-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/themes/adaptive.js"></script>
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- JQuery UI -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- JTable -->
    <script src="{{ asset('jtable/jquery.jtable.js') }}"></script>
    <script src="{{ asset('jtable/jquery.jtable.min.js') }}"></script>
    <script src="{{ asset('jtable/localization/jquery.jtable.es.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- SweetAlert 2 -->
    <script src={{ asset('plugins/sweetalert2/sweetalert2.min.js') }}></script>
    <script>
        const API_URL = "{{ url('/') }}";
        const NAME_ENTERPRISE = "{{ $enterprise->nombre_comercial }}";
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    <!-- Hightcharts -->
    <script src="{{ asset('highcharts/highcharts.js') }}"></script>
	<script src="{{ asset('highcharts/exporting.js') }}"></script>
    <!-- Extras -->
    <script src="{{ asset('js/lodash.min.js') }}"></script>
    <script src="{{ asset('js/slimselect.min.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/funciones.js') }}"></script>
    <script src="{{ asset('js/modalDetails.js') }}"></script>
    <script src="{{ asset('js/deleteHandler.js') }}"></script>
    <script src="{{ asset('js/logout.js') }}"></script>
</head>
<body class="sidebar-mini sidebar-mini-md layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{ asset('storage/'.$enterprise->logo_miniatura) }}" alt="AdminLTELogo" height="100" width="100">
        </div>
        <!-- Navbar -->
        @include('layouts.navbar', $enterprise)
        <!-- Sidebar -->
        @include('layouts.sidebar', $enterprise)
        <!-- Content Wrapper -->
        @yield('content')
        <!-- Footer -->
        @include('layouts.footer', $enterprise)
    </div>
    <div class="modal fade" id="modal-default" tabindex="-1" aria-modal="true" role="dialog" data-backdrop="static" aria-labelledby="staticBackdropLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.modal').on('hidden.bs.modal', function () {
            // Limpiar el contenido del modal
            $(this).find('.modal-body').html('');
            $(this).find('.modal-title').html('');
        });
    </script>
    <!-- ./wrapper -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleMenuIcon = document.getElementById('toggleMenuIcon');
            const body = document.querySelector('body');

            toggleMenuIcon.addEventListener('click', () => {
                if (body.classList.contains('sidebar-collapse')) {
                    localStorage.setItem('sidebarState', 'expanded');
                } else {
                    localStorage.setItem('sidebarState', 'collapsed');
                }
            });

            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed') {
                body.classList.add('sidebar-collapse');
            }
        });
    </script>
</body>
</html>