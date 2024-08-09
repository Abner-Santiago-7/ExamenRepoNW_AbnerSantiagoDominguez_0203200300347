<section class="container-l WWList">
    <section class="depth-1 px-4 py-4">
        <h2>Lista de Vehiculos</h2>
        <section class="grid">
            <form action="index.php?page=Vehiculos-Vehiculos" method="post" class="row">
                <input class="col-8" type="text" name="search" placeholder="Buscar por marca" value="{{search}}">
                <button class="col-4" type="submit"><i class="fa-solid fa-magnifying-glass"></i> &nbsp;Buscar</button>
            </form>
        </section>
    </section>
    <table class="my-4">
        <thead>
            <tr>
                <th>Id</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Año Fabricacion</th>
                <th>Tipo Combustible</th>
                <th>Kilometraje</th>
                <th><a href="index.php?page=Vehiculos-Vehiculo&mode=INS">
                        <i class="fa-solid fa-file-circle-plus"></i>
                        &nbsp; Nuevo Vehiculo</a></th>
            </tr>
        </thead>
        <tbody>
            {{foreach vehiculos}}
            <tr style="text-align: center;">
                <td>{{id_vehiculo}}</td>
                <td >{{marca}}</td>
                <td >{{modelo}}</td>
                <td >{{a_fabricacion}}</td>
                <td >{{tipo_combustible}}</td>
                <td >{{kilometraje}}</td>

                <td class="center">
                    <a href="index.php?page=Vehiculos-Vehiculo&mode=DSP&id_vehiculo={{id_vehiculo}}">
                        <i class="fa-solid fa-pen"></i> &nbsp; Visualizar
                    </a>
                    &nbsp;
                    &nbsp;
                    <a href="index.php?page=Vehiculos-Vehiculo&mode=UPD&id_vehiculo={{id_vehiculo}}">
                        <i class="fa-solid fa-pen"></i> &nbsp; Editar
                    </a>
                    &nbsp;
                    &nbsp;
                    <a href="index.php?page=Vehiculos-Vehiculo&mode=DEL&id_vehiculo={{id_vehiculo}}">
                        <i class="fa-solid fa-trash-can"></i> &nbsp;
                        Eliminar
                    </a>
                </td>
            </tr>
            {{endfor vehiculos}}

        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">Total de registros: {{total}}</td>
            </tr>
        </tfoot>
    </table>
</section>