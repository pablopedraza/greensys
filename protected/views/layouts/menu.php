

<nav class="navbar navbar-default navbar-fixed-top" role="navigation"  id="Menu">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <a class="navbar-brand" href="#" id="MenuLogo">Green</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex5-collapse">
          <ul class="nav navbar-nav">
          <li <?php if ($active=="inicio"){ echo 'class="active"';}?> ><a href="<?php echo Yii::app()->createUrl("site/indexGreen")?>"><i class="fa fa-home fa-fw"></i> Dashboard</a></li>
          <?php if(Yii::app()->user->checkAccess('ProductManage')):?>			          
          	<li <?php if ($active=="productos"){ echo 'class="active"';}?> ><a href="<?php echo Yii::app()->createUrl("product/index")?>"><i class="fa fa-star fa-fw"></i> Productos</a></li>
          <?php endif?>			                    	
          <?php if(Yii::app()->user->checkAccess('BudgetManage')):?>			          
          <li <?php if ($active=="presupuestos"){ echo 'class="active"';}?> ><a href="<?php echo Yii::app()->createUrl("budget/index")?>"><i class="fa fa-dollar fa-fw"></i> Presupuestos</a></li>
          <?php endif?>						                    	
          <?php if(Yii::app()->user->checkAccess('SupplierManage')||Yii::app()->user->checkAccess('SupplierMagane')):?>			          
          <li <?php if ($active=="proveedores"){ echo 'class="active"';}?> ><a href="<?php echo Yii::app()->createUrl("supplier/index")?>"><i class="fa fa-truck fa-fw"></i> Proveedores</a></li>
          <?php endif?>			                    	
          <?php if(Yii::app()->user->checkAccess('PriceListManage')):?>			          
          <li <?php if ($active=="precios"){ echo 'class="active"';}?> ><a href="<?php echo Yii::app()->createUrl("priceList/index")?>"><i class="fa fa-book fa-fw"></i> Listas de Precios</a></li>
          <?php endif?>			                    	
          <?php if(Yii::app()->user->checkAccess('CustomerManage')):?>			          
          <li <?php if ($active=="clientes"){ echo 'class="active"';}?> ><a href="<?php echo Yii::app()->createUrl("customer/index")?>"><i class="fa fa-glass fa-fw"></i> Clientes</a></li>
          <?php endif?>			                    	
          <?php if(Yii::app()->user->checkAccess('ProjectManage')):?>			          
          <li <?php if ($active=="proyectos"){ echo 'class="active"';}?> ><a href="<?php echo Yii::app()->createUrl("project/index")?>"><i class="fa fa-building fa-fw"></i> Proyectos</a></li>
          <?php endif?>                    	
          <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">M&aacute;s <i class="fa fa-caret-down fa-fw"></i></a>
                        <ul class="dropdown-menu">
				          <?php if(Yii::app()->user->checkAccess('BudgetManage')):?>			          
                	      <li><a href="#">Permisos</a></li>
				          <?php endif?>			                    	
                	      <?php if(Yii::app()->user->checkAccess('UserManage')):?>			          
                	      <li><a href="#">Usuarios</a></li>
				          <?php endif?>			                    	
                	      <?php if(Yii::app()->user->checkAccess('AreaManage')):?>			          
                	      <li><a href="<?php echo Yii::app()->createUrl("area/index")?>">Areas</a></li>
				          <?php endif?>			                    	
                	      <?php if(Yii::app()->user->checkAccess('ImporterManage')):?>			          
                	      <li><a href="<?php echo Yii::app()->createUrl("importer/index")?>">Importadores</a></li>
				          <?php endif?>			                    	
                	      <?php if(Yii::app()->user->checkAccess('ProductRequirementManage')):?>			          
                	      <li><a href="<?php echo Yii::app()->createUrl("productRequirement/index")?>">Requerimientos</a></li>
				          <?php endif?>			                    	
                	      <?php if(Yii::app()->user->checkAccess('ServiceManage')):?>			          
                	      <li><a href="<?php echo Yii::app()->createUrl("service/index")?>">Servicios</a></li>
				          <?php endif?>			                    	
                	      </ul>
          </li>
          </ul>

        </div><!-- /.navbar-collapse -->
      </nav>