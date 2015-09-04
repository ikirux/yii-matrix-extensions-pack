<?php

/**
 * GInitializrModelCode class file.
 *
 * @author Carlos Pinto <ikirux@gmail.com>
 * @link http://developen.cl
 * @copyright Copyright &copy; 2014 Carlos Pinto
 * @license http://giix.org/license/ New BSD License
 */
Yii::import('system.gii.generators.model.ModelCode');

/**
 * GInitializrModelCode is the model for GInitializr model generator.
 *
 * @author Carlos Pinto <ikirux@gmail.com>
 */
class GInitializrModelCode extends ModelCode 
{
    /**
     * Indica si se debe incluir el behavior de auditoria
     */
    public $generateAudit = false;

    /**
     * Indica si se debe generar los mensajes con la funcion t()
     */    
    public $messageSupport = false;

    /**
     * Nombre de los campos de auditoria
     */
    public $createAttribute = 'create_time';
    public $createUser = 'create_user_id';
    public $updateAttribute = 'update_time';
    public $updateUser = 'update_user_id';

    const BEHAVIOR_DATE = 1;
    const BEHAVIOR_AUDIT = 2;
    const BEHAVIOR_UPLOAD = 3;

	/**
	 * Generates the rules for table fields.
	 * @param CDbTableSchema $table The table definition.
	 * @return array The rules for the table.
	 */
	public function generateRules($table)
	{
		$rules = [];
		$required = [];
		$integers = [];
		$numerical = [];
		$length = [];
		$safe = [];
		$dates = [];
		$ruts = [];
		$uploads = [];
		$uploads_type = [];

		foreach ($table->columns as $column) {
			if ($column->autoIncrement) {
				continue;
			}

			// Uploading file doesn't need type validation
			if (strpos($column->name, "up_machine") === false) {

				$r = !$column->allowNull && $column->defaultValue === null
					&& $column->name != $this->createAttribute
					&& $column->name != $this->createUser
					&& $column->name != $this->updateAttribute
					&& strpos($column->name, "up_") === false
					&& $column->name != $this->updateUser;

				if ($r) {
					$required[] = $column->name;
				}

				if (strpos($column->name, "up_") !== false) {
					$uploads[] = $column->name;
					// Comment set allow type
					$upload_type = 'ALLOW_ALL';
					if (!empty($column->comment) && $types = explode(',', strtolower($column->comment))) {
						$upload_type = ''; // it has type validations
						foreach ($types as $type) {
							if ($type === 'pdf') {
								$upload_type .= 'ALLOW_PDF | ';
							} elseif ($type === 'doc') {
								$upload_type .= 'ALLOW_DOC | ';
							} elseif ($type === 'image') {
								$upload_type .= 'ALLOW_IMAGE | ';
							}
						}
						$upload_type = substr($upload_type, 0, strlen($upload_type) - 2);
					}

					$uploads_type[] = $upload_type;
				} elseif ($column->type === 'integer') {
					$integers[] = $column->name;
				} elseif ($column->type === 'double') {
					$numerical[] = $column->name;
				} elseif ($column->type === 'string' && $column->size > 0) {
					$length[$column->size][] = $column->name;
					if ($column->name == 'rut') {
						$ruts[] = $column->name;
					}
				} elseif ($column->dbType === 'date') {
					$dates[] = $column->name;
				} elseif(!$column->isPrimaryKey && !$r) {
					$safe[]=$column->name;
				}
			}
		}

		if ($required !== []) {
			$rules[] = "['" . implode(', ', $required) . "', 'required']";
		}

		if ($integers !== []) {
			$rules[] = "['" . implode(', ', $integers) . "', 'numerical', 'integerOnly' => true]";
		}

		if ($numerical !== []) {
			$rules[] = "['" . implode(', ', $numerical) . "', 'numerical']";
		}

		if ($length !== []) {
			foreach ($length as $len => $cols) {
				$rules[] = "['" . implode(', ', $cols) . "', 'length', 'max' => $len]";
			}
		}

		if ($ruts !== []) {
			$rules[] = "['" . implode(', ', $ruts) . "', 'matrixAssets.validators.Rut']";
		}
		
		if ($safe !== []) {
			$rules[] = "['" . implode(', ', $safe) . "', 'safe']";
		}

		if ($dates !== []) {
			$rules[] = "['" . implode(', ', $dates) . "', 'date', 'format' => Yii::app()->locale->dateFormat]";
		}

		if ($uploads !== []) {
			// Each uploadinf file can set its own allow_type
			foreach ($uploads as $key => $upload) {
				$rules[] = "['" . $upload . "', 'matrixAssets.validators.uploadFile', 'allowType' => " . $uploads_type[$key] . ", 'on' => 'uploadingFile']";
			}			
		}

		return $rules;
	}

