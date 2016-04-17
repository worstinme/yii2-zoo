<?php

return [  
	
	'category'=>[
		'label'=>'Категория',
		'type'=>'category',
		'refresh'=>true,
	],
	'name'=>[
		'label'=>'Наименование',
		'type'=>'textfield',
		'required'=>true,
	],
	'alias'=>[
		'label'=>'Псевдоним / slug для ссылки',
		'type'=>'alias',
		'related'=>'name',
	],
	'intro'=>[
		'label'=>'Вступление',
		'type'=>'textarea',
	],
	'content'=>[
		'label'=>'Содержание',
		'type'=>'textarea',
		'required'=>true,
	],
];