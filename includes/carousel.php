


<div class="col-lg-9 rounded">
    <!-- carousel aanmaken -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <?php
        $testing = sqlselect("select distinct g.stockgroupid from stockgroups as s inner join stockitemstockgroups as g on s.stockgroupid = g.stockgroupid order by g.stockgroupid;", array());
        $count = count($testing);
        print('<ol class="carousel-indicators">');
        for ($i = 0; $i < $count; $i++) {
            print('<li data-target="#carouselExampleIndicators" data-slide-to="' . $i . '"></li>');
        }

        print('</ol><div class="carousel-inner bg-white" style="text-align: center" role="listbox">');

        $firstslide = true;
        foreach ($testing as $row) {
            if ($firstslide) {
                $activity = 'active';
            } else {
                $activity = '';
            }
            $x = $row['stockgroupid'];
            $Y = sqlselect("SELECT s.*
                                                from stockitems as s
                                                inner join stockitemstockgroups as gs
                                                on s.stockitemid = gs.stockitemid
                                                inner join stockgroups as g on gs.stockgroupid = g.stockgroupid
                                                where s.stockitemid in 	(
                                                                                                select s.stockitemid
                                                                        from stockitems as s
                                                                        inner join stockitemstockgroups as g
                                                                        on s.stockitemid = g.stockitemid
                                                                        where g.stockgroupid = ?
                                                                        )
                                                order by recommendedretailprice, stockitemid
                                                limit 1;", array($x))[0];
            $Z = sqlselect("select * from stockgroups where stockgroupid = ?", ARRAY($x))[0];

            $imagemap = "./Images/stockimages/" . $Y['StockItemID'] . "";
            $images = array_diff(scandir($imagemap, 1), array('..', '.'));
            //print_r($images);
            $sourcelink = $imagemap . '/' . $images[0];
            print('<div class="carousel-item col-12 ' . $activity . '">
                                    <a href="productpage.php?productid=' . $Y['StockItemID'] . '">
                                         <h1>Goedkoopste producten per categorie!</h1>
                                        <h2>categorie: ' . $Z['StockGroupName'] . '</h2>
                                        <div class="text-center">
                                        <img class="center-block img-fluid" style="height:400px" src="' . $sourcelink . '" alt="' . $Y['StockItemName'] . '">
                                    </div>
                                    <h2>' . $Y['StockItemName'] . '</h2>
                                        <h2>€' . $Y['RecommendedRetailPrice'] . '</h2>
                                        <br>
                                    </a>
                                </div>');
            $firstslide = false;
        }
        ?>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>