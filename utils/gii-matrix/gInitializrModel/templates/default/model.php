<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */

/**
 * Si el modelo contiene un campo id y otro nombre, se generar el metodo getOptionList
 */
$generateGetOptionListMethod = false;
$countFields = 0;

/**
 * Si existen campos de tipo fecha se agregaran las rules correspondientes mas el behavior
 */
$existDateFields = false;

/**
 * Si existen campos de tipo upload, se incluye el bahavior correspondiente
 */
$existUploadFields = false;

/**
 * Arreglo de behaviors
 */
$arrBehaviors = [];
?>
<?php echo "<?php\n"; ?>

/**
 * This is the model class for table "<?php echo $tableName; ?>".
 *
 * The followings are the available columns in table '<?php echo $tableName; ?>':
<?php foreach($columns as $column): ?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php if ($column->name == 'id' || $column->name == 'nombre') { $countFields++; } ?>
<?php if ($column->dbType == 'date') { $existDateFields = true; } ?>
<?php if (strpos($column->name, "up_") !== false) { $existUploadFields = true; } ?>
<?php endforeach; ?>
<?php if ($countFields == 2) { $generateGetOptionListMethod = true; } ?>
<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
	if (preg_match("~^\[self::([^,]+), '([^']+)', '([^']+)'\]$~", $relation, $matches))		
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
	}
    ?>
<?php endforeach; ?>
<?php endif; ?>
 */
<?php 

if ($existDateFields == true) {
	$arrBehaviors[] = $this->generateBehaviors(GInitializrModelCode::BEHAVIOR_DATE);
}

if ($this->generateAudit) {
	$arrBehaviors[] = $this->generateBehaviors(GInitializrModelCode::BEHAVIOR_AUDIT);
}

if ($existUploadFields == true) {
	$arrBehaviors[] = $this->generateBehaviors(GInitializrModelCode::BEHAVIOR_UPLOAD);
}
?>
class <?php echo $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?>
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '<?php echo $tableName; ?>';
	}
<?php if (count($arrBehaviors) > 0): ?>
	
	public function behaviors()
	{
	    return [
<?php foreach ($arrBehaviors as $behavior): ?>
<?= $behavior ?>
<?php endforeach; ?>	
	    ];
	}
<?php endif; ?>

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			['<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on' => 'search'],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'r_$name' => $relation,\n"; ?>
<?php endforeach; ?>
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
<?php if ($this->messageSupport): ?>
<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => Yii::t('default', '$label'),\n"; ?>
<?php endforeach; ?>
<?php else: ?>
<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => '$label',\n"; ?>
<?php endforeach; ?>
<?php endif; ?>
		];
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
<?php	if ($existDateFields) {
			echo "\t\t\$this->convertMachineFormatDate(\$this);\n\n";
		}
?>
<?php
foreach($columns as $name=>$column)
{
    // Nos saltamos los campos de auditoria
    if ($column->name == $this->createAttribute ||
        $column->name == $this->createUser ||
        $column->name == $this->updateAttribute ||
        $column->name == $this->updateUser
    ) {
        $comment = '// ';
    } else {
    	$comment = '';
    }

	if ($column->type === 'string') {
		echo "\t\t$comment\$criteria->compare('$name', \$this->$name, true);\n";
	} else {
		echo "\t\t$comment\$criteria->compare('$name', \$this->$name);\n";
	}
}
?>

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}

<?php if($connectionId!='db'):?>
	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()-><?php echo $connectionId ?>;
	}

<?php endif?>
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return <?php echo $modelClass; ?> the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

<?php if ($generateGetOptionListMethod): ?>
	/**
	* Generates the data suitable for list-based HTML elements
	**/
	public function getOptionList()
	{
		return CHtml::listData($this->findAll(), 'id', 'nombre');
	}
<?php else: ?>
	/**
	* Generates the data suitable for list-based HTML elements
	* stub version
	**/
	public function getOptionList()
	{
		return [];
	}
<?php endif; ?>
}