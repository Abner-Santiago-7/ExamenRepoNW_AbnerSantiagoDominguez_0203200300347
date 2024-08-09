<?php

namespace Controllers\Vehiculos;

use \Dao\Vehiculos\Vehiculos as DaoVehiculos;
use \Utilities\Validators as Validators;
use \Utilities\Site as Site;

class Vehiculo extends \Controllers\PublicController{
    private $mode = "NAN";
    private $modeDscArr = [
        "INS" => "Nuevo Vehiculo",
        "UPD" => "Actualizando Vehiculo %s",
        "DSP" => "Detalle de %s",
        "DEL" => "Eliminando %s"
    ];
    private $modeDsc = "";

    /*Variables de la tabla */

    private $id_vehiculo = 0; 
    private $marca = "";
    private $modelo = "";
    private $año_fabricacion = 0;  
    private $tipo_combustible = "";
    private $kilometraje = 0; 
    /*Variables de la tabla */

    private $errors = array();
    private $xsrftk = "";

    public function run(): void
    {
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

    private function obtenerDatosDelGet()
    {
        if (isset($_GET["mode"])) {
            $this->mode = $_GET["mode"];
        }
        if (!isset($this->modeDscArr[$this->mode])) {
            throw new \Exception("Modo no válido");
        }
        if (isset($_GET["id_vehiculo"])) {
            $this->id_vehiculo = $_GET["id_vehiculo"];
        }

        if ($this->mode != "INS" && $this->id_vehiculo <= 0) {
            throw new \Exception("ID no válido");
        }
    }
    

    private function getDatosFromDB()
{
    if ($this->id_vehiculo > 0) {
        $vehiculos = DaoVehiculos::readVehiculo($this->id_vehiculo);
        if (!$vehiculos) {
            throw new \Exception("Vehículo no encontrado");
        }
        $this->marca = $vehiculos["marca"] ?? '';
        $this->modelo = $vehiculos["modelo"] ?? '';
        $this->año_fabricacion = $vehiculos["año_fabricacion"] ?? 0;
        $this->tipo_combustible = $vehiculos["tipo_combustible"] ?? '';
        $this->kilometraje = $vehiculos["kilometraje"] ?? 0;
    }
}

    private function obtenerDatosDePost()
    {
        $tmpMar = $_POST["marca"] ?? "";
        $tmpMod = $_POST["modelo"] ?? "";
        $tmpAf = $_POST["año_fabricacion"] ?? "";
        $tmpTc = $_POST["tipo_combustible"] ?? "";
        $tmpKm = $_POST["kilometraje"] ?? "";

        $tmpMode = $_POST["mode"] ?? "";
        $tmpXsrfTk = $_POST["xsrftk"] ?? "";

        $this->getXSRFToken();
        if (!$this->compareXSRFToken($tmpXsrfTk)) {
            $this->throwError("Ocurrio un error al procesar la solicitud.");
        }

        /*Marca*/
        if (Validators::IsEmpty($tmpMar)) {
            $this->addError("marca", "La marca no puede estar vacio", "error");
        }
        $this->marca = $tmpMar;
        
        /*Modelo */
        if (Validators::IsEmpty($tmpMod)) {
            $this->addError("modelo", "El modelo no puede estar vacio", "error");
        }
        $this->modelo = $tmpMod;

        /*Año*/
        if (Validators::IsEmpty($tmpAf)) {
            $this->addError("a_fabricacion", "El año de fabricaion no puede estar vacio", "error");
        }
        $this->a_fabricacion = $tmpAf;

        /*Tipo Combustible */
        if (!Validators::IsEmpty($tmpTc)) {
            $this->addError("tipo_combustible", "El Tipo de combustible no puede estar vacio", "error");
        } elseif (!Validators::IsAlpha($tmpTc)) {
            $this->addError("tipo_combustible", "El tipo de combustible debe ser una cadena", "error");
        }
        


          /*kilometraje */
          if (Validators::IsEmpty($tmpKm)) {
            $this->addError("kilometraje", "El kilometraje no puede estar vacio", "error");
        }
        $this->kilometraje = $tmpKm;

        /*Modo */
        if (Validators::IsEmpty($tmpMode) || !in_array($tmpMode, ["INS", "UPD", "DEL"])) {
            $this->throwError("Ocurrio un error al procesar la solicitud.");
        }
    }

    private function procesarAccion()
    {
        switch ($this->mode) {
            case "INS":
                $insResult = DaoVehiculos::createVehiculo(
    
                    $this->marca,
                    $this->modelo,
                    $this->año_fabricacion,
                    $this->tipo_combustible,
                    $this->kilometraje

                );
                $this->validateDBOperation(
                    "Vehiculo insertado correctamente",
                    "Ocurrio un error al insertar el vehiculo",
                    $insResult
                );
                break;
            case "UPD":
                $updResult = DaoVehiculos::updateVehiculo(
                    $this->id_vehiculo,
                    $this->marca,
                    $this->modelo,
                    $this->año_fabricacion,
                    $this->tipo_combustible,
                    $this->kilometraje
                );
                $this->validateDBOperation(
                    "Vehiculo actualizado correctamente",
                    "Ocurrio un error al actualizar el vehiculo",
                    $updResult
                );
                break;
            case "DEL":
                $delResult = DaoVehiculos::deleteVehiculo($this->id_vehiculo);
                $this->validateDBOperation(
                    "Vehiculo eliminado correctamente",
                    "Ocurrio un error al eliminar el vehiculo",
                    $delResult
                );
                break;
        }
    }

    private function validateDBOperation($msg, $error, $result)
    {
        if (!$result) {
            $this->errors["error_general"] = $error;
        } else {
            Site::redirectToWithMsg(
                "index.php?page=Vehiculos-Vehiculos",
                $msg
            );
        }
    }

    private function throwError($msg)
    {
        Site::redirectToWithMsg(
            "index.php?page=Vehiculos-Vehiculos",
            $msg
        );
    }

    private function addError($key, $msg, $context = "general")
    {
        if (!isset($this->errors[$context . "_" . $key])) {
            $this->errors[$context . "_" . $key] = [];
        }
        $this->errors[$context . "_" . $key][] = $msg;
    }

    private function generateXSRFToken()
    {
        $this->xsrftk = md5(uniqid(rand(), true));
        $_SESSION[$this->name . "_xsrftk"] = $this->xsrftk;
    }
    private function getXSRFToken()
    {
        if (isset($_SESSION[$this->name . "_xsrftk"])) {
            $this->xsrftk = $_SESSION[$this->name . "_xsrftk"];
        }
    }
    private function compareXSRFToken($postXSFR)
    {
        return $postXSFR === $this->xsrftk;
    }

    private function showView()
    {
        $this->generateXSRFToken();
        $viewData = array();
        $viewData["mode"] = $this->mode;
        $viewData["modeDsc"] = sprintf($this->modeDscArr[$this->mode], $this->marca);

        $viewData["id_vehiculo"] = $this->id_vehiculo;
        $viewData["marca"] = $this->marca;
        $viewData["modelo"] = $this->modelo;
        $viewData["año_fabricacion"] = $this->año_fabricacion;
        $viewData["tipo_combustible"] = $this->tipo_combustible;
        $viewData["kilometraje"] = $this->kilometraje;

        $viewData["errors"] = $this->errors;
        $viewData["xsrftk"] = $this->xsrftk;
        $viewData["isReadOnly"] = in_array($this->mode, ["DEL", "DSP"]) ? "readonly" : "";
        $viewData["isDisplay"] = $this->mode == "DSP";
        \Views\Renderer::render("vehiculo/vehiculo", $viewData);
    }
}