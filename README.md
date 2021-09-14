# DBSCAN-PHP-fromScratch
Clustering with DBSCAN coding using PHP from scratch without using machine learning library <br>
input page: <br>
![Input](https://github.com/f3rry12/DBSCAN-PHP-fromScratch/blob/main/readme_asset/ss1.jpg)
result page: <br>
![Output](https://github.com/f3rry12/DBSCAN-PHP-fromScratch/blob/main/readme_asset/ss2.jpg)
<br>
![Outlier](https://github.com/f3rry12/DBSCAN-PHP-fromScratch/blob/main/readme_asset/ss3.jpg)

## DBSCAN Code
In this project, i use 2 data : quantity ($QuanArr) and price ($PriceArr) to determine the cluster. <br> You may need to change/add/remove data based what you need
```php
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
      $clusterArr[$i]=$clusterke; //data-i join cluster $clusterke
      expand_neighbour($i,$clusterke);
          $clusterke++; //next cluster
    }
 }
}
```
