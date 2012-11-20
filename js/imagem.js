$(document).ready(function () {
	$('#img-principal').imgAreaSelect({
		aspectRatio: '1:'+(h/w),
		handles: true,
		onSelectChange: function (img, selection) { 
			var scaleX = w / selection.width; 
			var scaleY = h / selection.height; 

			$('#img-thumbnail').css({ 
				width: Math.round(scaleX * _width) + 'px', 
				height: Math.round(scaleY * _height) + 'px',
				marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
				marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
			});
			$('#x1').val((selection.x1*_proporcao));
			$('#y1').val((selection.y1*_proporcao));
			$('#x2').val((selection.x2*_proporcao));
			$('#y2').val((selection.y2*_proporcao));
			//$('#w').val(selection.width);
			//$('#h').val(selection.height);
		}
	}); 
}); 