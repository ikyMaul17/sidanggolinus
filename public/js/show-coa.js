function showModal(ref) {
    $('#table-modal > tbody > tr').remove();
	
	$.ajax({
		//(var url) parsing on-blade when call show-coa.js
		url : url+"/"+ref,
		method : "GET",
		success : function(data){
			// alert('test');
			// console.log(data);
			$('#tbody-show-coa').append(data);
		}
	});

	$('#showCoa').modal('show');
}