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

	</head>
	<body>

    <!-- start: page -->
        @yield('content')
    <!-- end: page -->


	<!-- start: js files -->
		@include('admin_layout.includes.js_files')
    <!-- end: js files -->

	</body>
</html>