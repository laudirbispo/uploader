<div id="page-wrapper">
    <div class="container-fluid"> 
		{BREADCRUMB}
        
        <!--- Flash Messages ---> 
        {FLASH_MESSAGES}
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 el-element-overlay">
                <div class="profile large m-b-30">
                    <div class="cover"> <img src="https://source.unsplash.com/random/700x300/daily"/>
                        <div class="layer">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="user-info">
                        <div class="profile-pic">
							<img src="{CURRENT_USER_PROFILE_PICTURE}"/>
                            <div class="layer">
                                <div class="loader"></div>
                            </div>
                            <a class="image-wrapper" href="#" role="button" data-toggle="modal" data-target="#cropit-profile-pic">
                            <label class="edit mdi mdi-camera" for="changePicture" type="file" title="Trocar de foto"></label>
                            </a> </div>
                        <div class="username">
                            <div class="name">{CURRENT_USER_NAME} {CURRENT_USER_LAST_NAME}</div>
                            <div class="about">Administrador</div>
                        </div>
                    </div>
                    <ul class="wallet-list">
                        <li>
                            <i class="mdi mdi-account-card-details"></i><a href="{URL_ACCOUNT_ABOUT}">Sobre Min</a>
                        </li>
                        <li><i class="mdi mdi-account-key"></i><a href="{URL_ACCOUNT_PASSWORD}">Segurança</a></li>
                        <li><i class="mdi mdi-lock"></i><a href="{URL_ACCOUNT_PERMISSIONS}">Permissões</a></li>
                        <li><i class="mdi mdi-settings"></i><a href="{URL_ACCOUNT_SETTINGS}">Configurações</a></li>
                        <li><i class="mdi mdi-information"></i><a href="javascript:void(0)">Info</a></li>
                        <li><i class="mdi mdi-exit-to-app"></i><a href="{URL_ADMIN_LOGOUT}">Sair</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8 col-sm-7 col-xs-12">
                
                {TAB_CONTENT}
                
            </div>
        </div>
        <!--- /.row ---> 
        
    </div>
    <!--- /.fluid ---> 
</div>
<div id="cropit-profile-pic" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Alterar foto de perfil</h4>
            </div>
            <div class="modal-body block-request">
				<div class="cropit">
					<div class="image-editor">
						<input type="file" id="cropit-image-input" class="cropit-image-input hidden">
						<div class="cropit-preview"></div>
						<div class="image-size-label"> </div>
						<input type="range" class="cropit-image-zoom-input">
						<div class="m-t-20">
							<label for="cropit-image-input">
							<a role="button" class="btn btn-primary waves-effect waves-light waves-circle m-r-10" title="Selecionar foto"><i class="mdi mdi-upload"></i></a>
							</label>
							<a role="button" class="btn btn-info waves-effect waves-light waves-circle m-r-10 rotate-ccw" title="Girar para a esquerda"><i class="mdi mdi-rotate-left"></i></a>
							<a role="button" class="btn btn-info waves-effect waves-light waves-circle m-r-10 rotate-cw" title="Girar para a direita"><i class="mdi mdi-rotate-right"></i></a>
							<a role="button" data-url="/test.php" data-csrf="{CSRF_TOKEN}" class="btn btn-success waves-effect waves-light waves-circle m-r-10 export" title="Salvar"><i class="mdi mdi-check"></i></a>
						</div>
					</div>
				</div>
				<div class="m-b-30 clearfix"></div>
            </div>
        
        </div>
        <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
</div>
