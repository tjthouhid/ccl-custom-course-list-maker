jQuery(function($){
	$("body").on("change",".select_country",function(){
		var cntry_id=$(this).val();
		if(cntry_id!=""){
			get_data_by_country_id(cntry_id);
		}
	});

	$("body").on("click",".know-btn",function(e){
		e.preventDefault();
		$(this).closest('.result-item').find('.show-more-div').slideDown('slow');
	});
	$("body").on("click",".hide-more",function(e){
		e.preventDefault();
		$(this).closest('.show-more-div').slideUp('slow');
	});
	$("body").on("click",".submit-btn",function(e){
		e.preventDefault();
		var versity_id=$(".select_veristy").val();
		var level_id=$(".select_level").val();
		get_data_by_search(versity_id,level_id)
	});
});
function get_data_by_country_id(cntry_id){

	 //    var data = {
		// 	'action': 'get_ccl_versity',
		// 	cntry_id: cntry_id      // We pass country id here!
		// };
		// $.post(ajax_object.ajax_url, data, function(response) {
		// 	if(response==true){
		// 		dis.closest("tr").remove();
		// 		$(".notifier-widget .success-result").fadeIn('slow').fadeOut('slow');
		// 	}else{
		// 		alert("Something Wrong! Try again.")
		// 		return false;
		// 	}
		// });
	$.ajax({
		url: ajax_object.ajax_url,
		type: 'post',
		dataType: 'json',
		data: {
		   'action': 'get_ccl_versity',
			'cntry_id': cntry_id
		},
		beforeSend:function(){
			$(".overlay").fadeIn();
		}
	})
	.done(function(data) {
		var select="<option value=''>Select Versity</option>";
		if(data.type==="fail"){
			alert("No Data Found!");
			select+="";
		}else{
			var datas=data.data;
			var total_data=datas.length;
			for(i=0;i<total_data;i++){
				select+="<option value='"+datas[i].versityId+"'>"+datas[i].versityTilte+"</option>";
				
			}
		}
		
		
		$(".select_veristy").html(select);
		$(".overlay").fadeOut();
		//console.log(data);

	})
	.fail(function() {
		console.log("error");
	});
	
}
function get_data_by_search(versity_id,level_id){
	$.ajax({
		url: ajax_object.ajax_url,
		type: 'post',
		dataType: 'json',
		data: {
			"action" : "get_ccl_course_data",
			versity_id: versity_id,
			level_id: level_id
		},
		beforeSend:function(){
			$(".overlay").fadeIn();
		}
	})
	.done(function(data) {
		var str="";
		if(data.type==="fail"){
			str+="No Data Found";
		}else{
			var datas=data.data;
			var total_data=datas.length;
			for(i=0;i<total_data;i++){
		 		ieltsRequirements=datas[i].ieltsRequirements;
		 		ieltsRequirements =  jQuery.parseJSON(ieltsRequirements);
		 		academicRequirements=datas[i].academicRequirements;
				str+='<div class="result-item">'+
				     '<h2 class="item-title">'+datas[i].courseName+' - '+datas[i].versityName+'</h2>'+
				      '<p class="item-dept">Department: '+datas[i].courseFaculty+'</p>'+
				      '<div class="row">'+
				            '<div class="col-6">'+
				                '<p class="item-level">Level: '+datas[i].levelTitle+'</p>'+
				                '<p class="item-duration">Duration: '+datas[i].courseDuration+'</p>'+
				                '<p class="item-duration">Awarded By: '+datas[i].courseAwardingBody+'</p>'+
				                '<a href="'+datas[i].courseUrl+'" class="item-link" target="_blank">Course Link</a>'+
				            '</div>'+
				            '<div class="col-6 margin-top-20">'+
				                '<div class="btn-div"><a href="#" class="know-btn">Know More</a></div>'+
				                '<div class="btn-div"><a href="#" class="apply-btn" target="_blank">Apply Now</a></div>'+
				            '</div>'+
				      '</div>'+
				      '<div class="show-more-div">'+
				            '<h3>Course Fee</h3>'+
				            '<p>'+datas[i].currencyName+' '+datas[i].tutionFee+' per year ('+datas[i].payable+')</p>'+
				            '<h3>IELTS Requirements</h3>'+
				            '<ul>'+
				              '<li>Overall: '+ieltsRequirements.Overall+'</li>'+
				              '<li>Listening: '+ieltsRequirements.Listening+'</li>'+
				              '<li>Reading: '+ieltsRequirements.Reading+'</li>'+
				              '<li>Writing: '+ieltsRequirements.Writing+'</li>'+
				              '<li>Speaking: '+ieltsRequirements.Speaking+'</li>'+
				            '</ul>'+
				            '<h3>Academic Requirements</h3>'+
				            '<ul>'+
				              '<li>Standard: '+academicRequirements.Standard+'</li>'+
				              '<li>Percentage: '+academicRequirements.Percentage+'</li>'+
				              '<li>WorkExperience: '+academicRequirements.WorkExperience+'</li>'+
				            '</ul>'+
				            '<a href="#" class="hide-more">Hide</a>'+
				      '</div>'+
				'</div>';
			}
		}
		
		$(".result-data .row .col-12").html(str);
		$(".overlay").fadeOut();

	})
	.fail(function() {
		console.log("error");
	});
	
}
