<?php

/**
 * FontAwesome loader class file.
 * @author Carlos Pinto <ikirux@gmail.com>
 */

/**
 * Bootstrap application component.
 */
class FontAwesome extends CApplicationComponent {

    /**
     * Assets package ID.
     */
    const PACKAGE_ID = 'fontawesome-component';

    /**
     * @var string path to assets
     */
    protected $assetsPath;

    /**
     * @var string URL to assets
     */
    protected $assetsUrl;    

    /**
     * Initializes the component.
     */
    public function init() {
        // Prevents the extension from registering scripts and publishing assets when ran from the command line.
        if (Yii::app() instanceof CConsoleApplication) {
            return;
        }

        parent::init();

        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        }

        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath);
        }

        $this->registerClientScript();        
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
                'css' => [
                    'css/font-awesome' . (YII_DEBUG ? '' : '.min') . '.css',
                ],
            ];
        }
        $cs->registerPackage(self::PACKAGE_ID);
    }
}