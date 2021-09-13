<?php
session_start();
include_once('conn.php');

$sql="SELECT * from retail";
$input = $conn->query($sql);

$epsilon = $_POST['epsilon'];
$minpoints = $_POST['minpoints'];

$indexcount=0;
$QuanArr=array();
$PriceArr=array();

$clusterArr=array();
$coreArr=array();
$matrix_distance = array();

//get dataset from sql to array
  while($row = mysqli_fetch_assoc($input)) {
    $QuanArr[$indexcount] = $row['Quantity'];
    $PriceArr[$indexcount] = $row['UnitPrice'];
    $coreArr[$indexcount] = false;
    $clusterArr[$indexcount]=-1;
    $indexcount++;
  }

// Make matrix 2D, the value are euclidian or distance 
for ($i=0; $i <$indexcount ; $i++) {
   $countNeigbour = 0; //count neighbor data - i
  for ($j=0; $j <$indexcount ; $j++) {
    $distance_matrix[$i][$j] = euclid($QuanArr[$i],$PriceArr[$i],$QuanArr[$j],$PriceArr[$j]);//calculate distance using euclidian between data-i and data-j
    if ($distance_matrix[$i][$j] <= $epsilon) {//if distance lees than epsilon
      $countNeigbour++;//count as neighbor
    }
    if ($countNeigbour >= $minpoints) { //distance is same or more than epsilon
      $coreArr[$i]=true; //data - i become core point for nect cluster
    }
  }
}

$clusterke=1; //cluster begin from 1
for ($i=0; $i < $indexcount ; $i++) {
  if ($coreArr[$i]) { //if data-i is core point
    if ($clusterArr[$i]==-1) { //if data-i not joining any cluster yet
      $clusterArr[$i]=$clusterke; //data ke i adalah cluster ke $clusterke
      expand_neighbour($i,$clusterke);
          $clusterke++; //next cluster
    }
 }
}

//searching for neighbor
function expand_neighbour ($index,$cluster){
  global $indexcount, $distance_matrix, $epsilon,$coreArr,$clusterArr ;
  for ($j=0; $j < $indexcount ; $j++) {
    if ($clusterArr[$j]==-1) {
      if ($distance_matrix[$index][$j] <= $epsilon  && $j != $index) {//if distance lees than epsilon
          $clusterArr[$j]=$cluster; //data-j join same clusterwith data index
        if ($coreArr[$j]) { //if data-j is core point
          expand_neighbour($j,$cluster);
        }
      }
    }

  }
}

//calculate distance
function euclid($x1,$y1,$x2,$y2)	{
  return sqrt(pow(($x1-$x2),2)+pow(($y1-$y2),2));
}

?>

<!-- tampilan -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>[amizon]Data penjualan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

  </head>
  <body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <!-- Brand/logo -->
  <a class="navbar-brand" href="#">
    <img src="img/logo.jpg" alt="logo" style="height:40px;">
  </a>
  </nav>

  <?php
  $hitungnoise=0;
  for ($i=0; $i <$indexcount ; $i++) {
    if ($clusterArr[$i]==-1) {
      $hitungnoise++;
    }
  }
  echo " epsilon        : ".$epsilon;
  echo "<br> min points     : ".$minpoints;
  echo "<br> jumlah outlier : ".$hitungnoise;
  echo "<br> jumlah cluster : ".($clusterke-1);
  ?>
    <h1>Data penjualan</h1>
    <div class="jumbotron" style="padding-top: 30px; padding-bottom: 30px;">
    <table class="table table-condensed table-hover table-bordered">
      <thead>
        <tr>
          <th>No.</th>
          <th>Stock Code</th>
          <th>Description</th>
          <th>Barang terjual</th>
          <th>Harga ($)</th>
          <th>Cluster</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $showsql = $conn->query($sql);
        $counte = 1;
            while($row = mysqli_fetch_assoc($showsql)) {
              ?>
          <tr>
          <?php if ($clusterArr[($counte-1)]>0) { ?>
          <td><?php echo $counte?></td>
          <td><?php echo $row['StockCode']?></td>
          <td><?php echo $row['Description']?></td>
          <td><?php echo $row['Quantity']?></td>
          <td><?php echo $row['UnitPrice']?></td>
          <td><?php echo $clusterArr[($counte-1)]?></td>
          <?php } ?>
          </tr>
                    <?php
                    $counte++;
                    }
                    ?>
    </tbody>
    </table>
    <br>
    <h2>Outlier spesial</h2>
    <h3>Harga lebih dari 150$</h3>
    <table class="table table-condensed table-hover table-bordered">
      <thead>
        <tr>
          <th>No.</th>
          <th>Stock Code</th>
          <th>Description</th>
          <th>Barang terjual</th>
          <th>Harga ($)</th>
          <th>Cluster</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $showsql = $conn->query($sql);
        $counte = 1;
            while($row = mysqli_fetch_assoc($showsql)) {
              ?>
          <tr>
          <?php if ($clusterArr[($counte-1)]==-1 && $row['UnitPrice']>150) { ?>
          <td><?php echo $counte?></td>
          <td><?php echo $row['StockCode']?></td>
          <td><?php echo $row['Description']?></td>
          <td><?php echo $row['Quantity']?></td>
          <td><?php echo $row['UnitPrice']?></td>
          <td><?php echo $clusterArr[($counte-1)]?></td>
          <?php } ?>
          </tr>
                    <?php
                    $counte++;
                    }
                    ?>
    </tbody>
    </table>
    <br>
    <h3>Terjual lebih dari 250</h3>
    <table class="table table-condensed table-hover table-bordered">
      <thead>
        <tr>
          <th>No.</th>
          <th>Stock Code</th>
          <th>Description</th>
          <th>Barang terjual</th>
          <th>Harga ($)</th>
          <th>Cluster</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $showsql = $conn->query($sql);
        $counte = 1;
            while($row = mysqli_fetch_assoc($showsql)) {
              ?>
          <tr>
          <?php if ($clusterArr[($counte-1)]==-1 && $row['Quantity']>250) { ?>
          <td><?php echo $counte?></td>
          <td><?php echo $row['StockCode']?></td>
          <td><?php echo $row['Description']?></td>
          <td><?php echo $row['Quantity']?></td>
          <td><?php echo $row['UnitPrice']?></td>
          <td><?php echo $clusterArr[($counte-1)]?></td>
          <?php } ?>
          </tr>
                    <?php
                    $counte++;
                    }
                    ?>
    </tbody>
    </table>
    <br>
    <h2>Outlier</h2>
    <table class="table table-condensed table-hover table-bordered">
      <thead>
        <tr>
          <th>No.</th>
          <th>Stock Code</th>
          <th>Description</th>
          <th>Barang terjual</th>
          <th>Harga ($)</th>
          <th>Cluster</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $showsql = $conn->query($sql);
        $counte = 1;
            while($row = mysqli_fetch_assoc($showsql)) {
              ?>
          <tr>
          <?php if ($clusterArr[($counte-1)]==-1) { ?>
          <td><?php echo $counte?></td>
          <td><?php echo $row['StockCode']?></td>
          <td><?php echo $row['Description']?></td>
          <td><?php echo $row['Quantity']?></td>
          <td><?php echo $row['UnitPrice']?></td>
          <td><?php echo $clusterArr[($counte-1)]?></td>
          <?php } ?>
          </tr>
                    <?php
                    $counte++;
                    }
                    ?>
    </tbody>
    </table>
  </div>
  <br>

  </body>
</html>
<?php
mysqli_close($conn);
?>
