<?php

namespace Joomla\Module\Birthdays\Site\Helper;

use \Joomla\CMS\Factory;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Helper for mod_birthdays
 *
 * @since  1.0
 */
class BirthdaysHelper
{
    /**
     * Show online count
     *
     * @return  array  The number of Users and Guests online.
     *
     * @since   1.5
     **/
    public static function getBirthdays()
    {
        $db    = Factory::getContainer()->get( 'DatabaseDriver' );
        // Get today's date
        $today = date( "Y-m-d 00:00:00" );

        // Get the next 5 upcoming birthdays
        // $query = $db->getQuery( true )
        //     ->select( $db->quoteName( [ 'birthday', 'name' ] ) )
        //     ->from( '#__birthdays' )
        //     ->where( $db->quoteName( 'state' ) . ' = 1' )
        //     ->where( $db->quoteName( 'birthday' ) . ' >= :today' )
        //     ->order( $db->quoteName( 'birthday' ) )
        //     ->bind( ':today', $today, ParameterType::STRING )
        //     ->setLimit( '5' );
        $query = "
            WITH upcoming_birthdays AS (
                SELECT 
                    `name`,
                    CASE
                        WHEN DATE_FORMAT(`birthday`, '%m-%d') >= DATE_FORMAT(CURDATE(), '%m-%d') THEN
                            STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', DATE_FORMAT(`birthday`, '%m-%d')), '%Y-%m-%d')
                        ELSE
                            STR_TO_DATE(CONCAT(YEAR(CURDATE()) + 1, '-', DATE_FORMAT(`birthday`, '%m-%d')), '%Y-%m-%d')
                    END AS `next_birthday_date`,
                    CASE
                        WHEN DATE_FORMAT(`birthday`, '%m-%d') >= DATE_FORMAT(CURDATE(), '%m-%d') THEN
                            YEAR(CURDATE()) - YEAR(`birthday`)
                        ELSE
                            YEAR(CURDATE()) - YEAR(`birthday`) + 1
                    END AS `next_age`
                FROM 
                    `#__birthdays`
                ORDER BY 
                    CASE
                        WHEN DATE_FORMAT(`birthday`, '%m-%d') >= DATE_FORMAT(CURDATE(), '%m-%d') THEN DATE_FORMAT(`birthday`, '%m-%d')
                        ELSE CONCAT('9999-', DATE_FORMAT(`birthday`, '%m-%d'))
                    END
            )
            SELECT 
                `name`,
                `next_birthday_date`,
                `next_age`
            FROM 
                upcoming_birthdays
            ORDER BY 
                `next_birthday_date`
            LIMIT 5;";
        $db->setQuery( $query );
        $birthdays = $db->loadObjectList();

        return $birthdays;
    }
}
