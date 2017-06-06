<?php

class OnlyOneHeader extends CValidator
{
    public $message = 'Solo puede haber un elemento sin dependencia';

    /**
    * Valida que el rut sea valido.
    * If there is any error, the error message is added to the object.
    * @param CModel $object the object being validated
    * @param string $attribute the attribute being validated
    */
    protected function validateAttribute($object, $attribute)
    {
        $attributeValue = $object->$attribute;
        $tableName = $object->tableName();

        if (empty($attributeValue)) {
            $command = Yii::app()->db->createCommand("SELECT COUNT(id) FROM {$tableName} WHERE {$attribute} IS NULL");

            if ($command->queryScalar() >= 1) {
                $this->addError($object, $attribute, $this->message);
                return false;
            }			
        }

        return true;
     }
}