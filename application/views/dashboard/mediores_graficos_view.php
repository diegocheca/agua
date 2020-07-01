<div class="col-md-6">
    <div id="pie-charts" class="dash-widget-item">
        <div class="bgm-blue">
            <div class="dash-widget-header">
                <div class="dash-widget-title">Email Statistics</div>
            </div>
            <div class="clearfix"></div>

            <div class="text-center p-20 m-t-25">
                <div class="easy-pie sec-pie-3 m-b-15" data-percent="40">
                    <div class="percent">40</div>
                    <div class="pie-title">Total de mails enviados</div>
                </div>
                <?php echo anchor('inventario/','Ver mails',array('class'=>'btn btn-block btn-lg bgm-indigo')); ?>
            </div>
        </div>
        <div class="p-t-20 p-b-10 text-center">
            <div class="easy-pie sec-pie-2 m-b-2" data-percent="70">
                <div class="percent">70</div>
                <div class="pie-title">Mails correctos</div>
            </div>
             <div class="easy-pie sec-pie-1 m-b-7" data-percent="20">
                <div class="percent">20</div>
                <div class="pie-title">Mails con error</div>
            </div>
        </div>
    </div>
</div>

