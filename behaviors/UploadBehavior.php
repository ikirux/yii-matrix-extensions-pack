<?php
/**
 * UploadBehavior class file.
 *
 * @author Carlos Pinto <ikirux@gmail.com>
 * @link http://www.yiiframework.com/
 */

class UploadBehavior extends CActiveRecordBehavior {
	/**
	 * @var string file upload mime_type
	 */
	public $mime_type = '';

	/**
	 * @var string file upload size 
	 */
	public $size = '';

	/**
	 * @var mixed CUploadedFile object
	 */
	public $file = null;	
	public $fileNameAttribute;
	public $fileInternalAttribute;	

	/**
	 * @var array backup older upload's value fields
	 */
	public $uploadValueFieldsBackup = [];

	public function beforeSave($event)
	{
		$this->populateFields();
	}

	public function populateFields()
	{
		// Si se han subido archivos
		if (Yii::app()->user->hasState('MatrixUploadFiles')) {
			// Guardamos los valores de uploads 
			if (!$this->owner->isNewRecord) {
		    	$fields = $this->owner->tableSchema->getColumnNames();
		    	// Buscamos el patron "up_machine"
		    	foreach ($fields as $field) {
		    		if (strpos($field, "up_machine") !== false) {
		    			if (!empty($this->owner->$field)) {
							$this->uploadValueFieldsBackup[$field] = $this->owner->$field;
		    			}
		    		}
		    	}
			}

			$userFiles = Yii::app()->user->getState('MatrixUploadFiles');
			foreach ($userFiles as $userFile) {
				// Save the original name
				$this->owner->{$userFile['fileNameAttribute']} = $userFile['fileName'];
				
				// Route in the filesystem where the uploading file will be save
				$this->owner->{$userFile['fileInternalAttribute']} = $userFile['publicPath'] . DIRECTORY_SEPARATOR . $userFile['fileInternalName'];
			}	
		}
	}

	public function afterSave($event)
	{	
		$this->moveUploadedFilesToFinalDestination($event);
	}	

	public function moveUploadedFilesToFinalDestination($event = null)
	{
		// Si se han subido archivos
		if (Yii::app()->user->hasState('MatrixUploadFiles')) {		
			$userFiles = Yii::app()->user->getState('MatrixUploadFiles');
			Yii::app()->user->setState('MatrixUploadFiles', null);

			foreach ($userFiles as $userFile) {
				$temporalFile = $userFile["temporalPath"] . $userFile['fileInternalName'];
				if (is_file($temporalFile)) {
					if (!is_null($event)) {
						if ($this->beforeUpload($event, $temporalFile)) {
							$finalFile = Yii::app()->getBasePath() . $this->owner->{$userFile['fileInternalAttribute']};
							if (rename($temporalFile, $finalFile)) {
								chmod(Yii::app()->getBasePath() . $this->owner->{$userFile['fileInternalAttribute']} , 0777);
								$this->afterUpload($event, $finalFile);
							} else {
								throw new Exception('Could not save file');
							}
						} else {
							throw new Exception('Could not execute beforeUpload');
						}
					} else {
						$finalFile = Yii::app()->getBasePath() . $this->owner->{$userFile['fileInternalAttribute']};
						if (rename($temporalFile, $finalFile)) {
							chmod(Yii::app()->getBasePath() . $this->owner->{$userFile['fileInternalAttribute']} , 0777);
							$this->afterUpload($event, $finalFile);
						} else {
							throw new Exception('Could not save file');
						}
					}
				} else {
					throw new Exception('Could not save file, there is no such file');
				}
			}

			// Eliminamos los archivos desde el disco si es que cambiaron
			if (!$this->owner->isNewRecord) {
		    	foreach ($this->uploadValueFieldsBackup as $key => $oldFile) {
		    		if ($oldFile !== $this->owner->$key) {
						if (!@unlink(Yii::app()->getBasePath() . $oldFile)) {
							Yii::log("I couldn't delete " . $oldFile, CLogger::LEVEL_WARNING);
						} 
		    		}
		    	}
			}			
		}
	}

	public function beforeDelete($event)
    {
    	// Eliminamos los archivos relacionados
    	// Obtenemos los campos de la tabla
    	$fields = $this->owner->tableSchema->getColumnNames();
    	// Buscamos el patron "up_machine"
    	foreach ($fields as $field) {
    		if (strpos($field, "up_machine") !== false) {
    			if (!empty($this->owner->$field)) {
					if (!@unlink(Yii::app()->getBasePath() . $this->owner->$field)) {
						Yii::log("I couldn't delete " . $this->owner->$field, CLogger::LEVEL_WARNING);
						//prevent real deletion
        				return false;
					}    				
    			}
    		}
    	}

    	return parent::beforeDelete($event);
    }

	protected function beforeUpload($event, $temporalFile)
	{
		if (method_exists($this->owner, 'beforeUpload')) {
			return $this->owner->beforeUpload($temporalFile);
		}

		return true;
	}

	public function afterUpload($event, $finalFile)
	{
		if (method_exists($this->owner, 'afterUpload')) {
			$this->owner->afterUpload($finalFile);
		}		
	}
}
