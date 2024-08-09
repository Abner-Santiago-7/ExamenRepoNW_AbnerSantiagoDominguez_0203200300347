<section class="container-l WWList">
    <section class="depth-1 px-4 py-4">
        <h2>Lista de productos</h2>
        <section class="grid">
            <form action="index.php?page=Mantenimientos-Productos-Productos" method="post" class="row">
                <input class="col-8" type="text" name="search" placeholder="Buscar por nombre" value="{{search}}">
                <button class="col-4" type="submit"> <i class="fa-solid fa-magnifying-glass"></i>
                &nbsp; Buscar</button>
            </form>
        </section>
    </section>
    <table class="my-4">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th><a href="index.php?page=Mantenimientos-Productos-Producto&mode=INS">
                    <i class="fa-solid fa-circle-plus"></i> 
                    &nbsp; Nuevo producto</a></th>
            </tr>
        </thead>
        <tbody>
            {{foreach productos}}
                <tr>
                    <td>{{id}}</td>
                    <td><a href="index.php?page=Mantenimientos-Productos-Producto&mode=DSP&id={{id}}"><i class="fa-solid fa-eye"></i> &nbsp;{{name}}</a></td>
                    <td>{{category_name}}</td>
                    <td>{{price}}</td>
                    <td>{{stock}}</td>
                    <td>{{status}}</td>
                    <td>
                        <a href="index.php?page=Mantenimientos-Productos-Producto&mode=UPD&id={{id}}">
                            <i class="fa-solid fa-pen"></i> &nbsp; Editar
                        </a>
                        &nbsp;
                        &nbsp;
                        <a href="index.php?page=Mantenimientos-Productos-Producto&mode=DEL&id={{id}}">
                            <i class="fa-solid fa-trash-can"></i> &nbsp;
                            Eliminar
                        </a>
                    </td>
                </tr>
            {{endfor productos}}

        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">Total de registros: {{total}}</td>
            </tr>
        </tfoot>
    </table>
</section>