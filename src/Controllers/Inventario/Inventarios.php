<?php

namespace Controllers\Inventarios;

use \Dao\Inventarios\Inventarios as DaoInventarios;

const SESSION_Inventarios_SEARCH = "Inventarios_search_data";

class Inventarios extends \Controllers\PublicController {
    public function run(): void {
        $viewData = array();
        $viewData["search"] = $this->getSessionSearchData();
        if ($this->isPostBack()) {
            $viewData["search"] = $this->getSearchData();
            $this->setSessionSearchData($viewData["search"]);
        }
        $viewData["Inventarios"] = DaoInventarios::readAllInventarios($viewData["search"]);
        $viewData["total"] = count($viewData["Inventarios"]);

        \Views\Renderer::render("Inventarios/Inventarios", $viewData);
    }

    private function getSearchData() {
        return [
            "ProductID" => $_POST["ProductID"] ?? "",
            "WarehouseLocation" => $_POST["WarehouseLocation"] ?? "",
            "LastUpdated" => $_POST["LastUpdated"] ?? ""
        ];
    }

    private function setSessionSearchData($searchData) {
        $_SESSION[SESSION_Inventarios_SEARCH] = $searchData;
    }

    private function getSessionSearchData() {
        return $_SESSION[SESSION_Inventarios_SEARCH] ?? [
            "ProductID" => "",
            "WarehouseLocation" => "",
            "LastUpdated" => ""
        ];
    }
}
