<?php declare(strict_types=1);

/**
 * ======= ATENÇÃO ========
 *
 * A Estrutura a seguir deve ser rigorasamente seguida.
 */

return [
	// jQueryConfirm plugin
	'jQueryConfirm' => [ 
		'name' => 'jQueryConfirm', 
		'version' => 'v3.3.0',
		'license' => 'MIT http://opensource.org/licenses/MIT',
		'copyright' => '2013-2017',
		'authors' => ['names' => ['Boniface Pereira'],
						   'contacts' => ['hey@craftpip.com']
						  ],
		'website' => 'craftpip.com',
		'files' => [
            'css' => [
                '/assets/plugins/jQueryConfirm/jquery-confirm.min.css' => 'rel="stylesheet"',
            ],
			'js'  => [
                '/assets/plugins/jQueryConfirm/jquery-confirm.min.js' => 'async defer',
            ],						   
		],
		'info' => 'Um plugin do jQuery que oferece um excelente conjunto de recursos, Auto-close, Ajax-loading, Temas, Animações e muito mais.',
	],
	

	'BootstrapValidator' => [
		'name' => 'BootstrapValidator', 
		'version' => 'v0.10.2',
		'license' => 'MIT http://opensource.org/licenses/MIT',
		'copyright' => '2016 Cina Saffary',
		'authors' => ['names' => ['@1000hz'],
						   'contacts' => ['']
						  ],
		'website' => 'https://github.com/1000hz/bootstrap-validator',
		'files' => [
            'js' => [
                '/assets/plugins/bootstrap-validator/dist/validator.min.js' => 'async defer',
            ]
		],
		'info' => 'The Validator plugin offers automatic form validation configurable via mostly HTML5 standard attributes. It also provides an unobtrusive user experience, because nobody likes a naggy form.',
	],
	
	// Other plugin
	'Cropit' => [ 
		'name' => 'Cropit Profile Picture', 
		'version' => '0.5.1',
		'license' => 'MIT http://opensource.org/licenses/MIT',
		'copyright' => 'unknown',
		'authors' => ['names' => ['Scott Cheng'],
					  'contacts' => ['me@scottcheng.com']
					 ],
		'website' => 'https://github.com/scottcheng/cropit',
		'files' => [
            'css' => [
                '/assets/plugins/Cropit/cropit.css' => 'rel="stylesheet"',
            ],
			'js' => [
                '/assets/plugins/Cropit/cropit.js' => 'async defer',
			]
		],
		'info' => 'Customizable crop and zoom image.',
	],
	
	'blockUI' => [ 
		'name' => 'jQuery blockUI', 
		'version' => '2.70.0-2014.11.23',
		'license' => 'Dual licensed under the MIT and GPL licenses',
		'copyright' => 'Copyright (c] 2007-2013 M. Alsup',
		'authors' => ['names' => ['Scott Cheng'],
						   'contacts' => ['me@scottcheng.com']
						  ],
		'website' => 'http://malsup.com/jquery/block/',
		'files' => [
            'js' => [
                '/assets/plugins/blockUI/jquery.blockUI.min.js' => 'async defer',
             ],
									   
		],
		'info' => 'The jQuery BlockUI Plugin lets you simulate synchronous behavior when using AJAX, without locking the browser.',
	],
	
	'pwdHandler' => [ 
		'name' => 'pwdHandler', 
		'version' => '1.0.0',
		'license' => 'MIT http://opensource.org/licenses/MIT',
		'copyright' => 'Copyright (c] 2018 Laudir Bispo',
		'authors' => ['names' => ['Laudir Bispo'],
						   'contacts' => ['laudirbispo@outlook.com']
						  ],
		'website' => 'https://www.facebook.com/laudirbispo',
		'files' => [
            'js' => [
                '/assets/plugins/pwdHandler/pwdHandler.min.js' => 'async defer',
            ],							 
		],
		'info' => 'The jQuery BlockUI Plugin lets you simulate synchronous behavior when using AJAX, without locking the browser.',
	],
	
	// Other plugin
	'Switchery' => [ 
		'name' => 'Switchery', 
		'version' => '0.8.2',
		'license' => 'MIT http://opensource.org/licenses/MIT',
		'copyright' => 'unknown',
		'authors' => ['names' => ['Alexander Petkov'],
					  'contacts' => ['abpetkov@gmail.com']
					 ],
		'website' => 'http://abpetkov.github.io/switchery/',
		'files' => [
            'css' => [
                '/assets/plugins/switchery/switchery.min.css' => 'rel="stylesheet"',
            ],   			
            'js' => [
                '/assets/plugins/switchery/switchery.min.js' => ''
            ],
		],
		'info' => 'Switchery is a simple component that helps you turn your default HTML checkbox inputs into beautiful iOS 7 style switches in just few simple steps.',
	],
	
	'sweetalert' => [ 
		'name' => 'Sweetalert', 
		'version' => '1.0',
		'license' => 'undefined',
		'copyright' => 'unknown',
		'authors' => ['names' => ['Tristan Edwards'],
					  'contacts' => ['abpetkov@gmail.com']
					 ],
		'website' => 'https://sweetalert.js.org/',
		'files' => [
            'css' => [
                '/assets/plugins/sweetalert/sweetalert.min.css' => 'rel="stylesheet"',
            ],   			
            'js' => [
                '/assets/plugins/sweetalert/sweetalert.min.js' => ''
            ],
		],
		'info' => 'A beautiful replacement for messages.',
	],
];
