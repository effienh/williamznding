<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>WWI de internationale groothandel</title>

        <?php// Bootstrap core CSS  ?>
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <?php// font awesome  ?>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" crossorigin="anonymous">
        <?php// Custom styles for this template  ?>
        <link href="css/shop-homepage.css" rel="stylesheet">

        <?php //includes DB.php en SQL.php
        	  include __DIR__ . '/includes/DB.php';
              include __DIR__ . '/includes/sql.php';
              
              //Checkt of er een categorie bestaat en filtert op integer
              if(isset($_GET['category'])){
                $category = trim(filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT));
              }
              $page = (int)trim(filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT));
              
              //checkt of er een search bestaat en filtert op speciale karakters
              if(isset($_GET['search'])){
                $search["search"] = trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS));
              }
              // checkt of selected tags bestaat en zet deze filtered in een array
              if(isset($_GET['selectedtags'])){

                $args = array(
                              'selectedtags'    => array(
                                                          'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                                                          'flags'  => FILTER_REQUIRE_ARRAY,
                                                        )
                );
                $search["tags"] = filter_input_array(INPUT_GET, $args);
              }
              else{
                $search['tags']['selectedtags'] = array("EMPTY");;
              }
         ?>

    </head>


    <body>

        <?php// Navigation  ?>
        <?php include __DIR__ . '/includes/header.php';
              include __DIR__ . '/includes/card.php'; ?>

        <?php// Page Content  ?>
        <div class="container-fluid bg-wwi">
            <div class="container bg-wwi">
                <div class="row">
                    <?php// sidebar  ?>
                    <?php
                    include 'includes/sidebar.php';


                    if(empty($category) or empty($search['search'])){
//                      exit;
                    }
                    //if(!sqlexists("select count(*) from stockitemstockgroups where stockgroupid=? ",array($category))){
  //                    exit;
                    //}

                    ?>


                    <div class="col-lg-9">
                    <?php  include __DIR__ . '/includes/navbar.php';;

                    if (isset($search['search'])) {

                      include __DIR__ . '/includes/tagcloud.php';
                    }
                    ?>


                        <div class="row">

                            <?php
                            if (!isset($page)) {
                                $page = 0;
                            }
                            if (isset($category)) {
                                Getcard("category", $category, $_SESSION['perpage'], $page);
                            }
                            if (isset($search["search"])) {
                                Getcard("search", $search, $_SESSION['perpage'], $page);
                            }
                            ?>

                        </div>
                        <?php// /.row  ?>

                    </div>
                    <?php// /.col-lg-9  ?>

                </div>
                <?php// /.row  ?>

            </div>
        </div>
        <?php// /.container  ?>

        <?php// Footer  ?>
        <?php include __DIR__ . '/includes/footer.php';
        $db = NULL; ?>

    </body>

</html>
