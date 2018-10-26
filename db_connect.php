<?php

  /** Имя файла для импорта */
  define('IMPORT_FILE_NAME', "west_zapchasti.xml");

  /** Имя базы данных для WordPress */
  define('DB_NAME', 'intergaz');

  /** Имя пользователя MySQL */
  define('DB_USER', 'zapchasti_user');

  /** Пароль к базе данных MySQL */
  define('DB_PASSWORD', 'pEu9eK3M');

  /** Имя сервера MySQL */
  define('DB_HOST', '127.0.0.1');

  $hDB = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );