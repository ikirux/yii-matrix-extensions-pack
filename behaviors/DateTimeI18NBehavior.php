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

	public function convertMachineFormatDate($origin)
	{
		//search for date/datetime columns. Convert it to pure PHP date format
		foreach ($origin->tableSchema->columns as $columnName => $column) {
			if (($column->dbType != 'date') and ($column->dbType != 'datetime')) {
				continue;
			}
									
			if (!strlen($origin->$columnName)) { 
				$origin->$columnName = null;
				continue;
			}
			
			if (($column->dbType == 'date')) {				
				$origin->$columnName = date($this->dateOutcomeFormat, CDateTimeParser::parse($origin->$columnName, Yii::app()->locale->dateFormat));
			} else { // datetime				
				$origin->$columnName = date($this->dateTimeOutcomeFormat, 
					CDateTimeParser::parse($origin->$columnName, 
						strtr(Yii::app()->locale->dateTimeFormat, 
							array("{0}" => Yii::app()->locale->timeFormat,
								  "{1}" => Yii::app()->locale->dateFormat))));
			}			
		}

		return true;
	}

	public function convertHumanFormatDate($origin)
	{
		foreach ($origin->tableSchema->columns as $columnName => $column) {
			if (($column->dbType != 'date') and ($column->dbType != 'datetime')) continue;

            // Store original somewhere
            if (isset($origin->_original_dates)) {
            	$origin->_original_dates [$columnName] = $event->sender->$columnName;
            }

			if (!strlen($origin->$columnName)) {
				$origin->$columnName = null;
				continue;
			}

			if ($column->dbType == 'date') {	
				$origin->$columnName = Yii::app()->dateFormatter->formatDateTime(
					CDateTimeParser::parse($origin->$columnName, $this->dateIncomeFormat), 'medium', null);
			} else {	
               	$newval = CDateTimeParser::parse($origin->$columnName, $this->dateTimeIncomeFormat);
               	// Check convert works, otherwise if source date is 0000-00-00 00:00:00 would return NOW()
				$origin->$columnName = $newval !== FALSE ? Yii::app()->dateFormatter->formatDateTime($newval, 'medium', 'medium') : null;
			}
		}

		return true;
	}

	public function beforeSave($event)
	{
		return $this->convertMachineFormatDate($event->sender);
	}

	public function afterFind($event)
	{
		parent::afterFind($event);
		$this->convertHumanFormatDate($event->sender);
	}
}