<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */

$existRutField = false;
$existUploadFields = false;
foreach ($this->tableSchema->columns as $column) {
	if ($column->name == 'rut') {
		$existRutField = true;
	} 	

	if (strpos($column->name, "up_") !== false) {
		$existUploadFields = true;
	}
}

$modelElementName = strtolower($this->modelClass);
$modelTarget = new $this->modelClass;
$attributesModel = $modelTarget->attributeLabels();

?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass . "\n"; ?>
{
	/**
	* @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	* using two-column layout. See 'protected/views/layouts/column2.php'.
	*/
	public $layout = '//layouts/column2';

	/**
	* @var string texto en singular utilizado en los titulos de las vistas
	*/
	public $singularTitle = '<?php echo $this->singular; ?>';

	/**
	* @var string texto en plural utilizado en los titulos de las vistas
	*/
	public $pluralTitle = '<?php echo $this->plural; ?>';

<?php if ($existUploadFields): ?>
	public function actions()
    {
        return [
            'upload' => [
                'class' => 'ext.actions.MatrixUploadAction',
                'path' => Yii::app()->getBasePath() . "/../uploads/tmp/<?php echo $this->tableSchema->name; ?>",
                'publicPath' => "/../uploads/final/<?php echo $this->tableSchema->name; ?>",
                'formClass' => '<?php echo $this->modelClass; ?>',
            ],
        ];
    }

<?php endif; ?>
<?php if ($existRutField): ?>
	public function behaviors()
	{
		return [
			'rutBehavior' => ['class' => 'ext.behaviors.RutBehavior']
     	];
	}

<?php endif; ?>
	/**
	* @return array action filters
	*/
	public function filters()
	{
		return [
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		];
	}

	/**
	* Specifies the access control rules.
	* This method is used by the 'accessControl' filter.
	* @return array access control rules
	*/
	public function accessRules()
	{
		return [
			['allow',
				'users' => ['@'],
			],
			['deny',  // deny all users
				'users' => ['*'],
			],
		];
	}

	/**
	* Displays a particular model.
	* @param integer $id the ID of the model to be displayed
	*/
	public function actionView($id)
	{
		$this->render('view', [
			'model' => $this->loadModel($id),
		]);
	}

	/**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
<?php if ($existUploadFields == false): ?>
	public function actionCreate()
	{
		$model = new <?php echo $this->modelClass; ?>;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
			$model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
			if ($model->save()) {
<?php if ($this->messageSupport): ?>
				Yii::app()->user->setFlash('success', Yii::t('default', 'La operación se realizó con éxito'));
<?php else: ?>
				Yii::app()->user->setFlash('success', 'La operación se realizó con éxito');
<?php endif; ?>
				$this->redirect(['view', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>]);
			} else {
<?php if ($this->messageSupport): ?>
				Yii::app()->user->setFlash('error', Yii::t('default', 'Se ha producido un error al realizar la operación'));
<?php else: ?>
				Yii::app()->user->setFlash('error', 'Se ha producido un error al realizar la operación');
<?php endif; ?>
			}
		}

		$this->render('create', [
			'model'	=> $model,
		]);
	}
<?php elseif ($existUploadFields == true): ?>
	public function actionCreate()
	{
		$model = new <?php echo $this->modelClass; ?>;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
			$model->attributes = $_POST['<?php echo $this->modelClass; ?>'];

			$transaction = Yii::app()->db->beginTransaction();

			try {
	            if ($model->save()) {
	                $transaction->commit();
<?php if ($this->messageSupport): ?>
					Yii::app()->user->setFlash('success', Yii::t('default', 'La operación se realizó con éxito'));
<?php else: ?>
					Yii::app()->user->setFlash('success', 'La operación se realizó con éxito');
<?php endif; ?>
					$this->redirect(['view', 'id' => $model->id]);	                
	            }
	        } catch(Exception $e) {
	            $transaction->rollback();
<?php if ($this->messageSupport): ?>
					Yii::app()->user->setFlash('error', Yii::t('default', 'Se ha producido un error al realizar la operación'));
<?php else: ?>
					Yii::app()->user->setFlash('error', 'Se ha producido un error al realizar la operación');
<?php endif; ?>
	            Yii::app()->handleException($e);
	        }
		}

		$this->render('create', [
			'model'	=> $model,
		]);
	}
<?php endif; ?>

	/**
	* Updates a particular model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param integer $id the ID of the model to be updated
	*/
<?php if ($existUploadFields == false): ?>
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
			$model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
			if ($model->save()) {
<?php if ($this->messageSupport): ?>
				Yii::app()->user->setFlash('success', Yii::t('default', 'La operación se realizó con éxito'));
<?php else: ?>
				Yii::app()->user->setFlash('success', 'La operación se realizó con éxito');
<?php endif; ?>
				$this->redirect(['view', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>]);
			} else {
<?php if ($this->messageSupport): ?>
				Yii::app()->user->setFlash('error', Yii::t('default', 'Se ha producido un error al realizar la operación'));
<?php else: ?>
				Yii::app()->user->setFlash('error', 'Se ha producido un error al realizar la operación');
<?php endif; ?>
			}
		}

		$this->render('update', [
			'model' => $model,
		]);
	}
