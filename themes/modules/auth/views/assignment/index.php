<?php
/* @var $this AssignmentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    Yii::t('AuthModule.main', 'Assignments'),
);
?>

<?php
Yii::log($dataProvider->itemCount);
$data = $dataProvider->getData();

foreach ($data as $item) {
    Yii::log($item->username);
}
?>

<?= BsHtml::pageHeader(Yii::t('AuthModule.main', 'Assignments')) ?>
<div class="panel panel-default">
    <div class="panel-body container-fluid">

        <div class="row">
            <div class="col-lg-12">
            <?php $this->widget('bootstrap.widgets.BsGridView', [
                'dataProvider' => $dataProvider,
                'enableSorting' => false,
                'type' => BsHtml::GRID_TYPE_BORDERED,
                'emptyText' => Yii::t('AuthModule.main', 'No assignments found.'),
                //'template'=>'{view}',
                'columns' => [
                    [
                        'header' => Yii::t('AuthModule.main', 'User'),
                        'class' => 'AuthAssignmentNameColumn',
                    ],
                    [
                        'header' => Yii::t('AuthModule.main', 'Assigned items'),
                        'class' => 'AuthAssignmentItemsColumn',
                    ],
                    [
                        'class' => 'AuthAssignmentViewColumn',
                    ],                    
                ],
            ]); ?>
            </div>
        </div>
    </div>
</div>
<?php /*$this->widget(
    'bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped hover',
        'dataProvider' => $dataProvider,
        'emptyText' => Yii::t('AuthModule.main', 'No assignments found.'),
        'template' => "{items}\n{pager}",
        'columns' => array(
            array(
                'header' => Yii::t('AuthModule.main', 'User'),
                'class' => 'AuthAssignmentNameColumn',
            ),
            array(
                'header' => Yii::t('AuthModule.main', 'Assigned items'),
                'class' => 'AuthAssignmentItemsColumn',
            ),
            array(
                'class' => 'AuthAssignmentViewColumn',
            ),
        ),
    )
); */?>
