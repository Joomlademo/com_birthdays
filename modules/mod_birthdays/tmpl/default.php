<?php

defined( '_JEXEC' ) or die;
?>

<div class="mod-birthdays">
    <?php if (count($birthdays) > 0) : ?>
    <ul>
    <?php foreach ($birthdays as $birthday) : ?>
        <?php $next_birthday = date("M j", strtotime( "{$birthday->next_birthday_date} 00:00:00" )); ?>
        <li>
            <span class="name me-1"><?php echo $birthday->name; ?></span>
            <span class="date me-1">(<?php echo $next_birthday; ?>)</span>
            <span class="age"><?php echo $birthday->next_age; ?></span>
        </li>
    <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>