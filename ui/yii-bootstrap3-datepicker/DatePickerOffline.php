<?php
/**
 * DatePickerOffline widget class
 *
 * @author: Carlos Pinto <ikirux@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 *
 */
class DatePickerOffline extends CWidget
{
    /**
     * Assets package ID.
     */
    const PACKAGE_ID = 'yii-datepicker-bootstrap3';

    /**
    * @var array
    */
    public $package = [];

	/**
	 * @var array the options for the Bootstrap JavaScript plugin.
	 */
	public $options = [];

	/**
	 * @var array the options for the Bootstrap JavaScript plugin.
	 */
	public $cssClass;

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
        parent::init();

		if (!isset($this->options['language'])) {
			$this->options['language'] = Yii::app()->language;
		}

		if (!isset($this->options['format'])) {
			$this->options['format'] = 'dd/mm/yyyy';
		} 
		
		if (!isset($this->options['weekStart'])) {
			$this->options['weekStart'] = 0; // Sunday
		}

        $this->registerClientScript();
	}

	/**
	 * Registers required client script for bootstrap datepicker. It is not used through bootstrap->registerPlugin
	 * in order to attach events if any
	 */
	public function registerClientScript()
	{
        // Prepare script package.
        $this->package = array_merge([
            'baseUrl' => $this->getAssetsUrl(),
            'js' => [
                YII_DEBUG ? 'js/bootstrap-datepicker.js' : 'js/bootstrap-datepicker.min.js',
                $this->options['language'] != "en" ? 'js/locales/bootstrap-datepicker.' . $this->options['language'] . '.js' : '',
            ],
            'css' => [
                YII_DEBUG ? 'css/datepicker3.css' : 'css/datepicker3.min.css'
            ],
            'depends' => [
                'jquery',
                'jquery.ui',
            ],
        ], $this->package);

        $clientScript = Yii::app()->getClientScript();
		$options = !empty($this->options) ? CJavaScript::encode($this->options) : '';

        $clientScript
            ->addPackage(self::PACKAGE_ID, $this->package)
            ->registerPackage(self::PACKAGE_ID)
            ->registerScript(
                self::PACKAGE_ID,
                'jQuery("body").on("focus", ".' . $this->cssClass .'", function() {
                    $(this).datepicker(' . $options . ');
                });');            
	}

    /**
    * Get the assets path.
    * @return string
    */
    public function getAssetsPath()
    {
        return dirname(__FILE__) . '/assets';
    }

    /**
    * Publish assets and return url.
    * @return string
    */
    public function getAssetsUrl()
    {
        return Yii::app()->getAssetManager()->publish($this->getAssetsPath());
    }	
}