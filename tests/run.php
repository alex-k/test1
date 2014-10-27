<?php


require_once 'suites.php';


class AllTests {

    public static function suite() {
            $suite = new TestSuite('All');
            return $suite;
    }
}


