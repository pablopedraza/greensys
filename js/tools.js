function unselectRow(id)
{
	$("#"+id+" > table > tbody > tr").each(function(i)
		{
			if($(this).hasClass("selected"))
			{
				$(this).removeClass("selected");
			}
		}
	);
}

function selectSpecificRow(idGrid, idRow)
{
	var index = 0;
	if(idRow != "")
	{
		$("#"+idGrid).find('.keys span').each(function(i)
		{
			if(  "" + idRow + "" == $(this).text())
				return false;
			index++;
		});
		
		$("#"+idGrid+" > table > tbody > tr").each(function(i)
		{
			if(i == index)
			{
				$(this).addClass('selected');
			}
	    });
	}
}

function fillSidebar(idGrid, sidebarTitle)
{
		var idProduct = $.fn.yiiGridView.getSelection(idGrid);
		$("#"+idGrid+" > table > tbody > tr").each(function(i)
        {
                if($(this).hasClass("selected"))
                {
                    var row = $.fn.yiiGridView.getRow(idGrid,i.toString());
					$("#sidebarTitle").html(sidebarTitle);
					$("#sidebarText").html("<li>"+$(row[0]).html()+"</li>"
							+"<li>"+$(row[1]).html()+"</li>"
							+"<li>"+$(row[2]).html()+"</li>"
							+"<li>"+$(row[3]).html()+"</li>"
							+"<li>"+$(row[4]).html()+"</li>"
							+"<li>"+$(row[5]).html()+"</li>"
							);	
					
                }
        });
}	

function markAddedRow(id)
{
        $("#"+id+" > table > tbody > tr").each(function(i)
        {
                if($(this).hasClass("selected"))
                {
					$.fn.yiiGridView.getRow(id,i.toString()).find("#addok").animate({opacity: "show"},4000);
					$.fn.yiiGridView.getRow(id,i.toString()).find("#addok").animate({opacity: "hide"},4000);
                }
        });
}	
function validateNumber(obj)
{
	var value=$(obj).val();
	if(value=="")
	{
    	$(obj).val("0.00");
	}
    var orignalValue=value;
    value=value.replace(/[0-9]*/g, "");			
   	var msg="Only Decimal Values allowed."; 						
   	value=value.replace(/\./, "");
    if (value!=""){
    	orignalValue=orignalValue.replace(value, "");
    	$(obj).val(orignalValue);
    	//alert(msg);
    }
}
function validateNumberInteger(obj)
{
	var value=$(obj).val();
	if(value=="")
	{
    	$(obj).val("0");
	}
	
    var orignalValue=value;
    value=value.replace(/[0-9]*/g, "");			
    if (value!=""){
    	orignalValue=orignalValue.replace(value, "");
    	$(obj).val(orignalValue);
    	//alert(msg);
    }
}

function fillVolumeTextBox(url,textBoxId,formId)
{
	$.post(url,
		$('#'+formId).serialize()
	).success(
		function(data) 
		{
			$('#'+textBoxId).val(data);
		}
	);
}

function fillWieghtTextBox(url,textBoxId,formId)
{
	$.post(url,
			$('#'+formId).serialize()
	).success(
		function(data) 
		{
			$('#'+textBoxId).val(data);
		}
	);
}
function preventSubmit(element)
{
	jQuery(element).keydown(function(event) {
        if (event.keyCode == 13) {
            return false;
         }
    });
}
