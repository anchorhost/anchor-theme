/**
 * Post outline — an automatic "On this page" sidebar for single posts.
 *
 * Collects h2/h3 headings from the prose, assigns anchor ids where missing,
 * and keeps a scroll-spy highlight plus a reading estimate that counts down
 * as the reader moves through the article. Stays hidden when the post has
 * fewer than two headings or the viewport has no gutter for it.
 */
( function () {
	'use strict';

	var outline = document.getElementById( 'post-outline' );
	var prose   = document.querySelector( '.post-body > .prose' );

	if ( ! outline || ! prose ) {
		return;
	}

	var headings = Array.prototype.filter.call(
		prose.querySelectorAll( 'h2, h3' ),
		function ( h ) {
			return ! h.closest( '.author-box' ) && h.textContent.trim();
		}
	);

	if ( headings.length < 2 ) {
		return;
	}

	var seen = {};

	function slugify( text ) {
		var slug = text.toLowerCase()
			.normalize( 'NFD' ).replace( /[\u0300-\u036f]/g, '' )
			.replace( /[^a-z0-9]+/g, '-' )
			.replace( /^-+|-+$/g, '' ) || 'section';

		if ( seen[ slug ] ) {
			seen[ slug ] += 1;
			slug += '-' + seen[ slug ];
		} else {
			seen[ slug ] = 1;
		}

		return slug;
	}

	var list = document.getElementById( 'post-outline-list' );
	var links = headings.map( function ( h ) {
		if ( ! h.id ) {
			h.id = slugify( h.textContent );
		}

		var text = h.textContent.trim();

		// No title attribute: the label chip is the tooltip, and the native
		// one would render a second, overlapping copy on hover.
		var a = document.createElement( 'a' );
		a.className = 'post-outline__link' + ( 'H3' === h.tagName ? ' post-outline__link--sub' : '' );
		a.href      = '#' + h.id;

		var tick = document.createElement( 'span' );
		tick.className = 'post-outline__tick';
		tick.setAttribute( 'aria-hidden', 'true' );

		var label = document.createElement( 'span' );
		label.className   = 'post-outline__label';
		label.textContent = text;

		a.appendChild( tick );
		a.appendChild( label );
		list.appendChild( a );
		return a;
	} );

	var reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	list.addEventListener( 'click', function ( e ) {
		var a = e.target.closest( 'a' );
		if ( ! a ) {
			return;
		}

		e.preventDefault();

		var target = document.getElementById( a.getAttribute( 'href' ).slice( 1 ) );
		if ( ! target ) {
			return;
		}

		history.replaceState( null, '', a.getAttribute( 'href' ) );
		window.scrollTo( {
			top: target.getBoundingClientRect().top + window.scrollY - 96,
			behavior: reduced ? 'auto' : 'smooth',
		} );

		target.classList.remove( 'outline-ping' );
		void target.offsetWidth;
		target.classList.add( 'outline-ping' );
		setTimeout( function () {
			target.classList.remove( 'outline-ping' );
		}, 1400 );
	} );

	/**
	 * Only the heading nearest the cursor shows its label, so the rail stays
	 * a quiet column of ticks while still being readable on approach.
	 */
	var near = null;

	/**
	 * The chip is position:fixed, so its coordinates are set here against the
	 * tick's live viewport rect and clamped to stay fully on screen.
	 */
	function positionChip( link ) {
		var label = link.querySelector( '.post-outline__label' );
		var tick  = link.querySelector( '.post-outline__tick' );
		if ( ! label || ! tick ) {
			return;
		}

		var r   = tick.getBoundingClientRect();
		var top = r.top + r.height / 2;
		var half = label.offsetHeight / 2;

		top = Math.min( Math.max( top, half + 84 ), window.innerHeight - half - 14 );

		label.style.left = ( r.right + 12 ) + 'px';
		label.style.top  = top + 'px';
	}

	function setNear( link ) {
		if ( near === link ) {
			return;
		}
		if ( near ) {
			near.classList.remove( 'is-near' );
		}
		near = link;
		if ( near ) {
			near.classList.add( 'is-near' );
			positionChip( near );
		}
	}

	outline.addEventListener( 'mousemove', function ( e ) {
		var closest  = null;
		var shortest = Infinity;

		links.forEach( function ( a ) {
			var box  = a.getBoundingClientRect();
			var dist = Math.abs( e.clientY - ( box.top + box.height / 2 ) );
			if ( dist < shortest ) {
				shortest = dist;
				closest  = a;
			}
		} );

		setNear( closest );
	} );

	outline.addEventListener( 'mouseleave', function () {
		setNear( null );
	} );

	list.addEventListener( 'focusin', function ( e ) {
		var a = e.target.closest( '.post-outline__link' );
		if ( a ) {
			setNear( a );
		}
	} );

	list.addEventListener( 'focusout', function () {
		setNear( null );
	} );

	var estimate  = document.getElementById( 'post-outline-estimate' );
	var minutes   = parseInt( outline.dataset.minutes, 10 ) || 0;
	var readLabel = outline.dataset.readLabel || '';
	var leftTpl   = outline.dataset.leftTemplate || '%d';
	var doneLabel = outline.dataset.doneLabel || '';

	estimate.textContent = readLabel;
	outline.hidden = false;

	var ticking = false;

	function update() {
		ticking = false;

		var spyLine = window.scrollY + 110;
		var active  = -1;

		for ( var i = 0; i < headings.length; i++ ) {
			if ( headings[ i ].getBoundingClientRect().top + window.scrollY <= spyLine ) {
				active = i;
			}
		}

		links.forEach( function ( a, i ) {
			a.classList.toggle( 'is-active', i === active );
			a.classList.toggle( 'is-read', i < active );
		} );

		// The rail is sticky, but it still travels before it pins.
		if ( near ) {
			positionChip( near );
		}

		var rect  = prose.getBoundingClientRect();
		var total = rect.height - window.innerHeight * 0.6;
		var done  = Math.min( 1, Math.max( 0, ( window.scrollY - ( rect.top + window.scrollY - window.innerHeight * 0.4 ) ) / Math.max( 1, total ) ) );

		if ( done <= 0.02 ) {
			estimate.textContent = readLabel;
		} else if ( done >= 0.98 ) {
			estimate.textContent = doneLabel;
		} else {
			estimate.textContent = leftTpl.replace( '%d', Math.max( 1, Math.ceil( minutes * ( 1 - done ) ) ) );
		}
	}

	function onScroll() {
		if ( ! ticking ) {
			ticking = true;
			window.requestAnimationFrame( update );
		}
	}

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', onScroll );
	update();
} )();
