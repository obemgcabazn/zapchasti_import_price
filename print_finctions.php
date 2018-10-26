<?php

  function var_dump_pre($val){
    echo '<pre>';
    var_dump($val);
    echo  '</pre>';
  }

  function print_pre($val){
    echo '<pre>';
    print_r($val);
    echo  '</pre>';
  }

  function echo_br($val){
    echo $val . "<br>";
  }

  function echo_br_foreach($array){
    foreach ( $array as $key ) {
      echo_br( $key );
    }
  }