<!-- 

Vista de forma del catalogo de avisos

@author DEVELOPER 1 <correo@developer1> cel <1111111111>
@creado 29-12-2014

-->

<style type="text/css">
    div.modal {
        position: fixed;
        top: 10%;
        left: 50%;
        z-index: 1050;
        width: 680px;
        margin-left: -330px;
        background-color: #fff;
        border: 1px solid #999;
        border: 1px solid rgba(0,0,0,0.3);
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;
        -webkit-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
        -moz-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
        box-shadow: 0 3px 7px rgba(0,0,0,0.3);
        -webkit-background-clip: padding-box;
        -moz-background-clip: padding-box;
        background-clip: padding-box;
        outline: none;
    }
</style>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"></h3>
    </div>
    <div class="modal-body">
        
        <div class="row-fluid">

            <div class="span12">
                <div id="txCuerpo"></div>
            </div>
        
        </div>
        
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo lang("general_accion_cancelar");  ?></button>
    </div>
    
</div>