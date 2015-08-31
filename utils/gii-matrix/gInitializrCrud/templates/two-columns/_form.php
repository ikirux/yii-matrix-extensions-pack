<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */

// Primero contamos las columnas y descontando las que no generan controles
$sumaColumnas = 0;
$elementosPorColumna = 0;
foreach ($this->tableSchema->columns as $column) {
    // Nos saltamos los campos de auditoria
    if (!($column->autoIncrement ||
        $column->name == $this->createAttribute ||
        $column->name == $this->createUser ||
        $column->name == $this->updateAttribute ||
        $column->name == $this->updateUser ||
        strpos($column->name, "up_machine") !== false)
    ) {
        $sumaColumnas++;
    }
}
$elementosPorColumna = round($sumaColumnas / 2);
define('_TABS_FORM_', 3);
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form BSActiveForm */
<?php echo "?>\n"; ?>

<?php echo "<?php \$form = \$this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'id' => '" . $this->class2id($this->modelClass) . "-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
]); ?>\n"; ?>
<?php if ($this->messageSupport): ?>
    <p class="help-block"><?= '<?= Yii::t(\'default\', \'Campos con\'); ?>' ?> <span class="required">*</span> <?= '<?= Yii::t(\'default\', \'son requeridos.\'); ?>' ?></p>
<?php else: ?>
    <p class="help-block">Campos con <span class="required">*</span> son requeridos.</p>
<?php endif; ?> 
    <?php echo "<?= \$form->errorSummary(\$model); ?>\n"; ?>

    <div class="row">
<?php
$id_columna = 0;
foreach ($this->tableSchema->columns as $column) :
    if ($column->autoIncrement) {
        continue;
    }

    // Nos saltamos los campos de auditoria
    if ($column->name == $this->createAttribute ||
        $column->name == $this->createUser ||
        $column->name == $this->updateAttribute ||
        $column->name == $this->updateUser
    ) {
        continue;
    }    

    if ($id_columna == 0) {
        echo "\t\t<div class=\"col-md-5\">\n";
    } elseif ($id_columna == $elementosPorColumna) {
        echo "\t\t</div>
        <div class=\"col-md-5\">\n";
    }

    $id_columna++;
    ?>
<?php if ($column->dbType === 'date'): ?>
<?= $this->generateDateControlGroup($column, _TABS_FORM_) . "\n"; ?>
<?php elseif (strpos($column->name, "up_") !== false && strpos($column->name, "up_machine") === false): ?>
<?= $this->generateUpload($this->modelClass, $column, _TABS_FORM_) . "\n"; ?>
<?php elseif(preg_match('/(.*)_id$/i', $column->name, $matches, PREG_OFFSET_CAPTURE)): ?>
<?php   $foreignModel = $this->normalizeModelName($matches[1][0]); ?>
<?php   if ($this->existModel($foreignModel)): ?>
<?= $this->generateActiveControlGroup($this->modelClass, $column, _TABS_FORM_, $foreignModel) . "\n"; ?>
<?php   else: ?>
<?= $this->generateEmptyList($column, _TABS_FORM_) . "\n"; ?>
<?php   endif; ?>
<?php elseif (strpos($column->name, "up_machine") === false): ?>
<?= $this->generateActiveControlGroup($this->modelClass, $column, _TABS_FORM_) . "\n"; ?>
<?php endif; ?>
<?php endforeach; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
<?php if ($this->messageSupport): ?>
            <?php echo "<?= BsHtml::submitButton(Yii::t('default', 'Guardar'), ['color' => BsHtml::BUTTON_COLOR_PRIMARY]); ?>"; ?>
<?php else: ?>
            <?php echo "<?= BsHtml::submitButton('Guardar', ['color' => BsHtml::BUTTON_COLOR_PRIMARY]); ?>"; ?>    
<?php endif; ?>
        </div>
    </div>      
<?php echo "<?php \$this->endWidget(); ?>\n"; ?>