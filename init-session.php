<?php

session_name("OpenEMR");
session_start();

if ((time() - $_SESSION["last_update"]) > 7200) {
    $site_id=$_SESSION['site_id'];
    session_unset();
    session_destroy();
    unset($_COOKIE[session_name()]);
    echo "<script>top.location='/openemr/interface/login/login_frame.php?error=1&site=".$site_id."'</script>";
    return;
}
else
{
    $_SESSION["last_update"] = time();
}

require_once("init-em.php");
?>
