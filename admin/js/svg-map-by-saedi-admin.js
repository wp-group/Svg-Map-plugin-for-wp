(function ($) {
	'use strict';

	$(document).ready(function () {

		/* -----------------------------
		 * Helpers
		 * ----------------------------- */
		function showMsg(msg, type) {
			$('#msg')
				.removeClass()
				.addClass('notice notice-' + type)
				.text(msg)
				.show();
		}

		function reloadSoon(delay = 800) {
			setTimeout(function () {
				location.reload();
			}, delay);
		}

		function updateSelectedCount() {
			var count = $(".map .st0.selected").length;
			$('#msg').text(count + ' point(s) selected').show();
		}

		/* -----------------------------
		 * Map Click Handling
		 * ----------------------------- */
		$(".map").children('.st0').on("click", function () {
			$(this).toggleClass('selected');
			updateSelectedCount();
		});

		/* -----------------------------
		 * Restore selected points
		 * ----------------------------- */
		var ul = $("svg.map");
		var items = ul.find("circle");

		if (typeof js_map_points !== 'undefined') {
			for (var i = 0; i < js_map_points.length; i++) {
				items.eq(js_map_points[i]).addClass("selected");
			}
			updateSelectedCount();
		}

		/* -----------------------------
		 * Save Selected Points
		 * ----------------------------- */
		$('#save_data').on('click', function () {
			var $btn = $(this);
			var points = [];

			$(".map").children('.st0.selected').each(function () {
				points.push($(this).index());
			});

			if (points.length === 0) {
				showMsg('No points selected', 'warning');
				return;
			}

			$btn
				.addClass('is-busy')
				.prop('disabled', true)
				.find('.label')
				.text('Saving…');

			$.post(ajaxurl, {
				action: 'save_data',
				points: points,
				nonce: svgMapSecurity.nonce
			}, function (response) {
				showMsg(response, 'success');
				reloadSoon();
			}).fail(function () {
				showMsg('Save failed', 'error');
				reloadSoon(1500);
			});
		});

		/* -----------------------------
		 * Add / Update Popup
		 * ----------------------------- */
		$('.add_popup').on('click', function (e) {
			e.preventDefault();

			var point = $(this).data('point');
			var popup = $('#popup-' + point ).val();

			$.post(ajaxurl, {
				action: 'add_popup',
				point: point,
				popup: popup,
				nonce: svgMapSecurity.nonce
			}, function () {
				showMsg('Popup saved', 'success');
				reloadSoon();
			});
		});

		/* -----------------------------
		 * Delete Point
		 * ----------------------------- */
		$('.delete_point').on('click', function (e) {
			e.preventDefault();

			if (!confirm('Remove this point?')) return;

			var point = $(this).data('point');

			$.post(ajaxurl, {
				action: 'delete_data',
				point: point,
				nonce: svgMapSecurity.nonce
			}, function () {
				showMsg('Point removed', 'success');
				reloadSoon();
			});
		});
	});
})(jQuery);
