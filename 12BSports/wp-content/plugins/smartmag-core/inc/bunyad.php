<?php
/**
 * Bunyad framework factory extension.
 * 
 * This aids in better code completion for most IDEs.
 * Most methods here are simply a wrapper for Bunyad_Base::get() method.
 * 
 * @see Bunyad_Base
 * @see Bunyad_Base::get()
 * 
 * @method static Bunyad_Theme_Archives     archives($fresh, ...$args)
 * @method static Bunyad_Theme_Authenticate authenticate($fresh, ...$args)
 * @method static Bunyad_Theme_Media        media($fresh, ...$args)  Media Helpers.
 * @method static \Bunyad\Blocks\Helpers    blocks($fresh, ...$args)
 * @method static Bunyad_Theme_Amp          amp($fresh, ...$args)  AMP class.
 * @method static Bunyad_Theme_Lazyload     lazyload($fresh, ...$args)  Lazyload class.
 * @method static \SmartMag\Reviews\Module  reviews($fresh, ...$args) Reviews Module.
 */
class Bunyad extends Bunyad_Base {

	/**
	 * Main Theme Object
	 *
	 * @return Bunyad_Theme_SmartMag
	 */
	public static function theme() {
		return self::get('theme');
	}
}