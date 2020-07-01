<button id="nuevo_egreso" name="nuevo_egreso" type="button">Mostrar</button>
 <div class="modal fade" id="nuevo_egreso_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Agregando un Egreso de Dinero</h4>
                </div>
                <div class="modal-body">
                    <div class="row"  id="nueva_tarea_div">
                        <div class="row">
                          <div class="col-md-8">
                                <label for="tipo_egreso">Tipo de Compra:</label>
                                <div class="fg-line select">
                                    <select id="tipo_egreso" type="text" name="tipo_egreso"  class="chosen" >
                                        <option value="0" selected>Seleccione</option>
                                        <option value="1" > Stock</option>
                                        <option value="2" > Mantemiento</option>
                                        <option value="3" > Particular</option>
                                        <option value="4" > Deuda</option>
                                        <option value="5" > Devolucion</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                     
                        <input type="hidden" name="id_evento_nuevo" id="id_evento_nuevo" value="-1" >

                        <div class="row">
                            <div class="col-md-8">
                                <label for="descripcion_egreso">Descripcion</label>
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-assignment"></i></span>
                                    <div class="fg-line">
                                        <input id="descripcion_egreso" type="text" name="descripcion_egreso" class="form-control input-sm"  >
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                 <label for="estado_evento_nuevo">Tipo de pago:</label>
                                <div class="fg-line select">
                                    <select id="estado_evento_nuevo" type="text" name="estado_evento_nuevo"  class="chosen" >
                                        <option value="0" selected>Seleccione</option>
                                        <option value="1" >Contado</option>
                                        <option value="2" >Cuotas</option>
                                        <option value="3" >Tarjeta</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                 <label for="estado_evento_nuevo">Estado de evento:</label>
                                <div class="fg-line select">
                                    <select id="estado_evento_nuevo" type="text" name="estado_evento_nuevo"  class="chosen" >
                                        <option value="0" selected>Seleccione</option>
                                        <option value="1" >Sin Comenzar</option>
                                        <option value="2" >Comenzo</option>
                                        <option value="3" >Suspendida</option>
                                        <option value="4" >Termino</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="button"  class="btn btn-success" id="guadar_evento_nuevo_modal" name="guadar_evento_nuevo_modal"> Guardar</button>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
        $("#nuevo_egreso").on("click",function(){
        $("#nuevo_egreso_modal").modal('toggle');
    });

</script>