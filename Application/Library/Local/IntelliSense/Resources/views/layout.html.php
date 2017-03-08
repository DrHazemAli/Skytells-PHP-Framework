<?php
/**
* Layout template file for IntelliSense's pretty error output.
*/
?>
<!DOCTYPE html><?php echo $preface; ?>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $tpl->escape($page_title) ?></title>

    <style><?php echo $stylesheet ?></style>
  </head>
  <body>

    <div class="IntelliSense container">
      <div class="stack-container">

        <?php $tpl->render($panel_left_outer) ?>

        <?php $tpl->render($panel_details_outer) ?>

      </div>
    </div>

    <script><?php echo $zepto ?></script>
    <script><?php echo $clipboard ?></script>
    <script><?php echo $javascript ?></script>
  </body>
</html>
