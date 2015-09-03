<?php

class SourceMessageController extends Controller
{
	/**
	* @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	* using two-column layout. See 'protected/views/layouts/column2.php'.
	*/
	public $layout = '//layouts/column2';

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
	* Lists all models.
	*/
	public function actionIndex()
	{
		$model = new SourceMessage('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['SourceMessage'])) {
			$model->attributes = $_GET['SourceMessage'];
		}

		$this->render('index', [
			'model' => $model,
		]);		
	}

	public function actionListPdf()
	{
		$pdf = Yii::createComponent('YiiPDF', 'L');

		// set document information
		$pdf->SetCreator(Yii::app()->name);
		$pdf->SetAuthor(Yii::app()->name);
		$pdf->SetTitle(Yii::t('default', 'Listado') . ' Source Messages');
		$pdf->SetSubject(Yii::t('default', 'Listado') . ' Source Messages');

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

		$model = new SourceMessage('search');
		$dataProvider = $model->search();
		$iterator = new CDataProviderIterator($dataProvider);

		// Generamos la lista de elementos
		$html = "<h1><?= Yii::t('default', 'Listado'); ?> Source Messages</h1>
		<table border=\"1\">
		<tr>
		<th>{$model->getAttributeLabel('id')}</th>
		<th>{$model->getAttributeLabel('category')}</th>
		<th>{$model->getAttributeLabel('message')}</th>
		</tr>";

		foreach($iterator as $key => $element) {

			// Si se ha alcanzado el maximo, dejamos de generar filas
			if ($key >= Yii::app()->params['MAX_PDF_ROWS']) {
				break;
			}		

			$html .= "<tr>
			<td>{$element->id}</td>
			<td>{$element->category}</td>
			<td>{$element->message}</td>
			</tr>";
		}

		$html .= '</table>';

		$pdf->writeHTML($html, true, false, true, false, '');

		//Close and output PDF document
		$pdf->Output('listado_Source Messages.pdf', 'I');
		Yii::app()->end();
	}

	public function actionListExcel()
	{
		$excel = Yii::createComponent('YiiExcel')->createPHPExcel();

		// Set document properties
		$excel->getProperties()->setCreator(Yii::app()->name)
			->setLastModifiedBy(Yii::app()->name)
			->setTitle(Yii::t('default', 'Listado') . ' Source Messages')
			->setSubject(Yii::t('default', 'Listado') . ' Source Messages')
			->setDescription("")
			->setKeywords("")
			->setCategory("");

		$model = new SourceMessage('search');
		$dataProvider = $model->search();
		$iterator = new CDataProviderIterator($dataProvider);

		$index = 1;
		$letterCode = 65;	// Desde la A

		// Generamos la lista de titulos
		$excel->setActiveSheetIndex(0)
			->setCellValue(chr($letterCode++) . $index, $model->getAttributeLabel('id'))
			->setCellValue(chr($letterCode++) . $index, $model->getAttributeLabel('category'))
			->setCellValue(chr($letterCode++) . $index, $model->getAttributeLabel('message'));

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
				->setCellValue(chr($letterCode++) . $index, $element->id)
				->setCellValue(chr($letterCode++) . $index, $element->category)
				->setCellValue(chr($letterCode++) . $index, $element->message);
		}

		// Rename worksheet
		$excel->getActiveSheet()->setTitle(Yii::t('default', 'Listado') . ' Source Messages');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$excel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="list_Source Messages.xls"');
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

	/**
	* Returns the data model based on the primary key given in the GET variable.
	* If the data model is not found, an HTTP exception will be raised.
	* @param integer $id the ID of the model to be loaded
	* @return SourceMessage the loaded model
	* @throws CHttpException
	*/
	public function loadModel($id)
	{
		$model = SourceMessage::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, Yii::t('default','La página requerida no existe.'));
		}

		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param SourceMessage $model the model to be validated
	*/
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'source-message-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}