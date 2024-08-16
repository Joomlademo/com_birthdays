<?php
/**
 * @package     com_birthdays
 * @version     1.0.0
 * @copyright   Copyright (C) 2024. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Rene Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Birthdays\Administrator\Helper;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Database\Mysqli\MysqliQuery;

/**
 * Birthdays helper class
 */
class BirthdaysHelper
{
	/**
	 * Add the submenus
	 *
	 * @param string $name
	 */
	public static function addSubmenu($name = '')
	{
		\JHtmlSidebar::addEntry(
			Text::_('COM_BIRTHDAYS_TITLE_BIRTHDAYS'),
			'index.php?option=com_birthdays&view=birthdays',
			$name === 'birthdays'
		);
	}

	/**
	 * Gets a list of the actions that can be performed
	 *
	 * @return array
	 * @since    1.6
	 */
	public static function getActions() : array
	{
		$user	= Factory::getUser();
		$result	= [];

		$assetName = 'com_birthdays';

		$actions = [
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.edit.own', 'core.delete'
		];

		foreach ($actions as $action)
		{
			$result[$action] = $user->authorise($action, $assetName);
		}

		return $result;
	}

	/**
	 * Build the search query from the columns
	 *
	 * @param	string		        $searchPhrase	    Search for this phrase
	 * @param	array		        $searchColumns	    The columns in the DB to look up
	 * @param   MysqliQuery         $query              The query
	 *
	 * @return	MysqliQuery		    $query			    The query (search filters applied)
	 */
	public static function buildSearchQuery(string $searchPhrase, array $searchColumns, MysqliQuery $query) : MysqliQuery
	{
		$db = Factory::getDbo();

		$where = [];

		foreach ($searchColumns as $i => $searchColumn)
		{
			$where[] = $db->qn($searchColumn) . ' LIKE ' . $db->q('%' . $db->escape($searchPhrase, true) . '%');
		}

		if (!empty($where))
		{
			$query->where('(' . implode(' OR ', $where) . ')');
		}

		return $query;
	}

    /**
     * @param string $format
     * @return string
     */
    public static function convertStrftimeToDateTimeFormat(string $format): string
    {
        $replacements = [
            '%a' => 'D', '%A' => 'l', '%d' => 'd', '%e' => 'j', '%j' => 'z',
            '%u' => 'N', '%w' => 'w', '%U' => 'W', '%V' => 'W', '%W' => 'W',
            '%b' => 'M', '%B' => 'F', '%m' => 'm', '%C' => 'y', '%g' => 'y',
            '%G' => 'o', '%y' => 'y', '%Y' => 'Y', '%H' => 'H', '%I' => 'h',
            '%l' => 'g', '%M' => 'i', '%p' => 'A', '%P' => 'a', '%r' => 'h:i:s A',
            '%R' => 'H:i', '%S' => 's', '%T' => 'H:i:s', '%X' => 'H:i:s', '%z' => 'O',
            '%Z' => 'T', '%%' => '%'
        ];

        return strtr($format, $replacements);
    }

    /**
     * @param string $value
     * @param string $strftimeFormat
     * @return string
     */
    public static function convertFromStrftimeFormat(string $value, string $strftimeFormat): string
    {
        $datetime = \DateTime::createFromFormat('Y-m-d', $value);
        if (!$datetime) {
            return '';
        }
        return $datetime->format(self::convertStrftimeToDateTimeFormat($strftimeFormat));
    }
}
