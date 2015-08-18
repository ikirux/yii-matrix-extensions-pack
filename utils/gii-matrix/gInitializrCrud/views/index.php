<?php
$class = get_class($model);
Yii::app()->clientScript->registerScript(
    'gii.crud',
    "
$('#{$class}_controller').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('#{$class}_model').bind('keyup change', function(){
	var controller=$('#{$class}_controller');
    var singular=$('#{$class}_singular');
    var plural=$('#{$class}_plural');
	if(!controller.data('changed')) {
		var id=new String($(this).val().match(/\\w*$/));
		if(id.length>0) {
            singular.val(id);
            plural.val(id);
			id=id.substring(0,1).toLowerCase()+id.substring(1);
        }
		controller.val(id);
	}
});
"
);
?>
    <h1>Bootstrap Generator</h1>

    <p>This generator generates a controller and views that implement CRUD operations for the specified data model.</p>

<?php $form = $this->beginWidget('CCodeForm', array('model' => $model)); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'model'); ?>
        <?php echo $form->textField($model, 'model', array('size' => 65)); ?>
        <div class="tooltip">
            Model class is case-sensitive. It can be either a class name (e.g. <code>Post</code>)
            or the path alias of the class file (e.g. <code>application.models.Post</code>).
            Note that if the former, the class must be auto-loadable.
        </div>
        <?php echo $form->error($model, 'model'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'controller'); ?>
        <?php echo $form->textField($model, 'controller', array('size' => 65)); ?>
        <div class="tooltip">
            Controller ID is case-sensitive. CRUD controllers are often named after
            the model class name that they are dealing with. Below are some examples:
            <ul>
                <li><code>post</code> generates <code>PostController.php</code></li>
                <li><code>postTag</code> generates <code>PostTagController.php</code></li>
                <li><code>admin/user</code> generates <code>admin/UserController.php</code>.
                    If the application has an <code>admin</code> module enabled,
                    it will generate <code>UserController</code> (and other CRUD code)
                    within the module instead.
                </li>
            </ul>
        </div>
        <?php echo $form->error($model, 'controller'); ?>
    </div>
<?php echo get_class($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'generatePDF'); ?>
        <?php echo $form->checkBox($model, 'generatePDF', ['value' => 1]); ?>
        <div class="tooltip">
            Genera una opción que permite exportar los datos a PDF
        </div>
        <?php echo $form->error($model, 'generatePDF'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'generateExcel'); ?>
        <?php echo $form->checkBox($model, 'generateExcel', ['value' => 1]); ?>
        <div class="tooltip">
            Genera una opción que permite exportar los datos a Excel
        </div>
        <?php echo $form->error($model, 'generateExcel'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'messageSupport'); ?>
        <?php echo $form->checkBox($model, 'messageSupport', ['value' => 1]); ?>
        <div class="tooltip">
            Se le aplica la función t() a todas las cadenas de los templates
        </div>
        <?php echo $form->error($model, 'messageSupport'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'submenu'); ?>
        <?php echo $form->checkBox($model, 'submenu', ['value' => 1]); ?>
        <div class="tooltip">
            Permite incorporar una vista submenu, previamente definida
        </div>
        <?php echo $form->error($model, 'submenu'); ?>
    </div>    

    <div class="row sticky">
        <?php echo $form->labelEx($model, 'submenu_path'); ?>
        <?php echo $form->textField($model, 'submenu_path', array('size' => 65)); ?>
        <div class="tooltip">
            En caso de que se agregue un submenu, se utilizara esta ruta para la incrustación de la vista
        </div>
        <?php echo $form->error($model, 'submenu_path'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'singular'); ?>
        <?php echo $form->textField($model, 'singular', array('size' => 65)); ?>
        <div class="tooltip">
            Permite definir el nombre en singular de los títulos que son utilizados en la generación 
            de las vistas
        </div>
        <?php echo $form->error($model, 'singular'); ?>        
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'plural'); ?>
        <?php echo $form->textField($model, 'plural', array('size' => 65)); ?>
        <div class="tooltip">
            Permite definir el nombre en plural de los títulos que son utilizados en la generación 
            de las vistas
        </div>
        <?php echo $form->error($model, 'plural'); ?>        
    </div>

    <div class="row sticky">
        <?php echo $form->labelEx($model, 'baseControllerClass'); ?>
        <?php echo $form->textField($model, 'baseControllerClass', array('size' => 65)); ?>
        <div class="tooltip">
            This is the class that the new CRUD controller class will extend from.
            Please make sure the class exists and can be autoloaded.
        </div>
        <?php echo $form->error($model, 'baseControllerClass'); ?>
    </div>

<?php $this->endWidget(); ?>