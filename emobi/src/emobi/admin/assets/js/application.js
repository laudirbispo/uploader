// JavaScript Document
'use strict';

// Desativa envio de forms com ENTER
$(document).ready(function () {
    $('input').keypress(function (e) {
        var code = null;
        code = (e.keyCode ? e.keyCode : e.which);                
        return (code === 13) ? false : true;
    });

});


/*
$(document).ready(function(){         

        var validNavigation = false;

        // Attach the event keypress to exclude the F5 refresh (includes normal refresh)
        $(document).bind('keypress', function(e) {
            if (e.keyCode == 116){
                validNavigation = true;
            }
        });

        // Attach the event click for all links in the page
        $("a").bind("click", function() {
            validNavigation = false;
        });

        // Attach the event submit for all forms in the page
        $("form").bind("submit", function() {
          validNavigation = true;
        });

        // Attach the event click for all inputs in the page
        $("input[type=submit]").bind("click", function() {
          validNavigation = true;
        }); 

        window.onpopstate= function(e) {
            e.preventDefault();
                swal("Deseja mesmo saifr?", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");
                
            
            
        };

  });

*/

!function($) {
    var SweetAlert = function() {};

    //examples 
    SweetAlert.prototype.init = function() {
    
    var controlForm = $('form');
    var forms_changes = controlForm.serialize(); 
    var evAllow = false;
 
    $("a").bind("click", function(e) {

        var link = this.href;

        if (this.getAttribute('role') === 'button') {
            evAllow = true;
        }
        
        if ((controlForm.serialize() !== forms_changes) && (this.getAttribute('role') !== 'button') ) {
           
               swal({   
                    title: "Sair da Página?",   
                    text: "Notamos que você fez algumas alterações na página e não as salvou. Deseja sair mesmo assim?",   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonText: "Ficar", 
                    cancelButtonText: "Sair", 
                    closeOnConfirm: true,   
                    closeOnCancel: false,
                }, function(isConfirm){   
                    if (isConfirm) {     
                        swal.close(); 
                        evAllow = false;
                    } else {     
                        window.location = link;
                    } 
                });

           } else {
                evAllow = true;
           }
        
        if (!evAllow) {
            e.preventDefault();
        } else {
            return;
        }
        console.log(e.currentTarget);
        return;

    });
    
   
    
     // console.log(event.explicitOriginalTarget.activeElement.href);
 
},
    //init
    $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing 
function($) {
    $.SweetAlert.init();
}(window.jQuery);

/*
 * transforma decimal em moeda
 * n = numero a converter
 * c = numero de casas decimais
 * d = separador decimal
 * t = separador milhar 
 */
function numeroParaMoeda(n, c, d, t)
{
    c = isNaN(c = Math.abs(c)) ? 2 : c, d = d === undefined ? "," : d, t = t === undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

/*
 * Contador de caracteres restantes
 */
$(function (){
    $('[data-control="count-chars"]').bind("input keyup paste", function (){
        var maximo = $(this).attr('maxlength');
        var disponivel = maximo - $(this).val().length;
        if(disponivel < 0) 
        {
            var texto = $(this).val().substr(0, maximo); 
            $(this).val(texto);
            disponivel = 0;
        }
        $(".restant-chars").html('<span class="text-danger">'+disponivel+ '</span> caracteres restantes');
    });
});

/*
 * Altera o tipo do input para ver a senha
 */


/*
// função universal para submit de formulários
$('[data-action="submit-ajax"]').validator().on('submit', function (e) {
  var thisForm = $(this); 
 
  if (e.isDefaultPrevented()) {
    // handle the invalid form...
  } 
  else 
  {
    e.preventDefault();
    var formDados = jQuery(this).serialize();
    var formUrl = thisForm.attr('action');
	var formReset = thisForm.attr('data-control');
    var buttonSubmit = thisForm.find(':submit');
	var btnReset = buttonSubmit.html();
    buttonSubmit.html('<i class="fa fa-spinner fa-spin"></i> Aguarde');
    buttonSubmit.prop('disabled', true);
    
    jQuery.ajax({
      type: "POST",
      async:true,
      cache:false,
      url: formUrl,
      data: formDados,
	  dataType: 'json',
      success: function(data)
      {  
		    if (data.status === 'success' && formReset === 'reset')
			{
				thisForm.each(function(){this.reset();});
			}
			$.toast({
				heading: data.title,
				text: data.message,
				position: 'top-right',
				loaderBg: '#ff6849',
				icon: data.status,
				textColor: '#FFFFFF',
				hideAfter: 8000,
				stack: 6
			});
      },
      error: function ()
      {
        	$.toast({
				heading: 'Oppss. Algo errado',
				text: 'Não foi possível concluir seu pedido.',
				position: 'top-right',
				loaderBg: '#ff6849',
				icon: 'error',
        		textColor: '#FFFFFF',
				hideAfter: 8000,
				stack: 6
			});
      }
       
    }); 
	  
	 setTimeout(function(){ 
		buttonSubmit.html(btnReset);
		buttonSubmit.prop('disabled', false);     
	 }, 5000);
	
    
  }
});

*/

/**
 * Requisição Ajax para gerar senhas

$(document).on('click', '#generate-password', function(){
	var inputPassword = $('#password-new');
	var inputPasswordConfirm = $('#password-confirm');
	
	$.post('/admin/account/generate-password', {}, function(data){
    	inputPassword.val(data);
		inputPasswordConfirm.val(data);
		var passVal = inputPassword.val();
		var password = new PasswordStrong(passVal, 70);
		
		$('#progress-bar-password').css('width', password.score()+'%');
		if (password.isValid())
		{
			$('#password-valid').html('<i class="fa fa-check text-success"></i>');
		}
		else
		{
			$('#password-valid').html('<i class="fa fa-times text-danger"></i>');
		}
  	}); 
});
 */

/*
$(document).ready(function(){
	$("#password-new").bind("input keyup paste change", function (){
		var passVal = $('#password-new').val();
		var password = new PasswordStrong(passVal, 70);
		
		$('#progress-bar-password').css('width', password.score()+'%');
		if (password.isValid())
		{
			$('#password-valid').html('<i class="fa fa-check text-success"></i>');
		}
		else
		{
			$('#password-valid').html('<i class="fa fa-times text-danger"></i>');
		}
	});
	
});
*/


