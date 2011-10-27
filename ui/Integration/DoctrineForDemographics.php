       <tr>
       <td>
<?php
// Doctrine Documents
$widgetTitle = xl("Clinical");
$widgetLabel = "Clinical";
$widgetButtonLabel = "Docs";
$widgetButtonLink = "";
$widgetButtonClass = "";
$linkMethod = "";
$bodyClass = "notab";
$widgetAuth = true;
$fixedWidth = true;
expand_collapse_widget($widgetTitle, $widgetLabel, $widgetButtonLabel,
  $widgetButtonLink, $widgetButtonClass, $linkMethod, $bodyClass,
  $widgetAuth, $fixedWidth);
  include_once("/var/www/openemr/library/doctrine/ui/Summary/DisplayDocuments.php")
?>
       </div>
     </td>
    </tr>