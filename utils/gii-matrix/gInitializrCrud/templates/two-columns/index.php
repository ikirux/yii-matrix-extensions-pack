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

<?php
if ($this->messageSupport) {
echo "\$this->breadcrumbs = [
    Yii::t('default', \$this->pluralTitle) => ['index'],
    Yii::t('default', 'Listar'),
];\n\n";
} else {
echo "\$this->breadcrumbs = [
    \$this->pluralTitle => ['index'],
    'Listar',
];\n\n";
}

?>
<?php if ($this->messageSupport): ?>
$this->menu = [
    ['icon' => 'glyphicon glyphicon-plus-sign', 'label' => Yii::t('default', 'Crear ' . $this->singularTitle), 'url' => ['create'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.create")],
<?php if ($this->generatePDF): ?>
    ['icon' => 'glyphicon glyphicon-book', 'label' => Yii::t('default', 'Generar archivo PDF'), 'url' => ['listPDF'], 'linkOptions' => ['target' => '_blank'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.listPdf")],
<?php endif; ?>
<?php if ($this->generateExcel): ?>
    ['icon' => 'glyphicon glyphicon-floppy-save', 'label' => Yii::t('default', 'Generar archivo Excel'), 'url' => ['listExcel'], 'linkOptions' => ['target' => '_blank'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.listExcel")],
<?php endif; ?>
];
<?php else: ?>
$this->menu = [
    ['icon' => 'glyphicon glyphicon-plus-sign', 'label' => 'Crear ' . $this->singularTitle, 'url' => ['create'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.create")],
<?php if ($this->generatePDF): ?>
    ['icon' => 'glyphicon glyphicon-book', 'label' => 'Generar archivo PDF', 'url' => ['listPDF'], 'linkOptions' => ['target' => '_blank'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.listPdf")],
<?php endif; ?>
<?php if ($this->generateExcel): ?>
    ['icon' => 'glyphicon glyphicon-floppy-save', 'label' => 'Generar archivo Excel', 'url' => ['listExcel'], 'linkOptions' => ['target' => '_blank'], 'visible' => Yii::app()->user->checkAccess("<?= $opTemplate ?>.listExcel")],
<?php endif; ?>
];
<?php endif; ?>

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#<?php echo $this->class2id($this->modelClass); ?>-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});");
<?php if ($this->submenu) {
    echo "\n\$this->renderPartial('" . $this->submenu_path . "');\n";
} ?>
?>

<?php if ($this->messageSupport): ?>
<?php echo "<?= BsHtml::pageHeader(Yii::t('default', 'Listar ' . \$this->pluralTitle)); ?>\n"; ?>
<?php else: ?>
<?php echo "<?= BsHtml::pageHeader('Listar ' . \$this->pluralTitle) ?>\n"; ?>
<?php endif; ?>    
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
<?php if ($this->messageSupport): ?>
            <?php echo "<?= BsHtml::button(Yii::t('default', 'Búsqueda Avanzada'), ['class' => 'search-button', 'icon' => BsHtml::GLYPHICON_SEARCH, 'color' => BsHtml::BUTTON_COLOR_PRIMARY], '#'); ?>"; ?>
<?php else: ?>
            <?php echo "<?= BsHtml::button('Búsqueda Avanzada', ['class' => 'search-button', 'icon' => BsHtml::GLYPHICON_SEARCH, 'color' => BsHtml::BUTTON_COLOR_PRIMARY], '#'); ?>"; ?>    
<?php endif; ?>    
        </h3>
    </div>
    <div class="panel-body container-fluid">

        <div class="row">
            <div class="search-form col-lg-12" style="display:none">
                <?php echo "<?php \$this->renderPartial('_search', [
                    'model' => \$model,
                ]); ?>\n"; ?>
            </div>
        </div>
        <!-- search-form -->

        <div class="row">
            <div class="col-lg-12">
            <?php echo "<?php"; ?> $this->widget('bootstrap.widgets.BsGridView', [
    			'id' => '<?php echo $this->class2id($this->modelClass); ?>-grid',
    			'dataProvider' => $model->search(),
                'enableSorting' => false,
                'type' => BsHtml::GRID_TYPE_BORDERED,
    			'columns' => [
<?php
            $count = 0;
            foreach ($this->tableSchema->columns as $column) {
                // Nos saltamos los campos de auditoria y de uploads
                if ($column->name == $this->createAttribute ||
                    $column->name == $this->createUser ||
                    $column->name == $this->updateAttribute ||
                    $column->name == $this->updateUser ||
                    strpos($column->name, "up_") !== false
                ) {
                    continue;
                }
                
                // Revisa si hay un modelo relacionado con el atributo nombre
                if ($relationName = $this->hasKeyAttributeRelated($column->name)) {
                    echo "\t\t\t\t\t[\n";
                    echo "\t\t\t\t\t\t'name' => '$column->name',\n";
                    echo "\t\t\t\t\t\t'type' => 'raw',\n";
                    echo "\t\t\t\t\t\t'value' => '\$data->" . $relationName . "->nombre',\n";
                    echo "\t\t\t\t\t],\n";
                    continue;
                }

                echo "\t\t\t\t\t'" . $column->name . "',\n";    
            }
            if ($count >= 7) {
                echo "\t\t*/\n";
            }
            ?>
    				[
    					'class' => 'bootstrap.widgets.BsButtonColumn',
                        'afterDelete' => 'function(link, success, data){ if(success) $("#statusMsg").html(data); }',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => [
                                'visible' => 'Yii::app()->user->checkAccess("<?= $opTemplate ?>.view")',                              
                            ],                        
                            'update' => [
                                'visible' => 'Yii::app()->user->checkAccess("<?= $opTemplate ?>.update")',  
                            ],
                            'delete' => [
                                'visible' => 'Yii::app()->user->checkAccess("<?= $opTemplate ?>.delete")',                              
                            ],
                        ],                        
    				],
    			],
            ]); ?>
            </div>
        </div>
    </div>
</div>