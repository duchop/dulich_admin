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

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Overview</li>
                </ol>

                <!-- Tour -->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-table"></i>
                        List thông tin tour
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Hotel Name</th>
                                    <th>Hotel Address</th>
                                    <th>Hotel Id</th>
                                    <th>Hotel Type</th>
                                    <th>Update Datetime</th>
                                    <th>Category Hotel</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list_transportation as $transportation)
                                    <tr>
                                        <td>{{ $hotel->hotel_name }}</td>
                                        <td>{{ $hotel->address }}</td>
                                        <td>{{ $hotel->hotel_id }}</td>
                                        <td>{{ $hotel->hotel_type }}</td>
                                        <td>{{ $hotel->update_datetime }}</td>
                                        <td>{{ $hotel->getCategoryHotel->hotel_category_name }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
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
                    <span>Copyright © Your Website 2018</span>
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

