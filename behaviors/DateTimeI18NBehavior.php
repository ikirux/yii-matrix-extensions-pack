<?php

/*
 * DateTimeI18NBehavior
 * Automatically converts date and datetime fields to I18N format
 * 
 * Author: Ricardo Grana <rickgrana@yahoo.com.br>, <ricardo.grana@pmm.am.gov.br>
 * Version: 1.1
 * Requires: Yii 1.0.9 version 
 */

class DateTimeI18NBehavior  extends CActiveRecordBehavior
{
	public $dateOutcomeFormat = 'Y-m-d';
	public $dateTimeOutcomeFormat = 'Y-m-d H:i:s';

	public $dateIncomeFormat = 'yyyy-MM-dd';
	public $dateTimeIncomeFormat = 'yyyy-MM-dd hh:mm:ss';

	public $extra_date_field = [];
	public $_original_dates = [];

	public function convertMachineFormatDate($origin)
	{
		//search for date/datetime columns. Convert it to pure PHP date format
		foreach ($origin->tableSchema->columns as $columnName => $column) {
			if (!strlen($origin->$columnName)) {
				$origin->$columnName = null;			 
				continue;
			}

			if ($column->dbType == 'date') {
				$this->_original_dates[$columnName] = $origin->$columnName;				
				$origin->$columnName = date($this->dateOutcomeFormat, CDateTimeParser::parse($origin->$columnName, Yii::app()->locale->dateFormat));
			} elseif ($column->dbType == 'datetime') { // datetime
				$this->_original_dates[$columnName] = $origin->$columnName;				
				$origin->$columnName = date($this->dateTimeOutcomeFormat, 
					CDateTimeParser::parse($origin->$columnName, 
						strtr(Yii::app()->locale->dateTimeFormat, 
							array("{0}" => Yii::app()->locale->timeFormat,
								  "{1}" => Yii::app()->locale->dateFormat))));
			}			
		}

		foreach ($this->extra_date_field as $columnName) {
			if (!strlen($origin->$columnName)) {
				$origin->$columnName = null;			 
				continue;
			}
						
			$this->_original_dates[$columnName] = $origin->$columnName;				
			$origin->$columnName = date($this->dateOutcomeFormat, CDateTimeParser::parse($origin->$columnName, Yii::app()->locale->dateFormat));
		}	

		return true;
	}

	public function convertHumanFormatDate($origin)
	{
		foreach ($origin->tableSchema->columns as $columnName => $column) {
			if (!strlen($origin->$columnName)) { 
				continue;
			}

			if ($column->dbType == 'date') {
				$this->_original_dates[$columnName] = $origin->$columnName;	
				$origin->$columnName = Yii::app()->dateFormatter->formatDateTime(
					CDateTimeParser::parse($origin->$columnName, $this->dateIncomeFormat), 'medium', null);
			} elseif ($column->dbType == 'datetime') { // datetime
				$this->_original_dates[$columnName] = $origin->$columnName;
               	$newval = CDateTimeParser::parse($origin->$columnName, $this->dateTimeIncomeFormat);
               	// Check convert works, otherwise if source date is 0000-00-00 00:00:00 would return NOW()
				$origin->$columnName = $newval !== FALSE ? Yii::app()->dateFormatter->formatDateTime($newval, 'medium', 'medium') : null;
			}
		}

		foreach ($this->extra_date_field as $columnName) {
			$this->_original_dates[$columnName] = $origin->$columnName;	
			$origin->$columnName = Yii::app()->dateFormatter->formatDateTime(
					CDateTimeParser::parse($origin->$columnName, $this->dateIncomeFormat), 'medium', null);						
		}			

		return true;
	}

	public function beforeSave($event)
	{
		return $this->convertMachineFormatDate($event->sender);
	}
	
	/**
	 * Cuando hay un error generalmente se envia el mismo modelo a la vista
	 * por lo que ejecutaremos la transformacion a formato de fecha usuario
	 */
	public function afterSave($event)
	{
		return $this->convertHumanFormatDate($event->sender);
	}	

	public function afterFind($event)
	{
		parent::afterFind($event);
		$this->convertHumanFormatDate($event->sender);
	}
}