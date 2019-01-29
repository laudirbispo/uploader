<div id="page-wrapper">
    <div class="container-fluid"> 
		{BREADCRUMB} 

		<!--- Flash Messages ---> 
        {FLASH_MESSAGES}
        
        <div class="panel panel-default">
            <div class="panel-heading">Grupos de usuários
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
					<!-- BEGIN BUTTON_ADD_GROUPS -->
                    <div class=""> 
						<a href="{URL_ADD_GROUPS}" class="btn btn-primary waves-effect waves-light pull-right"  data-toggle="tooltip" data-original-title="Adicionar um grupo">
							<i class="mdi mdi-plus"></i>
						</a> 
					</div>
					<!-- END BUTTON_ADD_GROUPS -->
					
                    <div class="clearfix m-b-5"></div>
					
                    <!-- BEGIN TABLE_USER_GROUPS -->
                    <div class="table-responsive slimscroll-bottom">
                        <table id="demo-foo-addrow" class="table table-bordered m-t-10 table-hover custom-responsive-table table-striped contact-list" data-page-size="10">
                            <thead>
                                <tr>
                                    <th width="20%">Grupo</th>
                                    <th width="50%">Descrição</th>
									 <th width="10%">Status </th>
                                    <th width="15%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <!-- BEGIN COLS_GROUPS -->
                                <tr>
                                    <td>{GROUP_NAME}</td>
                                    <td>{GROUP_DESCRIPTION}</td>
									<td>{GROUP_STATUS}</td>
                                    <td>
                                        <a href="{URL_EDIT_PERMISSIONS}" class="btn btn-primary btn-outline waves-effect waves-light waves-circle m-r-5" data-toggle="tooltip" data-original-title="Editar permissões do grupo"><i class="mdi mdi-key"></i> </a> 
   
                                        <a href="{URL_EDIT_GROUP}" class="btn btn-success btn-outline waves-effect waves-light waves-circle m-r-5" data-toggle="tooltip" data-original-title="Editar grupo"><i class="mdi mdi-account-edit"></i> </a> 
                                    
									</td>
                                </tr>
                                <!-- END COLS_GROUPS -->
                                
                            </tbody>
                        </table>
                    </div> <!-- END TABLE_USER_GROUPS --> 
					
					<div class="alert alert-info">
						<h4><i class="mdi mdi-information-outline"></i> Não há grupos cadastrados até o momento. </h4>
						<p>Você pode criar grupos de usuários para lhe ajudar nas tarefas fornecendo a eles autorizações específicas dentro do sistema.</p><br>
						<a href="{URL_ADD_GROUPS}" class="btn btn-default btn-rounded btn-outline waves-effect waves-light" id="generate-password" >Criar um grupo</a>
					</div>
					<!-- FINALLY TABLE_USER_GROUPS -->
                    
                </div>
            </div>
        </div><!--- /.panel ---> 

    </div>
    <!--- /.fluid ---> 
</div>

