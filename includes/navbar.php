<?php
if(sanitize_get('page', 'number') == ''){
    $_GET['page'] = 1;
    //header('Location: ' . 'index.php' );
}

if(!isset($_GET['page'])){
  $_GET['page'] = 0;
}

if(isset($_POST['perpage'])){
  $_SESSION['perpage'] = $_POST['perpage'];
  $_SESSION['perpage'] = sanitize_post('perpage','number');
}

$currentlink = explode("&page",$_SERVER['REQUEST_URI']);
$linkcount = count($currentlink);
if ($linkcount >=2){
//  $currentlink[0] = $currentlink[0] . "&".$currentlink[1];
}

function getcountresult($search, $amount,$pagetype){
  if($pagetype == "search"){
    $searchwords = createsearch($search["search"],"count");
    $searchtags = addtags($search['tags']);
    $query = $searchwords["query"] . $searchtags['query'];
    $prepared = array_merge($searchwords['words'],$searchtags['words']);
    $resultamount = sqlselect($query,$prepared)[0]['count'];
  }
  elseif($pagetype == "category"){
    $resultamount = sqlselect("SELECT count(*) AS count FROM stockitems where StockItemID in (select StockItemID from stockitemstockgroups where stockgroupid = ?)", array($search))[0]['count'];
  }
  $result = ceil($resultamount/$amount)-1;
  return $result;
}

//controle van de gevraagde waarden
if(!empty($_GET['search'])){
  $pagetypeentry = $search;
  $pagetype = "search";
}elseif(!empty($_GET['category'])) {
  if(!sqlexists("SELECT count(*) FROM stockitemstockgroups WHERE stockgroupid=? ",array($category))){
    warning(warning("bunny"));
    exit;
  }
  $pagetypeentry = $category;
  $pagetype = "category";
}else{
  warning("geen mogelijke zoekfunctie!");
  exit;
}

$getcountresult = getcountresult($pagetypeentry, $_SESSION['perpage'],$pagetype);
if($getcountresult < $page){
  $page = 0;
}

 ?>

  <nav class="navbar navbar-expand-lg navbar-light bg-light rounded"style="margin-bottom:10px;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">

        <li class="nav-item">
          <?php
          $previous = (int)sanitize_get('page', 'number') - 1;
          ?>
          <a class="nav-link" <?php if($page == 0){echo "style='color: currentColor; opacity: 0.5; text-decoration: none;'";};
           if(!($page == 0)){echo("href=".$currentlink[0]. "&page=$previous");} ?> > Vorige pagina</a>
        </li>
        <li class="nav-item">
          <?php $next = $page+1;  ?>
          <a class="nav-link" <?php if(!($getcountresult <= $page)) { echo "href=". $currentlink[0]. "&page=$next";}
          else{echo "style='color: currentColor; opacity: 0.5; text-decoration: none;'";} ?> >Volgende pagina</a>
        </li>
      </ul>
      <div class="nav-item dropdown inline my-2 my-lg-0">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Aantal producten
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <button class="dropdown-item" name="perpage" value="30">30</button>
            <div class="dropdown-divider"></div>
          </form>
          <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <button class="dropdown-item" name="perpage" value="60">60</button>
            <div class="dropdown-divider"></div>
          </form>
          <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <button class="dropdown-item" name="perpage" value="90">90</button>
          </form>
        </div>
      </div>
    </div>
  </nav>
