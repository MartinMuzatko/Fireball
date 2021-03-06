<?php
namespace cms\page;
use cms\data\category\NewsCategoryNodeTree;
use wcf\system\menu\page\PageMenu;
use wcf\system\request\LinkHandler;
use wcf\system\MetaTagHandler;
use cms\data\news\ViewableNewsList;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\dashboard\DashboardHandler;
use wcf\system\WCF;
use wcf\page\SortablePage;

/**
 * @author	Jens Krumsieck
 * @copyright	2014 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.cms
 */

class NewsCategoryListPage extends SortablePage{

    public $activeMenuItem = 'cms.page.news';
    public $enableTracking = true;    
    public $neededModules = array('MODULE_NEWS');
    public $objectListClassName = 'cms\data\news\ViewableNewsList';    
    public $itemsPerPage = CMS_NEWS_PER_PAGE;
    public $limit = 10;
    public $categoryList = null;
    
    
    public function readData(){
        parent::readData();
        $categoryTree = new NewsCategoryNodeTree('de.codequake.cms.category.news');
        $this->categoryList = $categoryTree->getIterator();
        $this->categoryList->setMaxDepth(0);
        
        if (PageMenu::getInstance()->getLandingPage()->menuItem == 'cms.page.news') {
            WCF::getBreadcrumbs()->remove(0);

            MetaTagHandler::getInstance()->addTag('og:url', 'og:url', LinkHandler::getInstance()->getLink('NewsList', array('application' => 'cms')), true);
            MetaTagHandler::getInstance()->addTag('og:type', 'og:type', 'website', true);
            MetaTagHandler::getInstance()->addTag('og:title', 'og:title', WCF::getLanguage()->get(PAGE_TITLE), true);
            MetaTagHandler::getInstance()->addTag('og:description', 'og:description', WCF::getLanguage()->get(PAGE_DESCRIPTION), true);
        }
        
    }
    
    public function assignVariables(){
        parent::assignVariables();
        
        DashboardHandler::getInstance()->loadBoxes('de.codequake.cms.news.newsList', $this);
        
        WCF::getTPL()->assign(array(
            'categoryList' => $this->categoryList,
            'allowSpidersToIndexThisPage' => true,
            'sidebarCollapsed' => UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'de.codequake.cms.news.newsList'),
			'sidebarName' => 'de.codequake.cms.news.newsList'
            ));
    }
}