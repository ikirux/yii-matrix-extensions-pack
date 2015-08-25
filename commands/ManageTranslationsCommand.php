<?php

/**
 * Creara los migrates de las traducciones, al momento de crear el modelo y el CRUD
 */
class ManageTranslationsCommand extends CConsoleCommand
{
	private $migrateName;
	private $fpMigrate = null;
	private $crudSingularOperations = [
		'Crear',
		'Actualizar',
		'Borrar',
		'Ver',
	];
	private $crudPluralOperations = [
		'Listado',
		'Listar',	
	];
	private $defaultOperations = [
		'Listar',
		'Búsqueda Avanzada',
		'Crear',
		'Actualizar',
		'Generar archivo PDF',
		'Generar archivo Excel',
		'Guardar',
		'Buscar',
		'La operación se realizó con éxito',
		'Se ha producido un error al realizar la operación',
		'Request no válido.',
		'La página requerida no existe.',
		'Campos con',
		'son requeridos.',
	];

    public $translationTable = '{{SourceMessage}}';


	public function actionCreateModel($model) {
		$this->migrateName = $this->getNameMigrate($model);

		$model = new $model;

		$this->createMigrateFile();
		$this->writeMigrateModelTemplate($model);
		fclose($this->fpMigrate);
	}

	public function actionCreateCRUD($singular, $plural) {
		$this->migrateName = $this->getNameMigrate('crud_' . str_replace(' ', '_', $singular));

		$this->createMigrateFile();
		$this->writeMigrateCRUDTemplate($singular, $plural);
		fclose($this->fpMigrate);
	}	

	public function actionCreateDefault() {
		$this->migrateName = $this->getNameMigrate('default');

		$this->createMigrateFile();
		$this->writeMigrateDefaultTemplate();
		fclose($this->fpMigrate);
	}	

	private function getNameMigrate($suffix = '')
	{
		return 'm' . gmdate('ymd_His') . '_load_translations' . (empty($suffix) ? "" : "_$suffix");
	}

	private function createMigrateFile($path = 'application.migrations')
	{
		if ($this->fpMigrate == null) {
			if (!$this->fpMigrate = @fopen(Yii::getPathOfAlias($path) . DIRECTORY_SEPARATOR . $this->migrateName . '.php', 'w+')) {
				exit('Error: No se pudo crear el archivo migrate');
			}
		}
	}

	private function writeMigrateModelTemplate($model)
	{
		$content = '<?php

class ' . $this->migrateName . ' extends CDbMigration
{
	/*	
	public function up()
	{
	}

	public function down()
	{
		echo "' . $this->migrateName . ' does not support migration down.\\n";
		return false;
	}
	*/

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		// Se cargan las traducciones del modelo' . "\n";
		$labels = $model->attributeLabels();
		foreach ($labels as $key => $label) {
			if ($key != 'id') {
				$content .=	 "\t\t" . '$sql = "INSERT IGNORE INTO `' . $this->translationTable . '` VALUES(\'default\', \'' . $label . '\')";' . "\n";
				$content .=	 "\t\t" . '$this->execute($sql);' . "\n\n";				
			}
		}		

		$content = substr($content, 0, strlen($content) - 2);

		$content .=	 '
	}

	public function safeDown()
	{
		echo "' . $this->migrateName . ' does not support migration down.\\n";
		return false;		
	}
}';

		if (!fwrite($this->fpMigrate, $content)) {
			exit('Error: No se pudo escribir en el archivo migrate');
		}
	}

	private function writeMigrateCRUDTemplate($singular, $plural)
	{
		$content = '<?php

class ' . $this->migrateName . ' extends CDbMigration
{
	/*	
	public function up()
	{
	}

	public function down()
	{
		echo "' . $this->migrateName . ' does not support migration down.\\n";
		return false;
	}
	*/

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		// Se cargan las traducciones del CRUD' . "\n";
		foreach ($this->crudSingularOperations as $key => $operation) {
			$content .=	 "\t\t" . '$sql = "INSERT IGNORE INTO `' . $this->translationTable . '` VALUES(\'default\', \'' . $operation . ' ' . $singular . '\')";' . "\n";
			$content .=	 "\t\t" . '$this->execute($sql);' . "\n\n";				
		}		

		foreach ($this->crudPluralOperations as $key => $operation) {
			$content .=	 "\t\t" . '$sql = "INSERT IGNORE INTO `' . $this->translationTable . '` VALUES(\'default\', \'' . $operation . ' ' . $plural . '\')";' . "\n";
			$content .=	 "\t\t" . '$this->execute($sql);' . "\n\n";				
		}		

		$content = substr($content, 0, strlen($content) - 2);

		$content .=	 '
	}

	public function safeDown()
	{
		echo "' . $this->migrateName . ' does not support migration down.\\n";
		return false;		
	}
}';

		if (!fwrite($this->fpMigrate, $content)) {
			exit('Error: No se pudo escribir en el archivo migrate');
		}
	}

	private function writeMigrateDefaultTemplate()
	{
		$content = '<?php

class ' . $this->migrateName . ' extends CDbMigration
{
	/*	
	public function up()
	{
	}

	public function down()
	{
		echo "' . $this->migrateName . ' does not support migration down.\\n";
		return false;
	}
	*/

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		// Se cargan las traducciones del CRUD' . "\n";
		foreach ($this->defaultOperations as $key => $operation) {
			$content .=	 "\t\t" . '$sql = "INSERT IGNORE INTO `' . $this->translationTable . '` VALUES(\'default\', \'' . $operation . '\')";' . "\n";
			$content .=	 "\t\t" . '$this->execute($sql);' . "\n\n";				
		}		

		$content = substr($content, 0, strlen($content) - 2);

		$content .=	 '
	}

	public function safeDown()
	{
		echo "' . $this->migrateName . ' does not support migration down.\\n";
		return false;		
	}
}';

		if (!fwrite($this->fpMigrate, $content)) {
			exit('Error: No se pudo escribir en el archivo migrate');
		}
	}
}