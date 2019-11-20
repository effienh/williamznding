<?php

//functie voor het laten zien van tags op basis de zoek query
function filltags($select,$chosen){

  $tags =  "";

  foreach (sqlselect($select['query'],$select['words']) as $row) {
    $tags .= $row['Tags'];
  }
  $tags = array_unique(multiexplode(array("[",",","]"),$tags));

  foreach ($tags as $value) {
    if (!$value == ""){
      $value = trim($value,"\x22");
      if(in_array($value,$chosen['selectedtags'])){
        echo "
        <label class='btn btn-outline-success my-2 my-sm-0 active'>
          <input type='checkbox' autocomplete='off' value='$value' name='selectedtags[]' checked> $value
        </label>";
      }
      else{
        echo "
        <label class='btn btn-outline-success my-2 my-sm-0 '>
          <input type='checkbox' autocomplete='off' value='$value' name='selectedtags[]' > $value
        </label>";
      }


    }

  }
}

 ?>

 <div class="pos-f-t">
  <div class="collapse" id="navbarToggleExternalContent">
    <div class="bg-light p-4">
      <!--<h5 class="text-black h4">gevonden tags bij uw zoekopdracht</h5>-->
      <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <div class="btn-group-toggle" data-toggle="buttons">

      <?php

      $select = createsearch($search['search'],"");
      filltags($select,$search['tags']);
      ?>
      </div>
      <input type="hidden" name="search" value="<?php  echo $search['search'];?>">

      <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
  <div class="btn-group" >

  </div>
  <div class="input-group">
<button type="submit" class="btn btn-outline-primary">zoeken</button>

  </div>
</div>
    </form>
    </div>
  </div>
  <nav class="navbar navbar-light rounded bg-light" >
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    Gevonden tags bij uw zoekopdracht
  </nav>
</div>
