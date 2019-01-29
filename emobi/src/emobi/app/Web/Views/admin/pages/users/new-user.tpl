
<div id="page-wrapper">
    <div class="container-fluid"> 
       {BREADCRUMB} 
        
        <!--- Flash Messages ---> 
        {FLASH_MESSAGES}
        
		<div class="panel panel-default">
			<div class="panel-heading">Adicionar usuário
				<div class="panel-action">
					<div class="dropdown"> 
						<a class="dropdown-toggle" id="PanelActionDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="mdi mdi-dots-horizontal dots"></i></a>
						<ul class="dropdown-menu bullet dropdown-menu-right" aria-labelledby="PanelActionDropdown" role="menu">
							<li role="presentation"><a href="javascript:void(0)" role="menuitem">
								<i class="mdi mdi-help-circle-outline" aria-hidden="true"></i> Obter ajuda</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="panel-wrapper">
				<form action="/admin/users/new" method="post" data-toggle="validator" class="form-material">
					
					<input type="hidden" name="csrf_token" value="{CSRF_TOKEN}">
					
					<div class="panel-body">
                       <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="first-name" class="control-label"><span class="required">*</span> Nome</label>
                                    <input type="text" name="first-name" class="form-control" id="first-name" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="last-name" class="control-label"><span class="required">*</span> Sobrenome</label>
                                    <input type="text" name="last-name" class="form-control" id="last-name" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="control-label"><span class="required">*</span> Username (nome de usuário)</label>
                                    <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
                                    <div class="help-block with-errors" id="username-validate-status"></div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="control-label"><span class="required">*</span> E-mail</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="E-mail" required>
                                    <div class="help-block with-errors" id="user-email-validate-status"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email-confirm" class="control-label"><span class="required">*</span> Confirme o E-mail</label>
                                    <input type="email" name="email-confirm" class="form-control" id="email-confirm" placeholder="E-mail" required data-match="#email">
                                    <div class="help-block with-errors" id="user-email-validate-status"></div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                
                                <div class="form-group">
                                    <label class="control-label"><span class="required">*</span> Gênero</label>
                                    <div class="radio radio-primary">
                                        <input type="radio" name="gender" id="gender-male" value="male" required>
                                        <label for="gender-male"> Masculino </label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" name="gender" id="gender-female" value="female" required>
                                        <label for="gender-female"> Feminino </label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" name="gender" id="gender-other" value="other" required>
                                        <label for="gender-other"> Outro </label>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <label class="control-label"><span class="required">*</span> Tipo de usuário e Grupos</label>
                                <div class="treeview-user-groups slimscroll-right">
                                    <ul>
                                        <!-- BEGIN SUPERADMIN_GROUP -->
                                        <li>
                                            <div class="form-group m-b-0">
                                                <div class="radio radio-success">
                                                    <input name="role" id="user-role-superadmin" value="superadmin" type="radio" required>
                                                    <label for="user-role-superadmin"> Superadmin </label>
                                                </div> 
                                            </div>
                                        </li>
                                        <!-- END SUPERADMIN_GROUP -->
                                        <li>
                                            <div class="form-group m-b-0">
                                                <div class="radio radio-success">
                                                    <input name="role" id="user-role-admin" value="admin" type="radio" required>
                                                    <label for="user-role-admin"> Admin </label>
                                                </div> 
                                            </div>
                                        </li>
                            
                                        <li>
                                            <div class="form-group m-b-0">
                                                <div class="radio radio-success">
                                                    <input name="role" id="user-role-client" value="client" type="radio" required>
                                                    <label for="user-role-client"> Cliente</label>
                                                </div> 
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group m-b-0">
                                                <div class="radio radio-success">
                                                    <input name="role" id="user-role-demo" value="demo" type="radio" required>
                                                    <label for="user-role-demo"> Demo </label>
                                                </div> 
                                            </div> 
                                        </li>
                                        <li>
                                            <div class="form-group m-b-0">
                                                <div class="radio radio-success">
                                                    <input name="role" id="user-role-assistant" value="assistant" type="radio">
                                                    <label for="user-role-manager"> Personalizado</label>
                                                </div> 
                                            </div>
                                            <!-- BEGIN MANAGER_GROUP -->
                                            <ul class="m-t-0">
                                                <!-- BEGIN GROUPS_LIST -->
                                                <li>
                                                    <div class="checkbox checkbox-success">
                                                        <input name="groups[]" value="{GROUP_UUID}" id="{GROUP_NAME}" type="checkbox">
                                                        <label for="{GROUP_NAME}"> {GROUP_NAME}</label>
                                                    </div>
                                                </li>
                                                <!-- END GROUPS_LIST -->
                                            </ul>
                                            <!-- END MANAGER_GROUP -->
                                        </li>
                                    </ul>
                                    
                                </div>
                                <p class="m-t-5">Você pode criar subgrupos personalizados com permissões específicas para cada um! <a href='{URL_USERS_GROUPS_NEW}'>Fazer isso agora?</a></p>
                                <br>
                                

                            </div>
                        </div>
					</div>
					<div class="panel-footer">
                        <button type="submit" name="submit-form" value="{SUBMIT_ACTION}" class="btn btn-info waves-effect waves-light">SALVAR</button>
                    </div>
				</form>
			</div>
		</div><!--- /.panel --->
		
		
	</div><!--- /.fluid ---> 
</div>

