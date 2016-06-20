<?php
/**
 * zz pico debug plugin - A Pico CMS debugging tool
 *
 * @author  Mattia Roccoberton
 * @link    http://blocknot.es
 * @license http://opensource.org/licenses/MIT
 * @version 0.1.4
 */

class zz_pico_debug extends AbstractPicoPlugin
{
  // protected $dependsOn = array();
  protected $enabled = false;
  protected $debug_info = [];

  // Triggered after Pico has loaded all available plugins
  public function onPluginsLoaded( array &$plugins )
  {
    $this->debug_info['onPluginsLoaded'] = 'plugins = ' . implode( ', ', array_keys( $plugins ) );
  }

  // Triggered after Pico has read its configuration
  public function onConfigLoaded( array &$config )
  {
    if( isset( $config['zz_pico_debug']['php_errors'] ) && $config['zz_pico_debug']['php_errors'] )
    {
      error_reporting( E_ALL );
      ini_set( "display_errors", 1 );
    }
    $config['twig_config']['debug'] = TRUE;   // enable Twig function: dump()
    $this->debug_info['onConfigLoaded'] = 'config = ';
    foreach( $config as $key => $value ) $this->debug_info['onConfigLoaded'] .= $key . ': ' . ( ( is_bool( $value ) || is_numeric( $value ) || ( is_string( $value ) && strlen( $value ) < 100 ) ) ? "<b>$value</b>" : gettype( $value ) ) . ', ';
  }

  // Triggered after Pico has evaluated the request URL
  public function onRequestUrl( &$url )
  {
    $this->debug_info['onRequestUrl'] = 'url = ' . var_export( $url, TRUE );
  }

  // Triggered after Pico has discovered the content file to serve
  public function onRequestFile( &$file )
  {
    $this->debug_info['onRequestFile'] = 'file = ' . var_export( $file, TRUE );
  }

  // Triggered before Pico reads the contents of the file to serve
  public function onContentLoading( &$file )
  {
    $this->debug_info['onContentLoading'] = 'file = ' . var_export( $file, TRUE );
  }

  // Triggered after Pico has read the contents of the file to serve
  public function onContentLoaded( &$rawContent )
  {
    $this->debug_info['onContentLoaded'] = 'rawContent (len) = ' . strlen( $rawContent );
  }

  // Triggered before Pico reads the contents of a 404 file
  public function on404ContentLoading( &$file )
  {
    $this->debug_info['on404ContentLoading'] = 'file = ' . var_export( $file, TRUE );
  }

  // Triggered after Pico has read the contents of the 404 file
  public function on404ContentLoaded( &$rawContent )
  {
    $this->debug_info['on404ContentLoaded'] = 'rawContent (len) = ' . strlen( $rawContent );
  }

  // Triggered when Pico reads its known meta header fields
  public function onMetaHeaders( array &$headers )
  {
    $this->debug_info['onMetaHeaders'] = 'headers = ' . var_export( $headers, TRUE );
  }

  // Triggered before Pico parses the meta header
  public function onMetaParsing( &$rawContent, array &$headers )
  {
    $this->debug_info['onMetaParsing'] = 'rawContent (len) = ' . strlen( $rawContent ) . ' - headers = ' .  var_export( $headers, TRUE );
  }

  // Triggered after Pico has parsed the meta header
  public function onMetaParsed( array &$meta )
  {
    $this->debug_info['onMetaParsed'] = 'meta = ' . var_export( $meta, TRUE );
  }

  // Triggered before Pico parses the pages content
  public function onContentParsing( &$rawContent )
  {
    $this->debug_info['onContentParsing'] = 'rawContent (len) = ' . strlen( $rawContent );
  }

  // Triggered after Pico has prepared the raw file contents for parsing
  public function onContentPrepared( &$content )
  {
    $this->debug_info['onContentPrepared'] = 'content (len) = ' . strlen( $content );
  }

  // Triggered after Pico has parsed the contents of the file to serve
  public function onContentParsed( &$content )
  {
    $this->debug_info['onContentParsed'] = 'content (len) = ' . strlen( $content );
  }

