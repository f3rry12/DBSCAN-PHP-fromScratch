<?php
session_start();
include_once('conn.php');

$sql="SELECT * from retail";

?>
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

  <div class="jumbotron" style="padding-top: 10px; padding-bottom: 5px;">
    <h3>Menentukan kluster</h3>
  <form action="result.php" method="post" target="hasil" >
    <table class="table table-condensed table-bordered">
      <tr>
        <th>Epsilon</th>
        <td><input name="epsilon" > </td>
      </tr>
      <tr>
        <th>min points</th>
        <td><input name="minpoints" > </td>
      </tr>
    </table>
    <button type="submit" class="btn btn-md btn-primary">Submit</button>
  </form>
  </div>

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
        </tr>
      </thead>
      <tbody>
        <?php
        $showsql = $conn->query($sql);
        $counte = 1;
            while($row = mysqli_fetch_assoc($showsql)) {
              ?>
          <tr>
          <td><?php echo $counte?></td>
          <td><?php echo $row['StockCode']?></td>
          <td><?php echo $row['Description']?></td>
          <td><?php echo $row['Quantity']?></td>
          <td><?php echo $row['UnitPrice']?></td>
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
