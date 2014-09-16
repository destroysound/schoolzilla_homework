<?php

class MapRowTest extends PHPUnit_Framework_TestCase {
  public function testRow1() {
    // create fake version of row 1
    $row = new StdClass();
    $row->columns = array('cf:0' => '1597530', 'cf:1' => '100', 'cf:2' => '80');
    $this->assertEmpty(Schoolzilla/Mappers/OurFileMapper->mapRow($row));
  }
  public function testRow2() {
    // create fake version of row 2
    $row = new StdClass();
    $row->columns = array('cf:0' => '2468975', 'cf:1' => '85', 'cf:2' => '');
    $this->assertCount( Schoolzilla/Mappers/OurFileMapper->mapRow($row), 1);
    // we can test individual errors by checking the getError attribute.
    $this->assertEquals(
      Schoolzilla/Mappers/OurFileMapper->mapRow($row)[0]->getError(),
      "Not a valid score.");
  }
  public function testRow3() {
    // create fake version of row 3
    $row = new StdClass();
    $row->columns = 
      array('cf:0' => '8675309', 'cf:1' => 'blue', 'cf:2' => '95');
    $this->assertCount(Schoolzilla/Mappers/OurFileMapper->mapRow($row), 2);
    $this->assertEquals(
      Schoolzilla/Mappers/OurFileMapper->mapRow($row)[0]->getError(),
      "Not a valid student_id.");
    $this->assertEquals(
        Schoolzilla/Mappers/OurFileMapper->mapRow($row)[1]->getError(),
        "Not a valid score.");
  }
}

?>
