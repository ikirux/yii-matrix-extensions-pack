<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
?>

<div class="container">
    <?= BsHtml::pageHeader('Dashboard'); ?>
    <?php $this->widget(
        'matrixAssets.ui.dashboard.DashBoard', 
        [
            'title' => 'Módulos Disponibles',
            'limitRow' => 6,
            'elements' => [
                [
                    'type' => 1,
                    'title' => 'Usuarios',
                    'icon' => 'fa-group',
                    'url' => $this->createUrl('user/user'),
                    'visible' => Yii::app()->user->checkAccess("user.access"),
                ],
                [
                    'type' => 2,
                    'title' => 'Auth',
                    'icon' => 'fa-lock',
                    'url' => $this->createUrl('auth/assignment'),
                    'visible' => Yii::app()->user->checkAccess("auth.access"),                    
                ],
                [
                    'type' => 3,
                    'title' => 'Traducciones',
                    'icon' => 'fa-font',
                    'url' => $this->createUrl('translate/sourceMessage'),
                    'visible' => Yii::app()->user->checkAccess("translate.access"),                    
                ],
                [
                    'type' => 4,
                    'title' => 'Administración',
                    'icon' => 'fa-cogs',
                    'url' => $this->createUrl('administracion/'),
                    'visible' => Yii::app()->user->checkAccess("administracion.access"),                    
                ],
                [
                    'type' => 6,
                    'title' => 'RRHH',
                    'icon' => 'fa-file-text-o ',
                    'url' => $this->createUrl('rrhh/'),
                    'visible' => Yii::app()->user->checkAccess("rrhh.access"),                    
                ],
                [
                    'type' => 5,
                    'title' => 'Activo Fijo',
                    'icon' => 'fa-barcode',
                    'url' => $this->createUrl('activofijo/'),
                    'visible' => Yii::app()->user->checkAccess("activofijo.access"),                    
                ],                
            ],
        ]
    ); ?>
</div>        
