<?php
namespace Controllers\Vehiculos;

use \Dao\Vehiculos\Vehiculos as DaoVehiculos;

const SESSION_VEHICULOS_SEARCH = "vehiculos_search_data";

class Vehiculos extends \Controllers\PublicController
{
    public function run(): void
    {
        $viewData = array();
        $viewData["search"] = $this->getSessionSearchData();
        if ($this->isPostBack()) {
            $viewData["search"] = $this->getSearchData();
            $this->setSessionSearchData($viewData["search"]);
        }
        $viewData["vehiculos"] = DaoVehiculos::readAllVehiculos($viewData["search"]);
        $viewData["total"] = count($viewData["vehiculos"]);

        \Views\Renderer::render("vehiculos/vehiculos", $viewData);
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
        if (isset($_SESSION[SESSION_VEHICULOS_SEARCH])) {
            return $_SESSION[SESSION_VEHICULOS_SEARCH];
        }
        return "";
    }

    private function setSessionSearchData($search)
    {
        $_SESSION[SESSION_VEHICULOS_SEARCH] = $search;
    }
}
