<?php
/**
 * @package     com_birthdays
 * @version     1.0.0
 * @copyright   Copyright (C) 2024. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Rene Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Birthdays\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Birthdays list controller
 */
class BirthdaysController extends BaseController
{
	/**
	 * Proxy for getModel.
	 * @since    1.6
	 *
	 * @param string $name
	 * @param string $prefix
	 *
	 * @return mixed
	 */
	public function &getModel($name = 'Birthday', $prefix = 'Administrator')
	{
		return parent::getModel($name, $prefix, ['ignore_request' => true]);
	}
}
