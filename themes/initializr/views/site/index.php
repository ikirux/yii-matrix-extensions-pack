<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
?>

<div class="container">
    <?= BsHtml::pageHeader('Dashboard'); ?>
    <?php $this->widget(
        'ext.ui.dashboard.DashBoard', 
        [
            'title' => 'Módulos Disponibles',
            'limitRow' => 6,
            'elements' => [
                [
                    'type' => 1,
                    'title' => 'Usuarios',
                    'icon' => 'fa-group',
                    'url' => $this->createUrl('user/user'),
                ],
                [
                    'type' => 2,
                    'title' => 'Auth',
                    'icon' => 'fa-lock',
                    'url' => $this->createUrl('auth/assignment'),
                ],
                [
                    'type' => 3,
                    'title' => 'Traducciones',
                    'icon' => 'fa-font',
                    'url' => $this->createUrl('translate/sourceMessage'),
                ],
                [
                    'type' => 4,
                    'title' => 'Administración',
                    'icon' => 'fa-cogs',
                    'url' => $this->createUrl('administracion/'),
                ],
                [
                    'type' => 5,
                    'title' => 'Docencia',
                    'icon' => 'fa-university',
                    'url' => $this->createUrl('docencia/'),
                ],
                [
                    'type' => 6,
                    'title' => 'RRHH',
                    'icon' => 'fa-file-text-o ',
                    'url' => $this->createUrl('rrhh/'),
                ],
                [
                    'type' => 5,
                    'title' => 'Activo Fijo',
                    'icon' => 'fa-archive',
                    'url' => $this->createUrl('activofijo/'),
                ],
                [
                    'type' => 1,
                    'title' => 'ADM. Bodega',
                    'icon' => 'fa-briefcase',
                    'url' => $this->createUrl('bodega/'),
                ],
                /*[
                    'type' => 1,
                    'title' => 'Fin. Alumno',
                    'icon' => 'fa-dollar',
                    'url' => $this->createUrl('finanzas/'),
                ],
                [
                    'type' => 2,
                    'title' => 'Tesis',
                    'icon' => 'fa-graduation-cap',
                    'url' => $this->createUrl('tesis/'),
                ],
                [
                    'type' => 3,
                    'title' => 'Presupuesto',
                    'icon' => 'fa-bar-chart-o',
                    'url' => $this->createUrl('presupuesto/'),
                ],*/
            ],
        ]
    ); ?>
</div>        