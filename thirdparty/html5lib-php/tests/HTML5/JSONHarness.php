<?php

/**
 * Implementation specifically for JSON format files.
 */
SimpleTest::ignore('HTML5_JSONHarness');
abstract class HTML5_JSONHarness extends HTML5_DataHarness
{
    protected $data;
    public function __construct() {
        parent::__construct();
        $this->data  = json_decode((string) file_get_contents((string) $this->filename));
    }
    public function getDescription($test) {
        return $test->description;
    }
    public function getDataTests() {
        return isset($this->data->tests) ? $this->data->tests : array();
        // could be a weird xmlViolationsTest
    }
}