  // Triggered before Pico reads all known pages
  public function onPagesLoading()
  {
    $this->debug_info['onPagesLoading'] = '';
  }

  // Triggered when Pico reads a single page from the list of all known pages
  public function onSinglePageLoaded( array &$pageData )
  {
    $data = new ArrayObject( $pageData );
    $data['content'] = 'length: ' . ( isset( $pageData['content'] ) ? strlen( $pageData['content'] ) : '-' );
    $data['raw_content'] = 'length: ' .  strlen( $pageData['raw_content'] );
    $this->debug_info['onSinglePageLoaded'] = 'pageData = ' . var_export( $data->getArrayCopy(), TRUE );
  }

  // Triggered after Pico has read all known pages
  public function onPagesLoaded(
    array &$pages,
    array &$currentPage = null,
    array &$previousPage = null,
    array &$nextPage = null
  ) {
    $this->debug_info['onPagesLoaded']  = 'pages = ' . implode( ', ', array_keys( $pages ) );
    $this->debug_info['onPagesLoaded'] .= ' - currentPage: ' . ( ( $currentPage && isset( $currentPage['id'] ) ) ? $currentPage['id'] : '-' );
    $this->debug_info['onPagesLoaded'] .= ' - previousPage: ' . ( ( $previousPage && isset( $previousPage['id'] ) ) ? $previousPage['id'] : '-' );
    $this->debug_info['onPagesLoaded'] .= ' - nextPage: ' . ( ( $nextPage && isset( $nextPage['id'] ) ) ? $nextPage['id'] : '-' );
  }

  // Triggered before Pico registers the twig template engine
  public function onTwigRegistration()
  {
    $this->debug_info['onTwigRegistration'] = '';
  }

  // Triggered before Pico renders the page
  public function onPageRendering( Twig_Environment &$twig, array &$twigVariables, &$templateName )
  {
    $this->debug_info['onPageRendering']  = 'twig = ' . get_class( $twig );
    // $this->debug_info['onPageRendering'] .= ' - twigVariables = ' . implode( ', ', array_keys( $twigVariables ) );
    $this->debug_info['onPageRendering'] .= ' - twigVariables = ';
    foreach( $twigVariables as $key => $value ) $this->debug_info['onPageRendering'] .= $key . ': ' . ( ( is_bool( $value ) || is_numeric( $value ) || ( is_string( $value ) && strlen( $value ) < 100 ) ) ? "<b>$value</b>" : gettype( $value ) ) . ', ';
    $this->debug_info['onPageRendering'] .= ' - templageName = ' . var_export( $templateName, TRUE );
  }

  // Triggered after Pico has rendered the page
  public function onPageRendered( &$output )
  {
    $this->debug_info['onPageRendered'] = 'output (len): ' .  strlen( $output );
    $debug  = '<div style="background: rgba( 255, 204, 22, 0.8 ); border: 1px solid red; color: #222; position: fixed; bottom: 0; font-size: 12px; margin: 0; overflow-y: scroll; padding: 5px; width: 100%; height: 25%; z-index: 99999;"><table style="width: 100%">';
    foreach( $this->debug_info as $key => $value ) $debug .= '<tr><td style="border-bottom: 1px dashed #d50"><i>' . $key . '</i>&nbsp;&nbsp;</td><td style="border-bottom: 1px dashed #d50">' . $value . "</td></tr>\n";
    $debug .= "</table></div>\n";
    // $debug  = '<div style="background: rgba( 255, 204, 22, 0.8 ); border: 1px dotted red; color: #222; position: fixed; bottom: 0; font-size: 12px; font-family: \'Consolas\', monospace; margin: 10px 0 0 0; overflow-y: scroll; padding: 5px; width: 100%; height: 25%; z-index: 99999;">';
    // foreach( $this->debug_info as $key => $value ) $debug .= '[' . $key . ']&nbsp; ' . $value . "<br/>\n";
    // $debug .= "</div>\n";
    $output = substr_replace( $output, $debug, strpos( $output, '</body>' ), 0 );
  }
}
