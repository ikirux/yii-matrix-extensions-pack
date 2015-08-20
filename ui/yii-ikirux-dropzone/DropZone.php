<?php

class Dropzone extends CWidget {
    /**
     * Assets package ID.
     */
    const PACKAGE_ID = 'yii-ikirux-dropzone';   
    /**
     * @var string path to assets
     */
    protected $assetsPath;
    /**
     * @var string URL to assets
     */
    protected $assetsUrl;
    /**
     * @var string The name of the file field
     */
    public $name = false;
    /**
     * @var CModel The model for the file field
     */
    public $model = false;
    /**
     * @var string The attribute of the model
     */
    public $attribute = false;
    /**
     * @var array An array of options that are supported by Dropzone
     */
    public $options = [];
    /**
     * @var string The URL that handles the file upload
     */
    public $url = false;
    /**
     * @var string The Javascript to be called in case of a succesful upload
     */
    public $onSuccess = false;

    public $idDiv = "fileup";
    public $machineName = "";

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

        if (!$this->url || $this->url == '') {
            $this->url = Yii::app()->createUrl('site/upload');
        }

        if (!$this->name && ($this->model && $this->attribute) && $this->model instanceof CModel) {
            $this->name = CHtml::activeName($this->model, $this->attribute);
        }
            
        $options = CMap::mergeArray([
            'url' => $this->url,
            'parallelUploads' => 1,
            'paramName' => $this->name,
            'init' => "js:function(){this.on('success',function(file){{$this->onSuccess}});}"
        ], $this->options);

        $this->registerClientScript($options);
    }

    /**
     * Create a div and the appropriate Javascript to make the div into the file upload area
     */
    public function run() 
    {
        echo CHtml::activeLabel($this->model, $this->attribute);
        echo CHtml::openTag('div', ['class' => 'dropzone', 'id' => $this->idDiv]);
        echo CHtml::closeTag('div');
    }

    /**
     * Register CSS and scripts.
     */
    protected function registerClientScript($options)
    {
        $options = CJavaScript::encode($options);
        $script = "Dropzone.options.$this->idDiv = {$options}";

        $cs = Yii::app()->clientScript;
        if (!isset($cs->packages[self::PACKAGE_ID])) {
            $cs->packages[self::PACKAGE_ID] = [
                'basePath' => $this->assetsPath,
                'baseUrl' => $this->assetsUrl,
                'js' => [
                    'dropzone' . (YII_DEBUG ? '' : '.min') . '.js',
                ],
                'css' => [
                    'dropzone' . (YII_DEBUG ? '' : '.min') . '.css',
                ],
                'depends' => [
                    'jquery',
                ],
            ];
        }
        $cs->registerPackage(self::PACKAGE_ID);
        $cs->registerScript(__CLASS__ . '#' . $this->id, $script, CClientScript::POS_END);
    }
}