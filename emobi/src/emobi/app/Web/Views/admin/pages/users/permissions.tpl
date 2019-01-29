<div id="page-wrapper">
    <div class="container-fluid"> 
		{BREADCRUMB} 
        
        <!--- Flash Messages ---> 
        {FLASH_MESSAGES}
	
		<div class="row">
			<div class="col-md-8">

				<div class="panel panel-default">
					<div class="panel-heading">Nome do grupo: <span class="text-muted">{GROUP_NAME}</span>
						<div class="panel-action">
							<div class="dropdown"> <a class="dropdown-toggle" id="PanelActionDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"> <i class="mdi mdi-dots-horizontal dots"></i> </a>
								<ul class="dropdown-menu bullet dropdown-menu-right" aria-labelledby="PanelActionDropdown" role="menu">
									<li role="presentation"><a href="javascript:void(0)" role="menuitem"> <i class="mdi mdi-help-circle-outline" aria-hidden="true"></i> Obter ajuda</a> </li>
								</ul>
							</div>
						</div>
                        
					</div>
					<div class="panel-wrapper">
						<div class="panel-body">
							<!-- BEGIN TABLE_PERMISSIONS -->
							<div class="table-responsive slimscroll-bottom">
								<table id="table-permissions" class="table t-p-5 table-bordered table-hover m-t-10 custom-responsive-table contact-list">
									<thead>
										<tr>
											<th colspan="1">Função</th>
											<th colspan="2">Status</th>
										</tr>
									</thead>
									<tbody>
										
										<form action="" method="post" enctype="application/x-www-form-urlencoded" id="form-permissions" data-control="submit-form" name="form-permissions" class="form-control">
											<input type="hidden" name="csrf.token" value="{CSRF_TOKEN}">
											<!-- BEGIN BLOCK_RESOURCES_GROUP -->
											<tr class="group-parent active">
												<td colspan="3"><b>{RESOURCE_GROUP}</b></td>
											</tr>
											<!-- BEGIN BLOCK_RESOURCE -->
											<tr class="group-children">
												<td class="p-l-20">
                                                    <i class="mdi mdi-chevron-double-right t12"></i>{RESOURCE_DESCRIPTION}
                                                </td>
												<td>
                                                    <input class="js-switch" type="checkbox" name="permissions[{RESOURCE}]" {CHECKED}>
												</td>
											</tr>
											<!-- END BLOCK_RESOURCE -->

										<!-- END BLOCK_RESOURCES_GROUP -->
										</form>
									</tbody>
								</table>
							</div> 
							<!-- END TABLE_PERMISSIONS -->
							
							<div class="alert alert-info">
								<h4>Olá! Não foi possível carregar a lista de permissões.</h4>
								<p>Estamos realmente tristes por isso! 
                                </p>
							</div>
							<!-- FINALLY TABLE_PERMISSIONS -->
							
						</div>
						<div class="panel-footer"> 
							<button type="submit" name="submit-form" value="form-permissions" form="form-permissions" class="btn btn-info waves-effect waves-light">SALVAR</button>
						</div>
					</div>
				</div><!--- /.panel ---> 

			</div><!--- /.col --->
			
			<div class="col-md-4">
				
				<div class="panel panel-default">
					<div class="panel-heading">Legenda</div>
					<div class="panel-wrapper">
						<div class="panel-body">
							<p>Nosso sistema de permissões é baseado em atividades.</p>
                            <p>
                                <img src="/admin/assets/images/allowed.png" height="20">
                                Atividade permitida
                            </p>
                            <p>
                                <img src="/admin/assets/images/denied.png" height="20">
                                Atividade negada
                            </p>
						</div>
					</div>
				</div><!--- /.panel ---> 
				
			</div><!--- /.col --->
			
		</div><!--- /.row --->
		
	</div><!--- /.fluid --->
</div><!--- /.wrapper --->
