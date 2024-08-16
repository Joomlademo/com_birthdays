<?php

\defined( '_JEXEC' ) or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\Birthdays\Site\Helper\BirthdaysHelper;

// Get the 5 next upcoming birthdays
$birthdays = BirthdaysHelper::getBirthdays();

require ModuleHelper::getLayoutPath( 'mod_birthdays', $params->get( 'layout', 'default' ) );