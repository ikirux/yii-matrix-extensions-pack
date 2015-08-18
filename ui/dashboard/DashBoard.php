<?php
/**
 * DashBoard class file.
 * @author Carlos Pinto <ikirux@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @since 0.0.1
 */

/**
 * DashBoard widget.
 * @see https://github.com/ikirux/matrix
 */ 
class DashBoard extends CWidget
{
    // Types
    const TYPE_DANGER = 1;
    const TYPE_PRIMARY = 2;    
    const TYPE_INVERSE = 3; 
    const TYPE_SUCCESS = 4; 
    const TYPE_WARNING = 5; 
    const TYPE_INFO = 6; 

    /**
    * List of social services to use.
    * Buttons will be generated in the order specified here.
    * Valid types are:
    * self::TYPE_DANGER, self::TYPE_PRIMARY and self::TYPE_INVERSE
    * self::TYPE_SUCCESS, self::TYPE_WARNING and self::TYPE_INFO    
    * @var array
    */
    public $types = [
        self::TYPE_DANGER => 'red danger',
        self::TYPE_PRIMARY => 'blue primary',
        self::TYPE_INVERSE => 'inverse',
        self::TYPE_SUCCESS => 'green success',
        self::TYPE_WARNING => 'yellow warning',
        self::TYPE_INFO => 'lite-blue info',   
    ];

    /**
     * @var integer limit element per row 
     */
    public $limitRow = 6;

    /**
     * @var string dashboard title
     */
    public $title = 'Shortcuts';

    /**
     * @var array data and color information for the chart
     */
    public $elements = [];

    /**
     * Runs the widget.
     */
    public function run()
    {
        echo CHtml::openTag('div', ['class' => 'dashboard-group']);
        // Title section
        echo CHtml::openTag('div', ['class' => 'header aligncenter']);
        echo CHtml::tag('h2', [], $this->title, true);
        echo CHtml::closeTag('div');
        // Elements section
        $countElement = 0;
        echo CHtml::openTag('ul', ['class' => 'quick-menu list-unstyled']);
        foreach ($this->elements as $element) {
            if ($countElement != 0 && $countElement % $this->limitRow == 0) {
                echo CHtml::closeTag('ul');
                echo CHtml::openTag('ul', ['class' => 'quick-menu list-unstyled']);
            }

            $visible = (isset($element['visible'])) ? $element['visible'] : true;

            if ($visible) {
                $type = (isset($this->types[$element['type']])) ? $this->types[$element['type']] : $this->types[self::TYPE_DANGER];

                echo CHtml::openTag('li', ['class' => $type]);
                echo CHtml::openTag('a', ['href' => $element['url']]);
                echo CHtml::tag('i', ['class' => 'fa ' . $element['icon']], '', true);
                echo CHtml::tag('span', [], $element['title'], true);
                echo CHtml::closeTag('a');
                echo CHtml::closeTag('li');
                $countElement++;
            }
        }
        echo CHtml::closeTag('ul');
        echo CHtml::closeTag('div');

        // Register css
        $assetsPath = Yii::getPathOfAlias('ext.ui.dashboard.assets');
        $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, false);   
        $cs = Yii::app()->getClientScript();
        $cssFilename = YII_DEBUG ? 'dashboard.css' : 'dashboard.min.css';
        $cs->registerCssFile($assetsUrl . "/$cssFilename", '');
    }
}
