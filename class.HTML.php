<?php

/* HTML  -

Last Edit:	April 9, 2011	// HTML_GROUP_LI added for http://BeautyOfNewYork.com
							// In HTML_LI class, add $innerhtml; debugging for $this->type
							//

*/

abstract class HTML {
	protected $result;
	function outerHTML(){
		return ($this->result . "\n");
	}
}

class HTML_GROUP extends HTML {
	protected $items = array();
	function __construct($items) {
		if(!is_array($items)){
            throw new Exception('Invalid parameter type at HTML group');
        }
		$this->items = $items;
		$this->result = "";
	}

	function buildHTML() {
		$out = "";
		foreach($this->items as $item) {
			if ($item instanceof HTML) {
				$out .= $item->outerHTML();
			} else if ($item instanceof Ad_Empty) {
				$out .= $item->outerHTML();
			} else {
				$out .= $item;
			}
		}
		$this->result = $out;
	}
	function outerHTML(){
		if ($this->result == "") {
			$this->buildHTML();
		}
		return ($this->result);
	}
}

class HTML_GROUP_BR extends HTML_GROUP {
	function buildHTML() {
		$out = "";
		foreach($this->items as $item) {
			$out .= $item."<br />";
		}
		$out = cutlast($out, 6);
		$this->result = $out;
	}
}
class HTML_GROUP_LI extends HTML_GROUP {
	function buildHTML() {
		$out = "";
		$my_entry = "";
		foreach($this->items as $item) {
			$obj = new HTML_DIV( array("class"=>"li_entry"), $item );
			$out .= "<li>". $obj->outerHTML(). "</li>";
		}
		$this->result = $out;
	}
}

abstract class HTML_WRAP extends HTML{
    protected $attributes;
	protected $attribute_string;
    protected function __construct($attributes){
        if(!is_array($attributes)){
            throw new Exception('Invalid attribute type');
        }
        $this->attributes=$attributes;
		$this->buildATTR();
    }

	protected function buildATTR() {
		$out = "";
		foreach($this->attributes as $attribute => $value){
            $out .= $attribute.'="'.$value.'" ';
        }
		$this->attribute_string = $out;
	}

	function outerHTML(){
		return ($this->result . "\n");
	}
}

abstract class HTML_ELEMENT extends HTML{
    protected $attributes;
	protected $attribute_string;
    protected function __construct($attributes){
        if(!is_array($attributes)){
            throw new Exception('Invalid attribute type');
        }
        $this->attributes=$attributes;
		$this->buildATTR();
    }

	protected function buildATTR() {
		$out = "";
		foreach($this->attributes as $attribute => $value){
            $out .= $attribute.'="'.$value.'" ';
        }
		$this->attribute_string = $out;
	}
}

// Div HTML
class HTML_DIV extends HTML_ELEMENT{
    protected $data;
	protected $type;
	protected $innerhtml;
    function __construct($attributes = array(), $data){
		if ( is_string($data) || is_numeric($data) || $data == null )	$this->type = 1;	// feb28
		else if (is_array($data))					$this->type = 2;
		else if ($data instanceof HTML)		$this->type = 3;
		else throw new Exception('Invalid parameter type at HTML_DIV class');

        parent::__construct($attributes);
		$this->result = "";
		$this->innerhtml = "";
		$this->data = $data;
    }

    function innerHTML(){
		$out = "";
		switch ($this->type) {
			case(1):
						$out .= $this->data;
						break;
			case(2):
						$group = new HTML_GROUP($this->data);
						$out .= $group->outerHTML();
						break;
			case(3):
						$out .= $this->data->outerHTML();
						break;
		}
        return $this->innerhtml = $out;
    }

	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$out = "<div ".$this->attribute_string.">" . CRLF . $this->innerhtml . "</div>";
			$this->result = $out;
		}
		return ($this->result . CRLF);
	}
}

// 'Unordered List Wrapper' class
class HTML_UL_WRAP extends HTML_WRAP {
    function __construct($attributes=array(), $data){
        parent::__construct($attributes);
        if(!is_string($data)){
            throw new Exception('Invalid parameter for UL Wrapper');
        }
		$this->result = "<ul ".$this->attribute_string.">\n".$data."</ul>\n";
    }
}

// 'Ordered List Wrapper' class
class HTML_OL_WRAP extends HTML_WRAP {
    function __construct($attributes=array(), $data){
        parent::__construct($attributes);
        if(!is_string($data)){
            throw new Exception('Invalid parameter for UL Wrapper');
        }
		$this->result = "<ol ".$this->attribute_string.">\n".$data."</ol>\n";
    }
}

// 'Ordered List' class
class HTML_OL extends HTML_DIV{
    private $items = array();
    function __construct($attributes=array(), $items=array()){
        parent::__construct($attributes, $items);
        if(!is_array($items)){
            throw new Exception('Invalid parameter for list items');
        }
        $this->items = $items;
    }

