<?php
/**
 * @package     com_birthdays
 * @version     1.0.0
 * @copyright   Copyright (C) 2024. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Rene Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Birthdays\Administrator\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Exception;

/**
 * The form field implementation
 */
class ForeignkeyField extends ListField
{
    private const STATE_PUBLISHED = 1;

    /**
     * The form field type
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'foreignkey';

    /**
     * Method to get the field input markup
     *
     * @return string    The field input markup
     * @throws Exception
     * @since 1.6
     */
    protected function getInput()
    {
        $db = Factory::getDbo();

        // Define the attributes to load
        $attributesToLoad = ['table', 'key', 'value', 'sql_where', 'sql_group', 'sql_order'];

        $attributes = [];
        foreach ($attributesToLoad as $attributeKey) {
            $attributes[$attributeKey] = (string) $this->getAttribute($attributeKey);
        }

        $query = $db->getQuery(true)
            ->select($db->qn([$attributes['key'], $attributes['value']]))
            ->from($db->qn($attributes['table']));

        if ($attributes['sql_where']) {
            $query->where($attributes['sql_where']);
        } else {
            $query->where($db->qn('state') . ' = ' . self::STATE_PUBLISHED);
        }

        if ($attributes['sql_group']) {
            $query->group($attributes['sql_group']);
        } else {
            $query->group($db->qn($attributes['value']));
        }

        if ($attributes['sql_order']) {
            $query->order($attributes['sql_order']);
        } else {
            $query->order($db->qn($attributes['value']));
        }

        $db->setQuery($query);
        $rows = $db->loadAssocList();

        $options = [];
	    $options[0] = HTMLHelper::_('select.option','',Text::_('JGLOBAL_SELECT_AN_OPTION'));

        if (!empty($rows)) {
            foreach ($rows as $row) {
                // Add each select option
                $options[] = HTMLHelper::_('select.option', $row[$attributes['key']], $row[$attributes['value']]);
            }
        }

        $key = $this->value;
        if (!Factory::getApplication()->isClient('administrator')) {
            $query = $db->getQuery(true)
                ->select($db->qn($attributes['key']))
                ->from($db->qn($attributes['table']))
                ->where($db->qn('state') . ' = ' . self::STATE_PUBLISHED)
                ->where($db->qn($attributes['value']) . ' = ' . $db->q($this->value));
            $db->setQuery($query);
            $key = $db->loadResult();
        }

        return HTMLHelper::_('select.genericlist', $options, $this->name, 'class="custom-select"', 'value', 'text', $key);;
    }
}
