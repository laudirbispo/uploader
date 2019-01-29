<section id="wrapper" class="login-register">
    <div class="login-box">
        <div class="white-box"> 
            
            <form class="form-horizontal form-material" id="loginform" action="{FORM_ACTION_UNLOCKSCREEN}" method="post">
                <div class="form-group">
                    <div class="col-xs-12 text-center">
                        <div class="user-thumb text-center"> <img alt="Profile Photo" class="img-circle" width="100" src="{CURRENT_USER_PROFILE_PICTURE}">
                            <h3>{CURRENT_USER_NAME} {CURRENT_USER_LAST_NAME}</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <input class="form-control" name="pin" type="password" required="" min="4" maxlength="10" pattern="0-9]+$" placeholder="PIN">
                    </div>
                </div>
                <div class="form-group text-center">
                    <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit" name="submit.form-unlockscreen">Entrar</button>
                    </div>
                </div>
            </form>
			
			<a href="{URL_ADMIN_LOGOUT}" class="btn btn-danger waves-effect waves-light pull-right" title="Sair"><i class="mdi mdi-account-convert"></i> </a>
			
			<div class="clearfix m-t-20"></div>
			<!-- BEGIN ERROR_MESSAGE -->
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {MESSAGE} 
			</div>
            <!-- END ERROR_MESSAGE -->
        </div>
		
	
    </div>
</section>
