
<?php

class ChWidget extends CWidget
{
    /**
    * @var assets handle
    */
    protected $_assetsUrl;
	
	public $type;
	public $properties;
	
    /**
    * Register the ChartJS lib
    */
	public function init()
	{
		$widgetFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . $this->type . '.php';
		if (!is_file($widgetFile))
			throw new CException('Widget ' . $this->type . ' not found');
		require_once $widgetFile;
	}
	
    /**
    * Register the ChartJS lib
    */
	public function run()
	{
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $jsFilename = YII_DEBUG ? 'Chart.js' : 'Chart.min.js';
        $cssFilename = YII_DEBUG ? 'styles.css' : 'styles.min.css';
		
        $cs->registerScriptFile($this->getAssetsUrl().'/js/'.$jsFilename, CClientScript::POS_HEAD);
        $cs->registerCssFile($this->getAssetsUrl() . "/css/".$cssFilename, '');
		
		$this->widget($this->type, $this->properties);
	}
	
    /**
    * Returns the URL to the published assets folder.
    * @return string the URL
    */
    protected function getAssetsUrl()
    {
        if (isset($this->_assetsUrl))
            return $this->_assetsUrl;
        else
        {
			$assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            $assetsUrl = Yii::app()->assetManager->publish($assetsPath, true, -1, YII_DEBUG);
            return $this->_assetsUrl = $assetsUrl;
        }
    }
}