<?php
class AllIntegrationTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All Integration Tests');
        $suite->addTestDirectoryRecursive(APP . 'Plugin' . DS . 'Apps' . DS . 'Test' . DS . 'Case' . DS . 'Integration');
        $suite->addTestDirectoryRecursive(APP . 'Plugin' . DS . 'Apps' . DS . 'Test' . DS . 'Case' . DS . 'Lib');
        return $suite;
    }
}
