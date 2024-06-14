<!DOCTYPE html>
<html lang="en">
<head>
  @include('layouts.head')
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  @include('layouts.header')
  @include('layouts.leftSidebar')

  <main class="py-4">
    @yield('content')
  </main>

<!-- /.content-wrapper -->
@include('layouts.footer')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

@include('layouts.footerScripts')
   @yield('footer-scripts')



</body>
</html>