<?php
namespace comments;

function printChildren(array $children, $n)
{
    $html = '';
    foreach ($children as $child) {
        $html .= "<div class='row col-sm-offset-1'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                <span>{$child->getId()}</span> <span>{$child->getDate()}</span>
            </div>
            <div class='panel-body'>
            <p>
                {$child->getText()}
            </p>";
        if ($n < 5) {
            $html .= "<span>Edit</span>";
        }
        if($child->hasChild()) {
            $html .= printChildren($child->getChildren(), ++$n);
        }
        $html .= "</div></div></div>";
    }
    return $html;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>comments</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <link rel="stylesheet" href="src/master.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="комментарий...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Оставить комментарий</button>
                        </span>
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
            </div>

            <?= printChildren($this->commentsTree, 0); ?>
        </div>


        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    </body>
</html>
