<?php

public function map($file) {
  $hbase = new HBase('server', 'port');
  $hbase->open();
  $client = $hbase->getClient();
  // get all rows from htable
  $scanner = $client->scannerOpen($this->getId(), // table name
                                  "",             // start row
                                  array( "cf:" )  // columns
  );
  $errors = array();
  try {
    while (true) {
      $row = $client->scannerGet($scanner);
      $errors = $this->mapRow($file, $row);
    }
  }
  catch ( NotFound $nf ) {
  $client->scannerClose( $scanner );
  }
  return $errors;
}

?>
