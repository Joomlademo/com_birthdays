<?php
/**
 * @package     com_birthdays
 * @version     1.0.0
 * @copyright   Copyright (C) 2024. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Rene Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Birthdays\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Component\Birthdays\Administrator\Helper\FormHelper;
use Joomla\Component\Birthdays\Administrator\Helper\BirthdaysHelper;
use Joomla\CMS\Form\Form;

/**
 * Birthdays model
 */
class BirthdaysModel extends ListModel
{
	/**
	 * @var		array		An array with the filtering columns
	 */
	protected $filter_fields;
	
    /**
     * Constructor
     *
     * @param    array    		An optional associative array of configuration settings
	 *
     * @see      JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
				'id', 'a.id',
				'birthday', 'a.birthday',
				'name', 'a.name',
				'created_by', 'a.created_by',
				'state', 'a.state',
				'ordering', 'a.ordering',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state
     *
     * Note. Calling getState in this method will result in recursion
     *
     * @param null $ordering
     * @param null $direction
     * @throws Exception
     */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables
		$app = Factory::getApplication('administrator');

		// Load the filter state
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'int');
		$this->setState('filter.state', $published);

		// List state information
		$value = $app->input->get('limit', $app->get('list_limit', 20), 'uint');
		$this->setState('list.limit', $value);

		$value = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $value);

		// Load the parameters
		$params = ComponentHelper::getParams('com_birthdays');
		$this->setState('params', $params);

		// List state information
		parent::populateState('a.ordering', 'asc');
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	DatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		$query	= $this->_db->getQuery(true);

		$query->select('a.id, a.birthday, a.name');
		$query->select('a.state, a.ordering');

		$query->from('`#__birthdays` AS a');

        $query->select('d.name AS `created_by`');
		$query->leftJoin($this->_db->qn('#__users') . ' AS `d` ON d.id = a.created_by');

		// Filter by published state
		$state = $this->getState('filter.published');

		if (is_numeric($state))
		{
			$query->where('a.state = ' . (int)$state);
		}
		elseif ($state !== '*')
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Search for this word
		$searchPhrase = $this->getState('filter.search');

		// Search in these columns
		$searchColumns = array(
            'a.birthday',
			'a.name',
			'd.name',
        );

		if (!empty($searchPhrase))
		{
			if (stripos($searchPhrase, 'id:') === 0)
			{
				// Build the ID search
				$idPart = (int) substr($searchPhrase, 3);
				$query->where($this->_db->qn('a.id') . ' = ' . $this->_db->q($idPart));
			}
			else
			{
				// Build the search query from the search word and search columns
				$query = BirthdaysHelper::buildSearchQuery($searchPhrase, $searchColumns, $query);
			}
		}

        $query->group($this->_db->qn('a.id'));

		// Add the list ordering clause
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');

        if ($orderCol && $orderDirn)
        {
	        $query->order($this->_db->escape($orderCol.' '.$orderDirn));
        }

		return $query;
	}

    /**
     * Method to get an array of data items
     *
     * @return  mixed An array of data on success, false on failure.
     */
    public function getItems()
    {
        Form::addFormPath(JPATH_ADMINISTRATOR . '/components/com_birthdays/forms');
        $form = $this->loadForm('com_birthdays.birthday', 'birthday', [
            'control' => 'jform',
            'load_data' => true
        ]);
        $formHelper = new FormHelper($form);
        return $formHelper->appendFieldOptions(parent::getItems())->getAll();
    }
}
