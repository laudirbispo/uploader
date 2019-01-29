// Theme color settings
$(document).ready(function () {
'use strict';
    function store(name, val) {
        if (typeof (Storage) !== "undefined") {
            localStorage.setItem(name, val);
        } else {
            window.alert('Please use a modern browser to properly view this template!');
        }
    }
	function get(name) {
		return localStorage.getItem(name);
	}
	
	var currentTheme = get('theme');

    $("*[data-theme]").click(function (e) {
        e.preventDefault();
        var currentStyle = $(this).attr('data-theme');
        store('theme', currentStyle);
        $('#theme').attr({
            href: '/themes/material/css/colors/' + currentStyle + '.css'
        });
    });

    
    if (currentTheme) {
        $('#theme').attr({
            href: '/themes/material/css/colors/' + currentTheme + '.css'
        });
		
		$('#themecolors li a').removeClass('working');
		$("[data-theme='"+currentTheme+"']").addClass('working');
    }
    // color selector
    $('#themecolors').on('click', 'a', function () {
        $('#themecolors li a').removeClass('working');
        $(this).addClass('working');
    });




    $("*[data-theme]").click(function (e) {
        e.preventDefault();
        var currentStyle = $(this).attr('data-theme');
        store('theme', currentStyle);
        $('#theme').attr({
            href: '/themes/material/css/colors/' + currentStyle + '.css'
        });
    });

     currentTheme = get('theme');
    if (currentTheme) {
        $('#theme').attr({
            href: '/themes/material/css/colors/' + currentTheme + '.css'
        });
    }
    // color selector
    $('#themecolors').on('click', 'a', function () {
        $('#themecolors li a').removeClass('working');
        $(this).addClass('working');
    });
});
