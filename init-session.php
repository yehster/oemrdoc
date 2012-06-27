<?php

session_name("OpenEMR");
session_start();

if (empty($_SESSION["last_update"]) || ((time() - $_SESSION["last_update"]) > 7200)) {
    $site_id=isset($_SESSION['site_id']) ? $_SESSION['site_id'] : "default";
    session_unset();
    session_destroy();
    unset($_COOKIE[session_name()]);
    $new_location="/openemr/interface/login/login_frame.php?error=1&site=".$site_id;
//    echo "<script>top.location='".$new_location."'</script>";
    header("Location:".$new_location);
    exit;
}
else
{
    $_SESSION["last_update"] = time();
}

require_once("init-em.php");
?>
