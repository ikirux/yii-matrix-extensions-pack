<?php

class TreeBehavior extends CBehavior
{
	public $treeField = '';

	public function updateIndexesTree($parentId, $izquierda)
	{
		$tableName = $this->owner->tableName();
		$derecha = $izquierda + 1;

		$command = Yii::app()->db->createCommand("SELECT id FROM {$tableName} WHERE {$this->treeField}=:parentId");
		$command->bindParam(":parentId", $parentId, PDO::PARAM_INT);

		$dataReader = $command->query();

		foreach ($dataReader as $row) {
			$derecha = $this->updateIndexesTree($row['id'], $derecha);
		}

		$command = Yii::app()->db->createCommand("UPDATE {$tableName} SET izquierda = :izquierda, derecha = :derecha WHERE id = :parentId");
		$command->bindParam(":parentId", $parentId, PDO::PARAM_INT);
		$command->bindParam(":izquierda", $izquierda, PDO::PARAM_INT);
		$command->bindParam(":derecha", $derecha, PDO::PARAM_INT);
		$command->execute();

		return $derecha + 1;
	}

	public function getHeader()
	{
		$tableName = $this->owner->tableName();		
		$command = Yii::app()->db->createCommand("SELECT id FROM {$tableName} WHERE {$this->treeField} IS NULL");
		$dataReader = $command->query();

		$row = $dataReader->read();
		return $row['id'];
	}

	public function getPath()
	{
		$tableName = $this->owner->tableName();		
		$command = Yii::app()->db->createCommand("SELECT nombre FROM {$tableName} WHERE izquierda < {$this->owner->izquierda} AND derecha > {$this->owner->derecha} ORDER BY izquierda ASC");
		$dataReader = $command->query();

		return $dataReader;
	}

	public function printPath()
	{
		$dataReader = $this->getPath();

		$path = '';
		foreach($dataReader as $key => $row) {
			$path .= $row['nombre'] . "\n";
		}

		return $path;
	}
}