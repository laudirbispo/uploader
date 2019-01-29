<div id="page-wrapper">
    <div class="container-fluid"> 
		{BREADCRUMB} 

        <!--- Flash Messages ---> 
        {FLASH_MESSAGES}
		<div class="panel panel-default">
            <div class="panel-heading">{PAGE}
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
					
					<form class="" action="{FORM_ACTION}" method="post" id="form-add-groups" data-toggle="validator">
						<input type="hidden" name="csrf_token" value="{CSRF_TOKEN}">
						<input type="hidden" name="group-id" value="{GROUP_ID}">
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="group-name" class="control-label"><span class="required">*</span> Nome do Grupo</label>
									<input type="text" name="group-name" value="{GROUP_NAME}" class="form-control" id="group-name" data-minlenght="2" maxlength="20" required>
									<div class="help-block with-errors"></div>
								</div>
                                <div class="form-group">
                                    <div class="checkbox checkbox-success">
                                        <input id="group-status" name="group-status" type="checkbox" {GROUP_STATUS}>
                                        <label for="group-status">Marque esta opção caso deseje bloquear as funções deste grupo.</label>
                                    </div>
                                </div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="group-description" class="control-label">Descrição</label>
									<textarea name="group-description" data-control="count-chars" id="group-description" class="form-control" maxlength="256">{GROUP_DESCRIPTION}</textarea>
									<div class="restant-chars text-right"></div>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>

					</form>
					
				</div>
				<div class="panel-footer">
					<button type="submit" form="form-add-groups" name="submit-form" value="{SUBMIT_ACTION}" class="btn btn-info waves-effect waves-light">SALVAR</button>
				</div>
            </div>
        </div><!--- /.panel --->
	
	</div><!--- /.fluid --->
</div><!--- /.wrapper --->
