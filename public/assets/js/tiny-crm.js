// datetime picker
$(document).ready(function(){
	jQuery('#datetimepicker').datetimepicker({
		// formatTime:'H:i',
		// minTime:'08:00',
		// // maxTime:'19:00',
		step: 15,
        // minTime:'8:00',
        // maxTime:'19:00',
        // timeFormat: 'HH:mm',
        // hourMin: 8,
	});
});



// update database for export
function updateExport(databaseId) {
	var exportDatabaseElement = $("#export-database-" + databaseId)
	var updateExportUrl = exportDatabaseElement.data('update-export-url');
	exportDatabaseElement.html('<i class="fa fa-refresh fa-spin"></i>');
	console.log(updateExportUrl);
	$.ajax({
        url: updateExportUrl,
        type: 'POST',
        success:function(data, textStatus, jqXHR) {
        	console.log(textStatus);
        	console.log(data);
        	// TODO smthg
        },
        error: function(jqXHR, textStatus, errorThrown) {
        	console.log(textStatus);
        	// TODO smthg
        }
    });
}

var TinyCRM = function() {
	// polling for databases table
	function handleDatabasesTable() {
		updateExportStatus();
		setTimeout(
	        handleDatabasesTable, /* Request next message */
	        1000 /* ..after 1 seconds */
	    );
	}
	
	// update state of updating export
	function updateExportStatus() {
	    $('#contacts-table > tbody  > tr').each(function() {
	        var databaseId = $(this).attr('id');
	        var exportDatabaseElement = $('#export-database-' + databaseId);
	        var updateStateExportUrl = exportDatabaseElement.data('update-state-export-url');
	        var downloadCsvUrl = exportDatabaseElement.data('download-csv-url');
	        var i = 0;	        
	        if(exportDatabaseElement.html().indexOf('<i class="fa fa-refresh fa-spin"></i>') >= 0) {
	            $.ajax({
	                // url: '/account/contacts/update-state-export-status/' + mailingListId,
	                url: updateStateExportUrl,
	                type: "POST",
	                success:function(data, textStatus, jqXHR) {
	                	console.log(data);
	                    if(data.state === 2) {
	                        exportDatabaseElement.html('<a href="' + downloadCsvUrl + '">Download</a>');
	                    }
	                },
	                error: function(jqXHR, textStatus, errorThrown) {
	                    console.log(textStatus);    
	                }
	            });
	        }
	    });
	}

	return {
		initDatabasesTable: function() {
			handleDatabasesTable();
		},
	}
}();