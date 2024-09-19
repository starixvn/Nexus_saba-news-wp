/**
 * Sphere Post Views - log AJAX count.
 * 
 * @copyright 2022 ThemeSphere
 */
'use strict';

(() => {
	const STORAGE_KEY = 'sphere-post-views';
	let configs;

	function init(postID) {
		configs = Sphere_PostViews;
		postID  = postID || configs.postID || null;

		if (!window.fetch || !configs || !postID) {
			return;
		}
	
		if (configs.sampling) {
			const rand = Math.floor(Math.random() * configs.samplingRate) + 1;
			if (rand !== 1)  {
				return;
			}
		}

		if (isCrawler()) {
			return;
		}

		// Already counted.
		if (recentlyCounted(postID)) {
			return;
		}

		const params = {
			method: 'POST',
			headers: {
				'Content-type': 'application/x-www-form-urlencoded'
			},
			body: [
				'post_id=' + postID,
				'action=update_views_ajax',
				'token=' + configs.token
			].join('&')
		};

		fetch(configs.ajaxUrl, params)
			.then(resp => resp.text())
			.then(data => logViewCount(postID));
	}

	/**
	 * Check if post count was recently counted.
	 */
	function recentlyCounted(id) {
		if (!configs.repeatCountDelay) {
			return false;
		}

		// Seconds in Hours converted to ms.
		const repeatCountDelay = 3600 * parseFloat(configs.repeatCountDelay) * 1000;
		const viewed = getStorage() || {};

		if (!viewed || !viewed.posts || (!id in viewed.posts)) {
			return false;
		}

		const lastViewed = parseInt(viewed.posts[id]);
		if ((Date.now() - lastViewed) < repeatCountDelay) {
			return true;
		}

		return false;
	}

	/**
	 * @returns {Boolean|Object}
	 */
	function getStorage() {
		let viewed = localStorage.getItem(STORAGE_KEY);
		if (!viewed) {
			return false;
		}

		try {
			viewed = JSON.parse(viewed);

			// Grown too large.
			if (viewed.posts && Object.keys(viewed.posts).length > 10000) {
				viewed = {};
			}

		} catch(e) {
			return false;
		}

		return viewed;
	}

	/**
	 * Add a view count to storage, if needed.
	 * 
	 * @param {Number} id 
	 */
	function logViewCount(id) {
		if (!configs.repeatCountDelay) {
			return;
		}

		const viewed = getStorage() || {};
		viewed.posts = viewed.posts || {};
		viewed.posts[id] = Date.now();

		localStorage.setItem(STORAGE_KEY, JSON.stringify(viewed));
	}

	/**
	 * Minimal crawler detection of popular bots.
	 */
	function isCrawler() {
		if (navigator.webdriver) {
			return true;
		}

		const isBot = /headless|bot|spider|crawl|google|baidu|bing|msn|teoma|slurp|yandex/i.test(navigator.userAgent);
		return isBot;
	}

	document.readyState !== 'loading' 
		? init()
		: document.addEventListener('DOMContentLoaded', () => init());

	document.addEventListener('spc-alp-pageview', e => {
		if (!e.detail.id) {
			return;
		}

		init(e.detail.id);
	});
})();
