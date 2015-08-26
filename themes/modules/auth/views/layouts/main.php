<?php
/* @var $this Controller */

$items = [];
if (Yii::app()->user->isGuest) {
    $items = BsHtml::linkButton('Ingresar', [
        'url' => [
            '/user/login',
        ],
        'type' => BsHtml::BUTTON_TYPE_NAVBARBUTTON,
        'color' => BsHtml::BUTTON_COLOR_SUCCESS,
    ]);
} else {
    $items = [
        'label' => '(' . Yii::app()->user->name . ')',
        'url' => [
            '/site/index'
        ],
        'items' => [
            [
                'label' => 'Ver Perfil',
                'url' => [
                    '/user/profile'
                ],
                'icon' => BsHtml::GLYPHICON_USER
            ],
            [
                'label' => 'Cambiar Contraseña',
                'url' => [
                    '/user/profile/changepassword'
                ],
                'icon' => BsHtml::GLYPHICON_LOCK
            ],  
            [
                'label' => 'Salir',
                'url' => [
                    '/user/logout'
                ],
                //'icon' => BsHtml::GLYPHICON_OFF
                'icon' => 'fa fa-cog fa-spin'
            ],
        ]
    ];        
}    

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="<?= Yii::app()->params['description']; ?>">
        <meta name="viewport" content="width=device-width">        
        <title><?= CHtml::encode($this->pageTitle); ?></title>
        <?php
        $cs = Yii::app()->clientScript;
		$themePath = Yii::app()->theme->baseUrl;

		/**
		 * StyleSHeets
		 */
		$cs
    		->registerCssFile($themePath . '/assets/css/bootstrap.min.css')
    		->registerCssFile($themePath . '/assets/css/bootstrap-theme.min.css')
    		->registerCssFile($themePath . '/assets/css/main.css');

		/**
		 * JavaScripts
		 */
		$cs
		    ->registerCoreScript('jquery', CClientScript::POS_END)
		    ->registerCoreScript('jquery.ui', CClientScript::POS_END)
		    ->registerScriptFile($themePath . '/assets/js/vendor/bootstrap.min.js', CClientScript::POS_END)
		    ->registerScriptFile($themePath . '/assets/js/plugins.js', CClientScript::POS_END)
		    ->registerScriptFile($themePath . '/assets/js/main.js', CClientScript::POS_END)
		    ->registerScript('tooltip',
        		"$('[data-toggle=\"tooltip\"]').tooltip();
        		$('[data-toggle=\"popover\"]').tooltip()"
        		, CClientScript::POS_READY);		
		?>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		    <script src="<?= Yii::app()->theme->baseUrl ?>/assets/js/html5shiv.js"></script>
		    <script src="<?= Yii::app()->theme->baseUrl ?>/assets/js/respond.min.js"></script>
		<![endif]-->

        <!--[if lt IE 9]>
            <script src="<?= Yii::app()->theme->baseUrl ?>/assets/js/vendor/html5-3.6-respond-1.1.0.min.js"></script>
        <![endif]-->
      
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

    <div class="container">

        <?php
        $this->widget('bootstrap.widgets.BsNavbar', [
            'collapse' => true,
            'brandLabel' => CHtml::encode($this->pageTitle),
            'color' => BsHtml::NAVBAR_COLOR_INVERSE,
            'position' => BsHtml::NAVBAR_POSITION_FIXED_TOP,
            'items' => [
                [
                    'class' => 'bootstrap.widgets.BsNav',
                    'type' => 'navbar',
                    'activateParents' => true,
                    'items' => [
                        [
                            'label' => 'Inicio',
                            'url' => [
                                '/site/index',
                            ]
                        ],
                        [
                            'label' => 'Acerca de',
                            'url' => [
                                '/site/page',
                                'view' => 'about'
                            ]
                        ],
                        [
                            'label' => 'Contacto',
                            'url' => [
                                '/site/contact',
                            ]
                        ],
                    ]
                ],
                [
                    'class' => 'bootstrap.widgets.BsNav',
                    'type' => 'navbar',
                    'activateParents' => true,
                    'items' => [$items],
                    'htmlOptions' => [
                        'pull' => BsHtml::NAVBAR_NAV_PULL_RIGHT,
                        'color' => BsHtml::BUTTON_COLOR_SUCCESS,
                    ]
                ]
            ]
        ]); ?>
    </div>

    <?php if (isset($this->breadcrumbs)): ?>
        <?php $this->widget('bootstrap.widgets.BsBreadcrumb', [
            'links' => $this->breadcrumbs,
            // will change the container to ul
            'tagName' => 'ul',
            // will generate the clickable breadcrumb links
            'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
            // will generate the current page url : <li>News</li>
            'inactiveLinkTemplate' => '<li>{label}</li>',
            // will generate your homeurl item : <li><a href="/dr/dr/public_html/">Home</a></li>
            'homeLink' => BsHtml::openTag('li') . BsHtml::openTag('a', ['href' => Yii::app()->createUrl('site/index')]) . BsHtml::icon(BsHtml::GLYPHICON_HOME) . BsHtml::closeTag('a') . BsHtml::closeTag('li')
        ]);
        ?>        
    <?php endif; ?>

    <?php echo $content; ?>  

    <hr>

    <div class="container">
        <footer class="well">
            <p>Copyright &copy; <?php echo date('Y'); ?> Mi Empresa.<br/>
    		    Todos los derechos reservados.<br/></p>
        </footer>
    </div> <!-- /container -->   

    </body>
</html>