<div id="page-wrapper">
    <div class="container-fluid">

        {BREADCRUMB}
        
        <!--- Flash Messages --->
        {FLASH_MESSAGES}
        
        <div class="panel panel-default">
			<div class="panel-heading">Lista de usuários
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
				<div class="panel-body">
					
					<div class="row">
						<div class="col-md-12 m-b-15">
							<a href="{URL_USERS_GROUPS}" class="btn btn-primary waves-effect waves-light pull-right m-l-10" title="Editar grupos de usuários e permissões.">
								<i class="mdi mdi-account-multiple"></i>
							</a>
							<a href="{URL_USERS_NEW}" class="btn btn-success waves-effect waves-light pull-right" title="Adicionar usuários.">
								<i class="mdi mdi-account-plus m-r-5"></i>
							</a>
				            <div class="clearfix"></div>
						</div>						
					</div> 
                    <!-- BEGIN TABLE_USERS -->
					<div class="table-responsive slimscroll-bottom">
						<table id="demo-foo-addrow" class="table m-t-30 table-bordered table-hover custom-responsive-table table-striped contact-list" data-page-size="10">
							<thead>
								<tr>
									<th width="20%">Nome</th>
									<th width="15%">Username</th>
									<th width="15%">Email</th>
									<th width="15%">Papel/Funções</th>
									<th width="10%">Status</th>
									<th width="25%">Ações</th>
								</tr>
							</thead>
							<tbody>

								<!-- BEGIN COLS_USERS -->
								<tr>
									<td>
										<a href="{URL_USER_PROFILE}">
											<img src="{USER_PROFILE_PICTURE}" alt="user" class="img-circle"/> 
											{USER_NAME} {USER_LAST_NAME}
										</a>
									</td>
                                    <td><b>{USER_USERNAME}</b></td>
									<td>{USER_EMAIL}</td>
									<td>{USER_ROLE_LABEL}</td>
									<td>
										<!-- BEGIN USER_STATUS_ACTIVE -->
										<span class="label label-success">Ativo</span>
										<!-- END USER_STATUS_ACTIVE -->
										<!-- BEGIN USER_STATUS_INACTIVE -->
										<span class="label label-danger">Inativo</span>
										<!-- END USER_STATUS_INACTIVE -->
										<!-- BEGIN USER_STATUS_UNKNOWN -->
										<span class="label label-warning">Desconhecido</span>
										<!-- END USER_STATUS_UNKNOWN -->
									</td>
                                    
									<td >										
										<a role="button" href="{URL_DELETE_USER}" class="btn btn-success waves-effect waves-light btn-xs m-r-15" data-toggle="tooltip" data-original-title="Editar informações"><i class="mdi mdi-account-edit"></i></a>
										<a role="button" data-user_id="{USER_ID}" class="btn btn-primary waves-effect waves-light btn-xs m-r-15" data-toggle="tooltip" data-original-title="Bloquear conta"><i class="mdi mdi-account-off"></i> </a>
										<div class="btn-group m-r-10">
                                            <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info dropdown-toggle waves-effect waves-light" type="button">Status <span class="caret"></span></button>
                                            <ul role="menu" class="dropdown-menu">
                                                <li><a href="#">Ativar</a></li>
                                                <li><a href="#">Suspender</a></li>
                                                <li><a href="#">Bloquear</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Desativar</a></li>
                                            </ul>
                                        </div>
									</td>
								</tr>
								<!-- END COLS_USERS -->

							</tbody>
						</table>
					</div>
                    <!-- END TABLE_USERS -->
                    <div class="alert alert-info">
                        <h4><i class="mdi mdi-information-outline"></i> Não há usuários cadastrados até o momento. </h4>
                        <p>Você pode adicionar usuários para lhe ajudar nas tarefas fornecendo a eles autorizações específicas dentro do sistema.</p><br>
                        <a href="/admin/users/new" class="btn btn-default btn-rounded btn-outline waves-effect waves-light">Adicionar usuários</a>
                    </div>
                    <!-- FINALLY TABLE_USERS -->
				</div>
			</div>

            
            
		</div><!--- PANEL --->

	</div><!--- /.fluid --->
</div>
