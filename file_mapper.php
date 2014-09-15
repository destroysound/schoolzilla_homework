<?php

private $student_id_column = 'cf:0';
private $class_columns = array(
  array('column_name' => 'cf:1',
        'subject' => 'math',
        'validator' => 'Schoolzilla/Validators/Score'),
  array('column_name' => 'cf:2',
        'subject' => 'science',
        'validator' => 'Schoolzilla/Validators/Score'),
);

private function mapRow($file, $row) {
  $values = $row->columns;
  $student_id = $values[$this->student_id_column];
  $student = new StudentQuery::create()->getOneByStudentId($student_id);
  $errors = array();
  if (!$student) {
    array_push($errors, 
      new Schoolzilla/ValidatorError('Not a valid student_id'));
  }
  // we're still going to run through all the validators anyway because we want
  // to make sure that we flag any bad data. but it's not going to get inserted
  // into the DB unless we have a student_id.
  for ($class_column in $this->class_columns) {
    $score = $values[$class_column->column_name];
    $validation_result = $class_column->validator::validate($math_score);
    if ($validation_result->isValid) {
      if ($student_id) {
        // for testing purposes, we may want to wait until we've parsed the row
        // in order to save it to the DB and include an array of TranscriptScore
        // objects that were created/altered.
        $subject = new SubjectQuery->create()
                       ->findOneBySubjectType($class_column->subject);
        $score = new TranscriptScoreQuery::create()->filterByStudent($student)
                     ->filterBySubject($subject)->findOne();
        if (!$score) {
          $score = new TranscriptScore();
        }
        $score->setStudent($student);
        $score->setSubject($subject);
        $score->setScore($score);
        $score->save();
      }
    }
    else {
      array_push($errors, $validation_result);
    }
  }
  return $errors;
}

?>
