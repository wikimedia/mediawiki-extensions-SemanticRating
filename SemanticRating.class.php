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

class SemanticRating {

	public function renderRating($input, $imagepath) {

		$output =
			Html::openElement('table', array('style' => 'display:inline;')) .
			Html::openElement('td');

		if ($input < 0) {
			$input = 0;
		} else if ($input > 5) {
			$input = 5;
		}

		$i = 1;
		while ($i <= $input) {
			$output .=
				Html::element('img',
					array('src' => $imagepath . 'yellowstar.png'));
			$i++;
		}
		if ($input - $i + 1 != 0) {
			$output .=
				Html::element('img',
					array('src' => $imagepath . 'halfstar.png'));
			$i++;
		}
		while ($i < 6) {
			$output .=
				Html::element('img',
					array('src' => $imagepath . 'greystar.png'));
			$i++;
		}

		$output .=
			Html::closeElement('td') .
			Html::closeElement('table');

		return $output;
	}

	public function editRating($cur_value, $input_name, $imagepath) {
	
		if (!is_numeric($cur_value) || $cur_value < 1 || $cur_value > 5) {
			$cur_value = 1;
		}
	
		$output =
			Html::openElement('table', array('style' => 'display:inline;')) .
			Html::openElement('td');
	
		global $sfgFieldNum;
		$input_id = "input_$sfgFieldNum";
		$output .= Html::element('input', array(
			'type' => 'hidden',
			'id' => $input_id,
			'name' => $input_name,
			'value' => $cur_value
			));
	
		$i = 1;
	
		$src =	$imagepath . 'yellowstar.png';
		while ($i < $cur_value + 1) {
			$output .= Html::element('img', array(
				'src' => $src,
				'id' => $input_id . '_s_' . $i,
				'onclick' => 'setrating(' . $i . ",'" . $input_id . "'" . ');'
				));
			$i++;
		}
			
		$src =	$imagepath . 'greystar.png';
		while ($i < 6) {
			$output .= Html::element('img', array(
				'src' => $src,
				'id' => $input_id . '_s_' . $i,
				'onclick' => 'setrating(' . $i . ",'" . $input_id . "'" . ');'
				));
			$i++;
		}
			
		$output .=
			Html::closeElement('td') .
			Html::closeElement('table');
	
		return $output;
	}
}
