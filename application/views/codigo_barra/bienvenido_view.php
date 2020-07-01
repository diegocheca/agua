<div class="col-sm-6">
    <!-- Recent Items -->
    <div class="card z-depth-2">
        <div class="card-header bgm-indigo">
            <h2>Codigo  de barra <small>pagar una boleta desde el codigo de barra</small></h2>
            <ul class="actions">
                <li class="dropdown">
                    <a href="" data-toggle="dropdown" aria-expanded="false">
                        <i style="color:white" class="zmdi zmdi-more-vert"></i>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a href="">Refresh</a>
                        </li>
                        <li>
                            <a href="">Settings</a>
                        </li>
                        <li>
                            <a href="">Other Settings</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="card-body">
			<div class="row">
                <div class="col-md-12">
                    <form method="post" action="<?php echo base_url("buscador/buscar_codigo");?>" autocomplete="off">
    					<br>
    					<div class="col-md-7">
    						<label for="codigo">Codigo de Boleta</label>
    						<div class="input-group form-group">
    							<span class="input-group-addon"><i class="zmdi zmdi-dialpad"></i></span>
    							<div class="fg-line">
    								<input type="text" name="codigo" id="codigo" placeholder="Codigo"  class="form-control input-sm" autofocus />
    							</div>
    						</div>
    					</div>
                        <div class="col-md-5">
                            <div class="row">
                                <ul>
                                    <li  style="list-style:none;">
                                        <button type="submit" id="buscar" name="buscar" style="width:95%" class="btn btn-success waves-effect"><i class="zmdi zmdi-search"></i> Buscar</button>
                                    </li>
                                    <br>
                                    <!-- <li  style="list-style:none;">
                                        <a href="#" class="btn btn-primary waves-effect" style="width:95%"><i class="zmdi zmdi-eye"></i> Ver Detalles</a>
                                    </li> -->
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
	       </div>
    	</div>
    </div>
</div>
