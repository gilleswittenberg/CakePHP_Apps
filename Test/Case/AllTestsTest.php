<?php
class AllTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All Tests');
        $suite->addTestFile(APP . 'Plugin' . DS . 'Apps' . DS . 'Test' . DS . 'Case' . DS . 'AllUnitTestsTest.php');
        $suite->addTestFile(APP . 'Plugin' . DS . 'Apps' . DS . 'Test' . DS . 'Case' . DS . 'AllIntegrationTestsTest.php');
        return $suite;
    }
}
