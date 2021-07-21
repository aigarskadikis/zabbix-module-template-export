(function () {
	let templateid = $('[name=templateid]');
	let form = templateid.closest('form');

	$($('#export-button').text()).appendTo('.tfoot-buttons');
	$('[name="export-5-0"]').click((e) => {
		let action = form.attr('action');
		let input = $('<input/>', {
			type: 'hidden',
			name: `templates[${templateid.val()}]`,
			value: templateid.val()
		});

		input.appendTo(form);
		form.attr('action', 'zabbix.php?action=export.templates.xml&backurl=templates.php');
		form.submit();
		setTimeout(() => {
			input.remove();
			form.attr('action', action);
		}, 0);
	});
})();
