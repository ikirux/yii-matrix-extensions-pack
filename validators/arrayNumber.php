<?php

class arrayNumber extends CValidator
{
	public $allowEmpty = false;

	/**
	* Valida el contenido del arreglo, solo va a permitir numeros.
	* If there is any error, the error message is added to the object.
	* @param CModel $object the object being validated
	* @param string $attribute the attribute being validated
	*/
	protected function validateAttribute($object, $attribute)
	{
	    $array = $object->$attribute;
	    
	    if (!$this->allowEmpty && empty($array)) {
	    	$this->addError($object, $attribute, 'El atributo no puede ser vacio');
	    } else {
		    // Primero verificamos que el atributo sea un arreglo
		    if (!is_array($array)) {
				$this->addError($object, $attribute, $attribute . ' no contiene códigos válidos');
		    } else {
				foreach ($array as $element) {
				    if (!is_numeric($element)) {
						$this->addError($object, $attribute, $attribute . 'no contiene códigos válidos');
						break;
				    }			    
				}
		    }			
	    }
	}
}