window.addEventListener("load", function() {

	// store tabs variables
	var tabs = document.querySelectorAll("ul.nav-tabs > li");
	
	// Add the OnClick Event Listener to the tabs
	for (var i = 0; i < tabs.length; i++) {
		tabs[i].addEventListener("click", switchTab);
	}

	function switchTab(event) {
		event.preventDefault();

		document.querySelector("ul.nav-tabs li.active").classList.remove("active");
		document.querySelector(".tab-pane.active").classList.remove("active");

		var clickedTab = event.currentTarget;
		var anchor = event.target;
		var activePaneID = anchor.getAttribute("href");

		clickedTab.classList.add("active");
		document.querySelector(activePaneID).classList.add("active");

	}

	// handle activation and deactivation of plugin post type toggles
	var gp_cpts = document.querySelectorAll(".ui-toggle.gigify-custom-post-type input[type=checkbox]");
	for(var i = 0; i < gp_cpts.length; i++) {
		gp_cpts[i].addEventListener("change", function(e) {
			el = e.currentTarget;
			
			jQuery.ajax({
				type: 'POST',
				url: '/wp-admin/admin-ajax.php',
				data: {
					action : 'manage_cpt_visibility',
					cpt_name : el.id,
					cpt_status: (el.checked ? 1 : 0),
					nonce: el.getAttribute("data-nonce")
				},
				success: function(response) {
					info = JSON.parse( response );
					console.log( info );
					if( info.type == 'success' ) {
						var msgDiv = jQuery("DIV#cpt-messages");
						msgDiv.addClass('notice notice-success').html( info.message );
					} else{
						console.log('An Error Occurred: ' + response );
					}
				 }
			});

		});
	}
	// console.log(gp_cpts);

	// Find the submenu items for the plugin menu
	var gp_menu_items = document.querySelectorAll("li.toplevel_page_gigify_plugin ul li a");
	console.log(gp_menu_items);

});