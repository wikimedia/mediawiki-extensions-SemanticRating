<?php

/*
 * Copyright (c) 2013 The MITRE Corporation
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

if (!defined('MEDIAWIKI')) {
	die('<b>Error:</b> This file is part of a MediaWiki extension and cannot be run standalone.');
}

if (version_compare($wgVersion, '1.21', 'lt')) {
	die('<b>Error:</b> This version of SemanticRating is only compatible with MediaWiki 1.21 or above.');
}

if (!defined('SF_VERSION')) {
	die('<b>Error:</b> SemanticRating is a Semantic Forms extension so must be included after Semantic Forms.');
}

if (version_compare(SF_VERSION, '2.5.2', 'lt')) {
	die('<b>Error:</b> This version of SemanticRating is only compatible with Semantic Forms 2.5.2 or above.');
}

$wgExtensionCredits['parserhook'][] = array (
	'name' => 'SemanticRating',
	'version' => '1.0',
	'author' => 'Cindy Cicalese',
	'descriptionmsg' => 'semanticrating-desc'
);

$wgExtensionMessagesFiles['SemanticRating'] =
	__DIR__ . '/SemanticRating.i18n.php';

$wgExtensionMessagesFiles['SemanticRatingMagic'] =
	__DIR__ . '/SemanticRating.i18n.magic.php';

$wgResourceModules['ext.SemanticRating'] = array(
	'localBasePath' => dirname(__FILE__),
	'remoteExtPath' => 'SemanticRating',
	'scripts' => 'SemanticRating.js'
);

$wgHooks['ParserFirstCallInit'][] = 'efSemanticRatingParserFunction_Setup';

function efSemanticRatingParserFunction_Setup (& $parser) {
	$parser->setFunctionHook('rating', 'renderRating');
	global $sfgFormPrinter;
	$sfgFormPrinter->setInputTypeHook('rating', 'editRating', array());
	return true;
}

function renderRating($parser, $input) {
	$output = SemanticRating::renderRating($input);
	return array($parser->insertStripItem( $output, $parser->mStripState ),
		'noparse' => false);
}

function editRating($cur_value, $input_name, $is_mandatory, $is_disabled,
	$field_args) {
	$output = SemanticRating::editRating($cur_value, $input_name);
	return array($output, null);
}

class SemanticRating {

	function renderRating($input) {
		global $wgServer, $wgScriptPath;
		$path = $wgServer . $wgScriptPath . "/extensions/SemanticRating/";

		$output = "<table style='display:inline;'><td>";
		if ($input < 0) {
			$input = 0;
		} else if ($input > 5) {
			$input = 5;
		}
		$i = 1;
		while ($i <= $input) {
			$output .=
				"<img src='$path" . "yellowstar.png' />";
			$i++;
		}
		if ($input - $i + 1 != 0) {
			$output .=
				"<img src='$path" . "halfstar.png' />";
			$i++;
		}
		while ($i < 6) {
			$output .=
				"<img src='$path" . "greystar.png' />";
			$i++;
		}
		$output .= "</td></table>";
		return $output;
	}

	function editRating($cur_value, $input_name) {
	
		global $wgOut;
		$wgOut->addModules('ext.SemanticRating');
	
		if (!is_numeric($cur_value) || $cur_value < 1 || $cur_value > 5) {
			$cur_value = 1;
		}
	
		$output = "<table style='display:inline'><td>";
	
		global $sfgFieldNum;
		$input_id = "input_$sfgFieldNum";
		$output .= '<input type="hidden" id="' . $input_id . '" name="' .
			$input_name . '" value="' . $cur_value . '" />';
	
		global $wgServer, $wgScriptPath;
		$path = $wgServer . $wgScriptPath . "/extensions/SemanticRating/";
	
		$src =	$path . 'yellowstar.png';
		for($i = 1; $i < ($cur_value + 1); $i++) {
			$output .= '<img src="' . $src . '" id="' . $input_id . '_s_' . $i .
				'" onclick="setrating(' . $i . ",'" . $input_id . "'" . ');" />';
		}
			
		$src =	$path . 'greystar.png';
		for($i = $i; $i < 6; $i++) {
			$output .= '<img src="' . $src . '" id="' . $input_id . '_s_' . $i .
				'" onclick="setrating(' . $i . ",'" . $input_id . "'" . ');" />';
		}
			
		$output .= "</td></table>";
	
		return $output;
	}
}
