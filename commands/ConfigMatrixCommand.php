<?php

/**
 * Realiza la configuracion inicial de una aplicacion que utiliza el boilerplate yii-matrix
 */
class ConfigMatrixCommand extends CConsoleCommand
{
	const dir_webserver_config = '/etc/apache2/sites-available/';

	public $webserverUser = 'www-data';
	public $osUser = 'ubuntu';
	public $dbName;
	public $dbUser = 'local';
	public $dbPass = 'local';
	public $virtualHostName;
	public $domainName;

	public function actionIndex()
	{
		$processUser = @posix_getpwuid(posix_geteuid());
		if ($processUser['name'] != "root") {
		   exit("You must run this script like root user \n");
		}

		$baseFolderProject = Yii::app()->basePath . '/..';
		$gitFolder = $baseFolderProject . '/.git';
		$mainConfigFile = Yii::app()->basePath . '/config/main.php';
		$consoleConfigFile = Yii::app()->basePath . '/config/console.php';
		echo "Eliminando directorio .git del boilerplate... ";
		echo $this->rrmdir($gitFolder) ? "Ok\n" : "Fail\n";	

		$prompt = readline("Ingrese el usuario del webserver a utilizar [$this->webserverUser]: ");
		if (!empty($prompt)) {
			$this->webserverUser = $prompt;
		}

		$prompt = readline("Ingrese el usuario de trabajo a utilizar [$this->osUser]: ");
		if (!empty($prompt)) {
			$this->osUser = $prompt;
		}

		echo "Configurando permisos de directorio... ";
		echo $this->rchmod($baseFolderProject, 0775) ? "Ok\n" : "Fail\n";

		echo "Configurando usuarios y grupo de directorio... ";
		echo $this->rchown($baseFolderProject) ? "Ok\n" : "Fail\n";

		do {
			$prompt = readline("Ingrese el nombre de la base de datos a utilizar: ");	
		} while (empty($prompt));
		$this->dbName = $prompt;
		
		$prompt = readline("Ingrese el nombre del usuario de base de datos a utilizar [$this->dbUser]: ");
		if (!empty($prompt)) {
			$this->dbUser = $prompt;
		}

		$prompt = readline("Ingrese la password del usuario de base de datos a utilizar [$this->dbPass]: ");
		if (!empty($prompt)) {
			$this->dbPass = $prompt;
		}

		$search = ['__DBNAME__', '__DBUSER__', '__DBPASS__'];
		$replace = [$this->dbName, $this->dbUser, $this->dbPass];

		echo "Generando archivos de configuración... ";
		// Se reemplazan configuraciones archivo principal
		$text = file_get_contents($mainConfigFile);
		$text = str_replace($search, $replace, $text);
		file_put_contents($mainConfigFile, $text);

		// Se reemplazan configuraciones archivo consola
		$text = file_get_contents($consoleConfigFile);
		$text = str_replace($search, $replace, $text);
		file_put_contents($consoleConfigFile, $text);		
		echo "Ok\n";

		echo "Cargando estructura base de la base de datos... ";
		shell_exec('php yiic migrate --interactive=0');
		shell_exec('php yiic migrate --migrationPath=user.migrations --interactive=0');
		echo "Ok\n";

		do {
			$prompt = readline("Ingrese el nombre del virtualhost a crear (no incluya la terminación .conf): ");	
		} while (empty($prompt));
		$this->virtualHostName = $prompt;

		do {
			$prompt = readline("Ingrese el nombre del dominio a utilizar: ");	
		} while (empty($prompt));		
		$this->domainName = $prompt;

		$tmpBaseFolderProject = realpath(Yii::app()->baseUrl . '/..');
		$virtualHostContent = <<<VIRTUAL
<VirtualHost *:80>
	<Directory "$tmpBaseFolderProject">
		Options Indexes FollowSymLinks
    		AllowOverride None
    		Order deny,allow
    		Allow from all
    		Satisfy all

    		IndexIgnore */*
    		RewriteEngine on

    		# if a directory or a file exists, use it directly
    		RewriteCond %{REQUEST_FILENAME} !-f
    		RewriteCond %{REQUEST_FILENAME} !-d

    		# otherwise forward it to index.php
    		RewriteRule . index.php
  	</Directory>

  	ServerAdmin correo@admin.com
  	DocumentRoot "$tmpBaseFolderProject"
  	ServerName $this->domainName
  	ServerAlias www.$this->domainName
  	ErrorLog "\${APACHE_LOG_DIR}/$this->virtualHostName.error.log"
  	CustomLog "\${APACHE_LOG_DIR}/$this->virtualHostName.access.log" common
</VirtualHost>
VIRTUAL;

		echo "Generando virtualhost... ";
		$virtualHost = $this->virtualHostName . '.conf';
		file_put_contents(self::dir_webserver_config . $virtualHost, $virtualHostContent);
		echo "Ok\n";

		echo "Habilitando virtualhost... ";
		shell_exec('a2ensite ' . $virtualHost);
		echo "Ok\n";

		echo "Reiniciando servidor web... ";
		shell_exec('service apache2 reload');
		echo "Ok\n";		
    }

	private function rrmdir($dir) {
	   	if (is_dir($dir)) {
	    	$objects = scandir($dir);
	     	foreach ($objects as $object) {
	       		if ($object != "." && $object != "..") {
	         		if (filetype($dir . "/" . $object) == "dir") {
	         			$this->rrmdir($dir . "/" . $object); 
	         		} else {
	         			unlink($dir . "/" . $object);
	         		}	
	       		}
	     	}
	     	reset($objects);
	     	return rmdir($dir);
	   	}
	} 

	private function rchmod($dir, $mode) {
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

		foreach($iterator as $item) {
		    if (!chmod($item, $mode)) {
		    	return false;
		    }
		}

		return true;
	}

	private function rchown($dir) {
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

		foreach($iterator as $item) {
		    if (!chown($item, $this->osUser) || !chgrp($item, $this->webserverUser)) {
		    	return false;
		    }
		}

		return true;
	}
}