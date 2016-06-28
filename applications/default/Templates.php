<?php

return [  

	'form'=>[
		'rows'=>[
			[	
				'items'=>[
					['element'=>'category'],
					['element'=>'name'],
					['element'=>'alias'],
					['element'=>'data'],
					['element'=>'preview'],
					['element'=>'intro'],
					['element'=>'content'],
				],
			],
		],
	],
	'full'=>[
		'rows'=>[
			[	
				'items'=>[
					['element'=>'name'],
					['element'=>'data'],
					['element'=>'preview'],
					['element'=>'intro'],
					['element'=>'content'],
				],
			],
		],
	],
	'teaser'=>[
		'rows'=>[
			[	
				'items'=>[
					['element'=>'name','params'=>['asUrl'=>true,'tag'=>'h2']],
					['element'=>'intro'],
					['element'=>'data'],
				],
			],
		],
	],

];