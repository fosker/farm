<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 22.09.2015
 * Time: 14:11
 */

/* @var $answers array */
?>
<h1>Результаты по анкете "<?=$answers[0]->view->survey->title;?>"</h1>

    <? $author = null;
    foreach($answers as $answer) :
        if($author != $answer->view->user->name) : ?>
            <h3><?=$answer->view->user->name;?></h3>
        <? endif; ?>
        <p><b><?=$answer->question->question;?></b> <?=$answer->value;?></p>
    <?
        $author = $answer->view->user->name;
    endforeach; ?>
