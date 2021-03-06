<?php
use wcf\system\dashboard\DashboardHandler;
use wcf\system\WCF;
use cms\data\stylesheet\StylesheetAction;
use cms\data\module\ModuleAction;

$package = $this->installation->getPackage();

//default values
DashboardHandler::setDefaultValues('de.codequake.cms.news.newsList', array(
    'de.codequake.cms.latestNews' => 1
));

//install date
$sql = "UPDATE	wcf".WCF_N."_option
    SET	optionValue = ?
    WHERE	optionName = ?";
$statement = WCF::getDB()->prepareStatement($sql);
$statement->execute(array(TIME_NOW, 'cms_install_date'));
