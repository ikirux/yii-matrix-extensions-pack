<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $data <?php echo $this->getModelClass(); ?> */
<?php echo "?>\n"; ?>

<div class="view">

<?php
echo "\t<b><?= CHtml::encode(\$data->getAttributeLabel('{$this->tableSchema->primaryKey}')); ?>:</b>\n";
echo "\t<?= CHtml::link(CHtml::encode(\$data->{$this->tableSchema->primaryKey}), ['view', 'id' => \$data->{$this->tableSchema->primaryKey}]); ?>\n\t<br />\n\n";
$count = 0;
foreach ($this->tableSchema->columns as $column) {
    if ($column->isPrimaryKey) {
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

    if (++$count == 7) {
        echo "\t<?php /*\n";
    }
    echo "\t<b><?= CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?>:</b>\n";
    echo "\t<?= CHtml::encode(\$data->{$column->name}); ?>\n\t<br />\n\n";
}
if ($count >= 7) {
    echo "\t*/ ?>\n";
}
?>

</div>