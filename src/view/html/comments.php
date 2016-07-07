<?php
namespace comments;

function printChildren(array $children, $n)
{
    foreach ($children as $child) {
        include 'src/view/html/post.php';
    }
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
        <link rel="stylesheet" href="src/view/css/master.css">
    </head>
    <body>
        <div class="container">
            <div>
                <div class="row">

                        <div class="col-sm-offset-3 col-sm-8">
                            <form id="0">
                            <div class="input-group">
                                <input type="text" name="text" class="form-control" placeholder="комментарий...">
                                <input type="hidden" name="parent" value="0">
                                <input type="hidden" name="nesting" value="0">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="postComment(0)">Оставить комментарий</button>
                                </span>
                            </div><!-- /input-group -->
                            </form>
                        </div><!-- /.col-lg-6 -->

                </div>

                <div id="children0">
                    <?= printChildren($commentsTree, 0); ?>
                </div>
            </div>

        </div>


        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script type="text/javascript" src="src/view/js/ajaxActions.js"></script>
    </body>
</html>
