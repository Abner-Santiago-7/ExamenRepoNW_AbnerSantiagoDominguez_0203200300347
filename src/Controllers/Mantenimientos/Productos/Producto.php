<?php

namespace Controllers\Mantenimientos\Productos;

use \Dao\Productos\Productos as DaoProductos;
use \Dao\Productos\Categories as DaoCategories;
use \Utilities\Validators as Validators;
use \Utilities\Site as Site;

class Producto extends \Controllers\PublicController
{
    private $mode = "NAN";
    private $modeDscArr = [
        "INS" => "Nuevo Producto",
        "UPD" => "Actualizando Producto %s",
        "DSP" => "Detalle de %s",
        "DEL" => "Eliminando %s"
    ];
    private $modeDsc = "";

    private $id = 0;
    private $prdname = "";
    private $price = 0;
    private $stock = 0;
    private $status = "ACT";
    private $category_id = 0;

    private $categories = [];

    private $errors = array();
    private $xsrftk = "";

    public function run(): void
    {

        // 1 cargarData del GET
        $this->obtenerDatosDelGet();
        // 2 si en GET viene el ID obtener producto del DB
        $this->getDatosFromDB();
        // 3 si es  un postback
        if ($this->isPostBack()) {
            $this->obtenerDatosDePost();
            if (count($this->errors) === 0) {
                // 3.3 si los datos son validos
                // 3.3.1 guardar datos en la base de datos
                // 3.3.2 redirigir a la lista de productos
                $this->procesarAccion();
            }
            // 3.4 si los datos no son validos
            // 3.4.1 mostrar errores
        }
        // 4 mostrar el formulario
        $this->showView();
    }

    private function obtenerDatosDelGet()
    {
        if (isset($_GET["mode"])) {
            $this->mode = $_GET["mode"];
        }
        if (!isset($this->modeDscArr[$this->mode])) {
            throw new \Exception("Modo no valido");
        }
        if (isset($_GET["id"])) {
            $this->id = intval($_GET["id"]);
        }
        if ($this->mode != "INS" && $this->id <= 0) {
            throw new \Exception("ID no valido");
        }
    }

    private function getDatosFromDB()
    {
        if ($this->id > 0) {
            $producto = DaoProductos::readProducto($this->id);
            if (!$producto) {
                throw new \Exception("Producto no encontrado");
            }
            $this->prdname = $producto["name"];
            $this->price = $producto["price"];
            $this->stock = $producto["stock"];
            $this->status = $producto["status"];
            $this->category_id = $producto["category_id"];
        }
    }

    private function obtenerDatosDePost()
    {
        $tmpName = $_POST["name"] ?? "";
        $tmpPrice = $_POST["price"] ?? "";
        $tmpStock = $_POST["stock"] ?? "";
        $tmpStatus = $_POST["status"] ?? "";
        $tmpMode = $_POST["mode"] ?? "";
        $tmpXsrfTk = $_POST["xsrftk"] ?? "";
        $tmpCategory_id = $_POST["category"] ?? 0;

        $this->getXSRFToken();
        if (!$this->compareXSRFToken($tmpXsrfTk)) {
            $this->throwError("Ocurrio un error al procesar la solicitud.");
        }

        if (Validators::IsEmpty($tmpName)) {
            $this->addError("name", "El nombre no puede estar vacio", "error");
        }
        $this->prdname = $tmpName;

        if (Validators::IsEmpty($tmpPrice)) {
            $this->addError("price", "El precio no puede estar vacio", "error");
        } elseif (!Validators::IsCurrency($tmpPrice)) {
            $this->addError("price", "El precio no es valido", "error");
        }
        $this->price = $tmpPrice;

        if (Validators::IsEmpty($tmpStock)) {
            $this->addError("stock", "El stock no puede estar vacio", "error");
        } elseif (!Validators::IsInteger($tmpStock)) {
            $this->addError("stock", "El stock no es valido", "error");
        }
        $this->stock = $tmpStock;

        if (Validators::IsEmpty($tmpStatus)) {
            $this->addError("status", "El status no puede estar vacio", "error");
        } elseif (!in_array($tmpStatus, ["ACT", "INA"])) {
            $this->addError("status", "El status no es valido", "error");
        }
        $this->status = $tmpStatus;

        if (Validators::IsEmpty($tmpMode) || !in_array($tmpMode, ["INS", "UPD", "DEL"])) {
            $this->throwError("Ocurrio un error al procesar la solicitud.");
        }

        if ($tmpCategory_id <= 0) {
            $this->addError("category", "La categoria no puede estar vacia", "error");
        }

        $this->category_id = $tmpCategory_id;
    }

    private function procesarAccion()
    {
        switch ($this->mode) {
            case "INS":
                $insResult = DaoProductos::createProducto(
                    $this->prdname,
                    $this->price,
                    $this->stock,
                    $this->status,
                    $this->category_id
                );
                $this->validateDBOperation(
                    "Producto insertado correctamente",
                    "Ocurrio un error al insertar el producto",
                    $insResult
                );
                break;
            case "UPD":
                $updResult = DaoProductos::updateProducto(
                    $this->id,
                    $this->prdname,
                    $this->price,
                    $this->stock,
                    $this->status,
                    $this->category_id
                );
                $this->validateDBOperation(
                    "Producto actualizado correctamente",
                    "Ocurrio un error al actualizar el producto",
                    $updResult
                );
                break;
            case "DEL":
                $delResult = DaoProductos::deleteProducto($this->id);
                $this->validateDBOperation(
                    "Producto eliminado correctamente",
                    "Ocurrio un error al eliminar el producto",
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
                "index.php?page=Mantenimientos-Productos-Productos",
                $msg
            );
        }
    }

    private function throwError($msg)
    {
        Site::redirectToWithMsg(
            "index.php?page=Mantenimientos-Productos-Productos",
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

    private function prepararCategories()
    {
        $tmpCategories = DaoCategories::getCategoriesForCombo();
        $this->categories = [];
        foreach ($tmpCategories as $category) {
            if ($category["category_id"] == $this->category_id) {
                $category["categorySelected"] = "selected";
            } else {
                $category["categorySelected"] = "";
            }
            $this->categories[] = $category;
        }
    }

    private function showView()
    {
        $this->generateXSRFToken();
        $viewData = array();
        $viewData["mode"] = $this->mode;
        $viewData["modeDsc"] = sprintf($this->modeDscArr[$this->mode], $this->prdname);
        $viewData["id"] = $this->id;
        $viewData["name"] = $this->prdname;
        $viewData["price"] = $this->price;
        $viewData["stock"] = $this->stock;
        $viewData["status"] = $this->status;
        $viewData["errors"] = $this->errors;
        $viewData["prdest" . $this->status] = "selected";
        $viewData["xsrftk"] = $this->xsrftk;
        $viewData["isReadOnly"] = in_array($this->mode, ["DEL", "DSP"]) ? "readonly" : "";
        $viewData["isDisplay"] = $this->mode == "DSP";
        $this->prepararCategories();
        $viewData["categories"] = $this->categories;
        \Views\Renderer::render("mantenimientos/productos/form", $viewData);
    }
}
