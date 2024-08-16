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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Birthdays\Site\Helper\DatetimeHelper;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
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
<form action="<?php echo Route::_('index.php?option=com_birthdays&view=birthdays'); ?>" method="get" name="adminForm" id="adminForm">
    <div id="filter-bar" class="btn-toolbar mb-2">
        <div class="input-group mb-2">
            <input type="text" name="filter_search" id="filter-search" class="form-control" placeholder="<?php echo Text::_('JSEARCH_FILTER'); ?>..." value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo Text::_('JSEARCH_FILTER'); ?>" />
            <div class="input-group-append">
                <button class="btn btn-secondary" type="submit"><?php echo Text::_('JSEARCH_FILTER'); ?></button>
                <button class="btn btn-secondary" id="clear-search" type="button"><?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?></button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="item-birthday">
						<?php echo HTMLHelper::_('grid.sort',  'COM_BIRTHDAYS_HEADING_FRONTEND_LIST_BIRTHDAYS_BIRTHDAY', 'a.birthday', $listDirn, $listOrder); ?>
					</th>
					<th class="item-name">
						<?php echo HTMLHelper::_('grid.sort',  'COM_BIRTHDAYS_HEADING_FRONTEND_LIST_BIRTHDAYS_NAME', 'a.name', $listDirn, $listOrder); ?>
					</th>
					<th class="item-created_by">
						<?php echo HTMLHelper::_('grid.sort',  'COM_BIRTHDAYS_HEADING_FRONTEND_LIST_BIRTHDAYS_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
					</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $i => $item) : ?>
                <tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
                    <td class="item-birthday">
						<a href="<?php echo Route::_('index.php?option=com_birthdays&view=birthday&id=' . $item->id . '&Itemid=' . $this->item_id); ?>">
							<?php if($item->birthday) : ?>
								<?php echo DatetimeHelper::convertFromStrftimeFormat($item->birthday, '%Y-%m-%d'); ?>
							<?php endif; ?>
						</a>
					</td>
					<td class="item-name">
						<a href="<?php echo Route::_('index.php?option=com_birthdays&view=birthday&id=' . $item->id . '&Itemid=' . $this->item_id); ?>">
							<?php echo $item->name; ?>
						</a>
					</td>
					<td class="item-created_by">
						<?php echo $item->created_by; ?>
					</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination center">
            <?php echo $this->pagination->getListFooter(); ?>
        </div>
        <input type="hidden" name="view" value="birthdays" />
        <input type="hidden" name="option" value="com_birthdays" />
        <input type="hidden" name="Itemid" value="<?php echo $this->item_id; ?>" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    </div>
</form>
