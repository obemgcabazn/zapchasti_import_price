<?php

  function get_post_id_by_sku($hDB, $sku, $title){

    $query = $hDB -> query("SELECT `post_id` FROM `wp_postmeta` WHERE `meta_value` = '" . $sku . "'");
    $row = $query -> fetch_array(MYSQLI_ASSOC);
    return $row;
  }

  function import_update_price_by_post_id($hDB, $post_id, $price){
    $query = $hDB -> query("UPDATE `wp_postmeta` SET `meta_value` = '" . $price . "' WHERE `post_id` = " . $post_id . " AND `meta_key` = '_regular_price'");
  }

  function import_update_sale_by_post_id($hDB, $post_id, $price, $sale){
    if($sale == 0){
      $query = $hDB -> query("UPDATE `wp_postmeta` SET `meta_value` = '' WHERE `post_id` = " . $post_id . " AND `meta_key` = '_sale_price'");
      $query = $hDB -> query("UPDATE `wp_postmeta` SET `meta_value` = '" . $price . "' WHERE `post_id` = " . $post_id . " AND `meta_key` = '_price'");
    }else{
      $query = $hDB -> query("UPDATE `wp_postmeta` SET `meta_value` = '" . $sale . "' WHERE `post_id` = " . $post_id . " AND `meta_key` = '_sale_price'");
      $query = $hDB -> query("UPDATE `wp_postmeta` SET `meta_value` = '" . $sale . "' WHERE `post_id` = " . $post_id . " AND `meta_key` = '_price'");
    }
  }

  function make_outofstock_status($hDB, $post_id){
    $query = $hDB -> query("UPDATE `wp_postmeta` SET `meta_value` = 'outofstock' WHERE `post_id` = '" . $post_id . "' AND `meta_key` = '_stock_status'");
  }

  function make_instock_status($hDB, $post_id){
    $query = $hDB -> query("UPDATE `wp_postmeta` SET `meta_value` = 'instock' WHERE `post_id` = \"" . $post_id . "\" AND `meta_key` = '_stock_status'");
  }