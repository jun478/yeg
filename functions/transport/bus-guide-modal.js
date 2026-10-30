(function () {
	'use strict';

	function initializeModal(dialog) {
		if (dialog.dataset.yegBusModalInitialized === 'true') {
			return;
		}

		var guide = dialog.closest('.yeg-bus-guide');
		var trigger = guide ? guide.querySelector('[data-yeg-bus-modal-open][aria-controls="' + dialog.id + '"]') : null;
		var closeButton = dialog.querySelector('[data-yeg-bus-modal-close]');
		var imageFrame = dialog.querySelector('.yeg-bus-guide-modal__image-frame');
		var lastFocusedElement = null;
		var fallbackInertRecords = [];

		if (!trigger || !closeButton || !imageFrame) {
			return;
		}

		dialog.dataset.yegBusModalInitialized = 'true';

		function restoreFocus() {
			if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
				lastFocusedElement.focus();
			}
			lastFocusedElement = null;
		}

		function makeOutsideContentInert() {
			var currentElement = dialog;

			fallbackInertRecords = [];

			while (currentElement.parentElement) {
				var parentElement = currentElement.parentElement;

				Array.prototype.forEach.call(parentElement.children, function (sibling) {
					if (sibling === currentElement) {
						return;
					}

					fallbackInertRecords.push({
						element: sibling,
						hadAttribute: sibling.hasAttribute('inert'),
						attributeValue: sibling.getAttribute('inert')
					});
					sibling.setAttribute('inert', '');
				});

				currentElement = parentElement;

				if (parentElement === document.body) {
					break;
				}
			}
		}

		function restoreOutsideContent() {
			fallbackInertRecords.forEach(function (record) {
				if (record.hadAttribute) {
					record.element.setAttribute('inert', record.attributeValue === null ? '' : record.attributeValue);
				} else {
					record.element.removeAttribute('inert');
				}
			});
			fallbackInertRecords = [];
		}

		function getFocusableElements() {
			var selector = 'a[href], area[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

			return Array.prototype.filter.call(dialog.querySelectorAll(selector), function (element) {
				return !element.hasAttribute('inert') && element.getClientRects().length > 0;
			});
		}

		function openModal() {
			lastFocusedElement = document.activeElement;

			if (typeof dialog.showModal === 'function') {
				dialog.showModal();
			} else {
				makeOutsideContentInert();
				dialog.setAttribute('open', '');
				dialog.classList.add('is-fallback-open');
				closeButton.focus();
			}
		}

		function closeModal() {
			if (dialog.classList.contains('is-fallback-open')) {
				dialog.removeAttribute('open');
				dialog.classList.remove('is-fallback-open');
				restoreOutsideContent();
				restoreFocus();
				return;
			}

			if (typeof dialog.close === 'function' && dialog.open) {
				dialog.close();
				return;
			}

			dialog.removeAttribute('open');
			dialog.classList.remove('is-fallback-open');
			restoreOutsideContent();
			restoreFocus();
		}

		trigger.addEventListener('click', openModal);
		closeButton.addEventListener('click', closeModal);
		dialog.addEventListener('close', function () {
			dialog.classList.remove('is-fallback-open');
			restoreOutsideContent();
			restoreFocus();
		});
		dialog.addEventListener('click', function (event) {
			if (!imageFrame.contains(event.target)) {
				closeModal();
			}
		});
		dialog.addEventListener('keydown', function (event) {
			if (!dialog.classList.contains('is-fallback-open')) {
				return;
			}

			if (event.key === 'Escape') {
				event.preventDefault();
				closeModal();
				return;
			}

			if (event.key === 'Tab') {
				var focusableElements = getFocusableElements();
				var firstElement = focusableElements[0];
				var lastElement = focusableElements[focusableElements.length - 1];
				var activeElementIsOutsideTrap = focusableElements.indexOf(document.activeElement) === -1;

				if (!firstElement) {
					event.preventDefault();
					return;
				}

				if (event.shiftKey && (document.activeElement === firstElement || activeElementIsOutsideTrap)) {
					event.preventDefault();
					lastElement.focus();
				} else if (!event.shiftKey && (document.activeElement === lastElement || activeElementIsOutsideTrap)) {
					event.preventDefault();
					firstElement.focus();
				}
			}
		});
	}

	function initializeModals() {
		document.querySelectorAll('[data-yeg-bus-modal]').forEach(initializeModal);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initializeModals);
	} else {
		initializeModals();
	}
}());
