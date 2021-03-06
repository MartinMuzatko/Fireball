<?php
namespace cms\page;
use cms\data\page\PageCache;
use cms\data\page\PageEditor;
use cms\system\counter\VisitCountHandler;
use wcf\page\AbstractPage;
use wcf\system\WCF;
use wcf\system\comment\CommentHandler;
use wcf\system\MetaTagHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\request\LinkHandler;
use wcf\system\menu\page\PageMenu;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\dashboard\DashboardHandler;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\exception\PermissionDeniedException;
use wcf\util\StringUtil;
use wcf\util\HeaderUtil;

/**
 * @author	Jens Krumsieck
 * @copyright	2014 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.cms
 */

class PagePage extends AbstractPage{
    const AVAILABLE_DURING_OFFLINE_MODE = true;
    
    public $bodyList = array();
    public $sidebarList = array();
    public $page = null;
    public $enableTracking = true;
    
    public $commentObjectTypeID = 0;
    public $commentManager = null;
    public $commentList = null;
    
    public function readParameters(){
        parent::readParameters();
        $alias = '';
        if(isset($_REQUEST['alias'])) $alias = StringUtil::trim($_REQUEST['alias']);
        if($alias != '') {
            $this->pageID = PageCache::getInstance()->getIDByAlias($alias);
            if($this->pageID == 0) throw new IllegalLinkException();
            $this->page = PageCache::getInstance()->getPage($this->pageID);
            if($this->page === null) throw new IllegalLinkException();
        }
        else{
            $sql  = "SELECT pageID FROM cms".WCF_N."_page WHERE isHome = ?";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(array(1));
            $row = $statement->fetchArray();
            $this->pageID = $row['pageID'];
            $this->page = PageCache::getInstance()->getPage($this->pageID);
            if($this->page->pageID != 0){
                $this->activeMenuItem = $this->page->title;
            }
            else throw new IllegalLinkException();
            
            
        }
        
        //check if offline and view page or exit
        // see: wcf\system\request\RequestHandler
        if (OFFLINE) {
             if (!WCF::getSession()->getPermission('admin.general.canViewPageDuringOfflineMode') && !$this->page->availableDuringOfflineMode) {
                WCF::getTPL()->assign(array(
                    'templateName' => 'offline'
                ));
                WCF::getTPL()->display('offline');
                exit;
			}
		}
    }
    
    public function readData(){
        parent::readData();
        //register visit
        VisitCountHandler::getInstance()->count();
        
        //count click
        $pageEditor = new PageEditor($this->page);
        $pageEditor->update(array('clicks' => $this->page->clicks+1));
        
        if(!$this->page->isVisible() || !$this->page->isAccessible()) throw new PermissionDeniedException();
        if (PageMenu::getInstance()->getLandingPage()->menuItem == $this->page->title) {
			WCF::getBreadcrumbs()->remove(0);
		}
               
        
        //breadcrumbs
        foreach($this->page->getParentPages() as $page){
            WCF::getBreadcrumbs()->add(new Breadcrumb($page->getTitle(), $page->getLink()));
        }
        
        //get Contents
        $this->bodyList = $this->page->getContentList();
        $this->sidebarList = $this->page->getContentList('sidebar');
        
        //comments
        if($this->page->isCommentable){
            $this->commentObjectTypeID = CommentHandler::getInstance()->getObjectTypeID('de.codequake.cms.page.comment');
            $this->commentManager = CommentHandler::getInstance()->getObjectType($this->commentObjectTypeID)->getProcessor();
            $this->commentList = CommentHandler::getInstance()->getCommentList($this->commentManager, $this->commentObjectTypeID, $this->page->pageID);
        }
        
        //meta tags
        if($this->page->metaKeywords !== '') MetaTagHandler::getInstance()->addTag('keywords', 'keywords', WCF::getLanguage()->get($this->page->metaKeywords));
        if($this->page->metaDescription !== '') MetaTagHandler::getInstance()->addTag('description', 'description', WCF::getLanguage()->get($this->page->metaDescription));
        if($this->page->metaDescription !== '') MetaTagHandler::getInstance()->addTag('og:description', 'og:description', WCF::getLanguage()->get($this->page->metaDescription), true);
        MetaTagHandler::getInstance()->addTag('robots', 'robots', $this->page->robots);
        MetaTagHandler::getInstance()->addTag('generator', 'generator', 'Fireball CMS');
        MetaTagHandler::getInstance()->addTag('og:title', 'og:title', $this->page->getTitle() . ' - ' . WCF::getLanguage()->get(PAGE_TITLE), true);
        MetaTagHandler::getInstance()->addTag('og:url', 'og:url', $this->page->getLink(), true);
        if(FACEBOOK_PUBLIC_KEY != '') MetaTagHandler::getInstance()->addTag('fb:app_id', 'fb:app_id', FACEBOOK_PUBLIC_KEY, true);
        MetaTagHandler::getInstance()->addTag('og:type', 'og:type', 'website', true);
    }
    
    public function assignVariables(){
        parent::assignVariables();
        WCF::getTPL()->assign(array('bodyList' => $this->bodyList,
                                    'sidebarList' => $this->sidebarList,
                                    'page' => $this->page,
                                    'likeData' => ((MODULE_LIKE && $this->commentList) ? $this->commentList->getLikeData() : array()),
                                    'commentCanAdd' => (WCF::getUser()->userID && WCF::getSession()->getPermission('user.cms.page.canAddComment')),
                                    'commentList' => $this->commentList,
                                    'commentObjectTypeID' => $this->commentObjectTypeID,
                                    'lastCommentTime' => ($this->commentList ? $this->commentList->getMinCommentTime() : 0),
                                    'allowSpidersToIndexThisPage' => true));
                                    
        //sidebar
        if($this->page->showSidebar == 1)  DashboardHandler::getInstance()->loadBoxes('de.codequake.cms.page', $this);
            WCF::getTPL()->assign(array('sidebarCollapsed'	=> UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'de.codequake.cms.page'),
                                        'sidebarName' => 'de.codequake.cms.page'));
        
    }
    
    public function show(){
        if($this->page->hasMenuItem()) $this->activeMenuItem = $this->page->title;
        else{
            //activate startpage-item
            $sql  = "SELECT pageID FROM cms".WCF_N."_page WHERE isHome = ?";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(array(1));
            $row = $statement->fetchArray();
            $startPageID = $row['pageID'];
            $startPage = PageCache::getInstance()->getPage($startPageID);
            $this->activeMenuItem = $startPage->title;
        }
        parent::show();
    }
   
    public function getObjectType(){
        return 'de.codequake.cms.page';
    }
    
    public function getObjectID() {
        if(isset($this->page->pageID))return $this->page->pageID;
        return 0;
    }
}