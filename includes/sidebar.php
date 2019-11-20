<div class="col-lg-3 bg-light rounded">

    <h1 class="my-4">Categorie&#235;n</h1>
    <div class="list-group">

        <?php
        //Geeft de categorieën weer aan de zijkant van de webshop.
        foreach (sqlselect("SELECT StockGroupName,stockgroupid FROM stockgroups where stockgroupid in (select stockgroupid from stockitemstockgroups)  order by StockGroupName",array()) as $row) {
          echo "<a href='search.php?category=" . $row['stockgroupid'] . "' class='list-group-item list-group-item-action list-group-item-dark'>" . $row['StockGroupName'] . "</a>";
        }
         ?>

    </div>

</div>
