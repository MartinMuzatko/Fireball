<?php
namespace cms\data\restore;
use wcf\data\DatabaseObjectEditor;

/**
 * @author	Jens Krumsieck
 * @copyright	2014 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.cms
 */

class RestoreEditor extends DatabaseObjectEditor{
    protected static $baseClass = 'cms\data\restore\Restore';
}