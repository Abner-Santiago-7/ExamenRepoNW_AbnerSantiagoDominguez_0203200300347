<section class="grid">
    <section class="row">
        <h2 class="col-12 col-m-6 offset-m-3 depth-1 p-4">{{modeDsc}}</h2>
    </section>
</section>
<section class="grid">
    <section class="row my-4">
        <form class="col-12 col-m-6 offset-m-3 depth-1" action="index.php?page=Vehiculos-Vehiculo&mode={{mode}}&id_vehiculo={{id_vehiculo}}"
            method="POST">
            <input type="hidden" name="id_vehiculo" value="{{id_vehiculo}}">
            <input type="hidden" name="xsrftk" value="{{xsrftk}}">
            <input type="hidden" name="mode" value="{{mode}}">

            <div class="row my-4">
                <label class="col-4" for="rtrt">Código:</label>
                <input class="col-8" type="text" name="id_vehiculo" id="rtrt" value="{{id_vehiculo}}" readonly>
            </div>

            <div class="row my-4">
                <label class="col-4" for="rtmar">Marca:</label>
                <input class="col-8" type="text" name="marca" id="rtmar" value="{{marca}}" required
                    {{isReadOnly}}>
                {{with errors}}
                {{if error_marca}}
                {{foreach error_marca}}
                <div class="col-12 error">{{this}}</div>
                {{endfor error_marca}}
                {{endif error_marca}}
                {{endwith errors}}
            </div>
            
            <div class="row my-4">
                <label class="col-4" for="rtmod">Modelo:</label>
                <input class="col-8" type="text" name="modelo" id="rtmod" value="{{modelo}}" required {{isReadOnly}}>
                {{with errors}}
                {{if error_modelo}}
                {{foreach error_modelo}}
                <div class="col-12 error">{{this}}</div>
                {{endfor error_modelo}}
                {{endif error_modelo}}
                {{endwith errors}}
            </div>

            <div class="row my-4">
                <label class="col-4" for="rtaf">Año Fabricacion:</label>
                <input class="col-8" type="number" name="a_fabricacion" id="rtaf" value="{{a_fabricacion}}" required {{isReadOnly}}>
                {{with errors}}
                {{if error_a_fabricacion}}
                {{foreach error_a_fabricacion}}
                <div class="col-12 error">{{this}}</div>
                {{endfor error_a_fabricacion}}
                {{endif error_a_fabricacion}}
                {{endwith errors}}
            </div>

            <div class="row my-4">
                <label class="col-4" for="rttc">Tipo Combustible:</label>
                <input class="col-8" type="text" name="tipo_combustible" id="rttc" value="{{tipo_combustible}}" required {{isReadOnly}}>
                {{with errors}}
                {{if error_tipo_combustible}}
                {{foreach error_tipo_combustible}}
                <div class="col-12 error">{{this}}</div>
                {{endfor error_tipo_combustible}}
                {{endif error_tipo_combustible}}
                {{endwith errors}}
            </div>

           <div class="row my-4">
                <label class="col-4" for="rtkm">Kilometraje:</label>
                <input class="col-8" type="number" name="kilometraje" id="rtkm" value="{{kilometraje}}" required {{isReadOnly}}>
                {{with errors}}
                {{if error_kilometraje}}
                {{foreach error_kilometraje}}
                <div class="col-12 error">{{this}}</div>
                {{endfor error_kilometraje}}
                {{endif error_kilometraje}}
                {{endwith errors}}
            </div>



            <div class="row flex-end">
                {{ifnot isDisplay}}
                <button type="submit" class="primary mx-2">
                    <i class="fa-solid fa-check"></i>&nbsp;
                    Guardar
                </button>
                {{endifnot isDisplay}}
                <button type="button" onclick="window.location='index.php?page=Vehiculos-Vehiculos'">
                    <i class="fa-solid fa-xmark"></i>
                    Cancelar
                </button>
            </div>
        </form>
    </section>
</section>