<?php elseif ($existUploadFields == true): ?>
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
			$model->attributes = $_POST['<?php echo $this->modelClass; ?>'];

			$transaction = Yii::app()->db->beginTransaction();

			try {
	            if ($model->save()) {
	                $transaction->commit();
<?php if ($this->messageSupport): ?>
					Yii::app()->user->setFlash('success', Yii::t('default', 'La operación se realizó con éxito'));
<?php else: ?>
					Yii::app()->user->setFlash('success', 'La operación se realizó con éxito');
<?php endif; ?>
					$this->redirect(['view', 'id' => $model->id]);	                
	            }
	        } catch(Exception $e) {
	            $transaction->rollback();
<?php if ($this->messageSupport): ?>
					Yii::app()->user->setFlash('error', Yii::t('default', 'Se ha producido un error al realizar la operación'));
<?php else: ?>
					Yii::app()->user->setFlash('error', 'Se ha producido un error al realizar la operación');
<?php endif; ?>
	            Yii::app()->handleException($e);
	        }
		}		

		$this->render('update', [
			'model' => $model,
		]);
	}
<?php endif; ?>

	/**
	* Deletes a particular model.
	* If deletion is successful, the browser will be redirected to the 'admin' page.
	* @param integer $id the ID of the model to be deleted
	*/
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			try {
			    $this->loadModel($id)->delete();
			    if (!isset($_GET['ajax'])) {
<?php if ($this->messageSupport): ?>
					Yii::app()->user->setFlash('success', Yii::t('default', 'La operación se realizó con éxito'));
<?php else: ?>
					Yii::app()->user->setFlash('success', 'La operación se realizó con éxito');
<?php endif; ?>
			    } else {
<?php if ($this->messageSupport): ?>
					echo $this->showFlashMessage('success', Yii::t('default', 'La operación se realizó con éxito'));
<?php else: ?>
					echo $this->showFlashMessage('success', 'La operación se realizó con éxito');
<?php endif; ?>
			    }
			} catch(CDbException $e) {
			    if (!isset($_GET['ajax'])) {
<?php if ($this->messageSupport): ?>
					Yii::app()->user->setFlash('error', Yii::t('default', 'Se ha producido un error al realizar la operación'));
<?php else: ?>
					Yii::app()->user->setFlash('error', 'Se ha producido un error al realizar la operación');
<?php endif; ?>
			    } else {
<?php if ($this->messageSupport): ?>
					echo $this->showFlashMessage('error', Yii::t('default', 'Se ha producido un error al realizar la operación'));
<?php else: ?>
					echo $this->showFlashMessage('error', 'Se ha producido un error al realizar la operación');
<?php endif; ?> 	
			    }
			}

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
			}
		} else {
<?php if ($this->messageSupport): ?>
			throw new CHttpException(400, Yii::t('default', 'Request no válido.'));
<?php else: ?>
			throw new CHttpException(400, 'Request no válido.');
<?php endif; ?>
		}
	}

	/**
	* Lists all models.
	*/
	public function actionIndex()
	{
		$model = new <?php echo $this->modelClass; ?>('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['<?php echo $this->modelClass; ?>'])) {
			$model->attributes = $_GET['<?php echo $this->modelClass; ?>'];
			Yii::app()->session['searchAttributes'] = $_GET['<?php echo $this->modelClass; ?>'];
		} else {
			unset(Yii::app()->session['searchAttributes']);
		}

		$this->render('index', [
			'model' => $model,
		]);		
	}

