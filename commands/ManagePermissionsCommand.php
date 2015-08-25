<?php

/**
 * Creara los migrates dependiendo del nombre de controlador, y algunos
 * nombres de operaciones entregadas
 */
class ManagePermissionsCommand extends CConsoleCommand
{
	const TYPE_ITEM = 0;
	const TYPE_TASK = 1;

	private $specialActions = "";
	private $fpMigrate = null;
	private $migrateName;
	private $controller;

	public $basicActions = "index,view,create,update,delete,listPdf,listExcel";
    public $itemChildTable = '{{AuthItemChild}}';
    public $itemTable = '{{AuthItem}}';

	public function actionCreate($controller, $module = '', $actions = '', $delimiter = ',') {
		$this->migrateName = $this->getNameMigrate();

		// Revisamos que acciones vamos a trabajar
		$this->basicActions = empty($this->basicActions) ? [] : explode($delimiter, $this->basicActions);
		$this->specialActions = empty($actions) ? [] : explode($delimiter, $actions);
		$this->controller = str_replace('Controller', '', $controller);

		$controllerActions = [];

		$tmpArray = array_merge($this->basicActions, $this->specialActions);
		foreach ($tmpArray as $action) {
			if (!empty($module)) {
				$controllerActions["$module.$this->controller.$action"] = ucwords($action) . " $this->controller del módulo $module";	
			} else {
				$controllerActions["$this->controller.$action"] = ucwords($action) . " $this->controller";	
			}			
		}

		if (!empty($module)) {
	    	$task = [
	    		"name" => $this->controller . ucwords($module) . "Admin",
	    		"desc" => "Administración Completa de $this->controller del módulo $module",
	    	];
    	} else {
	    	$task = [
	    		"name" => $this->controller . "Admin", 
	    		"desc" => "Administración Completa de $this->controller",
	    	];
    	}

		$this->createMigrateFile();
		$this->writeMigrateCrudTemplate($controllerActions, $task);
		fclose($this->fpMigrate);
	}

	public function actionCreateModule($moduleName) {
		$this->migrateName = $this->getNameMigrate();
		$this->createMigrateFile();
		$this->writeMigrateModuleTemplate("{$moduleName}.access", "Ingreso al módulo {$moduleName}");
		fclose($this->fpMigrate);
	}

	private function getNameMigrate()
	{
		return 'm' . gmdate('ymd_His') . '_load_permissions';
	}

	private function createMigrateFile($path = 'application.migrations')
	{
		if ($this->fpMigrate == null) {
			if (!$this->fpMigrate = @fopen(Yii::getPathOfAlias($path) . DIRECTORY_SEPARATOR . $this->migrateName . '.php', 'w+')) {
				exit('Error: No se pudo crear el archivo migrate');
			}
		}
	}

	private function writeMigrateCrudTemplate($controllerActions = [], $task = [])
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
		// Se cargan los items' . "\n";

		foreach ($controllerActions as $key => $action) {
			$content .=	 "\t\t" . '$sql = "INSERT INTO `' . $this->itemTable . '` VALUES(\'' . $key . '\', ' . self::TYPE_ITEM . ', \'' . $action . '\', NULL, \'N;\')";' . "\n";
			$content .=	 "\t\t" . '$this->execute($sql);' . "\n\n";
		}		

		$content .= "\t\t" . '// Se carga la tarea que agrupara los items cargados';
		$content .=	 "\n\t\t" . '$sql = "INSERT INTO `' . $this->itemTable . '` VALUES(\'' . $task['name'] . '\', ' . self::TYPE_TASK . ', \'' . $task['desc'] . '\', NULL, \'N;\')";' . "\n";
		$content .=	 "\t\t" . '$this->execute($sql);

		// Ahora se asocian los items a la tarea' . "\n";
		foreach ($controllerActions as $key => $action) {
			$content .=	 "\t\t" . '$sql = "INSERT INTO `' . $this->itemChildTable . '` VALUES(\'' . $task['name'] . '\', \'' . $key . '\')";' . "\n";
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

	private function writeMigrateModuleTemplate($name, $desc)
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
		// Se cargan los items' . "\n";

		$content .=	 "\t\t" . '$sql = "INSERT INTO `' . $this->itemTable . '` VALUES(\'' . $name . '\', ' . self::TYPE_ITEM . ', \'' . $desc . '\', NULL, \'N;\')";' . "\n";
		$content .=	 "\t\t" . '$this->execute($sql);';

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