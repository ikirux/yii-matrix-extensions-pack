<?php

/**
 * Spinedit widget
 *
 * @author Carlos Pinto <carlos.pinto@developen.cl>
 * @version 1.0
 * @license public domain (http://unlicense.org)
 * @package extensions.touchspin
 * @link http://plugins.jquery.com/bootstrap-touchspin/
 */

class TouchSpinWidget extends CInputWidget {
	/**
	 * Assets package ID.
	 */
	const PACKAGE_ID = 'touchspin-widget';

	/**
	 * @var string path to assets
	 */
	protected $assetsPath;

	/**
	 * @var string URL to assets
	 */
	protected $assetsUrl;

	/**
	 * @var array spinedit options
	 * @see http://www.virtuosoft.eu/code/bootstrap-touchspin/
	 */
	public $options = [];

	/**
	 * @var string|null textField selector for jQuery
	 */
	public $selector;

	/**
	 * Init widget
	 */
	public function init()
	{
		parent::init();
		if ($this->assetsPath === null) {
			$this->assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
		}

		if ($this->assetsUrl === null) {
			$this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath);
		}

		if ($this->selector === null) {
			list($this->name, $this->id) = $this->resolveNameId();
			$this->selector = '#' . $this->id;
		}

		$this->registerClientScript();
	}

	/**
	 * Run widget.
	 */
	public function run()
	{
		if ($this->hasModel()) {
			echo '<div class="form-group ">';
			echo CHtml::activeLabelEx($this->model, $this->attribute);
			echo '<div>';
			echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
			echo '</div>';
			echo '</div>';
		} else if ($this->selector !== null) {
			echo CHtml::textField($this->name, $this->value, $this->htmlOptions);
		}
	}

	/**
	 * Register CSS and scripts.
	 */
	protected function registerClientScript()
	{
		$cs = Yii::app()->clientScript;
		if (!isset($cs->packages[self::PACKAGE_ID])) {
			$cs->packages[self::PACKAGE_ID] = [
				'basePath' => $this->assetsPath,
				'baseUrl' => $this->assetsUrl,
				'js' => [
					'js/jquery.bootstrap-touchspin' . (YII_DEBUG ? '' : '.min') . '.js',
				],
				'css' => [
					'css/jquery.bootstrap-touchspin' . (YII_DEBUG ? '' : '.min') . '.css',
				],
				'depends' => [
					'jquery',
				],
			];
		}
		$cs->registerPackage(self::PACKAGE_ID);

		$cs->registerScript(
			__CLASS__ . '#' . $this->id,
			'jQuery(' . CJavaScript::encode($this->selector) . ').TouchSpin(' . CJavaScript::encode($this->options) . ');',
			CClientScript::POS_READY
		);
	}
}