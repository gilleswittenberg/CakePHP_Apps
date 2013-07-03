<?php
class AllTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All tests');
        $suite->addTestDirectoryRecursive(APP . 'Plugin' . DS . 'Apps' . DS . 'Test' . DS . 'Case');
        return $suite;
    }
}