<?php if ($this->generatePDF): ?>
	public function actionListPdf()
	{
		$pdf = Yii::createComponent('YiiPDF', 'L');

		// set document information
		$pdf->SetCreator(Yii::app()->name);
		$pdf->SetAuthor(Yii::app()->name);
<?php if ($this->messageSupport): ?>
		$pdf->SetTitle(Yii::t('default', 'Listado <?= $this->plural ?>'));
<?php else: ?>
		$pdf->SetTitle('Listado <?= $this->plural ?>');
<?php endif; ?>
<?php if ($this->messageSupport): ?>
		$pdf->SetSubject(Yii::t('default', 'Listado <?= $this->plural ?>'));
<?php else: ?>
		$pdf->SetSubject('Listado <?= $this->plural ?>');
<?php endif; ?>

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
			require_once(dirname(__FILE__) . '/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// set font
		$pdf->SetFont('times', 'BI', 10);

		// add a page
		$pdf->AddPage();

		$model = new <?= $this->modelClass ?>('search');
		if (isset(Yii::app()->session['searchAttributes'])) {
			$model->attributes = Yii::app()->session['searchAttributes'];
		}		
		$dataProvider = $model->search();
		$iterator = new CDataProviderIterator($dataProvider);

		// Generamos la lista de elementos
<?php if ($this->messageSupport): ?>
		$html = "<h1><?= "<?= Yii::t('default', 'Listado $this->plural'); ?>" ?></h1>
<?php else: ?>
		$html = "<h1>Listado <?= $this->plural ?></h1>
<?php endif; ?>
		<table border=\"1\">
		<tr>
<?php foreach ($attributesModel as $key => $attr): ?>
<?php if ( $key == $this->createAttribute ||
        $key == $this->createUser ||
        $key == $this->updateAttribute ||
        $key == $this->updateUser ||
        strpos($key, "up_") !== false
        ) {
			continue;
        	} ?>
		<th>{$model->getAttributeLabel('<?= $key ?>')}</th>
<?php endforeach; ?>
		</tr>";

		foreach($iterator as $key => $element) {

			// Si se ha alcanzado el maximo, dejamos de generar filas
			if ($key >= Yii::app()->params['MAX_PDF_ROWS']) {
				break;
			}		

			$html .= "<tr>
<?php foreach ($attributesModel as $key => $attr): ?>
<?php if ( $key == $this->createAttribute ||
        $key == $this->createUser ||
        $key == $this->updateAttribute ||
        $key == $this->updateUser ||
        strpos($key, "up_") !== false
        ) {
			continue;
        } ?>
<?php if ($relationName = $this->hasKeyAttributeRelated($key)): ?>
			<td>{$element-><?= $relationName ?>->nombre}</td>
<?php else: ?>
			<td>{$element-><?= $key ?>}</td>
<?php endif; ?>
<?php endforeach; ?>
			</tr>";
		}

		$html .= '</table>';

		$pdf->writeHTML($html, true, false, true, false, '');

		//Close and output PDF document
		$pdf->Output('listado_<?= $this->plural ?>.pdf', 'I');
		Yii::app()->end();
	}

<?php endif; ?>
<?php if ($this->generateExcel): ?>
	public function actionListExcel()
	{
		$excel = Yii::createComponent('YiiExcel')->createPHPExcel();

		// Set document properties
		$excel->getProperties()->setCreator(Yii::app()->name)
			->setLastModifiedBy(Yii::app()->name)
<?php if ($this->messageSupport): ?>
			->setTitle(Yii::t('default', 'Listado <?= $this->plural ?>'))
<?php else: ?>
			->setTitle('Listado <?= $this->plural ?>')
<?php endif; ?>
<?php if ($this->messageSupport): ?>
			->setSubject(Yii::t('default', 'Listado <?= $this->plural ?>'))
<?php else: ?>
			->setSubject('Listado <?= $this->plural ?>')
<?php endif; ?>
			->setDescription("")
			->setKeywords("")
			->setCategory("");

		$model = new <?= $this->modelClass ?>('search');
		if (isset(Yii::app()->session['searchAttributes'])) {
			$model->attributes = Yii::app()->session['searchAttributes'];
		}		
		$dataProvider = $model->search();
		$iterator = new CDataProviderIterator($dataProvider);

		$index = 1;
		$letterCode = 65;	// Desde la A

		// Generamos la lista de titulos
		$excel->setActiveSheetIndex(0)
<?php $code = ''; ?>
<?php foreach ($attributesModel as $key => $attr): ?>
<?php if ( $key == $this->createAttribute ||
        $key == $this->createUser ||
        $key == $this->updateAttribute ||
        $key == $this->updateUser ||
        strpos($key, "up_") !== false
        ) {
			continue;
        	} ?>
<?php $code .= "\t\t\t" . '->setCellValue(chr($letterCode++) . $index, $model->getAttributeLabel(\'' . $key. '\'))' . "\n"; ?>
<?php endforeach; ?>
<?= substr($code, 0, -1) . ';'	?>


		// Ajustamos el ancho automatico, centramos los titulos, etc
		$activeSheet = $excel->getActiveSheet();
		for ($i = $letterCode; $i >= 65; $i--) {
			$activeSheet->getColumnDimension(chr($i))->setAutoSize(true);
		}

		$styleArray = [
			'borders' => [
				'allborders' => [
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				],
			],
			'fill' => [
            	'type' => PHPExcel_Style_Fill::FILL_SOLID,
            	'color' => array('rgb' => 'CCCCCC')
        	],
        	'font' => [
		        'bold' => true,
		    ],
		    'alignment'=> [
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		    ],
		];		

		$activeSheet->getStyle(chr(65) . '1:' . chr(--$letterCode) . '1')->applyFromArray($styleArray);

		// Generamos la lista de elementos
		$letterCode = 66; // Desde la B en adelante
		foreach($iterator as $element) {
			$index++;
			$letterCode = 65; // Desde la A en adelante		

			// Si se ha alcanzado el maximo, dejamos de generar filas
			if ($index > Yii::app()->params['MAX_EXCEL_ROWS']) {
				break;
			}

			$excel->setActiveSheetIndex(0)
<?php $code = ''; ?>
<?php foreach ($attributesModel as $key => $attr): ?>
<?php if ( $key == $this->createAttribute ||
        $key == $this->createUser ||
        $key == $this->updateAttribute ||
        $key == $this->updateUser ||
        strpos($key, "up_") !== false
        ) {
			continue;
        } ?>
<?php if ($relationName = $this->hasKeyAttributeRelated($key)): ?>
<?php $code .= "\t\t\t\t" . '->setCellValue(chr($letterCode++) . $index, $element->' . $relationName . "->nombre)\n"; ?>
<?php else: ?>
<?php $code .= "\t\t\t\t" . '->setCellValue(chr($letterCode++) . $index, $element->' . $key . ")\n"; ?>
<?php endif; ?>
<?php endforeach; ?>
<?= substr($code, 0, -1) . ';'	?>

		}

		// Rename worksheet
<?php if ($this->messageSupport): ?>
		$excel->getActiveSheet()->setTitle(Yii::t('default', 'Listado <?= $this->plural ?>'));
<?php else: ?>
		$excel->getActiveSheet()->setTitle('Listado <?= $this->plural ?>');
<?php endif; ?>

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$excel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="list_<?= $this->plural ?>.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$objWriter->save('php://output');	
		Yii::app()->end();		
	}

<?php endif; ?>
	/**
	* Returns the data model based on the primary key given in the GET variable.
	* If the data model is not found, an HTTP exception will be raised.
	* @param integer $id the ID of the model to be loaded
	* @return <?php echo $this->modelClass; ?> the loaded model
	* @throws CHttpException
	*/
	public function loadModel($id)
	{
		$model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
		if ($model === null) {
<?php if ($this->messageSupport): ?>
			throw new CHttpException(404, Yii::t('default','La página requerida no existe.'));
<?php else: ?>
			throw new CHttpException(404, 'La página requerida no existe.');
<?php endif; ?>
		}

		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param <?php echo $this->modelClass; ?> $model the model to be validated
	*/
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === '<?php echo $this->class2id($this->modelClass); ?>-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}