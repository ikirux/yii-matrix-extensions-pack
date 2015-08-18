<?php

class TypeAheadAction extends CAction {
	
	public function run($model, $attribute, $query, $limit) {
				
		$class = new $model();	

		$data = $class::model()->findAll(['condition' => $attribute . ' LIKE :query', 'limit' => $limit, 'params' => [':query' => $query . '%'],]);
		
		echo CJSON::encode($data);
	}
}