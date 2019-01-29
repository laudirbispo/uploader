<!DOCTYPE html>  
<html lang="{LANG}">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" type="image/png" sizes="128x143" href="{FAVICON}">
<title>{PAGE_TITLE}</title>
<link  href="/vendor/twbs/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="/themes/material/css/style.css" rel="stylesheet">
<link href="/admin/assets/css/custom.css" rel="stylesheet" async defer>
<link href="/themes/material/css/animate.css" rel="stylesheet" async defer>
<link href="/themes/material/css/colors/default.css" id="theme" rel="stylesheet" async defer>
<link href="/themes/material/css/md-color-palette.min.css" id="theme" rel="stylesheet" async defer>
<link href="/assets/plugins/metisMenu/metisMenu.min.css" rel="stylesheet" async defer>
<link href="/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet" async defer>
{CSS_FILES}
{CSS_BLOCKS}

</head>

<body class="fix-header">
<!--- Preloader --->
<div class="preloader">
	<svg class="circular" viewBox="25 25 50 50">
		<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
	</svg>
</div>

<div id="wrapper">

{NAVBAR_MENU_TOP}

{SIDEBAR_MENU_LEFT}
	
{CONTENT}
	
{SIDEBAR_SETTINGS_RIGHT}

</div>

<!-- BEGIN FOOTER_BLOCK -->
<footer class="footer text-center"> 
{COPY_RIGHT} EMOBI Multitask -  <a href="https:://easymobi.com.br">easymobi.com.br</a> 
</footer>
<!-- END FOOTER_BLOCK -->
    
<script src="/vendor/components/jquery/jquery.min.js"></script>
<script src="/vendor/tether/tether.js"></script>
<script src="/vendor/twbs/bootstrap-3.3.7/js/bootstrap.min.js"></script>

<script src="/assets/plugins/metisMenu/metisMenu.min.js" async defer></script>
<script src="/themes/material/js/jquery.slimscroll.js" async defer></script>
<script src="/themes/material/js/waves.js" async defer></script>
<script src="/themes/material/js/custom.js" async defer></script>
<script src="/themes/material/js/style.switcher.js" async defer></script>
<script src="/assets/plugins/toast-master/js/jquery.toast.js" async defer></script>
<script src="/admin/assets/js/application.js" async defer></script>
{JAVASCRIPT_FILES}	
{JAVASCRIPT_BLOCKS}

</body>
</html>