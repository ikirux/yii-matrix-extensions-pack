<?php
/* @var $this DefaultController */

$this->breadcrumbs = [
	'Ejemplos prácticos',
];

Yii::app()->clientScript->registerScript('help', "
$('.tutorial-title').click(function(event) {
	var titleObject = $(event.target);
	$('#' + titleObject.attr('tutorial-related')).toggleClass('hidden');
	return false;
});
");
?>
<h1>Listado de Ejemplos (Recetas)</h1>

<h2 class="tutorial-title" tutorial-related="listas-constantes">Listas de Constantes</h2>
<div id="listas-constantes" class="hidden">
<p>1) Primero definimos las constantes en el modelo a utilizar:</p>
<code><pre>
class Servidor extends CActiveRecord
{
	const ESTADO_HABILITADO = 1;
	const ESTADO_DESHABILITADO = 2;

	// El resto del código
}	
</pre></code>
<p>
2) Luego definimos dos métodos en el modelo, uno para obtener el listado de los valores asociados a las constantes, y otro para obtener el 
valor particular del atributo relacionado a la constante:
</p>
<code><pre>
class Servidor extends CActiveRecord
{
	// El resto del código

	public function getEstadoOptionList()
	{
		return [
			self::ESTADO_HABILITADO => 'Habilitado',
			self::ESTADO_DESHABILITADO => 'Deshabilitado',
		];
	}

	public function getEstado()
	{
		$data = $this->getEstadoOptionList();

		return isset($data[$this->estado_id]) ? $data[$this->estado_id] : 'Sin Valor';
	}
}	
</pre></code>
<p>
3) Ahora ya podemos utilizar estos métodos en las listas:
</p>
<code><pre>
// Vista _form.php
&lt;?= $form->dropDownListControlGroup($model, 'estado_id', Servidor::model()->getEstadoOptionList(), [
    'prompt' => $this->getPrompt(),
]); ?>

// Vista view.php
[
	'name' => 'estado_id',
	'type' => 'raw',
	'value' => $model->getEstado(),
],

// Vista index.php
[
	'name' => 'estado_id',
	'type' => 'raw',
	'value' => '$data->getEstado()',
],
</pre></code>
</div>
<h2 class="tutorial-title" tutorial-related="listas-cargas-ajax">Carga de Listas Enlazadas con Ajax</h2>
<div id="listas-cargas-ajax" class="hidden">
<p>
1) En este ejemplo supondremos que tenemos un modelo Funcionario que está relacionado con una comuna, y esta a su vez relacionada con una región. 
Primero definimos un atributo que contendra la referencia a la región:</p>
<code><pre>
class Funcionario extends CActiveRecord
{
	// Lo definimos de forma manual ya que no existe en la tabla funcionario (solo existe la referencia de comuna_id)
	public <b>$region_id</b>;
}	
</pre></code>
<p>2) Agregamos la referencia de region en las rules correspondientes:</p>
<code><pre>
class Funcionario extends CActiveRecord
{
	// El resto del código

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['..., <b>region_id</b>, comuna_id', 'required'],
			['<b>region_id</b>, comuna_id, ...', 'numerical', 'integerOnly' => true],
		],	
	}
}	
</pre></code>
<p>3) Agregamos la eqtiqueta relacionada al atributo:</p>
<code><pre>
class Funcionario extends CActiveRecord
{
	// El resto del código

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			...
			'<b>region_id</b>' => 'Región',
			...
		];
	}
}	
</pre></code>
<p>4) Agregamos el atributo a las vistas:</p>
<code><pre>
// Vista _form.php
&lt;?= $form->dropDownListControlGroup($model, 'region_id', Region::model()->getOptionList(), [
    'prompt' => $this->getPrompt(),
    'ajax' => [
        'type' => 'post',
        'update' => '#' . CHtml::activeId($model, 'comuna_id'),
        'url' => CController::createUrl('funcionario/getComuna'),
        'data' => ['region_id' => 'js:this.value'],
    ],
]); ?>
&lt;?= $form->dropDownListControlGroup($model, 'comuna_id', Comuna::model()->getOptionList(), [
    'prompt' => $this->getPrompt(),
]); ?>

// Vista view.php
[
	'name' => 'region_id',
	'type' => 'raw',
	'value' => $model->r_comuna->r_region->nombre,
],

// Vista index.php
[
	'name' => 'region_id',
	'type' => 'raw',
	'value' => '$data->r_comuna->r_region->nombre',
],
</pre></code>
<p>5) Ahora Agregamos al controlador de funcionario (FuncionarioController), el método que enviará las el listado
de comunas cuando se seleccione una región:</p>
<code><pre>
class FuncionarioController extends Controller
{
	// El resto del código

    public function actionGetComuna() 
    {
    	if (Yii::app()->request->isAjaxRequest) {
	        $criteria = new CDbCriteria;
	        $criteria->compare('region_id', (int)$_POST['region_id']);
	        $criteria->order = 'nombre';

	        $models = Comuna::model()->findAll($criteria);
	        $data = CHtml::listData($models, 'id', 'nombre');

	        echo CHtml::tag('option', ['value' => ''], $controller->getPrompt(), true);
	        foreach ($data as $value => $name) {
	            echo CHtml::tag('option', ['value' => $value], CHtml::encode($name), true);
	        }      
        }
    }
}
</pre></code>
<p>6) Finalmente en la acción update del controlador de Funcionario, realizamos una pequeña modificación
para que el atributo region_id sea cargado al momento de mostrar el formulario de edición:</p>
de comunas cuando se seleccione una región:</p>
<code><pre>
class FuncionarioController extends Controller
{
	// El resto del código

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Funcionario'])) {
                    ...
		} <b>else {
			$model->region_id = $model->r_comuna->region_id;
		}</b>

		$this->render('update', [
			'model' => $model,
		]);
	}
}
</pre></code>
</div>