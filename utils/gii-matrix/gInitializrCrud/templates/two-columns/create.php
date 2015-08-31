<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */

// El prefijo de la operacion
$opTemplate = $this->prefixPermission();
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
<?php echo "?>\n"; ?>

<?php
echo "<?php\n";

if ($this->messageSupport) {
	echo "\$this->breadcrumbs = [
	Yii::t('default', \$this->pluralTitle) => ['index'],
	Yii::t('default', 'Crear'),
];

\$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => Yii::t('default', 'Listar ' . \$this->pluralTitle), 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess('$opTemplate.index')],
];\n";	
} else {
	echo "\$this->breadcrumbs = [
	\$this->pluralTitle => ['index'],
	'Crear',
];

\$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => 'Listar ' . \$this->pluralTitle, 'url' => ['$opTemplate.index']],
];\n";
}
if ($this->submenu) {
	echo "\n\$this->renderPartial('" . $this->submenu_path . "');\n";
}
echo "?>\n";
?>

<?php if ($this->messageSupport): ?>
<?php echo "<?= BsHtml::pageHeader(Yii::t('default', 'Crear ' . \$this->singularTitle)) ?>\n"; ?>
<?php else: ?>
<?php echo "<?= BsHtml::pageHeader('Crear ' . \$this->singularTitle) ?>\n"; ?>
<?php endif; ?>

<?php echo "<?php \$this->renderPartial('_form', ['model' => \$model]); ?>"; ?>