<?php
namespace cms\data\restore;
use wcf\data\DatabaseObjectList;

/**
 * @author	Jens Krumsieck
 * @copyright	2014 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.cms
 */

class RestoreList extends DatabaseObjectList{
    public $className = 'cms\data\restore\Restore';
}