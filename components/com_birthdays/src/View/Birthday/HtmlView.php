<?php
/**
 * @package     com_birthdays
 * @version     1.0.0
 * @copyright   Copyright (C) 2024. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Rene Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Component\Birthdays\Site\View\Birthday;

// No direct access
defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Pagination\Pagination;
use Joomla\Registry\Registry;

/**
 * Birthdays detail view
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The active item
     *
     * @var    object
     * @since  1.5
     */
    protected $item;

    /**
     * The pagination object
     *
     * @var    ?Pagination
     * @since  1.6
     */
    protected $pagination;

    /**
     * The form object
     *
     * @var    object
     * @since  1.5
     */
    protected $form;

    /**
     * The model state
     *
     * @var    object
     * @since  1.5
     */
    protected $state;

    /**
     * The component params
     *
     * @var    Registry
     * @since  1.5
     */
    protected $params;

	/**
	 * @throws Exception
	 */
    public function display($tpl = null): void
    {
		$app = Factory::getApplication();

        $this->form 				= $this->get('Form');
        $this->state 				= $this->get('State');
        $this->item 				= $this->get('Item');
        $this->pagination           = $this->get('pagination');

        $this->params 				= $app->getParams('com_birthdays');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        $this->setupDocument();

        parent::display($tpl);
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function hasAccess(): bool
    {
        $app = Factory::getApplication();
        $user = Factory::getUser();

        if($this->_layout == 'edit') {
            $isEdit = ($app->input->getInt('id', 0) || $this->params->get('id'));
            if ($isEdit) {
                $authorised = $user->authorise('core.edit', 'com_birthdays');
                $access = new AccessHelper();
                $access->preloadOwnRecords('#__birthdays');
                if ($access->canAccessOwnRecord()) {
                    return true;
                }
            } else {
                $authorised = $user->authorise('core.create', 'com_birthdays');
            }
            if ($authorised !== true) {
                $app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
                return false;
            }
        }

        return true;
    }

    /**
     * @return  void
     *
     * @throws \Exception
     * @since   1.6
     */
    protected function setupDocument(): void
    {
        $document = Factory::getDocument();
        $app   = Factory::getApplication();

        if ($document === null) {
            return;
        }
	    $wa = $document->getWebAssetManager();
	    $wa->registerAndUseStyle('my-style', 'components/com_birthdays/assets/css/birthdays.css');
	    $wa->registerAndUseScript('my-script', 'components/com_birthdays/assets/js/detail.js');

        if ($app === null) {
            return;
        }

        $menus = $app->getMenu();
        if ($menus === null) {
            return;
        }
        $menu = $menus->getActive();

        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', Text::_('COM_BIRTHDAYS_DEFAULT_PAGE_TITLE'));
        }

        $title = $this->params->get('page_title', '');

        if (empty($title)) {
            $title = $app->get('sitename');
        } elseif ((int)$app->get('sitename_pagetitles', 0) === 1) {
            $title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        } elseif ((int)$app->get('sitename_pagetitles', 0) === 2) {
            $title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }

        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }
}
