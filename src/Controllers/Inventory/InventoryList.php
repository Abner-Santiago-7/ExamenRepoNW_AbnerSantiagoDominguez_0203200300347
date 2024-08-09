<?php

namespace Controllers\Inventory;

use \Dao\Inventory\Inventory as DaoInventory;
use \Utilities\Site;

class InventoryList extends \Controllers\PublicController
{
    public function run(): void
    {
        $viewData = [];
        $viewData["search"] = $_POST["search"] ?? '';
        $viewData["inventories"] = DaoInventory::getAllInventories($viewData["search"]);
        \Views\Renderer::render("inventory/inventorylist", $viewData);
    }
}
