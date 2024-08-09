<?php

namespace Controllers\Landing;

use Controllers\PublicController;
use Dao\Productos\Productos;
use Views\Renderer;

class ProductosOferta extends PublicController
{

    public function run(): void
    {
        //
        // Extraer los 5 productos con Oferta
        // DAO (Data Access Object ) Productos Las Ofertas
        // Crear una Vista para mostrar los productos
        // Inyectar los productos en la vista

        $viewData = array();
        $viewData["productos"] = Productos::getProductosOferta();


        Renderer::render('productos/productosOferta', $viewData);
    }
}
