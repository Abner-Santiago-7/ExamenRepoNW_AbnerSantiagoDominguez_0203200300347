<?php


namespace Controllers\Mantenimientos\Productos;

use \Dao\Productos\Productos as DaoProductos;

const SESSION_PRODUCTOS_SEARCH = "productos_search_data";

class Productos extends \Controllers\PublicController
{
    public function run(): void
    {
        $viewData = array();
        $viewData["search"] = $this->getSessionSearchData();
        if ($this->isPostBack()) {
            $viewData["search"] = $this->getSearchData();
            $this->setSessionSearchData($viewData["search"]);
        }
        $viewData["productos"] = DaoProductos::readAllProductos($viewData["search"]);
        $viewData["total"] = count($viewData["productos"]);

        \Views\Renderer::render("mantenimientos/productos/lista", $viewData);
    }

    private function getSearchData()
    {
        if (isset($_POST["search"])) {
            return $_POST["search"];
        }
        return "";
    }

    private function getSessionSearchData()
    {
        if (isset($_SESSION[SESSION_PRODUCTOS_SEARCH])) {
            return $_SESSION[SESSION_PRODUCTOS_SEARCH];
        }
        return "";
    }

    private function setSessionSearchData($search)
    {
        $_SESSION[SESSION_PRODUCTOS_SEARCH] = $search;
    }
}
