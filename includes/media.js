/*
* Attaches the image uploader to the input field
*/
jQuery(document).ready(function($){
	// Instantiates the variable that holds the media library frame.
	var meta_image_frame;

	// Runs when the image button is clicked.
	$(".rhd-ag-image-button").click(function(e){

		// Prevents the default action from occuring.
		e.preventDefault();

		// Sets image #
		var image_id = parseInt( $(this).parents(".rhd-ag-image-select").data("index") );

		// Sets up the media library frame
		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
			title: meta_image.title,
			button: { text:  meta_image.button },
			library: { type: "image" }
		});

		// Runs when an image is selected.
		meta_image_frame.on("select", function(){

			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = meta_image_frame.state().get("selection").first().toJSON();

			// Sends the attachment URL to our custom image input field.
			$("#rhd-ag-thumb-" + image_id + " img").remove();
			$("#rhd-ag-thumb-" + image_id).append('<img src="' + media_attachment.sizes.thumbnail.url + '" />');
			$("#rhd-ag-id-" + image_id).val(media_attachment.id);
		});

		// Opens the media library frame.
		meta_image_frame.open();
	});


	$(".add-ag-image").on("click", function(){
		var count = $(".rhd-ag-image-select").length;
		var field = $("#rhd-ag-select-images table .rhd-ag-image-select:last-of-type").clone(true);
		var fieldLocation = $(".rhd-ag-image-select:last-of-type");

		$(field).find(".rhd-ag-thumb img").remove();
		$(field).find("input").each(function(){
			if ( ! $(this).is(":button") ) {
				$(this).val("");
			}
		});

		field.insertAfter(fieldLocation);

		updateGalleryIndices();

		$("#rhd-ag-image-count").val(count + 1);

		return false;
	});

	$(".remove-ag-image").on("click", function(){
		$(this).parents(".rhd-ag-image-select").fadeOut('fast', function(){
			var count = $("#rhd-ag-image-count").val();
			$("#rhd-ag-image-count").val(count - 1);

			$(this).find(".rhd-ag-link").val("");
			$(this).find(".rhd-ag-id").val("");
			$(this).find(".rhd-ag-caption").val("");

			$(this).remove();
			updateGalleryIndices();
		});
	});

	$(".sortable").sortable({
		opacity: 0.6,
		cursor: 'move',
		revert: true,
		placeholder: "drag-line",
		update: updateGalleryIndices
	});


	function updateGalleryIndices() {
		$(".rhd-ag-image-select").each(function(i){
			var $this = $(this);
			var newID = i;

			$this.data("index", newID);
			$this.attr("data-index", newID);
			$this.find(".rhd-ag-row-id p").text(newID + 1);
			$this.find(".rhd-ag-image-button").data("image-id", newID);

			$this.find("[class*=rhd-ag-]").each(function(){
				var attrID = $(this).attr('id');
				var attrClass = $(this).attr('class');
				var attrName = $(this).attr('name');

				if (typeof attrID !== typeof undefined && attrID !== false)
					$(this).attr("id", $(this).attr("id").replace(/(\d+)/, newID));

				if (typeof attrName !== typeof undefined && attrName !== false)
					$(this).attr("name", $(this).attr("name").replace(/(\d+)/, newID));

				if (typeof attrClass !== typeof undefined && attrClass !== false)
					$(this).attr("class", $(this).attr("class").replace(/(\d+)/, newID));
			});

			$this.find("label").each(function(){
				$(this).attr("for", $(this).attr("for").replace(/(\d+)/, newID));
			});

			$this.find(".rhd-ag-image-button").data("image-id", newID);
		});
	}

});