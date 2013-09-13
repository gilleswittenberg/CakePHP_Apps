<?php
class AllUnitTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All Unit Tests');
        $suite->addTestDirectoryRecursive(APP . 'Plugin' . DS . 'Apps' . DS . 'Test' . DS . 'Case' . DS . 'Model');
        $suite->addTestDirectoryRecursive(APP . 'Plugin' . DS . 'Apps' . DS . 'Test' . DS . 'Case' . DS . 'Console');
        return $suite;
    }
}
