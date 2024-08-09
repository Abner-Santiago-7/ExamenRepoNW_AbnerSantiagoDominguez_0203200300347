<?php

namespace Controllers\Inventario;

use \Dao\Inventarios\Inventarios as DaoInventario;
use \Utilities\Validators as Validators;
use \Utilities\Site as Site;

class Inventario extends \Controllers\PublicController {
    private $mode = "NAN";
    private $modeDscArr = [
        "INS" => "Nuevo Inventario",
        "UPD" => "Actualizando Inventario %s",
        "DSP" => "Detalle de %s",
        "DEL" => "Eliminando %s"
    ];
    private $modeDsc = "";

    /* Variables de la tabla */
    private $InventarioID = 0; 
    private $ProductID = 0;
    private $Quantity = 0;
    private $WarehouseLocation = "";
    private $LastUpdated = ""; 
    /* Variables de la tabla */

    private $errors = array();
    private $xsrftk = "";

    public function run(): void {
        $this->obtenerDatosDelGet();
        $this->getDatosFromDB();
        if ($this->isPostBack()) {
            $this->obtenerDatosDePost();
            if (count($this->errors) === 0) {
                $this->procesarAccion();
            }
        }
        $this->showView();
    }

    private function obtenerDatosDelGet() {
        if (isset($_GET["mode"])) {
            $this->mode = $_GET["mode"];
        }
        if (!isset($this->modeDscArr[$this->mode])) {
            throw new \Exception("Modo no válido");
        }
        if (isset($_GET["InventarioID"])) {
            $this->InventarioID = $_GET["InventarioID"];
        }

        if ($this->mode != "INS" && $this->InventarioID <= 0) {
            throw new \Exception("ID no válido");
        }
    }

    private function getDatosFromDB() {
        if ($this->InventarioID > 0) {
            $Inventario = DaoInventario::readInventarios($this->InventarioID);
            if (!$Inventario) {
                throw new \Exception("Inventario no encontrado");
            }
            $this->ProductID = $Inventario["ProductID"] ?? 0;
            $this->Quantity = $Inventario["Quantity"] ?? 0;
            $this->WarehouseLocation = $Inventario["WarehouseLocation"] ?? '';
            $this->LastUpdated = $Inventario["LastUpdated"] ?? '';
        }
    }

    private function obtenerDatosDePost() {
        $tmpProdID = $_POST["ProductID"] ?? 0;
        $tmpQty = $_POST["Quantity"] ?? 0;
        $tmpLocation = $_POST["WarehouseLocation"] ?? "";
        $tmpLastUpdated = $_POST["LastUpdated"] ?? "";

        $tmpMode = $_POST["mode"] ?? "";
        $tmpXsrfTk = $_POST["xsrftk"] ?? "";

        $this->getXSRFToken();
        if (!$this->compareXSRFToken($tmpXsrfTk)) {
            $this->throwError("Ocurrio un error al procesar la solicitud.");
        }

        /* ProductID */
        if (Validators::IsEmpty($tmpProdID)) {
            $this->addError("ProductID", "El ProductID no puede estar vacío", "error");
        }
        $this->ProductID = $tmpProdID;

        /* Quantity */
        if (Validators::IsEmpty($tmpQty)) {
            $this->addError("Quantity", "La cantidad no puede estar vacía", "error");
        }
        $this->Quantity = $tmpQty;

        /* Warehouse Location */
        if (Validators::IsEmpty($tmpLocation)) {
            $this->addError("WarehouseLocation", "La ubicación del almacén no puede estar vacía", "error");
        }
        $this->WarehouseLocation = $tmpLocation;

        /* LastUpdated */
        if (Validators::IsEmpty($tmpLastUpdated)) {
            $this->addError("LastUpdated", "La fecha de actualización no puede estar vacía", "error");
        }
        $this->LastUpdated = $tmpLastUpdated;

        /* Modo */
        if (Validators::IsEmpty($tmpMode) || !in_array($tmpMode, ["INS", "UPD", "DEL"])) {
            $this->throwError("Ocurrio un error al procesar la solicitud.");
        }
    }

    private function procesarAccion() {
        switch ($this->mode) {
            case "INS":
                $insResult = DaoInventario::createInventarios(
                    $this->ProductID,
                    $this->Quantity,
                    $this->WarehouseLocation,
                    $this->LastUpdated
                );
                $this->validateDBOperation(
                    "Inventario insertado correctamente",
                    "Ocurrio un error al insertar el inventario",
                    $insResult
                );
                break;
            case "UPD":
                $updResult = DaoInventario::updateInventarios(
                    $this->InventarioID,
                    $this->ProductID,
                    $this->Quantity,
                    $this->WarehouseLocation,
                    $this->LastUpdated
                );
                $this->validateDBOperation(
                    "Inventario actualizado correctamente",
                    "Ocurrio un error al actualizar el inventario",
                    $updResult
                );
                break;
            case "DEL":
                $delResult = DaoInventario::deleteInventarios($this->InventarioID);
                $this->validateDBOperation(
                    "Inventario eliminado correctamente",
                    "Ocurrio un error al eliminar el inventario",
                    $delResult
                );
                break;
        }
    }

    private function validateDBOperation($msg, $error, $result) {
        if (!$result) {
            $this->errors["error_general"] = $error;
        } else {
            Site::redirectToWithMsg(
                "index.php?page=Inventario-Inventarios",
                $msg
            );
        }
    }

    private function throwError($msg) {
        Site::redirectToWithMsg(
            "index.php?page=Inventario-Inventarios",
            $msg
        );
    }

    private function addError($key, $msg, $context = "general") {
        if (!isset($this->errors[$context . "_" . $key])) {
            $this->errors[$context . "_" . $key] = $msg;
        }
    }

    private function showView() {
        $viewData = [
            "errors" => $this->errors,
            "ProductID" => $this->ProductID,
            "Quantity" => $this->Quantity,
            "WarehouseLocation" => $this->WarehouseLocation,
            "LastUpdated" => $this->LastUpdated,
            "xsrftk" => $this->xsrftk,
            "mode" => $this->mode,
            "modeDsc" => sprintf($this->modeDscArr[$this->mode], $this->InventarioID)
        ];
        $this->renderView("inventario/inventario.tpl", $viewData);
    }
}
