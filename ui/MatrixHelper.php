<?php
/**
 * User: Pascal Brewing
 * Date: 11.09.13
 * Time: 11:22
 * @package bootstrap.helpers
 * Class BsHtml
 */
 
class MatrixHelper extends CHtml
{
    public static function buildCountryItems()
    {
        $sql = "SELECT l.name, l.code, cc.code FROM `{{Language}}` l LEFT JOIN `{{CountryCode}}` cc ON (country_code_id = cc.id)";
        $dataReader = Yii::app()->db->createCommand($sql)->query();
        $dataReader->bindColumn(1, $name);
        $dataReader->bindColumn(2, $codeLanguage);
        $dataReader->bindColumn(3, $codeCountry);
        $currentCodeFlag = '';
        $languageParam = Yii::app()->urlManager->languageParam;
        $items = [];
        while ($dataReader->read() !== false) {
            if (Yii::app()->language == $codeLanguage) {
                $currentCodeFlag = $codeCountry;
            } else {
                $items[] = [
                    'icon' => "glyphicon flag-icon flag-icon-$codeCountry",
                    'class' => 'bootstrap.widgets.BsNav',
                    'type' => 'navbar',
                    'label' => $name,
                    'url' => ['', $languageParam => $codeLanguage],                    
                ];
            }
        }

        $languageListMenu = [
            'class' => 'bootstrap.widgets.BsNav',
            'type' => 'navbar',
            'activateParents' => true,
            'items' => [
                [
                    'icon' => "glyphicon flag-icon flag-icon-$currentCodeFlag",
                    'class' => 'bootstrap.widgets.BsNav',
                    'type' => 'navbar',
                    'activateParents' => true,
                    'style' => 'padding-left: 1em',
                    'items' => $items,
                ],
            ],
            'htmlOptions' => [
                'pull' => BsHtml::NAVBAR_NAV_PULL_RIGHT,
            ]                
        ];

        return $languageListMenu;
    }
}