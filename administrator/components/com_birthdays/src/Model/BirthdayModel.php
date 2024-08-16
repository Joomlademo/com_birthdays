<?php
/**
 * @package     com_birthdays
 * @version     1.0.0
 * @copyright   Copyright (C) 2024. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Rene Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Birthdays\Administrator\Model;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\UCM\UCMType;
use Joomla\Component\Birthdays\Administrator\Helper\BirthdaysHelper;

/**
 * Birthdays model
 */
class BirthdayModel extends AdminModel
{
	/**
	 * @var		string	The prefix to use with controller messages
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_BIRTHDAYS';

	/**
	 * Method to get the record form.
	 *
	 * @param    array $data An optional array of data for the form to interogate
	 * @param bool $loadData
	 *
	 * @return bool A JForm object on success, false on failure
     * @throws \Exception
	 * @since    1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form
		$form = $this->loadForm('com_birthdays.birthday', 'birthday', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form
	 *
	 * @return	mixed	The data for the form
     * @throws  \Exception
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data
		$data = Factory::getApplication()->getUserState('com_birthdays.edit.birthday.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

        return $data;
	}

	/**
	 * Prepare and sanitise the table prior to saving
	 *
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = Factory::getDbo();
                $query = $db->getQuery(true)
                    ->select('MAX(ordering)')
                    ->from($db->qn('#__birthdays'));
				$db->setQuery($query);
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}
		}
	}

	/**
	 * Method to initialize member variables used by batch methods and other methods like saveorder()
	 *
	 * @return  void
	 *
     * @throws \Exception
	 * @since   3.8.2
	 */
	public function initBatch()
	{
		if ($this->batchSet === null)
		{
			$this->batchSet = true;

			// Get current user
			$this->user = Factory::getUser();

			// Get table
			$this->table = $this->getTable();

			// Get table class name
			$tc = explode('\\', \get_class($this->table));
			$this->tableClassName = end($tc);

			if ($this->typeAlias === null) {
				$this->typeAlias = '';
			}

			// Get UCM Type data
			$this->contentType = new UCMType;
			$this->type = $this->contentType->getTypeByTable($this->tableClassName)
				?: $this->contentType->getTypeByAlias($this->typeAlias);
		}
	}

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function save($data)
    {
        return parent::save($data);
    }
}
