// Jquery function for order fields
// When the page is loaded define the current order and items to reorder
$(document).ready( function(){
	/* Call the container items to reorder tables */
	$('.table-list').sortable({ 
			accept: 'tables',
			opacity: 0.6, 
			handle : '.move',	
			cursor: 'move',
			connectWith: '.table-list',
			update: function(event, ui) {
				var list = $(this).sortable( 'serialize');
				$.post( 'tables.php?op=order', list );
			},
			receive: function(event, ui) {
				var list = $(this).sortable( 'serialize');                    
				$.post( 'tables.php?op=order', list );                      
			}
		}
	).disableSelection();	
	/* Call the container items to reorder fields */
	$('.field-list').sortable({
			accept: 'fields',
			opacity: 0.6, 
			handle : '.move',	
			cursor: 'move',
			connectWith: '.field-list',
			update: function(event, ui) {
				var list = $(this).sortable( 'serialize');
				$.post( 'fields.php?op=order', list );
			},
			receive: function(event, ui) {
				var list = $(this).sortable( 'serialize');                    
				$.post( 'fields.php?op=order', list );                      
			}
		}
	).disableSelection();
});