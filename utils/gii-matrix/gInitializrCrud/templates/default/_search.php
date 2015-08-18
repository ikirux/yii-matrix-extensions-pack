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
<div class="row">
    <div class="col-lg-5">
<?php echo "\t\t<?php \$form = \$this->beginWidget('bootstrap.widgets.BsActiveForm', [
            'action' => Yii::app()->createUrl(\$this->route),
            'method' => 'get',
        ]); ?>\n"; ?>
<?php foreach ($this->tableSchema->columns as $column): ?>
<?php
        $field = $this->generateInputField($this->modelClass, $column);
        if (strpos($field, 'password') !== false) {
            continue;
        }

        // Nos saltamos los campos de auditoria y uploads
        if ($column->name == $this->createAttribute ||
            $column->name == $this->createUser ||
            $column->name == $this->updateAttribute ||
            $column->name == $this->updateUser ||
            strpos($column->name, "up_") !== false
        ) {
            continue;
        }          
?>
<?php if ($column->dbType === 'date'): ?>
            <?php echo "<?php " . $this->generateDateControlGroup($this->modelClass, $column, 'search') . "; ?>\n"; ?>
<?php elseif(preg_match('/(.*)_id$/i', $column->name, $matches, PREG_OFFSET_CAPTURE)): ?>
<?php   $foreignModel = $this->normalizeModelName($matches[1][0]); ?>
<?php   if ($this->existModel($foreignModel)): ?>
            <?php echo "<?= " . $this->generateActiveControlGroup($this->modelClass, $column, 'search', $foreignModel) . "; ?>\n"; ?>
<?php   else: ?>
            <?php echo "<?= " . $this->generateEmptyList($this->modelClass, $column, 'search') . "; ?>\n"; ?>
<?php   endif; ?>
<?php else: ?>
            <?php echo "<?= " . $this->generateActiveControlGroup($this->modelClass, $column, 'search') . "; ?>\n"; ?>
<?php endif; ?>
<?php endforeach; ?>
    
            <div class="form-actions" style="padding-bottom: 1em;">
<?php if ($this->messageSupport): ?>
                <?php echo "<?= BsHtml::submitButton(Yii::t('default', 'Buscar'), ['color' => BsHtml::BUTTON_COLOR_PRIMARY,]);?>\n" ?>
<?php else: ?>
                <?php echo "<?= BsHtml::submitButton('Buscar', ['color' => BsHtml::BUTTON_COLOR_PRIMARY,]);?>\n" ?>
<?php endif; ?>
            </div>
<?php echo "\t\t<?php \$this->endWidget(); ?>\n"; ?>
    </div>
</div>