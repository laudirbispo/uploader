<div class="panel panel-default">
    <div class="panel-heading">Alterar senha
        <div class="panel-action">
            <div class="dropdown"> <a class="dropdown-toggle" id="PanelActionDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"> <i class="mdi mdi-dots-horizontal dots"></i> </a>
                <ul class="dropdown-menu bullet dropdown-menu-right" aria-labelledby="PanelActionDropdown" role="menu">
                    <li role="presentation"><a href="javascript:void(0)" role="menuitem"> <i class="mdi mdi-help-circle" aria-hidden="true"></i> Obter ajuda</a> </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="panel-wrapper">
        <div class="panel-body">
            <div class="col-md-6">
            <form action="{FORM_ACTION_UPDATE_PASSWORD}" method="post" id="form-update-password" enctype="application/x-www-form-urlencoded" data-toggle="validator" class="form-material">
                <input type="hidden" name="csrf_token" value="{CSRF_TOKEN}">
                <div class="form-group">
                    <label for="password-current" class="control-label"><span class="text-danger">*</span> Senha atual</label>
                    <input type="password" class="form-control" name="password-current" id="password-current" placeholder="Senha atual" maxlength="64" required>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group m-b-5">

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="input-password-new" class="control-label"><span class="text-danger">*</span> Nova senha</label>
                            <input type="password" name="password-new" data-minlength="8" maxlength="64" class="form-control" id="password-new" placeholder="Senha" data-control="input-view-password" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="password-confirm" class="control-label"><span class="text-danger">*</span> Confirme a senha</label>
                            <input type="password" class="form-control" name="password-confirm" id="password-confirm" data-match="#password-new" data-match-error="Whoops, campos não coincidem!" placeholder="Confirmar" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success btn-rounded waves-effect waves-light" id="generate-password" role="button" type="button">Gerar senha</button>
                <button type="button" class="btn btn-info waves-circle waves-effect waves-light pull-right" data-action="show-password" title="Mostrar/Ocultar senha" role="button"><i class="mdi mdi-eye"></i> </button>
                <div class="clearfix m-b-30"></div>
                <div>Força da senha: <div style="display:inline" id="password-strong-text"></div></div>
                <div class="progress progress-sm">
                    <div class="progress-bar progress-bar-info" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="" role="progressbar" id="progress-bar-password"> <span id="progress-bar-text-value" class=""></span> </div>
                </div>

            </form>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" form="form-update-password" name="submit-form" class="btn btn-info waves-effect waves-light">SALVAR</button>
        </div>
    </div>
</div><!--- /.panel ---> 







