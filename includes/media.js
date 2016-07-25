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
		var image_id = $(this).data("image-id");

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
			$("#rhd-ag-image-" + image_id).val(media_attachment.url);
		});

		// Opens the media library frame.
		meta_image_frame.open();
	});


	$(".add-ag-image").on("click", function(){
		var count = $(".rhd-ag-image-select").length;
			var field = $(this).siblings(".rhd-ag-image-select:last-of-type").clone(true);
		var fieldLocation = $(".rhd-ag-image-select:last-of-type");

		$(field).find(".rhd-ag-image, .rhd-ag-link, .rhd-ag-image-button").each(function(){
			if ( ! $(this).is(":button") ) {
				$(this).val("");
			}

			$(this).attr({
				"name" : $(this).attr("name").replace(/(\d+)/, function(x) {
					return parseInt(x) + 1;
				}),
				"id" : $(this).attr("id").replace(/(\d+)/, function(x) {
					return parseInt(x) + 1;
				})
			});
		});

		$(field).find("label").each(function(){
			$(this).attr("for", $(this).attr("for").replace(/(\d+)/, function(x) {
				return parseInt(x) + 1;
			}));
		});

		$("#rhd-ag-image-count").val(count + 1);
		$(field).find(".rhd-ag-image-button").data("image-id", count + 1);

		field.insertAfter(fieldLocation);

		return false;
	});
});