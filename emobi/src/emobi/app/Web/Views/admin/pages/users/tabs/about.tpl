<div class="panel panel-default">
    <div class="panel-heading">Sobre Min
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
        
            <form class="form-material" action="#" method="post" id="form-update-profile" data-toggle="validator" enctype="application/x-www-form-urlencoded">
                <input type="hidden" name="csrf_token" value="{CSRF_TOKEN}">

                <div class="form-group">
                    <label for="user-name" class="control-label"><span class="required">*</span> Nome</label>
                    <input type="text" name="user-first-name" maxlength="50" value="{USER_FIRST_NAME}" class="form-control" id="user-name" required>
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group">
                    <label for="user-last-name" class="control-label"><span class="required">*</span> Sobrenome</label>
                    <input type="text" name="user-last-name" maxlength="60" value="{USER_LAST_NAME}" class="form-control" id="user-last-name" required>
                    <div class="help-block with-errors"></div>
                </div>
                
                <div class="form-group">
                    <label for="textarea" class="control-label">Biografia</label>
                    <textarea id="textarea" name="user-about" data-control="count-chars" class="form-control" style="min-height:150px;" maxlength="1024">{USER_ABOUT}</textarea> 
                    <div class="help-block with-errors restant-chars"></div> 
                </div>
 
                <div class="form-group">
                    <label class="control-label"><span class="required">*</span> Gênero</label>
                    <div class="radio radio-primary">
                        <input type="radio" name="user-gender" id="gender-male" value="male" required {GENDER_MALE}>
                        <label for="gender-male"> Masculino</label>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" name="user-gender" id="gender-female" value="female" required {GENDER_FEMALE}>
                        <label for="gender-female"> Feminino</label>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" name="user-gender" id="gender-female" value="other" required {GENDER_OTHER}>
                        <label for="gender-female"> Outro</label>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>

            </form>

        </div>
        <div class="panel-footer">
            <button type="submit" form="form-update-profile" name="submit-form" class="btn btn-info waves-effect waves-light">SALVAR</button>
        </div>
    </div>
</div><!--- /.panel ---> 