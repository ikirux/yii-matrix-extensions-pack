<?php

class uploadFile extends CValidator
{
    public $allowType;
    public $maxFileSize; // Bytes

    public $mymeTypes = [
        'PDF' => ['application/pdf'],
        'DOC' => ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'IMAGE' => ['image/jpeg', 'image/png'],
    ];

    public $extensions = [
        'PDF' => ['pdf'],
        'DOC' => ['doc', 'docx'],
        'IMAGE' => ['jpg', 'jpeg', 'png'],
    ];

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object, $attribute)
    {
    	$file = CUploadedFile::getInstance($object, $attribute);
    	if (!$file instanceof CUploadedFile) {
    		$this->addError($object, $attribute, 'No se detecto el archivo');
    		return;
    	}

        if ($this->allowType === null) {
            $this->allowType = ALLOW_ALL;
        }

        if ($this->maxFileSize === null) {
            $this->maxFileSize = Yii::app()->params['MAX_UPLOAD_FILE'];
        }

        $mymeTypes = [];
        $extensions = [];
        if ($this->allowType === 1) {
			$mymeTypes = $this->mymeTypes['PDF'];
			$extensions = $this->extensions['PDF'];
        } elseif ($this->allowType === 2) {
			$mymeTypes = $this->mymeTypes['DOC'];
			$extensions = $this->extensions['DOC'];			
        } elseif ($this->allowType === 3) {
			$mymeTypes = array_merge($this->mymeTypes['PDF'], $this->mymeTypes['DOC']);
			$extensions = array_merge($this->extensions['PDF'], $this->extensions['DOC']);
        } elseif ($this->allowType === 4) {
			$mymeTypes = $this->mymeTypes['IMAGE'];
			$extensions = $this->extensions['IMAGE'];			
        } elseif ($this->allowType === 5) {
			$mymeTypes = array_merge($this->mymeTypes['PDF'], $this->mymeTypes['IMAGE']);
			$extensions = array_merge($this->extensions['PDF'], $this->extensions['IMAGE']);		
        } elseif ($this->allowType === 6) {
			$mymeTypes = array_merge($this->mymeTypes['DOC'], $this->mymeTypes['IMAGE']);
			$extensions = array_merge($this->extensions['DOC'], $this->extensions['IMAGE']);				
        } elseif ($this->allowType === 7) {
			$mymeTypes = array_merge($this->mymeTypes['PDF'], $this->mymeTypes['DOC'], $this->mymeTypes['IMAGE']);
			$extensions = array_merge($this->extensions['PDF'], $this->extensions['DOC'], $this->extensions['IMAGE']);	
        }

        if ($file->getSize() > $this->maxFileSize) {
        	$this->addError($object, $attribute, 'El archivo supera el tamaño máximo (' . ($this->maxFileSize / (1024 * 1000)) . 'MB)');
        } elseif (!in_array(trim(shell_exec('file --mime-type --brief ' . $file->getTempName())), $mymeTypes)) {
        	$this->addError($object, $attribute, 'El archivo no es del tipo correcto (Code: 1)');
        } elseif (!in_array($file->getExtensionName(), $extensions)) {
        	$this->addError($object, $attribute, 'El archivo no es del tipo correcto (Code: 2)');
        }
    }    
}    
