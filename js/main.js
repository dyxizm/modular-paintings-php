
var smplDir = 'samples/';
var w_img = $('#preview img').width();
$(function(){
	$("#selectFile").change(function(){
		$("#preview img").attr('src', smplDir+$("#selectFile").val());
	});

	$('#add').click(function(){
		var moduleLength = $('.module').length;
		if($('.module').length<6){
			
			var module = _.template( 
				$("#tpl_module").html() 
			)({
				mlength: moduleLength
			});
			var block = $("#tpl_block").html();
			
			$('#modules').append(module);
			$('#blocks').append(block);				
			update();
			checkStart();
	
			$('.blockHeight').draggable({
				axis: "y",
				containment: "parent",
				drag: function( event, ui ){
					var h_img = $('#preview img').height();
					var parent = $(this).parent();
					var eq = $(this).parents('.block').index()-1;
					var y = ui.position.top;
					var h = $(this).height();
					var marginYtop = Math.round((y/h_img)*100)
					parent.children('.marginYtop').height(y);
					parent.children('.marginYbottom').height(h_img-y-h);
					$('.module').eq(eq).children('.y').val(marginYtop);			
				}
			});
	
		}
		return false;
	});
	
	$('#del').click(function(){
		$('#modules').html('');
		$('#blocks').html('').append('<div id="out" class="back"></div>');
		checkStart()
		return false;
	});	
	
	$("#modules").on('change', "input", function() {
		update();
	});

	$("#styleConfig").submit(function(event) {
		event.preventDefault();
		$('#start').text('Making...');
		$('#start').prop('disabled', true);	
		$.ajax({
			type: "POST",
			url: $(this).attr('action'),
			data: $(this).serialize(), // serializes the form's elements.
			success: function(data)
			{	
				// disabled start button
				$('#start').text('Start');
				$('#start').prop('disabled', false);
			}
		});
	});
	
});

function update() {
	var h_img = $('#preview img').height();
	var w_bs = 0;
	//console.log(h_img);
	$('.module').each(function(i,m){
		var w = Math.round(parseFloat($(m).children('.w').val())*w_img*0.01);
		var h = Math.round(parseFloat($(m).children('.h').val())*h_img*0.01);
		var x = Math.round(parseFloat($(m).children('.x').val())*w_img*0.01);
		var y = Math.round(parseFloat($(m).children('.y').val())*h_img*0.01);
		$('.block').eq(i).width(w+x).height(h_img);			
		$('.block').eq(i).children('.marginX').width(x).height(h_img);
		$('.block').eq(i).children('.blockWidth').width(w);
		$('.block').eq(i).find('.blockHeight').height(h).width(w).css('top',y);
		$('.block').eq(i).find('.marginYtop').height(y).width(w);
		$('.block').eq(i).find('.marginYbottom').height(h_img-y-h).width(w).css('top',h);
		w_bs +=x+w;		
	});
	$('#out').width(w_img-w_bs).height(h_img);
}

function checkStart(){
	if(($('.module').length!=0) && ($('#preview img').length!=0)){
		$('#start').prop('disabled', false);
	}else{
		$('#start').prop('disabled', true);	
	}
}
