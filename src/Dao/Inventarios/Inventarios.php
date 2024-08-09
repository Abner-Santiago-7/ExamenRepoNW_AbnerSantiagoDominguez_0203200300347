<?php

namespace Dao\Inventory;

use \Dao\AbstractDAO as AbstractDAO;

class Inventory extends AbstractDAO {

    public static function createInventory($ProductID, $Quantity, $WarehouseLocation, $LastUpdated) {
        $sql = "INSERT INTO Inventory (ProductID, Quantity, Warehouse Location, LastUpdated) VALUES (?, ?, ?, ?)";
        $params = [$ProductID, $Quantity, $WarehouseLocation, $LastUpdated];
        return self::executeNonQuery($sql, $params);
    }

    public static function updateInventory($InventoryID, $ProductID, $Quantity, $WarehouseLocation, $LastUpdated) {
        $sql = "UPDATE Inventory SET ProductID = ?, Quantity = ?, Warehouse Location = ?, LastUpdated = ? WHERE InventoryID = ?";
        $params = [$ProductID, $Quantity, $WarehouseLocation, $LastUpdated, $InventoryID];
        return self::executeNonQuery($sql, $params);
    }

    public static function deleteInventory($InventoryID) {
        $sql = "DELETE FROM Inventory WHERE InventoryID = ?";
        $params = [$InventoryID];
        return self::executeNonQuery($sql, $params);
    }

    public static function readInventory($InventoryID) {
        $sql = "SELECT * FROM Inventory WHERE InventoryID = ?";
        $params = [$InventoryID];
        return self::getRow($sql, $params);
    }

    public static function readAllInventories($search = []) {
        $sql = "SELECT * FROM Inventory WHERE 1=1";
        $params = [];
        
        if (!empty($search["ProductID"])) {
            $sql .= " AND ProductID = ?";
            $params[] = $search["ProductID"];
        }

        if (!empty($search["WarehouseLocation"])) {
            $sql .= " AND Warehouse Location LIKE ?";
            $params[] = '%' . $search["WarehouseLocation"] . '%';
        }

        if (!empty($search["LastUpdated"])) {
            $sql .= " AND LastUpdated = ?";
            $params[] = $search["LastUpdated"];
        }

        return self::getRows($sql, $params);
    }
}
