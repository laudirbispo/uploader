<section id="wrapper" class="new-login-register">
        <div class="lg-info-panel">
            <div class="inner-panel">
                <a href="javascript:void(0)" class="p-20 di"> <img src="/admin/assets/images/logotipo-white.png" height="100"></a>
                <div class="lg-content">
                    <h2>SIMPLES, FÁCIL, RÁPIDO E COMPLETO GERENCIADOR DE CONTEÚDO DA EASY-MOBI</h2>
                </div>
            </div>
        </div>
        <div class="new-login-box">
            
            <!--- Flash Messages ---> 
            {FLASH_MESSAGES}
			
            <div class="white-box">
                <h3 class="box-title m-b-0 p-l-0">ENTRE AGORA MESMO</h3>
                <form class="form-horizontal new-lg-form" name="form-login" method="post" id="loginform" action="{LOGIN_FORM_ACTION}" data-toggle="validator" role="form">
                    <input name="redirect" value='{REDIRECT}' type="hidden">
                    <div class="col-md-12">
                        <div class="form-group has-feedback m-t-20">
                            <label>Username</label>
                            <input class="form-control" name="username" value="{USERNAME}" type="text" placeholder="Username" required>
                            <div class="help-block with-errors">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <label>Senha</label>
                            <input class="form-control" name="password" value="{PASSWORD}" type="password" placeholder="Password" required>
                            <div class="help-block with-errors">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-info pull-left p-t-0">
                                <input id="checkbox-signup" name="remember" type="checkbox" {REMEMBER} >
                                <label for="checkbox-signup">Lembre de mim</label>
                            </div>
                            <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-key m-r-5"></i> Esqueceu a senha?</a>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit" name="form-submit-login">Entrar</button>
                        </div>
                    </div>
                </form>
                <form class="form-horizontal" id="recoverform" action="#">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>Recuperar senha</h3>
                            <p class="text-muted">Digite seu e-mail e as instruções serão enviadas para você!</p>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" name="recover-password-email" type="text" required="" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
