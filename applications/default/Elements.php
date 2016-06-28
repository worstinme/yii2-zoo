<?php

return [  
	
	'category'=>[
		'label'=>'Категория',
		'type'=>'category',
		'refresh'=>true,
	],
	'name'=>[
		'label'=>'Наименование',
		'type'=>'name',
		'required'=>true,
	],
	'alias'=>[
		'label'=>'Псевдоним / slug для ссылки',
		'type'=>'alias',
		'related'=>'name',
	],
	'data'=>[
		'label'=>'Дата',
		'type'=>'datepicker',
		'required'=>true,
	],
	'preview'=>[
		'label'=>'Изображение',
		'type'=>'image',
		'required'=>true,
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