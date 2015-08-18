<?php

class Rut extends CValidator
{
     const RutRegex = "/[0-9]+K?/";

     public $message = 'No es un rut válido';
     public $allowEmpty = false;

     /**
     * Valida que el rut sea valido.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
     protected function validateAttribute($object, $attribute)
     {
          $rut = $object->$attribute;

          if (empty($rut) && $this->allowEmpty) {
               return true;
          }

          $coef = [3, 2, 7, 6, 5, 4, 3, 2];

          // In case that rut is padded with spaces
          $rut = strtoupper(trim($rut));

          if (!preg_match(self::RutRegex, $rut)) {
               $this->addError($object, $attribute, $this->message);
               return false;
          }

          if (strlen($rut) > 9) {
               $this->addError($object, $attribute, $this->message);
               return false;
          }

          // If shorter than 9 characters (8 + control char) ...
          while (strlen($rut) < 9) {
               $rut = "0" . $rut;
          }

          $total = 0;
          for ($index = 0; $index < strlen($rut) - 1; $index++) {
               $curr = substr($rut, $index, 1);
               $total += $coef[$index] * ($curr - '0');
          }

          $rest = 11 - ($total % 11);

          if ($rest == 11) { 
               $rest = 0;
          }     

          if (($rest == 10) && (substr($rut, -1) == 'K')) {
               return true;
          }

          if (substr($rut, strlen($rut) - 1, 1) == ('0' + $rest)) {
               return true;
          }

          $this->addError($object, $attribute, $this->message);
          return false;
     }
}