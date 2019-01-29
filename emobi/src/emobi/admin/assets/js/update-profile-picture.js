// JavaScript Document
$(function() {
	"use strict";
	var imageData;
	var oldImage = $('.profile-pic > img').attr("src");
	
	$('.image-editor').cropit({
	    exportZoom: 1,
	    imageBackground: true,
	    allowDragNDrop: false,
	    imageBackgroundBorderWidth: 20,
		onImageError: function() {
			$('div.block-request').block({
				message: 'Por favor! Selecione uma imagem válida.',
				css: {
					color: '#fff',
					border: '0px',
					backgroundColor: 'transparent'
				}
			});
			setTimeout(function () {
				resetCropit(imageData);
			}, 2000);
        }
	});

	$('.rotate-cw').click(function() {
	  $('.image-editor').cropit('rotateCW');
	});
	$('.rotate-ccw').click(function() {
	  $('.image-editor').cropit('rotateCCW');
	});

	$('.export').click(function() {
		var imageData = $('.image-editor').cropit('export');
	 	var csrf_token = $('.export').attr('data-csrf');
		var url = $('.export').attr('data-url');
	  	$.ajax({
            type: "POST",
			url: url,
            data: {image:imageData, csrf_token:csrf_token},
            beforeSend: function () {
                $('div.block-request').block({
					message: '<img src="/assets/loadings/loading-cylon.svg" height="50" alt="Loading "/><br>Um momento por favor...',
					css: {
						color: '#fff',
						border: '0px',
						backgroundColor: 'transparent'
					}
				});
            },
            success: function (data) {
				if (data.status_code === 200)
				{
					setTimeout(function () {
						resetCropit(imageData);
						$('#cropit-profile-pic').modal('hide');
					}, 2000);
				}
				else
				{
					$('div.block-request').block({
						message: data.status_msg,
						css: {
							color: '#fff',
							border: '0px',
							backgroundColor: 'transparent'
						}
					});
					resetCropit(oldImage);	
				}
                
            },
			error: function () {
				$('div.block-request').block({
					message: 'Não foi possível atender seu pedido. Tente novamente mais tarde!',
					css: {
						color: '#fff',
						border: '0px',
						backgroundColor: 'transparent'
					}
				});
				setTimeout(function () {
					resetCropit(oldImage);
				}, 2000);
			}
			
        });
		
	});
	
	function resetCropit (imageData)
	{
		$('div.block-request').unblock();
		$('.profile-pic > img').attr("src", imageData);
		$('.cropit-preview-image').removeAttr('src');
		$('.cropit-preview-background').removeAttr('src');
	}
	
});