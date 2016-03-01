<?php 

return [
	'visoftbasemodule' => [
		'templates' => [
			'sign-in' => 'visoft-base-module/authentication/sign-in',
		],
		'layouts' => [
			'sign-in' => 'layout/layout-blank',
		],
		'forms' => [
			'sign-in' => 'TinyCRM\Form\AuthenticationForm',
		],
		'redirects' => [
			'sign-in' => [
				'route' => 'tiny-crm'
			],
			'sign-out' => [
				'route' => 'sign-in'
			],
		],
	],
];