    function innerHTML(){
		$out = "";
        foreach($this->items as $item){
            $out .= ($item instanceof HTML) ? $item->outerHTML() : $item;
        }
        return $this->innerhtml = "\n" . $out;
    }

	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$out = "<ol ".$this->attribute_string.">\n".$this->innerhtml."</ol>\n";
			$this->result = $out;
		}
		return ($this->result . "\n");
	}
}

// 'Unordered List' class
class HTML_UL extends HTML_DIV{
    private $items = array();
    function __construct($attributes=array(), $items=array()){
        parent::__construct($attributes, $items);
        if(!is_array($items)){
            throw new Exception('Invalid parameter for list items');
        }
        $this->items = $items;
    }

    function innerHTML(){
		$out = "";
        foreach($this->items as $item){
            $out .= ($item instanceof HTML) ? $item->outerHTML() : $item;
        }
        return $this->innerhtml = "\n" . $out;
    }

	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$out = "<ul ".$this->attribute_string.">\n".$this->innerhtml."</ul>\n";
			$this->result = $out;
		}
		return ($this->result . "\n");
	}
}

// 'Select Wrapper' class
class HTML_SELECT_WRAP extends HTML_WRAP {
    function __construct($attributes=array(), $data){
        parent::__construct($attributes);
        if(!is_string($data)){
            throw new Exception('Invalid parameter for LI Wrapper');
        }
		$this->result = "<select ".$this->attribute_string.">" . CRLF. $data. CRLF. '</select>'. CRLF;
    }
}

// 'List Item Wrapper' class
class HTML_LI_WRAP extends HTML_WRAP {
    function __construct($attributes=array(), $data){
        parent::__construct($attributes);
        if(!is_string($data)){
            throw new Exception('Invalid parameter for LI Wrapper');
        }
		$this->result = "<li ".$this->attribute_string.">" . CRLF . $data."</li>" . CRLF;
    }
}

// 'List Item' class
class HTML_LI extends HTML_ELEMENT {
    private $items;
	private $data;
	private $type;
	private $innerhtml;
    public function __construct($attributes=array(), $data){
        if(!$data instanceof HTML && !is_string($data) && !is_array($data)){
            throw new Exception('Invalid parameter type at HTML_LI class');
        }
        parent::__construct($attributes);

		if (is_string($data)) {
			$this->data = $data;
			$this->type = 1;
		} else if (is_array($data)) {
			$this->items = $data;
			$this->type = 2;
		} else if ($data instanceof HTML) {
			$this->data = $data;
			$this->type = 3;
		} else {
			throw new Exception('Invalid parameter type at HTML_LI class');
		}
    }

    private function innerHTML(){
		$out = "";
		switch ($this->type) {
			case(1):	$out .= $this->data;
						break;
			case(2):	$group = new HTML_GROUP_LI($this->items);
						$out .= $group->outerHTML();
						break;
			case(3):	$out .= $this->data->outerHTML();
						break;
		}
        return ($out. "\n");
    }

	public function outerHTML(){
		if ($this->innerhtml == "") {
			$this->innerhtml = $this->innerHTML();
		}
		return($this->innerhtml);
/*
		if ($this->result == "") {
			$out = "<ul ".$this->attribute_string.">\n".$this->innerhtml."</ul>";	// strange? feb 14, 2011
			$this->result = $out;
		}
		return ($this->result . "\n");
*/
	}
} // end of class HTML_LI

// 'Image' class
class HTML_IMG extends HTML_WRAP{
    function __construct($attributes = array()){
        parent::__construct($attributes);
		$this->result = "<img " . $this->attribute_string . " />";
    }
}

// 'Anchor' class
class HTML_A extends HTML_WRAP{
    function __construct($attributes = array(), $data){
        parent::__construct($attributes);
		$this->result = "<a " . $this->attribute_string . ">" . $data . "</a>";
    }
}

// 'Header' classes
class HTML_H1 extends HTML_WRAP{
    function __construct($attributes = array(), $data){
        parent::__construct($attributes);
		$this->result = "<h1 " . $this->attribute_string . ">" . $data . "</h1>";
    }
}
class HTML_H2 extends HTML_WRAP{
    function __construct($attributes = array(), $data){
        parent::__construct($attributes);
		$this->result = "<h2 " . $this->attribute_string . ">" . $data . "</h2>";
    }
}
class HTML_H3 extends HTML_WRAP{
    function __construct($attributes = array(), $data){
        parent::__construct($attributes);
		$this->result = "<h3 " . $this->attribute_string . ">" . $data . "</h3>";
    }
}

class HTML_FORM extends HTML_DIV{
    function __construct($attributes = array(), $data){
        parent::__construct($attributes, $data);
    }
	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$out = "<form " . $this->attribute_string . ">" . CRLF . $this->innerhtml . "</form>";
			$this->result = $out;
		}
		return ($this->result . CRLF);
	}
}

