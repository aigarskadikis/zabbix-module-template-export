(function () {
	let templateid = $('[name=templateid]');
	let form = templateid.closest('form');

	if (!form.length) {
		return;
	}

	let button = $($('#export-button').text()).appendTo('.tfoot-buttons');

	button.find('button').css({margin: '0 -1px 0 0'});
	form.submit(e => {
		if (!form.data('action')) {
			return;
		}

		let form_clone = form.clone();

		form.attr('action', form.data('action'));
		form.data('action', null);

		$('<input/>', {
			type: 'hidden',
			name: `templates[${templateid.val()}]`,
			value: templateid.val()
		}).appendTo(form_clone);
		form_clone.hide().appendTo('body').submit();
		setTimeout(() => form_clone.remove(), 0);

		e.preventDefault();
		return false;
	})
})();
