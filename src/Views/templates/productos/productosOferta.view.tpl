<h1>Productos en Oferta</h1>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="row">
                {{foreach productos}}
                <div class="col-4">
                    <div class="card">
                        <img src="{{imagen}}" class="card-img-top" alt="{{nombre}}">
                        <h2>{{nombre}}</h2>
                        <span>{{precio}}</span>
                    </div>
                </div>
                {{endfor productos}}
            </div>
        </div>
    </div>
</div>