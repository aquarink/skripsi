<?php

class Utama {

    public function ambilLink() {

        if (isset($_GET['p'])) {

            $link = $_GET['p'];

            if (empty($link)) {

                include_once $this->callCtrl('index');
                include_once $this->viewLink('index');
            } elseif ($link == 'out') {
                session_destroy();
                header("Location: ?");
            } else {

                $controller = 'p_controller/' . ucfirst($link) . 'View.php';

                if (file_exists($controller)) {

                    include_once $controller;
                } else {

                    include_once $this->callCtrl('error');
                }
            }
        } else {

            include_once $this->callCtrl('index');
        }
    }

    private function callCtrl($ctrl = NULL) {

        return 'p_controller/' . ucfirst($ctrl) . 'View.php';
    }

}

session_start();

$utama = new Utama();
$link = $utama->ambilLink();
