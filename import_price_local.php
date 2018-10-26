<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Обновление цен в базе</title>
</head>
<body>
  <?php
  require_once('exceptions.php');
  require_once('db_connect.php');
  require_once('print_finctions.php');
  require_once('db_functions.php');

  /*
  var_dump_pre ($val)
  print_pre ($val)
  echo_br ($val)
  echo_br_foreach ($array)
  */

  $outOfStockItems = array(); // Хранит артикулы с Quantity = 0
  $noFound = array(); // Хранит арт не найденные в базе
  $emptyItemsSku = array(); // Хранит Title узлов без ратикула
  $emptyItemsPrice = array(); // Храние артикулы без цены
  $exceptionLog = array(); // Список артикулов, попавших под исключения

  if (file_exists( IMPORT_FILE_NAME )) 
  {
      $smpl_xml = simplexml_load_file( IMPORT_FILE_NAME );

      if (false === $smpl_xml) {
          echo "Failed loading XML\n";
          foreach(libxml_get_errors() as $error) {
              echo "\t", $error->message;
          }
      }

      foreach($smpl_xml->Item as $product){
        $sku = $product->Artikul;
        $title = $product->attributes();
        $sale = $product->Sale;
        $quantitiy = $product->Quantity;
        
        // Определяем цену
        if (isset($product->Price1) && $product->Price1 != 0){
          $price = $product->Price1;
        } else {
          $price = $product->Price2;
        }

        if( preg_match( "/\n/", $sku[0] ) || $sku[0] == "" )
        {
          $emptyItemsSku[] = $title;
        }
        elseif ( $price[0] == "" || $price[0] == 0 || preg_match( "/\n/", $price[0] ) )
        {
          $emptyItemsPrice[] = $sku[0];
        }
        elseif ( !in_array( $sku[0], $exceptionItems ) )
        {
          $row = get_post_id_by_sku( $hDB, $sku, $title );

          if($row == "") {
            $noFound[] = $sku[0];
          }else{
            import_update_price_by_post_id($hDB, $row['post_id'], $price);

            if($quantitiy == 0){
              make_outofstock_status($hDB, $row['post_id']);
              $outOfStockItems[] = $sku[0];
              
            }else{
              make_instock_status($hDB, $row['post_id']);
            }

            // Проверка на возможности скидки
            if( !in_array( $sku[0], $exceptionSale )) {
              import_update_sale_by_post_id($hDB, $row['post_id'], $price, $sale);
            }else{
              if($skidka != 1){
                $skidka = 1;
                echo "<h3>Артикулы, которые есть на сайте, но обновлены без скидки:</h3>";
              }
              echo $sku[0];
              echo "<br>";
            }
          }
        }
        else {
          $exceptionLog[] = $sku[0];
        }
      }
      echo "<p>Обновление прошло успешно в " . date("H:i:s") . "</p>";
  } 
  else { 
    exit('Failed to open ' . IMPORT_FILE_NAME); 
  }
?>

<h3>Закончились на складе: <?=count($outOfStockItems)?> элементов</h3>
<?php echo_br_foreach($outOfStockItems); ?>

<h3>Не найдены артикулы: <?=count($noFound)?> элементов</h3>
<?php echo_br_foreach($noFound); ?>

<h3>Пустые артикулы: <?=count($emptyItemsSku)?> элементов</h3>
<?php echo_br_foreach($emptyItemsSku); ?>

<h3>Артикулы без цены: <?=count($emptyItemsPrice)?> элементов</h3>
<?php echo_br_foreach($emptyItemsPrice); ?>

<h3>Артикулы, попавшие под исключения: <?=count($exceptionLog)?> элементов</h3>
<?php echo_br_foreach($exceptionLog); ?>

</body>
</html>