	/**
	 * Generates the rules for table fields.
	 * @param CDbTableSchema $table The table definition.
	 * @return array The rules for the table.
	 */
	protected function generateRelations()
	{
		if (!$this->buildRelations) {
			return [];
		}

		$schemaName='';
		if (($pos = strpos($this->tableName,'.')) !== false) {
			$schemaName=substr($this->tableName, 0, $pos);
		}

		$relations = [];
		foreach (Yii::app()->{$this->connectionId}->schema->getTables($schemaName) as $table) {
			if ($this->tablePrefix != '' && strpos($table->name, $this->tablePrefix) !== 0) {
				continue;
			}

			$tableName = $table->name;

			if ($this->isRelationTable($table)) {
				$pks = $table->primaryKey;
				$fks = $table->foreignKeys;

				$table0 = $fks[$pks[0]][0];
				$table1 = $fks[$pks[1]][0];
				$className0 = $this->generateClassName($table0);
				$className1 = $this->generateClassName($table1);

				$unprefixedTableName = $this->removePrefix($tableName);
				$relationName = $this->generateRelationName($table0, $table1, true);
				$relations[$className0][$relationName] = "[self::MANY_MANY, '$className1', '$unprefixedTableName($pks[0], $pks[1])']";
				$relationName = $this->generateRelationName($table1, $table0, true);

				$i = 1;
				$rawName = $relationName;
				while (isset($relations[$className1][$relationName])) {
					$relationName = $rawName . $i++;
				}

				$relations[$className1][$relationName] = "[self::MANY_MANY, '$className0', '$unprefixedTableName($pks[1], $pks[0])']";
			} else {
				$className = $this->generateClassName($tableName);
				foreach ($table->foreignKeys as $fkName => $fkEntry) {
					// Put table and key name in variables for easier reading
					$refTable = $fkEntry[0]; // Table name that current fk references to
					$refKey = $fkEntry[1];   // Key in that table being referenced
					$refClassName = $this->generateClassName($refTable);

					// Add relation for this table
					$relationName = $this->generateRelationName($tableName, $fkName, false);
					$relations[$className][$relationName] = "[self::BELONGS_TO, '$refClassName', '$fkName']";

					// Add relation for the referenced table
					$relationType = $table->primaryKey === $fkName ? 'HAS_ONE' : 'HAS_MANY';
					$relationName = $this->generateRelationName($refTable, $this->removePrefix($tableName, false), $relationType === 'HAS_MANY');
					$i = 1;
					$rawName = $relationName;
					while (isset($relations[$refClassName][$relationName])) {
						$relationName = $rawName . ($i++);
					}
					$relations[$refClassName][$relationName] = "[self::$relationType, '$className', '$fkName']";
				}
			}
		}
		return $relations;
	}	

	public function generateBehaviors($type)	
	{
		switch ($type) {
			case self::BEHAVIOR_DATE:
				return "\t\t\t'datetimeI18NBehavior' => [
				'class' => 'matrixAssets.behaviors.DateTimeI18NBehavior'
			],\n";
			case self::BEHAVIOR_AUDIT:
				return "\t\t\t'AuditBehavior' => [
				'class' => 'matrixAssets.behaviors.AuditBehavior',
				'createAttribute' => 'create_time',
				'createUser' => 'create_user_id',
				'updateAttribute' => 'update_time',
				'updateUser' => 'update_user_id',
			],\n";
			case self::BEHAVIOR_UPLOAD:
				return "\t\t\t'UploadBehavior' => [
				'class' => 'matrixAssets.behaviors.UploadBehavior',
			],";
		}
	}

    public function attributeLabels() {
        $customLabels = [
            'generateAudit' => 'Generar Auditoría',
            'messageSupport' => 'Generar Soporte para Traducciones',            
        ];
            
        return array_merge(parent::attributeLabels(), $customLabels);
    }

    public function rules() {
    	$parentRules = parent::rules();
        $customRules = [
            ['generateAudit, messageSupport', 'safe'],
        ];

        // Debemos reemplazar la rules que valida el nombre del modelo, ya que las tablas de la base de codigo
        // tienen el patron {{nombre-tabla}}
        foreach ($parentRules as $key => &$rule) {
        	if ($rule[0] === 'tablePrefix, tableName, modelPath') {
        		Yii::log($rule['pattern']);
        		$rule['pattern'] = '/^([\w\{]+[\w\.]*\}*|\*?|\w+\.\*)/';
        	}
        }

        return array_merge($parentRules, $customRules);
    }	
}