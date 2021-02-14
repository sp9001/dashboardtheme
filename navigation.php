<!-- Grab the initial menu work that UserSpice does for you -->
<?php require_once($abs_us_root . $us_url_root . 'users/includes/template/database_navigation_prep.php'); ?>

<!-- This file is a way of allowing the end user to customize stuff -->
<!-- without getting in the middle of the whole template itself -->
<?php require_once($abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/assets/functions/style.php'); ?>
<?php
/*
dump($prep);
die();
*/
if(1===2){
if (file_exists($abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/info.xml')) {
  $xml = simplexml_load_file($abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/info.xml');
  $navstyle = $xml->navstyle;
}

if ($navstyle == 'Default') {
  ?>
  <!-- Set your logo and the "header" of the navigation here -->
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <a href="<?= $us_url_root ?>index.php"><img src="<?= $us_url_root ?>users/images/logo.png"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample03">
      <ul class="navbar-nav ml-auto">

        <!-- Here's where it gets tricky.  We need to concatenate together the html to make the menu. -->
        <!-- Basically you will be editing each function into the "style" of your menu -->
        <?php
        if ($settings->navigation_type == 0) {
          $query = $db->query("SELECT * FROM email");
          $results = $query->first();

          //Value of email_act used to determine whether to display the Resend Verification link
          $email_act = $results->email_act;

          // Set up notifications button/modal
          if ($user->isLoggedIn()) {
            if ($dayLimitQ = $db->query('SELECT notif_daylimit FROM settings', array()))
            $dayLimit = $dayLimitQ->results()[0]->notif_daylimit;
            else
            $dayLimit = 7;

            // 2nd parameter- true/false for all notifications or only current
            $notifications = new Notification($user->data()->id, false, $dayLimit);
          }
          require_once($abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/assets/functions/nav.php');
        }


        if ($settings->navigation_type == 1) {
          require_once($abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/assets/functions/dbnav.php');
        }
        ?>


        <!-- Close everything out and leave the hooks so error and bold messages work on your template -->
      </ul>
    </div>
  </div>
</nav>
<?php

} 

    if(isset($_GET['err'])){
      err("<font color='red'>".$err."</font>");
    }

    if(isset($_GET['msg'])){
      err($msg);
    }
}// Ending if 1=2