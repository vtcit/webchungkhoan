<?php
return [
	'' => 'site/index',
	'media/upload/<type:[\w-]+>' => 'media/upload',
	'media/<type:(list|grid|modal)>' => 'media/index',
	'<controller:(category|page|post)>/<type:[\w-]+>' => '<controller>/index',
	'<controller:(category|page|post)>/<type:[\w-]+>/create' => '<controller>/create',
	'<controller:[\w-]+>/<action:[\w\-]+>/<id:[\w-]+>' => '<controller>/<action>',
	'<controller:(plan-user)>/activate/<id:[\w-]+>/<user_id:[\w-]+>' => '<controller>/activate',
	// '<controller:[\w\-]+>/<action:[\w\-]+>',
];