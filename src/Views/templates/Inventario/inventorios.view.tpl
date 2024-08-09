<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventarios</title>
</head>
<body>
    <h1>Inventarios</h1>
    <form method="POST" action="index.php">
        <input type="hidden" name="page" value="Inventario-Inventarios" />
        <label for="ProductID">ProductID:</label>
        <input type="text" id="ProductID" name="ProductID" value="{$search.ProductID}" />
        <br />

        <label for="WarehouseLocation">Warehouse Location:</label>
        <input type="text" id="WarehouseLocation" name="WarehouseLocation" value="{$search.WarehouseLocation}" />
        <br />

        <label for="LastUpdated">Last Updated:</label>
        <input type="date" id="LastUpdated" name="LastUpdated" value="{$search.LastUpdated}" />
        <br />

        <button type="submit">Buscar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ProductID</th>
                <th>Quantity</th>
                <th>Warehouse Location</th>
                <th>Last Updated</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            {foreach $Inventarios as $Inventario}
                <tr>
                    <td>{$Inventario.ProductID}</td>
                    <td>{$Inventario.Quantity}</td>
                    <td>{$Inventario.WarehouseLocation}</td>
                    <td>{$Inventario.LastUpdated}</td>
                    <td>
                        <a href="index.php?page=Inventario-Inventario&mode=DSP&InventarioID={$Inventario.InventarioID}">Ver</a>
                        <a href="index.php?page=Inventario-Inventario&mode=UPD&InventarioID={$Inventario.InventarioID}">Editar</a>
                        <a href="index.php?page=Inventario-Inventario&mode=DEL&InventarioID={$Inventario.InventarioID}">Eliminar</a>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <p>Total de registros: {$total}</p>
</body>
</html>
