<?php
namespace cms\data\news;

/**
 * @author	Jens Krumsieck
 * @copyright	2014 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.cms
 */
 
class NewsFeedList extends CategoryNewsList {

	public $decoratorClassName = 'cms\data\news\NewsFeed';
}
