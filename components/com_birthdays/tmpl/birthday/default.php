<?php
/**
 * @package     com_birthdays
 * @version     1.0.0
 * @copyright   Copyright (C) 2024. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Rene Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\Component\Birthdays\Site\Helper\DatetimeHelper;
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1>
			<?php if ($this->escape($this->params->get('page_heading'))) : ?>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			<?php else : ?>
				<?php echo $this->escape($this->params->get('page_title')); ?>
			<?php endif; ?>
        </h1>
    </div>
<?php endif; ?>
<div class="table-responsive">
    <table class="table table-striped">
        <tr>
			<th class="item-birthday">
				<?php echo Text::_('COM_BIRTHDAYS_HEADING_FRONTEND_DETAIL_BIRTHDAY_BIRTHDAY'); ?>
			</th>
			<td>
				<?php if($this->item->birthday) : ?>
					<?php echo DatetimeHelper::convertFromStrftimeFormat($this->item->birthday, '%Y-%m-%d'); ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th class="item-name">
				<?php echo Text::_('COM_BIRTHDAYS_HEADING_FRONTEND_DETAIL_BIRTHDAY_NAME'); ?>
			</th>
			<td>
				<?php echo $this->item->name; ?>
			</td>
		</tr>
		<tr>
			<th class="item-created_by">
				<?php echo Text::_('COM_BIRTHDAYS_HEADING_FRONTEND_DETAIL_BIRTHDAY_CREATED_BY'); ?>
			</th>
			<td>
				<?php echo $this->item->created_by; ?>
			</td>
		</tr>
    </table>
</div>
