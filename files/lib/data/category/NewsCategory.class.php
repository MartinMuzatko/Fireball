<?php
namespace cms\data\category;

use wcf\data\category\AbstractDecoratedCategory;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\breadcrumb\IBreadcrumbProvider;
use wcf\system\category\CategoryHandler;
use wcf\system\category\CategoryPermissionHandler;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

class NewsCategory extends AbstractDecoratedCategory implements IBreadcrumbProvider{
    
    const OBJECT_TYPE_NAME = 'de.codequake.cms.category.news';
    
    protected $permissions = null;
    
    public function isAccessible() {
        if ($this->getObjectType()->objectType != self::OBJECT_TYPE_NAME) return false;
        return $this->getPermission('canViewCategory');
    }
    
    public function getPermission($permission) {
        if ($this->permissions === null) {
            $this->permissions = CategoryPermissionHandler::getInstance()->getPermissions($this->getDecoratedObject());
        }
        if (isset($this->permissions[$permission])) {
            return $this->permissions[$permission];
        }
        return true;
    }

    public function getBreadcrumb() {
        return new Breadcrumb(WCF::getLanguage()->get($this->title), LinkHandler::getInstance()->getLink('NewsCategory', array(
            'application' => 'cms',
            'object' => $this->getDecoratedObject()
        )));
    }

    
    public static function getAccessibleCategoryIDs($permissions = array('canViewCategory')) {
        $categoryIDs = array();
        foreach (CategoryHandler::getInstance()->getCategories(self::OBJECT_TYPE_NAME) as $category) {
            $result = true;
            $category = new NewsCategory($category);
            foreach ($permissions as $permission) {
                $result = $result && $category->getPermission($permission);
            }

            if ($result) {
                $categoryIDs[] = $category->categoryID;
            }
        }
        return $categoryIDs;
    }
    
}