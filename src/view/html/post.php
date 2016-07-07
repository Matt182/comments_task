<?php
use function comments\printChildren;
?>

<div class='row col-sm-offset-1' id="comment<?= $child->getId() ?>">
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <span><?= $child->getDate() ?></span>
            <button type="button" onclick="deleteComment(<?= $child->getId() ?>)"><span class="glyphicon glyphicon-remove"></span></button>
        </div>
        <div class='panel-body'>
            <p>
                <?= $child->getText() ?>
            </p>
            <?php if ($n <= 5): ?>
                <div class="row">
                    <form id="<?= $child->getId() ?>">
                        <div class="input-group">
                            <input type="text" name="text" class="form-control" placeholder="комментарий...">
                            <input type="hidden" name="parent" value="<?= $child->getId() ?>">
                            <input type="hidden" name="nesting" value="<?= $n ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="postComment(<?= $child->getId() ?>)">Отправить</button>
                            </span>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
            <div id="children<?= $child->getId() ?>">
                <?php if($child->hasChild()) printChildren($child->getChildren(), ++$n); ?>
            </div>
        </div>
    </div>
</div>
