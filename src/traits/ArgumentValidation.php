<?php

namespace NWP\Traits;

use \InvalidArgumentException as Error;

trait ArgumentValidation {
	public static function isString( $string ) {
		if ( ! is_string( $string ) )
			throw new Error("Given parameter must be of type string.");
			
		return $string;
	}

	public static function isCallable( $callback ) {
		if ( ! is_callable( $callback ) )
			throw new Error("Given parameter must be of type callable.");
			
		return $callback;
	}

	public static function isInt( $int ) {
		if ( ! is_numeric( $int ) )
			throw new Error("Given parameter must be of type integer.");
			
		return $int;
	}

	public static function isArray( $arr ) {
		if ( ! is_array( $arr ) )
			throw new Error("Given parameter must be of type integer.");
			
		return $arr;
	}
}