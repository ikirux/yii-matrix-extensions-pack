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

if ($this->messageSupport):
echo "\$this->breadcrumbs = [
	Yii::t('default', \$this->pluralTitle) => ['index'],
	\$model->{$nameColumn},
];\n";
else:
echo "\$this->breadcrumbs = [
	\$this->pluralTitle => ['index'],
	\$model->{$nameColumn},
];\n";
endif;
?>

<?php if ($this->messageSupport): ?>
$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => Yii::t('default', 'Listar ' . $this->singularTitle), 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.index")],
	['icon' => 'glyphicon glyphicon-plus-sign', 'label' => Yii::t('default', 'Crear ' . $this->singularTitle), 'url' => ['create'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.create")],
	['icon' => 'glyphicon glyphicon-edit', 'label' => Yii::t('default', 'Actualizar ' . $this->singularTitle), 'url' => ['update', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.update")],
	['icon' => 'glyphicon glyphicon-minus-sign', 'label' => Yii::t('default', 'Borrar ' . $this->singularTitle), 'url' => '#', 'linkOptions' => ['submit'=> ['delete', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>], 'confirm' => Yii::t('default', 'Está seguro que desea borrar este elemento?')], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.delete")],
];
<?php else: ?>
$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => 'Listar ' . $this->singularTitle, 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.index")],
	['icon' => 'glyphicon glyphicon-plus-sign', 'label' => 'Crear ' . $this->singularTitle, 'url' => ['create'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.create")],
	['icon' => 'glyphicon glyphicon-edit', 'label' => 'Actualizar ' . $this->singularTitle, 'url' => ['update', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.update")],
	['icon' => 'glyphicon glyphicon-minus-sign', 'label' => 'Borrar ' . $this->singularTitle, 'url' => '#', 'linkOptions' => ['submit'=> ['delete', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>], 'confirm' => 'Está seguro que desea borrar este elemento?'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.delete")],
];
<?php endif; ?>
<?php if ($this->submenu) {
    echo "\n\$this->renderPartial('" . $this->submenu_path . "');\n";
} ?>
?>

<?php if ($this->messageSupport): ?>
<?php echo "<?php echo BsHtml::pageHeader(Yii::t('default', 'Ver ' . \$this->singularTitle)); ?>\n"; ?>
<?php else: ?>
<?php echo "<?php echo BsHtml::pageHeader('Ver ' . \$this->singularTitle); ?>\n"; ?>    
<?php endif; ?>
<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView', [
	'htmlOptions' => [
		'class' => 'table table-striped table-condensed table-hover',
	],
	'data' => $model,
	'attributes' => [
<?php
foreach ($this->tableSchema->columns as $column) {
    // Nos saltamos los campos de auditoria
    if ($column->name == $this->createAttribute ||
        $column->name == $this->createUser ||
        $column->name == $this->updateAttribute ||
        $column->name == $this->updateUser ||
        strpos($column->name, "up_machine") !== false
    ) {
        continue;
    }  	

    // Revisa si hay un modelo relacionado con el atributo nombre
    if ($relationName = $this->hasKeyAttributeRelated($column->name)) {
    	echo "\t\t[\n";
        echo "\t\t\t'name' => '$column->name',\n";
        echo "\t\t\t'type' => 'raw',\n";
        echo "\t\t\t'value' => \$model->" . $relationName . "->nombre,\n";
        echo "\t\t],\n";
        continue;
    }

    if (strpos($column->name, "up_") !== false) {
        $field = str_replace('up_', '', $column->name);
        if (in_array('image', explode(',', strtolower($column->comment)))) {
            echo "\t\t[
            'name' => 'up_". $field . "',
            'type' => 'raw',
            'value' => CHtml::link(CHTML::image(Yii::app()->request->baseUrl . \$model->up_machine_". $field . ", '', []), Yii::app()->request->baseUrl . \$model->up_machine_" . $field . ", [
                'target' => '_blank',
                'class' => 'thumbnail',
                'style' => 'width:14em;',                
            ]),
        ],\n";            
        } else {
            echo "\t\t[
            'name' => 'up_". $field . "',
            'type' => 'raw',
            'value' => CHtml::link(\$model->up_". $field . ", Yii::app()->request->baseUrl . \$model->up_machine_". $field . ", ['target' => '_blank']),
        ],\n";
        }
    } elseif (stripos($column->dbType, 'text') !== false) {
        echo "\t\t[\n";
        echo "\t\t\t'name' => '$column->name',\n";
        echo "\t\t\t'type' => 'ntext',\n";
        echo "\t\t],\n";
    } else {
    	echo "\t\t'" . $column->name . "',\n";
    }    
}
?>
	],
]); ?>