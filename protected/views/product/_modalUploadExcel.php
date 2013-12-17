<!--MODAL EXCEL-->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Cargar Nuevo Excel</h4>
      </div>
      <div class="modal-body">
    
<form id="form-upload-excel" role="form" action="<?php echo ProductController::createUrl("AjaxUploadProductExcel"); ?>">
  <div class="form-group">
    <label for="campoArchivo">Archivo</label>
    <?php				
		echo CHtml::activeFileField($modelExcel, 'file'); 
	?>
  </div>
  <div class="form-group">
    <label for="campoMarca">Marca</label>
    <?php				
		echo CHtml::activeDropDownList($modelProductImportLog, 'Id_brand', 
		CHtml::listData($ddlBrand, 'Id', 'description')); 
	?>
  </div>
  <div class="form-group">
    <label for="campoPeso">Unidad de Peso</label>
    <?php
    	echo CHtml::activeDropDownList($modelProductImportLog, 'Id_measurement_unit_weight', 
		CHtml::listData($ddlMeasurementUnitWeight, 'Id', 'short_description'));
	?>
  </div>
  <div class="form-group">
    <label for="campoLineal">Unidad Lineal</label>
    <?php				
		echo CHtml::activeDropDownList($modelProductImportLog, 'Id_measurement_unit_linear', 
		CHtml::listData($ddlMeasurementUnitLinear, 'Id', 'short_description')); 
	?>
  </div>
</form>

<!--Esto aparece una vez que le das Cargar-->
<div class="estadoModal">
    <label for="campoLineal">Estado</label>
<div id="status-wait" style="display:none" class="alert alert-info"><i class="fa fa-spinner fa-spin"></i>
 <strong>Analizando archivo</strong>, espere por favor.</div>
 
 <div id="status-error" style="display:none" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i>
 <strong>Se ha producido un error</strong>, revise el archivo e int�ntelo nuevamente.</div>
 
 <div id="status-success" style="display:none" class="alert alert-success"><i class="fa fa-check"></i>
 <strong>La carga fue correcta.</strong></div>
 
 </div>
<!--Fin notificacion-->


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="uploadFile();" class="btn btn-primary btn-lg"><i class="fa fa-upload"></i> Cargar</button>
      </div>
    </div><!-- /.modal-content -->
<script type="text/javascript">
		$("#form-upload-excel").submit(function(e)
		{
		    var formObj = $(this);
		    var formURL = formObj.attr("action");
		    var formData = new FormData(this);
		    $.ajax({
		        url: formURL,
		    type: 'POST',
		        data:  formData,
		    mimeType:"multipart/form-data",
		    contentType: false,
		        cache: false,
		        processData:false,
		    success: function(data, textStatus, jqXHR)
		    {
		    	$('#status-wait').hide();
		    	$('#status-success').show();
		    	$('#tabPorMarca').html(data);
		    },
		     error: function(jqXHR, textStatus, errorThrown)
		     {
		    	$('#status-wait').hide();
			    $('#status-error').show();
		     }         
		    });
		    e.preventDefault(); //Prevent Default action.
		});
	
	function uploadFile()
	{		
		$('#status-wait').show();
		$('#form-upload-excel').submit();
	}
</script>
</div><!-- /.modal-dialog -->
<!--END MODAL EXCEL-->
