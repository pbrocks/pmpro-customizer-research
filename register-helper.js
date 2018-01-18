wp.customize.bind( 'ready', function() {
	var setting, control, customizeId = 'register_helper[9876543210][status]';
	// var panel = 'pmpro_panel';
	setting = new wp.customize.settingConstructor.register_helper( customizeId, 'publish', {
		previewer: wp.customize.previewer,
		transport: 'postMessage'
	} );
	wp.customize.add( setting.id, setting );
 
	control = new wp.customize.controlConstructor.register_helper( customizeId, {
		params: {
			section: 'dynamic_register_helper_items',
			type: 'register_helper', // Needed to for template.
			label: 'Status',
			active: true,
			settings: { 'default': setting.id },
			content: '<li></li>' // Shouldn't be required for long.
		}
	} );
	wp.customize.control.add( control.id, control );
} );

wp.customize.bind( 'ready', function() {
	var section = new wp.customize.Section( 'dynamic_register_helper_items', {
		params: {
			title: 'Register Helper (Dynamic)',
			type: 'register_helper_items', // For CSS class name.
			priority: 100,
			active: true,
			customizeAction: 'You are customizing:'
		}
	} );
	wp.customize.section.add( section.id, section ).panel('pmpro_panel1');
	// https://code.tutsplus.com/tutorials/customizer-javascript-apis-the-previewer--cms-27313
	// Change a Section priority
	wp.customize.section( 'memberlite_theme_options' ).panel('pmpro_panel1').priority( 1 );
} );

