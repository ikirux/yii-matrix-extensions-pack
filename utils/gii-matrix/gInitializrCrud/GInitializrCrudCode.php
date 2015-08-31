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

    public function generateControlGroup($modelClass, $column, $tabs = 0)
    {
        $html = "";        
        if ($column->type === 'boolean') {
            $html =            
"_TAB_<?= BsHtml::activeCheckBoxControlGroup(\$model, '{$column->name}'); ?>";
        } else {
            if (stripos($column->dbType, 'text') !== false) {
                $html =                
"_TAB_<?= BsHtml::activeTextAreaControlGroup(\$model, '{$column->name}', ['rows' => 6]); ?>";
            } else {
                if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                    $inputField = 'activePasswordControlGroup';
                } else {
                    $inputField = 'activeTextFieldControlGroup';
                }

                if ($column->type !== 'string' || $column->size === null) {
                    $html =                    
"_TAB_<?= BsHtml::{$inputField}(\$model, '{$column->name}'); ?>";
                } else {
                    if (($size = $maxLength = $column->size) > 60) {
                        $size = 60;
                    }
                    $html =                    
"_TAB_<?= BsHtml::{$inputField}(\$model, '{$column->name}', ['size' => $size, 'maxlength' => $maxLength]); ?>";
                }
            }
        }

        return $this->indent($html, $tabs);        
    }

    public function generateActiveControlGroup($modelClass, $column, $tabs = 0, $foreignModel = false)
    {
        $html = "";
        if ($foreignModel != false) {
            $html =
"_TAB_<?= \$form->dropDownListControlGroup(\$model, '{$column->name}', $foreignModel::model()->getOptionList(), [
_TAB_    'prompt' => \$this->getPrompt(),
_TAB_]); ?>";
        } elseif ($column->type === 'boolean') {
            $html =            
"_TAB_<?= \$form->checkBoxControlGroup(\$model, '{$column->name}'); ?>";
        } else {
            if (stripos($column->dbType, 'text') !== false) {
                $html =                
"_TAB_<?= \$form->textAreaControlGroup(\$model, '{$column->name}', ['rows' => 6]); ?>";
            } else {
                if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                    $inputField = 'passwordFieldControlGroup';
                } else {
                    $inputField = 'textFieldControlGroup';
                }

                if ($column->type !== 'string' || $column->size === null) {
                    $html =                    
"_TAB_<?= \$form->{$inputField}(\$model, '{$column->name}'); ?>";
                } else {
                    // search y form tienen distintas identaciones
                    if ($column->name == 'rut') {
                        if ($this->messageSupport) {
                            $html =                            
"_TAB_<?= \$form->{$inputField}(\$model, '{$column->name}', [
_TAB_    'maxlength' => $column->size, 
_TAB_    'help' => Yii::t('default', 'Ingresar rut sin puntos ni guión')
_TAB_]); ?>";
                        } else {
                            $html =                            
"_TAB_<?= \$form->{$inputField}(\$model, '{$column->name}', [
_TAB_    'maxlength' => $column->size, 
_TAB_    'help' => 'Ingresar rut sin puntos ni guión'
_TAB_]); ?>";
                        }
                    } else {
                        $html = 
"_TAB_<?= \$form->{$inputField}(\$model, '{$column->name}', ['maxlength' => $column->size]); ?>";    
                    }                    
                }
            }
        }

        return $this->indent($html, $tabs);
    }

    public function generateDateControlGroup($column, $tabs = 0)
    {
        $html = 
"_TAB_<?php \$this->widget('datepicker.DatePickerControl', [
_TAB_    'model' => \$model,
_TAB_    'attribute' => '{$column->name}',
_TAB_    'options' => [
_TAB_        'placement' => 'right',
_TAB_        'autoclose' => true,
_TAB_        'todayBtn' => true,
_TAB_    ]
_TAB_]); ?>";

        return $this->indent($html, $tabs);
    }  

    public function generateUpload($modelClass, $column, $tabs = 0)
    {
        $field = str_replace('up_', '', $column->name);
        $hasImage = false;
        $uploadTypeLengend = 'cualquiera';
        if (!empty($column->comment)) {
            $types = explode(',', strtolower($column->comment));
            $uploadTypeLengend = '';
            foreach ($types as $type) {
                if ($type === 'pdf') {
                    $uploadTypeLengend .= 'pdf, ';
                } elseif ($type === 'doc') {
                    $uploadTypeLengend .= 'doc, docx, ';
                } elseif ($type === 'image') {
                    $hasImage = true;
                    $uploadTypeLengend .= 'jpg, jpeg, png, ';
                }
            }
            $uploadTypeLengend = substr($uploadTypeLengend, 0, strlen($uploadTypeLengend) - 2);
        }

        $html = 
'_TAB_<div class="form-group">
_TAB_    <?php if (!$model->isNewRecord && $model->up_' . $field . '): ?>';
        // Si es de tipo imagen generamos un thumbnail
        if ($hasImage) {
            $html .= 
"_TAB_\n\t\t\t\t\t" . '<?= CHtml::link(CHTML::image(Yii::app()->request->baseUrl . $model->up_machine_imagen, "", []), Yii::app()->request->baseUrl . $model->up_machine_imagen, [
_TAB_            \'target\' => \'_blank\',
_TAB_            \'class\' => \'thumbnail\',
_TAB_        ]); ?>
_TAB_    <?php endif; ?>';
        } else {
            $html .= 
"_TAB_\n\t\t\t\t" . '<div style=\"padding:1em\">
_TAB_                    <?= CHtml::link($model->up_' . $field . ', Yii::app()->request->baseUrl . $model->up_machine_' . $field . ', [
_TAB_                        \'target\' => \'_blank\',
_TAB_                        \'class\' => \'thumbnail\',
_TAB_                    ]); ?>
_TAB_                </div>
_TAB_            <?php endif; ?>';
        }

            $html .= 
"_TAB_\n\t\t\t\t" . '<?php $this->widget(\'matrixAssets.ui.yii-ikirux-dropzone.DropZone\', [
_TAB_           \'url\' => Yii::app()->createUrl(\'' . $this->class2id($modelClass) . '/upload\', [
_TAB_           \'fileNameAttribute\' => \'up_' . $field . '\',
_TAB_           \'fileInternalAttribute\' => \'up_machine_' . $field . '\',
_TAB_        ]),
_TAB_        \'model\' => $model,
_TAB_        \'attribute\' => \'up_' . $field . '\',
_TAB_        \'idDiv\' => \'' . $field . 'Div\',
_TAB_        \'options\' => [
_TAB_            \'dictDefaultMessage\' => \'Arrastre un archivo aquí (' . $uploadTypeLengend . ')\',
_TAB_        ],
_TAB_    ]); ?>  
_TAB_</div>';

        return $this->indent($html, $tabs);
    }

    public function generateEmptyList($column, $tabs = 0)
    {
        $html =
"_TAB_<?= \$form->dropDownListControlGroup(\$model, '{$column->name}', [], [
_TAB_    'prompt' => \$this->getPrompt(),
_TAB_]); ?>";        

        return $this->indent($html, $tabs);    
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

    private function indent($subject, $count, $stringTab = "_TAB_")
    {
        $replace = str_repeat("\t", $count);
        return str_replace($stringTab, $replace, $subject);
    }
}