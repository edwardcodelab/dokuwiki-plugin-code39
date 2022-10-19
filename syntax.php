<?php
/**
 * DokuWiki Plugin code39 (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @auther dodotori @dokuwiki forum
 * code based on mark2memorize by shimamu <cooklecurry@gmail.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
	die();
}

class syntax_plugin_code39 extends DokuWiki_Syntax_Plugin
{
	/**
	 * @return string Syntax mode type
	 */
	public function getType()
	{
		return 'formatting';
	}

	/**
	 * @return string Paragraph type
	 */
	public function getPType()
	{
		return 'normal';
	}

	function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }

	/**
	 * @return int Sort order - Low numbers go before high numbers
	 */
	public function getSort()
	{
		return 100;
	}

	/**
	 * Connect lookup pattern to lexer.
	 *
	 * @param string $mode Parser mode
	 */
	public function connectTo($mode)
	{
		$this->Lexer->addEntryPattern('<code39.*?>(?=.*?</code39>)', $mode, 'plugin_code39');
	}

	public function postConnect()
	{
		$this->Lexer->addExitPattern('</code39>', 'plugin_code39');
	}

	/**
	 * Handle matches of the code39 syntax
	 *
	 * @param string       $match   The match of the syntax
	 * @param int          $state   The state of the handler
	 * @param int          $pos     The position in the document
	 * @param Doku_Handler $handler The handler
	 *
	 * @return array Data for the renderer
	 */
	public function handle($match, $state, $pos, Doku_Handler $handler)
	{
		switch ($state) {
		case DOKU_LEXER_ENTER :     return array($state, '');
		case DOKU_LEXER_UNMATCHED : return array($state, $match);
		case DOKU_LEXER_EXIT :      return array($state, '');
		}
		return array();
	}

	/**
	 * Render xhtml output or metadata
	 *
	 * @param string        $mode     Renderer mode (supported modes: xhtml)
	 * @param Doku_Renderer $renderer The renderer
	 * @param array         $data     The data from the handler() function
	 *
	 * @return bool If rendering was successful.
	 */
	public function render($mode, Doku_Renderer $renderer, $data)
	{
         $renderer->doc .= "<link href='https://fonts.googleapis.com/css?family=Libre Barcode 39 Extended Text' rel='stylesheet'>";
		if ($mode == 'xhtml') {
			list($state, $match) = $data;
			switch ($state) {
			case DOKU_LEXER_ENTER :      
				$renderer->doc .= "<span class='code39'>*"; 
				break;
			case DOKU_LEXER_UNMATCHED :  $renderer->doc .= $renderer->_xmlEntities($match); break;
			case DOKU_LEXER_EXIT :       $renderer->doc .= "*</span>"; break;
			}
			return true;
		}
		return false;
	}
}
?>