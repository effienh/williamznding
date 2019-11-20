<?php
//ophalen van orderdetails op basis van het gevraagde order id
function orderdetails($orderid) {

    $state['orderdetails'] = sqlselect("SELECT o.OrderID, o.OrderDate,o.ExpectedDeliveryDate,o.PickingCompletedWhen FROM orders o WHERE o.OrderID = ?", array($orderid))[0];
    $state['orderitems'] = sqlselect("SELECT o.StockItemID,o.PickedQuantity,o.UnitPrice,s.StockItemName FROM orderlines o LEFT JOIN stockitems s ON s.StockItemID = o.StockItemID WHERE o.OrderID = ?", array($orderid));
    return $state;
}
// functie voor het retourneren van de status van de order
function orderstate($delivery, $picking) {
    $delivery .= " 23:59:59";
    $delivery = strtotime($delivery);
    $picking = strtotime($picking);
    $state = "onbekend";
    //echo $delivery . ' a' . $picking . ' b' . time() . " c";
    if ($picking > time()) {
        $state = "Order wordt ingepakt";
    } else {
        $state = "Order is ingepakt en verzonden";
    }
    if ($delivery < time()) {
        $state = "Order is bezorgd";
    }
    return $state;
}
// functie voor het maken van een lijst met de items die in de order staat
function orderitems($items) {

    foreach ($items as $v) {
        $sourcelink = "./Images/afbeelding.jpg";
        $imagemap = "./images/stockimages/" . $v['StockItemID'] . "";
        if (is_dir($imagemap)) {
            $images = array_diff(scandir($imagemap, 1), array('..', '.'));
            //print_r($images);
            $sourcelink = $imagemap . '/' . $images[0];
        }
        ?>

        <div class='card col-md-10  m-1 shadow-sm h-md-250'>
            <div class='media'>
                <img class='align-self-center mr-3 m-1 rounded 'style='height:100px; width:100px; object-fit:cover;' src='<?php echo $sourcelink ?>' alt='Generic placeholder image'>
                <div class='media-body'>
                    <dl class='row'>
                        <dt class='col-sm-4'><?php echo $v['StockItemName'] ?></dt>
                        <dt class='col-sm'>aantal:</dt>
                        <dd class='col-sm'><?php echo $v['PickedQuantity'] ?></dd>
                        <dt class='col-sm'>prijs:</dt>
                        <dd class='col-sm'>€<?php echo $v['UnitPrice'] ?></dd>
                        <dt class='col-sm'>totaal:</dt>
                        <dd class='col-sm'>€<?php echo number_format($v['UnitPrice'] * $v['PickedQuantity'], 2) ?></dd>
                    </dl>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
