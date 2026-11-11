/**
 * Home hero: the layers between your site and the server.
 *
 * Problems rise from the server up through the stack and stop at the layer
 * whose job it is: the slab flashes, its label says what it did, and the
 * ticker under the stack tells the one-line story. The layers are tabs that
 * open their detail card; the stack spreads open around the selected one.
 *
 * Without JS, or with reduced motion, the server-rendered stack and labels
 * stay as they are and the layers still work as tabs.
 */
( function () {
	'use strict';

	var root = document.querySelector( '[data-layers]' );
	if ( ! root ) {
		return;
	}

	var geo = {};
	var threats = [];
	try {
		geo = JSON.parse( root.getAttribute( 'data-geo' ) || '{}' );
		threats = JSON.parse( root.getAttribute( 'data-threats' ) || '[]' );
	} catch ( e ) {}

	var SPREAD     = 9;     // how far the stack opens around the selected layer (SVG units)
	var CATCH_HOLD = 1800;  // how long a caught problem stays on its layer
	var GAP        = 800;   // pause between problems
	var IDLE       = 12000; // after a person picks a layer, auto-select resumes after this

	var stage    = root.querySelector( '[data-layers-stage]' );
	var labels   = Array.prototype.slice.call( root.querySelectorAll( '.lyr__label' ) );
	var slabs    = [];
	Array.prototype.forEach.call( root.querySelectorAll( '.lyr__slab' ), function ( s ) {
		slabs[ parseInt( s.getAttribute( 'data-slab' ), 10 ) ] = s;
	} );
	var detail   = document.querySelector( '[data-layers-detail]' );
	var panels   = detail ? Array.prototype.slice.call( detail.querySelectorAll( '.lyr-detail__panel' ) ) : [];
	var keys     = labels.map( function ( l ) { return l.getAttribute( 'data-layer' ); } );
	var tones    = labels.map( function ( l ) {
		var m = l.className.match( /lyr--(\w+)/ );
		return m ? m[1] : '';
	} );

	var scale    = 1;
	var selected = 0;
	var auto     = true;
	var idleTimer = null;
	var flight   = null; // { layer, caught }

	labels.forEach( function ( l, i ) {
		if ( l.classList.contains( 'is-selected' ) ) {
			selected = i;
		}
	} );

	/* ---------- Geometry ---------- */

	function measure() {
		scale = stage.clientWidth / ( geo.vbw || 600 );
	}

	function shiftOf( i ) {
		return i < selected ? -SPREAD : ( i > selected ? SPREAD : 0 );
	}

	function yOf( i ) {
		return geo.y0 + i * geo.gap + shiftOf( i );
	}

	function spread() {
		labels.forEach( function ( l, i ) {
			var s = shiftOf( i );
			if ( slabs[ i ] ) {
				slabs[ i ].style.setProperty( '--shift', s );
			}
			l.style.setProperty( '--shift-px', ( s * scale ) + 'px' );
		} );
	}

	/* ---------- Selection (tabs) ---------- */

	function select( i ) {
		selected = i;
		labels.forEach( function ( l, j ) {
			var on = j === i;
			l.classList.toggle( 'is-selected', on );
			l.setAttribute( 'aria-selected', on ? 'true' : 'false' );
			l.tabIndex = on ? 0 : -1;
			if ( slabs[ j ] ) {
				slabs[ j ].classList.toggle( 'is-selected', on );
			}
		} );
		panels.forEach( function ( p, j ) {
			var on = j === i;
			p.classList.toggle( 'is-active', on );
			if ( on ) {
				p.removeAttribute( 'aria-hidden' );
			} else {
				p.setAttribute( 'aria-hidden', 'true' );
			}
		} );
		if ( detail ) {
			detail.setAttribute( 'aria-labelledby', labels[ i ].id );
		}
		spread();
		// A caught problem rides along with its layer.
		if ( flight && flight.caught ) {
			setTy( yOf( flight.layer ) );
		}
	}

	function pick( i ) {
		auto = false;
		clearTimeout( idleTimer );
		idleTimer = setTimeout( function () {
			auto = true;
		}, IDLE );
		if ( i !== selected ) {
			select( i );
		}
	}

	labels.forEach( function ( l, i ) {
		l.addEventListener( 'click', function () {
			pick( i );
		} );
		l.addEventListener( 'pointerenter', function ( e ) {
			if ( 'mouse' === e.pointerType ) {
				pick( i );
			}
		} );
		l.addEventListener( 'keydown', function ( e ) {
			var next = null;
			if ( 'ArrowDown' === e.key || 'ArrowRight' === e.key ) {
				next = ( i + 1 ) % labels.length;
			} else if ( 'ArrowUp' === e.key || 'ArrowLeft' === e.key ) {
				next = ( i - 1 + labels.length ) % labels.length;
			} else if ( 'Home' === e.key ) {
				next = 0;
			} else if ( 'End' === e.key ) {
				next = labels.length - 1;
			}
			if ( null !== next ) {
				e.preventDefault();
				pick( next );
				labels[ next ].focus();
			}
		} );
	} );

	slabs.forEach( function ( s, i ) {
		s.addEventListener( 'click', function () {
			pick( i );
		} );
		s.addEventListener( 'pointerenter', function ( e ) {
			if ( 'mouse' === e.pointerType ) {
				pick( i );
			}
		} );
	} );

	measure();
	select( selected );

	window.addEventListener( 'resize', function () {
		measure();
		spread();
		if ( flight ) {
			threat.classList.add( 'no-anim' );
			setTy( flight.caught ? yOf( flight.layer ) : ty );
			void threat.offsetWidth;
			threat.classList.remove( 'no-anim' );
		}
	} );

	if ( ! threats.length || ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) ) {
		return;
	}

	/* ---------- Problems on their way up ---------- */

	var threat     = root.querySelector( '[data-layers-threat]' );
	var threatText = root.querySelector( '[data-layers-threat-text]' );
	var ticker     = root.querySelector( '[data-layers-ticker]' );
	var tickerText = root.querySelector( '[data-layers-ticker-text]' );
	var tickerDot  = ticker ? ticker.querySelector( 'i' ) : null;
	var pauseBtn   = root.querySelector( '[data-layers-pause]' );
	var pauseLabel = root.querySelector( '[data-layers-pause-label]' );
	var ty         = 0; // current target, SVG units

	threat.style.left = ( geo.cx / geo.vbw * 100 ) + '%';

	function setTy( y ) {
		ty = y;
		threat.style.setProperty( '--ty', ( y * scale ) + 'px' );
	}

	var at      = 0;
	var paused  = false;
	var visible = true;
	var waiting = false;
	var timers  = [];

	function later( fn, ms ) {
		timers.push( setTimeout( fn, ms ) );
	}

	function canRun() {
		return visible && ! paused && ! document.hidden;
	}

	function tell( line, tone ) {
		if ( ! ticker ) {
			return;
		}
		ticker.classList.add( 'is-out' );
		later( function () {
			tickerText.textContent = line;
			if ( tickerDot ) {
				tickerDot.style.setProperty( '--st', 'var(--' + toneVar( tone ) + ')' );
			}
			ticker.classList.remove( 'is-out' );
		}, 250 );
	}

	function toneVar( tone ) {
		return { ink: 'text-2', mute: 'text-3' }[ tone ] || tone;
	}

	function caught( li, t ) {
		flight.caught = true;
		threatText.textContent = t.done;
		threat.classList.add( 'is-caught', 'lyr--' + tones[ li ] );

		var slab = slabs[ li ];
		slab.classList.remove( 'is-catching' );
		void slab.getBoundingClientRect();
		slab.classList.add( 'is-catching' );
		later( function () {
			slab.classList.remove( 'is-catching' );
		}, 1500 );

		var c = labels[ li ].querySelector( '[data-layers-catch]' );
		if ( c ) {
			c.classList.remove( 'is-new' );
			void c.offsetWidth;
			c.textContent = t.done + ': ' + t.label;
			c.classList.add( 'is-new' );
			later( function () {
				c.textContent = c.getAttribute( 'data-rest' );
				c.classList.remove( 'is-new' );
			}, 3400 );
		}

		var item = panels[ li ] ? panels[ li ].querySelector( '[data-item="' + t.item + '"]' ) : null;
		if ( item ) {
			item.classList.add( 'is-live' );
			later( function () {
				item.classList.remove( 'is-live' );
			}, 3400 );
		}

		tell( t.line, tones[ li ] );

		if ( auto ) {
			select( li );
		}
	}

	function run() {
		if ( ! canRun() ) {
			waiting = true;
			return;
		}
		var t  = threats[ at % threats.length ];
		var li = keys.indexOf( t.layer );
		at++;
		if ( li < 0 ) {
			later( run, 50 );
			return;
		}

		flight = { layer: li, caught: false };
		threat.className = 'lyr__threat no-anim';
		threat.style.setProperty( '--dur', '0ms' );
		threatText.textContent = t.label;
		setTy( geo.vbh - 14 );
		void threat.offsetWidth;
		threat.classList.remove( 'no-anim' );
		threat.classList.add( 'is-live' );

		// The further it climbs, the longer it takes.
		var climb = 520 + ( keys.length - 1 - li ) * 360;
		later( function () {
			threat.style.setProperty( '--dur', climb + 'ms' );
			setTy( yOf( li ) );
		}, 320 );
		later( function () {
			caught( li, t );
		}, 320 + climb );
		later( function () {
			threat.classList.add( 'is-gone' );
		}, 320 + climb + CATCH_HOLD );
		later( function () {
			flight = null;
			run();
		}, 320 + climb + CATCH_HOLD + GAP );
	}

	function resume() {
		if ( waiting && canRun() ) {
			waiting = false;
			run();
		}
	}

	if ( pauseBtn ) {
		pauseBtn.hidden = false;
		pauseBtn.addEventListener( 'click', function () {
			paused = ! paused;
			pauseBtn.setAttribute( 'aria-pressed', paused ? 'true' : 'false' );
			pauseLabel.textContent = paused ? 'Play' : 'Pause';
			resume();
		} );
	}

	document.addEventListener( 'visibilitychange', resume );

	if ( 'IntersectionObserver' in window ) {
		new IntersectionObserver( function ( entries ) {
			visible = entries[0].isIntersecting;
			resume();
		} ).observe( root );
	}

	// Fonts change nothing in the SVG, but the stage width can settle late.
	if ( document.fonts && document.fonts.ready ) {
		document.fonts.ready.then( function () {
			measure();
			spread();
		} );
	}

	later( run, 900 );
}() );
