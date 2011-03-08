<style type="text/css">

    table.ObservationTable {
        border: 1px solid;
        border-collapse: collapse;
    }

        th.ObservationTable {
        border: 1px solid;
        background-color: lightblue;
}
    td.ObservationTable {
        border: 1px solid;
    }

    .normal {
            background-color: lightgreen;
    }

    .abnormal {
            background-color: lightpink;
    }



</style>
<?php
    include('/var/www/openemr/library/doctrine/init-em.php');
    include('/var/www/openemr/library/doctrine/ui/FormUtilities.php');
    $sd="PE:GEN";
    $sect = $em->getRepository('library\doctrine\Entities\SectionHeading')->findOneBy(array('shortDesc' => $sd));

    $FormDOM = new DOMDocument("1.0","utf-8");
    $div1 = $FormDOM->createElement("DIV");
    $FormDOM->appendChild($div1);

    createObservationTable($em,$FormDOM,$div1,$sect->getText(),$sect->getCode(),$sect->getCode_type());

    echo $FormDOM->saveHTML();
?>
