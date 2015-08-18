<?php
/**
 * GInitializrCrudCode class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap.gii
 */

Yii::import('gii.generators.crud.CrudCode');

class GInitializrCrudCode extends CrudCode
{
    /**
     * Indica si se debe generar la opcion de exportacion a PDF
     */
    public $generatePDF = false;

    /**
     * Nombre de los campos de auditoria
     */
    public $createAttribute = 'create_time';
    public $createUser = 'create_user_id';
    public $updateAttribute = 'update_time';
    public $updateUser = 'update_user_id';

    /**
     * Indica si se debe generar la opcion de exportacion a Excel
     */    
    public $generateExcel = false;

    /**
     * Indica si se debe generar los mensajes con la funcion t()
     */    
    public $messageSupport = false;

    /**
     * Nombre que indica el singular de los elementos controlador por el modelo
     */    
    public $singular = '';

    /**
     * Nombre que indica el plural de los elementos controlador por el modelo
     */    
    public $plural = '';

    /**
     * Indica si se va o no a ingresar un submenu en las vistas
     */    
    public $submenu = '';

    /**
     * Ruta desde donde se incluye el submenu, en caso de agregarse
     */    
    public $submenu_path = '/default/_menu';        

    public function generateControlGroup($modelClass, $column)
    {
        if ($column->type === 'boolean') {
            return "BsHtml::activeCheckBoxControlGroup(\$model, '{$column->name}')";
        } else {
            if (stripos($column->dbType, 'text') !== false) {
                return "BsHtml::activeTextAreaControlGroup(\$model, '{$column->name}', ['rows' => 6])";
            } else {
                if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                    $inputField = 'activePasswordControlGroup';
                } else {
                    $inputField = 'activeTextFieldControlGroup';
                }

                if ($column->type !== 'string' || $column->size === null) {
                    return "BsHtml::{$inputField}(\$model, '{$column->name}')";
                } else {
                    if (($size = $maxLength = $column->size) > 60) {
                        $size = 60;
                    }
                    return "BsHtml::{$inputField}(\$model, '{$column->name}', ['size' => $size, 'maxlength' => $maxLength])";
                }
            }
        }
    }

    public function generateActiveControlGroup($modelClass, $column, $view = '', $foreignModel = false)
    {
        if ($foreignModel != false && $view == 'search') {
            return "\$form->dropDownListControlGroup(\$model, '{$column->name}', $foreignModel::model()->getOptionList(), [
                'prompt' => \$this->getPrompt(),
            ])";
        } elseif ($foreignModel != false && $view == 'form') {
            return "\$form->dropDownListControlGroup(\$model, '{$column->name}', $foreignModel::model()->getOptionList(), [
            'prompt' => \$this->getPrompt(),
        ])";
        }

        if ($column->type === 'boolean') {
            return "\$form->checkBoxControlGroup(\$model, '{$column->name}')";
        } else {
            if (stripos($column->dbType, 'text') !== false) {
                return "\$form->textAreaControlGroup(\$model, '{$column->name}', ['rows' => 6])";
            } else {
                if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                    $inputField = 'passwordFieldControlGroup';
                } else {
                    $inputField = 'textFieldControlGroup';
                }

                if ($column->type !== 'string' || $column->size === null) {
                    return "\$form->{$inputField}(\$model, '{$column->name}')";
                } else {
                    // search y form tienen distintas identaciones
                    if ($column->name == 'rut' && $view == 'search') {
                        if ($this->messageSupport) {
                            return "\$form->{$inputField}(\$model, '{$column->name}', [
        'maxlength' => $column->size, 
        'help' => Yii::t('default', 'Ingresar rut sin puntos ni guión')
    ])";
                        } else {
                            return "\$form->{$inputField}(\$model, '{$column->name}', [
        'maxlength' => $column->size, 
        'help' => 'Ingresar rut sin puntos ni guión'
    ])";
                        }
                    } elseif($column->name == 'rut' && $view == 'form') {
                        if ($this->messageSupport) {
                    return "\$form->{$inputField}(\$model, '{$column->name}', [
            'maxlength' => $column->size, 
            'help' => 'Ingresar rut sin puntos ni guión'
        ])";
                        } else {
                    return "\$form->{$inputField}(\$model, '{$column->name}', [
            'maxlength' => $column->size, 
            'help' => Yii::t('default', 'Ingresar rut sin puntos ni guión') 
        ])";
                        }
                    } else {
                    return "\$form->{$inputField}(\$model, '{$column->name}', ['maxlength' => $column->size])";    
                    }                    
                }
            }
        }
    }

    public function generateDateControlGroup($modelClass, $column, $view = '')
    {
        if ($column->dbType === 'date' && $view == 'search') {
            return "\$this->widget('datepicker.DatePickerControl', [
                'model' => \$model,
                'attribute' => '{$column->name}',
                'options' => [
                    'placement' => 'right',
                    'autoclose' => true,
                    'todayBtn' => true,
                ]
            ])";
        } elseif ($column->dbType === 'date' && $view == 'form') {
            return "\$this->widget('datepicker.DatePickerControl', [
            'model' => \$model,
            'attribute' => '{$column->name}',
            'options' => [
                'placement' => 'right',
                'autoclose' => true,
                'todayBtn' => true,
            ]
        ])";
        }
    }  

    public function generateUpload($modelClass, $column)
    {
        $field = str_replace('up_', '', $column->name);
        $uploadTypeLengend = 'cualquiera';
        if (!empty($column->comment)) {
            $types = explode(',', strtolower($column->comment));
            $uploadTypeLengend = '';
            foreach ($types as $type) {
                Yii::log($type);
                if ($type === 'pdf') {
                    $uploadTypeLengend .= 'pdf, ';
                } elseif ($type === 'doc') {
                    $uploadTypeLengend .= 'doc, docx, ';
                } elseif ($type === 'image') {
                    $uploadTypeLengend .= 'jpg, jpeg, png, ';
                }
            }
            $uploadTypeLengend = substr($uploadTypeLengend, 0, strlen($uploadTypeLengend) - 2);
        }

        return '<div class="form-group">
            <?php if (!$model->isNewRecord): ?>
                <?php if ($model->up_' . $field . '): ?>
                    <div style="padding:1em">
                        <?= CHtml::link($model->up_' . $field . ', Yii::app()->request->baseUrl . $model->up_machine_' . $field . ', [
                            \'target\' => \'_blank\',
                            \'class\' => \'thumbnail\',
                        ]); ?>
                    </div>
                <?php endif; ?>        
            <?php endif; ?>

            <?php $this->widget(\'ext.ui.yii-ikirux-dropzone.DropZone\', [
                \'url\' => Yii::app()->createUrl(\'' . $this->class2id($modelClass) . '/upload\', [
                    \'fileNameAttribute\' => \'up_' . $field . '\',
                    \'fileInternalAttribute\' => \'up_machine_' . $field . '\',
                ]),
                \'model\' => $model,
                \'attribute\' => \'up_' . $field . '\',
                \'idDiv\' => \'' . $field . 'Div\',
                \'options\' => [
                    \'dictDefaultMessage\' => \'Arrastre un archivo aquí (' . $uploadTypeLengend . ')\',
                ],
            ]); ?>  
        </div>';
    }

    public function generateEmptyList($modelClass, $column, $view = '')
    {
        if ($view == 'search') {
            return "\$form->dropDownListControlGroup(\$model, '{$column->name}', [], [
                'prompt' => \$this->getPrompt(),
            ])";
        } elseif ($view == 'form') {
            return "\$form->dropDownListControlGroup(\$model, '{$column->name}', [], [
            'prompt' => \$this->getPrompt(),
        ])";
        }        
    }

    public function attributeLabels() 
    {
        $customLabels = [
            'generatePDF' => 'Generar Exportación a PDF',
            'generateExcel' => 'Generar Exportación a Excel',
            'messageSupport' => 'Generar Soporte para Traducciones',
            'singular' => 'Nombre Singular',
            'plural' => 'Nombre Plural',
            'submenu' => 'Agregar SubMenu',
            'submenu_path' => 'Ruta del SubMenu',
        ];
            
        return array_merge(parent::attributeLabels(), $customLabels);
    }

    public function rules() 
    {
        $customRules = [
            ['singular, plural', 'required'],
            ['generatePDF, generateExcel, messageSupport, submenu, submenu_path', 'safe'],
            ['submenu_path', 'sticky'],
        ];

        return array_merge(parent::rules(), $customRules);
    }

    public function pluralize($name) 
    {
        if (Yii::app()->language == 'es') {
            // si termina en a, e, o, á, é, ó va s
            $strongVowels = ['a', 'e', 'o', 'á', 'é', 'ó'];
            // si termina en i, u va es
            $weakVowels = ['i', 'u', 'í', 'ú'];
            // si termina en consonante va es 
            $lastLetter = substr($name, -1);

            if (in_array($lastLetter, $strongVowels)) {
                $name .= 's';
            } elseif (in_array($lastLetter, $weakVowels)) {
                $name .= 'es';
            } else { // Hay otras excepciones
                $name .= 'es';
            }

            return $name;
        } else {
            return parent::pluralize($name);
        }
    }

    /**
     * Devuelve el nombre de la relacion en caso de existir un modelo relacionado
     * con el atributo "nombre"
     **/
    public function hasKeyAttributeRelated($columnName) 
    {
        if (preg_match('/(.*)_id$/i', $columnName, $matches, PREG_OFFSET_CAPTURE)) {
            if ($model = new $this->modelClass)  {
                $metadaObj = $model->getMetaData();
                $relationName = "r_" . $this->normalizeRelationName($matches[1][0]);
                // Revisamos si existe la relation
                if ($metadaObj->hasRelation($relationName)) {
                    // Vamos si hay definido un atributo nombre
                    $nameRelatedModel = $this->normalizeModelName($matches[1][0]);

                    $relatedModel = new $nameRelatedModel;
                    if ($relatedModel->hasAttribute('nombre')) {
                        return $relationName;
                    }
                }
            }
        }
        
        return false;    
    }

    public function normalizeModelName($modelName)
    {
        $tmpArray = split('_', $modelName);
        array_walk($tmpArray, function(&$item, $key) {
            $item = ucwords($item);
        });
        
        return implode('', $tmpArray);
    }

    public function normalizeRelationName($relationName)
    {
        $tmpArray = split('_', $relationName);
        array_walk($tmpArray, function(&$item, $key) {
            if ($key != 0) {
                $item = ucwords($item);    
            }            
        });
        
        return implode('', $tmpArray);
    }    

    /**
     * Deveulve verdadero o falso dependiendo si existe o no el modelo
     **/
    public function existModel($modelName)
    {
        $current_error_reporting = error_reporting();
        error_reporting(0);
        $existModel = class_exists($modelName, true);
        error_reporting($current_error_reporting);
        return $existModel;
    }  

    /**
     * Crea el prefijo del permiso que corresponde
     **/
    public function prefixPermission()   
    {
        $controllerName = strtolower(str_replace('Controller', '', $this->getControllerClass()));
        $idModule = $this->getModule()->id;
        $moduleName =  $idModule != Yii::app()->id ? $idModule : '';

        return empty($moduleName) ? $controllerName : "$moduleName.$controllerName";
    }
}