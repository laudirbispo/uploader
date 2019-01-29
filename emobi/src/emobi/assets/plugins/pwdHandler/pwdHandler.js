// JavaScript Document
"use strict";
// Define Class
var pwdHandler =  {
	validator: '[data-toggle="validator"]',
	input: '#password-new',
	inputMatch: '#password-confirm',
	btnShowPwd: '[data-action="show-password"]',
	btnGeneratePwd: '#generate-password',
	btnClassVisible: 'mdi-eye',
	btnClassInvisible: 'mdi-eye-off',
	barProgress: '#progress-bar-password',
	pwdStrongText: '#password-strong-text',
	maxChars: 16,
	minChars: 8,
	generatePassword: function (len) {
		
		if (len > this.maxChars || len < this.minChars) {return false;}
		
		var pwd = [],
			cc = String.fromCharCode,
			R = Math.random,
			rnd, 
			i;
		pwd.push(cc(48 + (0 || R() * 10))); // push a number
		pwd.push(cc(65 + (0 || R() * 26))); // push an upper case letter
		pwd.push(cc(33 + (0 || R() * 15))); // push an special chars

		for (i = 2; i < len; i++) 
		{
			rnd = 0 || R() * 62; // generate upper OR lower OR number
			pwd.push(cc(48 + rnd + (rnd > 9 ? 7 : 0) + (rnd > 35 ? 6 : 0)));
		}

		// shuffle letters in password
		return pwd.sort(function() { return R() - 0.5; }).join('');
	},
	getStrongPassword: function () {
		var score = 0;
		var len = $(this.input).val().length;
		var password = $(this.input).val();
		
		if (password.match(/[a-z]+/)) {score += 10;}
		if (password.match(/[A-Z]+/)) {score += 15;}
		if (password.match(/[0-9]/))  {score += 15;}
		if (password.match(/.*[-,),(,!,\\,/,@,#,$,%,^,&,*,+,.,?,_,~]/)) {score += 15;}
		if (len >= this.minChars && len <= this.maxChars) {score += 35;}
		if (len > this.maxChars) {score += 45;}
		if (password.match(/[ \t]+$/)) {score = 0;}

		return score;
	},
	updatePasswordStrongBar: function () 
	{
		var score, strongText, barProgressColor;
		score = this.getStrongPassword();
		strongText = '<span class="text-danger">Precisamos de uma senha forte... <i class="mdi mdi-window-close"></i></span>';
		barProgressColor = '#f44336';
		
		if (score === 0 )  {
			strongText = '<span class="text-danger">Inválida <i class="mdi mdi-window-close"></i></span>';
		}
		if (score !== 0 && score < 25 )  {
			strongText = '<span class="text-warning">Muito fraca <i class="mdi mdi-window-close"></i></span>';
			barProgressColor = '#ff9800';
		}
		if (score === 25) {
			strongText = '<span class="text-warning">Fraca <i class="mdi mdi-window-close"></i></span>';
			barProgressColor = '#ff9800';
		}
		if (score === 30 || score === 35) {
			strongText = '<span class="text-warning">Fraca <i class="mdi mdi-window-close"></i></span>';
			barProgressColor = '#ff9800';
		}
		if (score === 40 || score === 45) {
			strongText = '<span class="text-info">Podemos melhorar... <i class="mdi mdi-window-close"></i></span>';
			barProgressColor = '#1e88e5';
		}
		if (score === 50 || score === 55) {
			strongText = '<span class="text-primary">Quase bom <i class="mdi mdi-window-close"></i></span>';
			barProgressColor = '#707cd2';
		}
		if (score === 60 || score === 65) {
			strongText = '<span class="text-success">Bom <i class="mdi mdi-check"></i></span>';
			barProgressColor = '#4caf50';
		}
		if (score === 70 || score === 75) {
			strongText = '<span class="text-success">Muito bom <i class="mdi mdi-check"></i></span>';
			barProgressColor = '#4caf50';
		}
		if (score === 80 || score === 85) {
			strongText = '<span class="text-success">Forte <i class="mdi mdi-check-all"></i></span>';
			barProgressColor = '#4caf50';
		}
		if (score >= 90) {
			strongText = '<span class="text-success">Muito forte <i class="mdi mdi-check-all"></i></span>';
			barProgressColor = '#4caf50';
		}
		
		$(this.pwdStrongText).html(strongText);
		$(this.barProgress).css('width', score+'%');
		$(this.barProgress).css('background-color', barProgressColor);
	}
	
};

$(document).ready(function () { 
	
	// Show password function
	$(document).on('click', pwdHandler.btnShowPwd, function () {

		if( $(pwdHandler.input).attr('type') === 'password' )
		{
		  $(pwdHandler.input).attr('type', 'text'); 
		  $(this).find('i').removeClass(pwdHandler.btnClassVisible).addClass(pwdHandler.btnClassInvisible);
		}
		else
		{
		  $(pwdHandler.input).attr('type', 'password'); 
		  $(this).find('i').removeClass(pwdHandler.btnClassInvisible).addClass(pwdHandler.btnClassVisible);
		}
	});
	
	// Generate random password function
	$(document).on('click', pwdHandler.btnGeneratePwd, function () {
		var pass = pwdHandler.generatePassword(8);
		$(pwdHandler.input).val(pass);
		$(pwdHandler.inputMatch).val(pass);
		pwdHandler.updatePasswordStrongBar();
	});
	
	// Update progress bar function
	$(document).ready(function(){
		$(pwdHandler.input).bind("input keyup paste change", function (){
			pwdHandler.updatePasswordStrongBar();
			if (false !== pwdHandler.validator) {$(pwdHandler.validator).validator('update');}
		});
	});
	
});