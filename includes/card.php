<?php

//include __DIR__ . '/db.php';

/*
functie voor het maken van de zogeheten 'kaarten' van de producten
$method, aangeven welke manier er gegevens opgehaald moeten worden voor de kaarten
$MultiId, het filter op de query om een resultaat meer nauwkeurig te maken
$show, het aantal resultaten dat gewenst is
$page, de pagina waarin je bevind op basis van het aantal gewenste resultaten
 */

function Getcard($method, $MultiId, $show, $page) {
    $page = $page * $show;
    //ophalen van de items die in de aanbieding zijn
    if ($method == "sale") {
        foreach (sqlselect("SELECT * FROM stockitems where stockitemID IN (SELECT stockitemID FROM specialdeals WHERE startdate >= '?' AND enddate <= '?') LIMIT $MultiId", array(date("m-d-Y"), date("m-d-Y"))) as $row) {
            CardTemplate($row['StockItemName'], $row['Marketingcomments'], $row['RecommendedRetailPrice'], $row['StockItemID']);
        }
    }
    // ophalen van een willekeurig product per gevulde category
    if ($method == "homepage") {

        foreach (sqlselect("SELECT DISTINCT stockgroupid FROM stockitemstockgroups", array()) as $row) {
            foreach (sqlselect("SELECT StockItemID FROM stockitemstockgroups WHERE stockgroupid=? ORDER BY rand() LIMIT 1", array($row['stockgroupid'])) as $row2) {
                CardTemplateID($row2['StockItemID']);
            }
        }
    }
    // zoeken in de database naar items op basis van tags en product omschrijving
    if ($method == "search") {
        //$searchwords = multiexplode(array(",",".","|",":"," "),$MultiId);
        $searchwords = createsearch($MultiId['search'], "");
        $searchtags = addtags($MultiId['tags']);
        $select = $searchwords['query'];
        $select .= $searchtags['query'];
        $select .= " limit $page,$show";
        $prepared = array_merge($searchwords['words'], $searchtags['words']);
        foreach (sqlselect($select, $prepared) as $row) {
            CardTemplate($row['StockItemName'], $row['MarketingComments'], $row['RecommendedRetailPrice'], $row['StockItemID']);
        }
//          return $tags;
    }
    //geeft de resultaten die beschikbaar zijn voor de gekozen categorie
    if ($method == "category") {
        $groupid = $MultiId;
        foreach (sqlselect("SELECT * FROM stockitems where StockItemID in (select StockItemID from stockitemstockgroups where stockgroupid = ?) limit $page,$show ", array($groupid)) as $row) {
            CardTemplate($row['StockItemName'], $row['MarketingComments'], $row['RecommendedRetailPrice'], $row['StockItemID']);
        }
    }
}
// string opsplitsen op basis van een groep tekens eerst worden alle tekens vervangen door 1 soort teken en dan zal de string worden gesplitst op basis van dat teken
function multiexplode($delimiters, $string) {
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return $launch;
}
// standaard formaat voor artikelen in html
function CardTemplate($name, $description, $price, $id) {
    ?>
    <div class="col-lg-4 col-md-6 mt-4">
        <div class="card h-100">
            <a href='<?php echo "productpage.php?productid=$id"; ?>'>
                <?php
                $stockimagemap = "./images/stockimages";
                $stockimagemaps = array_diff(scandir($stockimagemap, 1), array('..', '.'));
                if (in_array($id, $stockimagemaps)) {
                    $imagemap = "./images/stockimages/" . $id . "";
                    $images = array_diff(scandir($imagemap, 1), array('..', '.'));
                    $count = count($images);
                    $sourcelink = $imagemap . '/' . $images[0];
                    print('<img class="card-img-top img-thumbnail" style="height: 145px; object-fit:cover;" src="' . $sourcelink . '" alt="">');
                } else {
                    print('<img class="card-img-top" src="http://placehold.it/700x400" alt="">');
                }
                ?>
                <div class="card-body">
                    <h4 class="card-title">
                        <?php echo $name; ?>
                    </h4>
                    <h5><?php echo $description; ?></h5>
                    <p class="card-text"><?php echo '€' . $price; ?></p>
                </div>
        </div>
    </a>
    </div>


    <?php
}
//functie voor het maken van een string met meerdere zoekwoorden
function createsearch($MultiId, $count) {
    $searchwords = multiexplode(array(",", ".", "|", ":", " "), $MultiId);
    if ($count == "count") {
        $select = "SELECT count(*) as count FROM stockitems ";
    } else {
        $select = "SELECT * FROM stockitems ";
    }
    $wordarray = array();
    foreach ($searchwords as $key => $value) {
        if ($key == 0) {
            $select .= " where SearchDetails LIKE ?";
            $wordarray[] = "%$value%";
        } elseif (strlen($value) == 0) {

        } else {
            $select .= " AND SearchDetails LIKE ?";
            $wordarray[] = "%$value%";
        }
    }

    $statement = array("query" => $select, "words" => $wordarray);
    return $statement;
}
// functie voor het ophalen van artikelgegevens op basis van het artikel id
function CardTemplateID($id) {
    foreach (sqlselect("SELECT * FROM stockitems where StockItemID = ?", array($id)) as $row) {
        CardTemplate($row['StockItemName'], $row['MarketingComments'], $row['RecommendedRetailPrice'], $row['StockItemID']);
    }
}
//functie voor het filteren op tags bij het zoeken
function addtags($tag) {
    if ($tag['selectedtags'][0] != "EMPTY") {
        $wordarray = array();
        $query = " and (";
        foreach ($tag['selectedtags'] as $t) {
            if ($tag['selectedtags'][0] == $t) {
                $query .= "  Tags like ? ";
                $wordarray[] = "%$t%";
            } else {
                $query .= " OR Tags like ? ";
                $wordarray[] = "%$t%";
            }
        }
        $query .= ")";
        $statement = array("query" => $query, "words" => $wordarray);
        return $statement;
    }
    return array("query" => "", "words" => array());
}
?>
