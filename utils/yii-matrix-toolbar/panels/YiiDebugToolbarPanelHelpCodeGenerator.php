<?php

class YiiDebugToolbarPanelHelpCodeGenerator extends YiiDebugToolbarPanel
{
	public $i = 'l';
	
    /**
     * Logs.
     *
     * @var array
     */
    private $_logs;

    /**
     * {@inheritdoc}
     */
    public function getMenuTitle()
    {
        return YiiDebug::t('Ayuda');
    }

    /**
     * {@inheritdoc}
     */
    public function getMenuSubTitle()
    {
        return 'Manual de Ayuda';
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return YiiDebug::t('Ayuda Generador de Código');
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->render('help');
    }

    public function getViewPath($checkTheme = false)
    {
        return dirname(__FILE__) . '/../views/panels';
    }    
}