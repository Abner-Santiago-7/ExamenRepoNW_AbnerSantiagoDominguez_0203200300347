<section class="container-l WWList">
    <section class="depth-1 px-4 py-4">
        <h2>Lista de Inventario</h2>
        <section class="grid">
            <form method="post" class="row">
                <input class="col-8" type="text" name="search" placeholder="Buscar por nombre de producto" value="{{search}}">
                <button class="col-4" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>&nbsp;Buscar
                </button>
            </form>
        </section>
    </section>
    <table class="my-4">
        <thead>
            <tr>
                <th>ID Inventario</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Ubicación del Almacén</th>
                <th>Última Actualización</th>
                <th>
                    <a href="index.php?page=Inventory-Inventory&mode=INS">
                        <i class="fa-solid fa-file-circle-plus"></i>&nbsp;Nuevo
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{#each inventories}}
            <tr>
                <td>{{InventoryID}}</td>
                <td>{{ProductName}}</td>
                <td>{{Quantity}}</td>
                <td>{{WarehouseLocation}}</td>
                <td>{{LastUpdated}}</td>
                <td class="center">
                    <a href="index.php?page=Inventory-Inventory&mode=DSP&InventoryID={{InventoryID}}">
                        <i class="fa-solid fa-eye"></i>&nbsp;Ver
                    </a>
                    &nbsp;|&nbsp;
                    <a href="index.php?page=Inventory-Inventory&mode=UPD&InventoryID={{InventoryID}}">
                        <i class="fa-solid fa-pen"></i>&nbsp;Editar
                    </a>
                    &nbsp;|&nbsp;
                    <a href="index.php?page=Inventory-Inventory&mode=DEL&InventoryID={{InventoryID}}">
                        <i class="fa-solid fa-trash"></i>&nbsp;Eliminar
                    </a>
                </td>
            </tr>
            {{/each}}
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">Total de registros: {{inventories.length}}</td>
            </tr>
        </tfoot>
    </table>
</section>