// 'Paragraph' class
class HTML_P extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function innerHTML(){
		$out = "";
		switch ($this->type) {
			case(1):
						if ($this->data == "") {
							$out .= "<br />";
						} else {
							$out .= $this->data;
						}
						break;
			case(2):
						$group = new HTML_GROUP_BR($this->data);	// array entries are separated by <br />
						$out .= $group->outerHTML();
						break;
			case(3):
						$out .= $this->data->outerHTML();
						break;
			default:
						throw new Exception('Invalid paragraph type at HTML_P');
						break;
		}
        return $this->innerhtml = $out;
    }
	function outerHTML(){
		if ($this->innerhtml == "") {
			$this->innerHTML();
		}
		if ($this->result == "") {
			$this->result = "<p ".$this->attribute_string.">\n" . $this->innerhtml . "</p>";
		}
		return ($this->result . CRLF);
	}
}

// 'Input' class
class HTML_INPUT extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$this->result = "<input ".$this->attribute_string.">" . $this->innerhtml . "</input>";
		}
		return ($this->result . CRLF);
	}
}

// 'Button' class
class HTML_BUTTON extends HTML_INPUT {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$this->result = "<button ".$this->attribute_string.">" . $this->innerhtml . "</button>";
		}
		return ($this->result . CRLF);
	}
}

// 'Textarea' class
class HTML_TEXTAREA extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$this->result = "<textarea ".$this->attribute_string.">" . $this->innerhtml . "</textarea>";
		}
		return ($this->result . CRLF);
	}
}

// 'Span' class
class HTML_SPAN extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$this->result = "<span ".$this->attribute_string.">" . $this->innerhtml . "</span>";
		}
		return ($this->result . CRLF);
	}
}

// 'TABLE' class
class HTML_TABLE extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$this->result = "<table ".$this->attribute_string.">" . $this->innerhtml . "</table>";
		}
		return ($this->result . CRLF);
	}
}


// 'TR' class
class HTML_TR extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$this->result = "<tr ".$this->attribute_string.">" . $this->innerhtml . "</tr>";
		}
		return ($this->result . CRLF);
	}
}

// 'TD' class
class HTML_TD extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$this->result = "<td ".$this->attribute_string.">" . $this->innerhtml . "</td>";
		}
		return ($this->result . CRLF);
	}
}
// 'TD_LIST' class
class HTML_TD_LIST extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function innerHTML() {
		$out = "";
		foreach ($this->data as $td_data) {
			$tmp = new HTML_TD( $this->attributes, $td_data);
			$out .= $tmp->outerHTML();
		}
		$this->innerhtml = $out;
	}
	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$this->result = $this->innerhtml;
		}
		return ($this->result . CRLF);
	}
}
class HTML_TD1 extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function outerHTML(){
		if ($this->result == "") {
			$this->result = "<td ". $this->attribute_string.">" . $this->data . "</td>";
		}
		return ($this->result . CRLF);
	}
}
// 'TH' class
class HTML_TH extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function outerHTML(){
		if ($this->result == "") {
			$this->result = "<th ". $this->attribute_string.">" . $this->data . "</th>";
		}
		return ($this->result . CRLF);
	}
}
// 'TH_LIST' class
class HTML_TH_LIST extends HTML_DIV {
	function __construct($attributes=array(), $data){
        parent::__construct($attributes, $data);
    }
	function innerHTML() {
		$out = "";
		foreach ($this->data as $td_data) {
			$tmp = new HTML_TH( $this->attributes, $td_data);
			$out .= $tmp->outerHTML();
		}
		$this->innerhtml = $out;
	}
	function outerHTML(){
		if ($this->innerhtml == "") $this->innerHTML();
		if ($this->result == "") {
			$this->result = $this->innerhtml;
		}
		return ($this->result . CRLF);
	}
}

class HTML_FLASH extends HTML_ELEMENT {
	function __construct ($url, $width, $height, $id, $bg, $vars, $wmode){
		$out = "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' ";
		$out .= "codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' ";
		$out .= "width='" . $width . "' ";
		$out .= "height='" . $height . "' ";
		$out .= "id='" . $id . "' ";
		$out .= "align='middle'>" . CRLF;

		$out .= "<param name='allowScriptAccess' value='always' />";
		$out .= "<param name='movie' value='" . $url . "' />";
		$out .= "<param name='FlashVars' value='" . $vars . "' />";
		$out .= "<param name='wmode' value='" . $wmode . "' />";
		$out .= "<param name='menu' value='false' />";
		$out .= "<param name='quality' value='high' />";
		$out .= "<param name='bgcolor' value='" . $bg . "' />" . CRLF;

		$out .= "<embed src='" . $url . "' ";
		$out .= "FlashVars='" . $vars . "' ";
		$out .= "wmode='" . $wmode . "' ";
		$out .= "menu='false' quality='high' bgcolor='" . $bg . "' ";
		$out .= "width='" . $width . "' ";
		$out .= "height='" . $height . "' ";
		$out .= "name='" . $id + "' ";
		$out .= "align='middle' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />" . CRLF;
		$out .= "</object>";

		$this->result = $out;
	}

	function outerHTML () {
		return($this->result . CRLF);
	}
}


// ETC
class HTML_ETC {
	const HR = "<hr />";
}
?>
