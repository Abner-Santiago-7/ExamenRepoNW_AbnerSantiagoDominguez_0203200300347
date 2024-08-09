<?php

namespace Controllers\Inventory;

use \Dao\Inventory\Inventory as DaoInventory;
use \Utilities\Validators as Validators;
use \Utilities\Site as Site;

class Inventory extends \Controllers\PublicController
{
    private $mode = "";
    private $modeDscArr = [
        "INS" => "Nuevo Inventario",
        "UPD" => "Actualizando Inventario %s",
        "DSP" => "Detalle de Inventario %s",
        "DEL" => "Eliminando Inventario %s"
    ];
    private $viewData = [];
    private $arrErrors = [];

    public function run(): void
    {
        $this->init();
        if ($this->isPostBack()) {
            $this->handlePost();
        }
        $this->renderView();
    }

    private function init()
    {
        $this->viewData = [
            "mode" => $_GET["mode"] ?? "INS",
            "InventoryID" => intval($_GET["InventoryID"] ?? 0),
            "ProductID" => "",
            "Quantity" => "",
            "WarehouseLocation" => "",
            "LastUpdated" => date('Y-m-d'),
            "xsrftoken" => "",
            "readonly" => false,
            "showAction" => true,
        ];

        if (!isset($this->modeDscArr[$this->viewData["mode"]])) {
            Site::redirectToWithMsg(
                "index.php?page=Inventory-InventoryList",
                "Modo de operación no válido."
            );
        }

        if ($this->viewData["mode"] !== "INS" && $this->viewData["InventoryID"] <= 0) {
            Site::redirectToWithMsg(
                "index.php?page=Inventory-InventoryList",
                "Identificador de Inventario no válido."
            );
        }

        $this->viewData["xsrftoken"] = md5(time() + rand(0, 9999));
        $_SESSION["inventory_xsrf_token"] = $this->viewData["xsrftoken"];

        if ($this->viewData["mode"] !== "INS") {
            $dbInventory = DaoInventory::getInventoryById($this->viewData["InventoryID"]);
            if (!$dbInventory) {
                Site::redirectToWithMsg(
                    "index.php?page=Inventory-InventoryList",
                    "Inventario no encontrado."
                );
            }
            $this->viewData = array_merge($this->viewData, $dbInventory);
            $this->viewData["LastUpdated"] = date('Y-m-d', strtotime($dbInventory["LastUpdated"]));
        }

        if (in_array($this->viewData["mode"], ["DSP", "DEL"])) {
            $this->viewData["readonly"] = true;
            if ($this->viewData["mode"] === "DEL") {
                $this->viewData["showAction"] = true;
            } else {
                $this->viewData["showAction"] = false;
            }
        }
    }

    private function handlePost()
    {
        // Validar XSRF Token
        if (
            !isset($_POST["xsrftoken"]) ||
            $_POST["xsrftoken"] !== $_SESSION["inventory_xsrf_token"]
        ) {
            $this->arrErrors[] = "Error de validación, intente nuevamente.";
        }

        // Recoger datos del formulario
        $this->viewData["ProductID"] = intval($_POST["ProductID"] ?? 0);
        $this->viewData["Quantity"] = intval($_POST["Quantity"] ?? 0);
        $this->viewData["WarehouseLocation"] = $_POST["WarehouseLocation"] ?? "";
        $this->viewData["LastUpdated"] = $_POST["LastUpdated"] ?? date('Y-m-d');

        // Validaciones
        if ($this->viewData["ProductID"] <= 0) {
            $this->arrErrors[] = "Producto es requerido.";
        }

        if ($this->viewData["Quantity"] < 0) {
            $this->arrErrors[] = "La cantidad no puede ser negativa.";
        }

        if (Validators::IsEmpty($this->viewData["WarehouseLocation"])) {
            $this->arrErrors[] = "La ubicación del almacén es requerida.";
        }

        if (!Validators::isValidDate($this->viewData["LastUpdated"])) {
            $this->arrErrors[] = "Fecha de actualización no es válida.";
        }

        if (empty($this->arrErrors)) {
            switch ($this->viewData["mode"]) {
                case "INS":
                    $inserted = DaoInventory::createInventory(
                        $this->viewData["ProductID"],
                        $this->viewData["Quantity"],
                        $this->viewData["WarehouseLocation"],
                        $this->viewData["LastUpdated"]
                    );
                    if ($inserted) {
                        Site::redirectToWithMsg(
                            "index.php?page=Inventory-InventoryList",
                            "Inventario creado exitosamente."
                        );
                    }
                    break;
                case "UPD":
                    $updated = DaoInventory::updateInventory(
                        $this->viewData["InventoryID"],
                        $this->viewData["ProductID"],
                        $this->viewData["Quantity"],
                        $this->viewData["WarehouseLocation"],
                        $this->viewData["LastUpdated"]
                    );
                    if ($updated) {
                        Site::redirectToWithMsg(
                            "index.php?page=Inventory-InventoryList",
                            "Inventario actualizado exitosamente."
                        );
                    }
                    break;
                case "DEL":
                    $deleted = DaoInventory::deleteInventory($this->viewData["InventoryID"]);
                    if ($deleted) {
                        Site::redirectToWithMsg(
                            "index.php?page=Inventory-InventoryList",
                            "Inventario eliminado."
                        );
                    }
                    break;
            }
        }
    }

    private function renderView()
    {
        $this->viewData["modeDsc"] = sprintf(
            $this->modeDscArr[$this->viewData["mode"]],
            $this->viewData["InventoryID"]
        );
        $this->viewData["arrErrors"] = $this->arrErrors;
        \Views\Renderer::render("inventory/inventory", $this->viewData);
    }
}
