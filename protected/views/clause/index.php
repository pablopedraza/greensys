<script>
$(document).ready (function (){
new nicEditor({buttonList : ['title','bold','italic','underline','strikeThrough','ol','ul','indent','outdent','h1']}).panelInstance('clause_default_description');
});
</script>

<div class="container" id="screenClausulas">
  <div class="row">
    <div class="col-sm-12">
  <h1 class="pageTitle">Cl&aacute;usulas de Contrato</h1>
  </div>
  </div>
  <div class="row">
    <div class="col-sm-12">

    <textarea class="form-control" rows="20" id="clause_default_description"></textarea>
    
    </div>    
    <!-- /.col-sm-12 --> 
  </div>
  <!-- /.row --> 
 <div class="buttonGroup">
        <button type="button" class="btn btn-default" id="cancel"> Reset</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
 </div>
    <!-- /.col-sm-12 --> 
  </div>
  
  </div>
  <!-- /.container --> 
  