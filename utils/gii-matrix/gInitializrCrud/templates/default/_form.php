<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */
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
    <div class="col-lg-5">
        <?php echo "<?= \$form->errorSummary(\$model); ?>\n"; ?>

<?php
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

    ?>
<?php if ($column->dbType === 'date'): ?>
        <?= "<?php " . $this->generateDateControlGroup($this->modelClass, $column, 'form') . "; ?>\n\n"; ?>
<?php elseif (strpos($column->name, "up_") !== false && strpos($column->name, "up_machine") === false): ?>
        <?= $this->generateUpload($this->modelClass, $column) . "\n"; ?>
<?php elseif(preg_match('/(.*)_id$/i', $column->name, $matches, PREG_OFFSET_CAPTURE)): ?>
<?php   $foreignModel = $this->normalizeModelName($matches[1][0]); ?>
<?php   if ($this->existModel($foreignModel)): ?>
    <?php echo "\t<?= " . $this->generateActiveControlGroup($this->modelClass, $column, 'form', $foreignModel) . "; ?>\n"; ?>
<?php   else: ?>
    <?php echo "\t<?= " . $this->generateEmptyList($this->modelClass, $column, 'form') . "; ?>\n"; ?>    
<?php   endif; ?>
<?php elseif (strpos($column->name, "up_machine") === false): ?>
        <?= "<?= " . $this->generateActiveControlGroup($this->modelClass, $column, 'form') . "; ?>\n"; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php if ($this->messageSupport): ?>
        <?php echo "<?= BsHtml::submitButton(Yii::t('default', 'Guardar'), ['color' => BsHtml::BUTTON_COLOR_PRIMARY]); ?>\n"; ?>
<?php else: ?>
        <?php echo "<?= BsHtml::submitButton('Guardar', ['color' => BsHtml::BUTTON_COLOR_PRIMARY]); ?>\n"; ?>    
<?php endif; ?>
    </div>
<?php echo "<?php \$this->endWidget(); ?>\n"; ?>