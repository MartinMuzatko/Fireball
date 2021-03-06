<?php
namespace wcf\system\event\listener;
use cms\system\request\Route;
use cms\util\PageUtil;
use wcf\system\event\IEventListener;
use wcf\system\request\RouteHandler;
use wcf\system\application\ApplicationHandler;

/**
 * @author	Jens Krumsieck
 * @copyright	2014 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.cms
 */
 
class CMSRouteHandlerListener implements IEventListener {
	
	public function execute($eventObj, $className, $eventName) {
        //thx to SoftCreatR http://www.woltlab.com/forum/index.php/Thread/224017-Request-Handler/?postID=1332856#post1332856
		if (PACKAGE_ID != 1) {
			$route = new Route('cmsPageRoute');
			$route->setSchema('/{alias}/', 'Page');
            $route->setParameterOption('alias', null, '[a-z0-9/]+(?:\-{1}[a-z0-9/]+)*');
			$eventObj->addRoute($route);
		}
	}
}
