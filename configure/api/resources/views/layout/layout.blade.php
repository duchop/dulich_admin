<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    @yield('meta')

    <!--meta name="keywords" content="" /-->
    <title>@yield('title')</title>

    <!-- Bootstrap core CSS-->
    <link href="{!! asset("vendor/bootstrap/css/bootstrap.min.css") !!}" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="{!! asset("vendor/fontawesome-free/css/all.min.css") !!}" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="{!! asset("vendor/datatables/dataTables.bootstrap4.css") !!}" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{!! asset("css/sb-admin.css") !!}" rel="stylesheet">

    @yield('style')

</head>
<body id="@yield('body_id')" class="@yield('body_class')">
<div>
    @yield('content')
</div>

@yield('script')
</body>
</html>
