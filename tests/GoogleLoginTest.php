<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'core/Framework.php';

class GoogleLoginTest extends Framework {

    public function setUp() {
        parent::setup();
    }

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
    }

}

?>

