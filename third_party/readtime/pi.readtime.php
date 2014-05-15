<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Forward compatibility for the ee() function which replaces $this->EE in recent versions of ExpressionEngine
/**
 * < EE 2.6.0 backward compat
 */
if ( ! function_exists('ee'))
{
	function ee()
	{
		static $EE;
		if ( ! $EE) $EE = get_instance();
		return $EE;
	}
}

/*
========================================================
Plugin Read Time
--------------------------------------------------------
File: pi.readtime.php
========================================================
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF
ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO
EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN
AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE
OR OTHER DEALINGS IN THE SOFTWARE.
========================================================
*/

$plugin_info = array(
    'pi_name'           => 'Read Time',
    'pi_version'        => '0.1',
    'pi_author'         => 'Nathan Reed',
    'pi_author_url'     => 'http://vimia.co.uk',
    'pi_description'    => 'Calculates how long it takes to read a field',
    'pi_usage'          => readtime::usage()
);


/**
 * Excerpt Class
 *
 * @package     ExpressionEngine
 * @category    Plugin
 * @author      Nathan Reed
 * @copyright   Copyright (c) 2014, Nathan Reed
 * @link        NA
 */
 
Class Readtime {

    var $return_data;

    function __construct()
    {
        $this->speed = ee()->TMPL->fetch_param('speed', '200');
        
        // Cleanup speed parameter
        if (!is_numeric($this->speed))
        {
            ee()->TMPL->log_item('Excerpt: Error - speed parameter not numeric');
            $this->speed = 200;
        }

        // Pass cleaned tag content to $return_data
        $this->return_data = $this->readTime(ee()->TMPL->tagdata);
    }// END __construct
    
    
    function readTime($str)
    {
        // Clean content
        $str = strip_tags($str);
        $str = str_replace("\n", ' ', $str);
        $str = preg_replace("/\s+/", ' ', $str);
        $str = trim($str);
        
        // Calculate number of words
        $words = explode(' ', $str);
        $count = count($words);
        
        // Calculate time
        $fraction_time = $count / $this->speed;
        
        // Return if less than 1 minute
        if( $fraction_time < 1 ) {
        
            return 'Less than 1 minute';
            
        }
        
        
        // Round fraction up
        $result_time = ceil($count / $this->speed);
        
        $min_txt = $result_time <= 1 ? 'minute' : 'minutes';
        
        $result_time = $result_time . ' ' . $min_txt;
        
        
        // Return output
        return $result_time;
    }// END clean


    /**
     * Usage
     *
     * Plugin Usage
     *
     * @access  public
     * @return  string
     */
    function usage()
    {
        ob_start();
        ?>
Read time plugin

Wrap anything you want to be processed between the tag pairs. 
This will calculate the time after after.        

Example:
----------------
{exp:readtime speed="300"}{body}{/exp:readtime}


Parameters:
----------------
speed=""
Number of words read per minute. Default is 200.


----------------
Changelog

Version 0.1
- Beta release



        <?php
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }// END usage

}// END Class
/* End of file pi.readtime.php */
/* Location: ./system/expressionengine/third_party/readtime/pi.readtime.php */