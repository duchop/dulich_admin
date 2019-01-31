@extends('layout.layout')

@section('meta')
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
@endsection

@section('title'){!! 'Admin - dulich' !!}@endSection

@section('body_id'){!! 'page-top' !!}@endSection

@section('content')

    <!-- navbar -->
    @include('navbar')

    <div id="wrapper">

        <!-- Sidebar -->
        @include('sidebar')

        <div id="content-wrapper">

            <div class="container-fluid">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Màn hình Regist</a>
                    </li>
                    <li class="breadcrumb-item active">Tour</li>
                </ol>

                <!-- Tour -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" width="100%" cellspacing="0">
                                <tr>
                                    <td>Tour name: </td>
                                    <td><input type="text" name="tour_name"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright © travel Website 2019. Web developer HopDD</span>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
@endSection
@section('script')
    <!-- Bootstrap core JavaScript-->
    <script src="{!! asset("vendor/jquery/jquery.min.js") !!}"></script>
    <script src="{!! asset("vendor/bootstrap/js/bootstrap.bundle.min.js") !!}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{!! asset("vendor/jquery-easing/jquery.easing.min.js") !!}"></script>

    <!-- Page level plugin JavaScript-->
    <script src="{!! asset("vendor/datatables/jquery.dataTables.js") !!}"></script>
    <script src="{!! asset("vendor/datatables/dataTables.bootstrap4.js") !!}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{!! asset("js/sb-admin.min.js") !!}"></script>

    <!-- Demo scripts for this page-->
    <script src="{!! asset("js/demo/datatables-demo.js") !!}"></script>
    <script src="{!! asset("js/demo/chart-area-demo.js") !!}"></script>
@endSection

