<?php
require_once 'core/Pelenium_Framework.php';

class GoogleLoginTest extends Pelenium_Framework_SauceLabs {

    function fakeUsernames() {
        return array(
            array('fakeusername1','fakepassord1'),
            array('fakeusername2','fakepassord2')
        );
    }

    /**
     *  Test login of google
     * @param <type> $username
     * @param <type> $password
     * @dataProvider fakeUsernames
     */
    public function testFakeLogin($username, $password) {
        $tb = new TopbarElement($this, '/');
        $tb->gotoLogin();
        $login = new LoginElement($this);
        $login->login($username, $password);
        $tb->verifyFailedLogin();
//        $this->tagTest();
    }

    /**
     * This test will fail because image search does not have the login screen
     * @param <type> $username
     * @param <type> $password
     * @dataProvider fakeUsernames
     */
    public function testThisWillFail($username, $password) {
        $tb = new TopbarElement($this, '/');
        $tb->gotoImageSearch();
        $login = new LoginElement($this);
        $login->login($username, $password);
        $tb->verifyFailedLogin();
//        $this->tagTest('fail');
    }

}

?>

