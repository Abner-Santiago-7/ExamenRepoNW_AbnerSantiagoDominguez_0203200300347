<section class="grid">
    <section class="row">
        <h2 class="col-12 depth-1 p-4">{{modeDsc}}</h2>
    </section>
</section>
<section class="grid">
    <section class="row my-4">
        <form class="col-12 depth-1" method="POST">
            <input type="hidden" name="xsrftoken" value="{{xsrftoken}}">

            <div class="row my-2">
                <label class="col-4" for="ProductID">Producto:</label>
                <input class="col-8" type="number" name="ProductID" id="ProductID" value="{{ProductID}}" {{#if readonly}}readonly{{/if}}>
            </div>

            <div class="row my-2">
                <label class="col-4" for="Quantity">Cantidad:</label>
                <input class="col-8" type="number" name="Quantity" id="Quantity" value="{{Quantity}}" {{#if readonly}}readonly{{/if}}>
            </div>

            <div class="row my-2">
                <label class="col-4" for="WarehouseLocation">Ubicación del Almacén:</label>
                <input class="col-8" type="text" name="WarehouseLocation" id="WarehouseLocation" value="{{WarehouseLocation}}" {{#if readonly}}readonly{{/if}}>
            </div>

            <div class="row my-2">
                <label class="col-4" for="LastUpdated">Fecha de Actualización:</label>
                <input class="col-8" type="date" name="LastUpdated" id="LastUpdated" value="{{LastUpdated}}" {{#if readonly}}readonly{{/if}}>
            </div>

            {{#if arrErrors}}
            <div class="row my-2">
                <div class="col-12 error">
                    <ul>
                        {{#each arrErrors}}
                        <li>{{this}}</li>
                        {{/each}}
                    </ul>
                </div>
            </div>
            {{/if}}

            <div class="row flex-end">
                {{#if showAction}}
                <button type="submit" class="primary mx-2">
                    <i class="fa-solid fa-check"></i>&nbsp;Guardar
                </button>
                {{/if}}
                <button type="button" onclick="window.location='index.php?page=Inventory-InventoryList'">
                    <i class="fa-solid fa-xmark"></i>&nbsp;Cancelar
                </button>
            </div>
        </form>
    </section>
</section>
