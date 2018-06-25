<?php
/**
 * ChartJS widget class
 *
 * @author: Carlos Pinto <ikirux@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 *
 */
class ChartJS extends CWidget
{
    /**
     * Assets package ID.
     */
    const PACKAGE_ID = 'yii-chartjs';

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
	public $data;
	
	/**
	 * @var array the options for the Bootstrap JavaScript plugin.
	 */
	public $type;
	
	/**
	 * @var array the options for the Bootstrap JavaScript plugin.
	 */
	public $width;
	
	/**
	 * @var array the options for the Bootstrap JavaScript plugin.
	 */
	public $height;

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
        parent::init();
        
		if (!isset($this->width)) {
			$this->width = 400;
		} 
		
		if (!isset($this->height)) {
			$this->height = 400;
		} 		
        
        $this->createHTML();
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
                YII_DEBUG ? 'js/Chart.js' : 'js/Chart.min.js',
            ],
            'css' => [],
            'depends' => [
                'jquery',
            ],
        ], $this->package);

        $clientScript = Yii::app()->getClientScript();
		$options = !empty($this->options) ? CJavaScript::encode($this->options) : '{}';
		$data = !empty($this->data) ? CJavaScript::encode($this->data) : '{}';
        $ctx = $this->generateRandomString();		
		
        $clientScript
            ->addPackage(self::PACKAGE_ID, $this->package)
            ->registerPackage(self::PACKAGE_ID)
            ->registerScript(
                self::PACKAGE_ID . '_' . $ctx,
                'var ctx_' . $ctx . ' = $("#' . $this->getId() . '");
                 var chart_' . $ctx . ' = new Chart(ctx_' . $ctx .' , {
                    data: ' . $data . ',
                    type: "' . $this->type . '",
                    options: ' . $options . '
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
    
    public function createHTML()
    {
        echo CHtml::openTag('canvas', ['id' => $this->getId(), 'width' => $this->width, 'height' => $this->height]);
        echo CHtml::closeTag('canvas');
    }
    
    private function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
