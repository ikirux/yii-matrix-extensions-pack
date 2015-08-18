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
$nameColumn = $this->guessNameColumn($this->tableSchema->columns);

if ($this->messageSupport) {
	echo "\$this->breadcrumbs = [
	\$this->pluralTitle => ['index'],
	\$model->{$nameColumn} => ['view', 'id' => \$model->{$this->tableSchema->primaryKey}],
	Yii::t('default', 'Actualizar'),
];\n\n";
} else {
	echo "\$this->breadcrumbs = [
	\$this->pluralTitle => ['index'],
	\$model->{$nameColumn} => ['view', 'id' => \$model->{$this->tableSchema->primaryKey}],
	'Actualizar',
];\n\n";
}	
?>
<?php if ($this->messageSupport): ?>
$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => Yii::t('default', 'Listar ' . $this->singularTitle), 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.index")],
	['icon' => 'glyphicon glyphicon-plus-sign', 'label' => Yii::t('default', 'Crear ' . $this->singularTitle), 'url' => ['create'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.create")],
    ['icon' => 'glyphicon glyphicon-list-alt', 'label' => Yii::t('default', 'Ver ' . $this->singularTitle), 'url' => ['view', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.view")],
];
<?php else: ?>
$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => 'Listar ' . $this->singularTitle, 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.index")],
	['icon' => 'glyphicon glyphicon-plus-sign', 'label' => 'Crear ' . $this->singularTitle, 'url' => ['create'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.create")],
    ['icon' => 'glyphicon glyphicon-list-alt', 'label' => 'Ver ' . $this->singularTitle, 'url' => ['view', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.view")],
];
<?php endif; ?>
<?php if ($this->submenu) {
    echo "\n\$this->renderPartial('" . $this->submenu_path . "');\n";
} ?>
?>

<?php if ($this->messageSupport): ?>
<?php echo "<?= BsHtml::pageHeader(Yii::t('default', 'Actualizar ' . \$this->singularTitle)) ?>\n"; ?>
<?php else: ?>
<?php echo "<?= BsHtml::pageHeader('Actualizar ' . \$this->singularTitle) ?>\n"; ?>
<?php endif; ?>
<?php echo "<?php \$this->renderPartial('_form', ['model' => \$model]); ?>"; ?>