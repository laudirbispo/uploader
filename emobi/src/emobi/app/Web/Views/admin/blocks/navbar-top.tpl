<nav class="navbar navbar-default navbar-static-top m-b-0">
	<div class="navbar-header">
		<div class="top-left-part">
			<a class="logo" href="/admin/home">
				<b>
				<img src="/admin/assets/images/logotipo-black.png" alt="Logotipo" height="60" class="light-logo" />
				<img src="/admin/assets/images/logotipo-white.png" alt="Logotipo" height="60" class="dark-logo" />
			 	</b>
			</a>
		</div>
		<!--- /Logo --->
		<!--- Search input and Toggle icon --->
		<ul class="nav navbar-top-links navbar-left">
			<li>
				<a href="javascript:void(0)" class="open-close waves-effect waves-light visible-xs"><i class="ti-close ti-menu"></i></a>
			</li>
		</ul>
		<ul class="nav navbar-top-links navbar-right pull-right">
			<li>
				<form role="search" class="app-search hidden-sm hidden-xs m-r-10">
					<input type="text" placeholder="Search..." class="form-control"> <a href=""><i class="fa fa-search"></i></a> </form>
			</li>
			<li>
				<a role="button" class="right-side-toggle waves-effect waves-light" title="Alterar esquema de cores"><i class="mdi mdi-format-paint text-white"></i> </a>
			</li>
			<li class="dropdown">
				<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                    <img src="{CURRENT_PROFILE_PICTURE}" alt="user-img" width="36" class="img-circle">
                </a>
				<ul class="dropdown-menu dropdown-user animated flipInY">
					<li>
						<div class="dw-user-box">
							<div class="u-text">
								<h4 class="">{CURRENT_USERNAME}</h4>
								<p class="text-muted">{CURRENT_USER_EMAIL}</p></div>
						</div>
					</li>
					<li role="separator" class="divider"></li>
					<li><a href="{URL_ADMIN_MY_ACCOUNT}"><i class="ti-user"></i> Minha Conta</a></li>
					<li><a href="#"><i class="ti-wallet"></i> My Balance</a></li>
					<li><a href="#"><i class="ti-email"></i> Inbosx</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="{URL_ADMIN_LOCKSCREEN}"><i class="mdi mdi-lock-outline"></i> Modo de espera</a></li>
					<li><a href="{URL_ADMIN_LOGOUT}"><i class="mdi mdi-power"></i> Sair</a></li>
				</ul>
				<!--- /.dropdown-user --->
			</li>
			<!--- /.dropdown --->
		</ul>
	</div>
	<!--- /.navbar-header --->
	<!--- /.navbar-top-links --->
	<!--- /.navbar-static-side --->
</nav>