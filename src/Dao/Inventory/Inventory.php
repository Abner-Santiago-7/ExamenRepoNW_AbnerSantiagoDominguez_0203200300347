<?php

namespace Dao\Inventory;

use Dao\Table;

class Inventory extends Table
{
    public static function createInventory($ProductID, $Quantity, $WarehouseLocation, $LastUpdated)
    {
        $sqlstr = "INSERT INTO Inventory (ProductID, Quantity, WarehouseLocation, LastUpdated)
                   VALUES (:ProductID, :Quantity, :WarehouseLocation, :LastUpdated);";
        $params = [
            "ProductID" => $ProductID,
            "Quantity" => $Quantity,
            "WarehouseLocation" => $WarehouseLocation,
            "LastUpdated" => $LastUpdated
        ];
        return self::executeNonQuery($sqlstr, $params);
    }

    public static function updateInventory($InventoryID, $ProductID, $Quantity, $WarehouseLocation, $LastUpdated)
    {
        $sqlstr = "UPDATE Inventory SET 
                    ProductID = :ProductID,
                    Quantity = :Quantity,
                    WarehouseLocation = :WarehouseLocation,
                    LastUpdated = :LastUpdated
                   WHERE InventoryID = :InventoryID;";
        $params = [
            "InventoryID" => $InventoryID,
            "ProductID" => $ProductID,
            "Quantity" => $Quantity,
            "WarehouseLocation" => $WarehouseLocation,
            "LastUpdated" => $LastUpdated
        ];
        return self::executeNonQuery($sqlstr, $params);
    }

    public static function deleteInventory($InventoryID)
    {
        $sqlstr = "DELETE FROM Inventory WHERE InventoryID = :InventoryID;";
        $params = ["InventoryID" => $InventoryID];
        return self::executeNonQuery($sqlstr, $params);
    }

    public static function getInventoryById($InventoryID)
    {
        $sqlstr = "SELECT * FROM Inventory WHERE InventoryID = :InventoryID;";
        $params = ["InventoryID" => $InventoryID];
        return self::obtenerUnRegistro($sqlstr, $params);
    }

    public static function getAllInventories($filter = "")
    {
        $sqlstr = "SELECT inv.*, prod.ProductName FROM Inventory inv
                   INNER JOIN Products prod ON inv.ProductID = prod.ProductID
                   WHERE prod.ProductName LIKE :filter;";
        $params = ["filter" => "%" . $filter . "%"];
        return self::obtenerRegistros($sqlstr, $params);
    }
}
