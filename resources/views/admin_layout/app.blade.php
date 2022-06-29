<!doctype html>
<html class="fixed sidebar-left-xs">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="csrf-token" content="{{ csrf_token() }}">        
        <meta content="Admin Panel" name="description" />
        <meta content="Admin Panel" name="keywords">
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>@yield('title','Admin Panel')</title>
		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- start: css files -->
			@include('admin_layout.includes.css_files')
    	<!-- end: css files -->

		<!-- start: css -->
		@yield('css')
		<!-- end: css -->
	</head>
	<body>
		<section class="body">

			<!-- start: header -->
				@include('admin_layout.includes.header')
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
					@include('admin_layout.includes.sidebar')
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<!-- start: breadcrumbs -->
						@include('admin_layout.includes.breadcrumbs')
					<!-- end: breadcrumbs -->

					<!-- start: page -->
						@yield('content')
					<!-- end: page -->
				</section>
			</div>
		
		    <!-- start: Right Sidebar -->
				@include('admin_layout.includes.rtl_sidebar')
            <!-- end: Right Sidebar -->

		</section>
		<script>
            var App_name_global =  "{{ config('app.name', 'Laravel') }}";
        </script>
		<!-- start: js files -->
			@include('admin_layout.includes.js_files')
		<!-- end: js files -->
		<script>
			$(function () {
				toastr.options = {
					"closeButton": false,
					"debug": false,
					"newestOnTop": false,
					"progressBar": true,
					"positionClass": "toast-top-right",
					"preventDuplicates": false,
					"onclick": null,
					"showDuration": "300",
					"hideDuration": "1000",
					"timeOut": "5000",
					"extendedTimeOut": "1000",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				}
			});
		</script>
		<!-- start: script -->
		@yield('script')
		<!-- end: script -->
	</body>
</html>