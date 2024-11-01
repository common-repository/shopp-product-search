<?php

/**
*   Plugin Name: Shopp Product Search
*   Version: 1.0
*   Description: Plugin requires Shopp Plugin for WordPress, attaches the ability to search products by title to the WordPress search bar.
*   Author: dgilfoy, ivycat
*   
**/
/**
------------------------------------------------------------------------
Copyright 2011 IvyCat, Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/
class ICShoppProductSearch{
    
    protected $posts;
    protected $term;
    protected $html5;
    protected $segment;
    protected $dbc;
    protected $results;
    
    public function __construct( $url, $posts, $html5 ){
        global  $wpdb;
        $this->posts = $posts;
        $this->dbc = $wpdb;
        $this->html5 = $html5;
        self::set_term( $url );
    }
    
    public function display_results( $segment ){
        $products = self::search();
        $term = $this->term;
        $output = '';
        if( !$products){
            if( !$this->posts ) return self::no_results();
        }else{
            $markup_array = self::get_wrapper_markup();
            $wrapper = apply_filters( 'shopp_search_wrapper_markup', $markup_array );
            $output .= '<'.$wrapper['main'].' class="search-results">'."\r\n";
            $output .= ( $this->html5 ) ? '<header>' . $wrapper['heading'] . '</header>'."\r\n" : $wrapper['heading']."\r\n";
            foreach( $products as $product ):
                //$output .= '<img src=' . site_url( '/wp-content/uploads/products/'. self::get_product_image( $product->id ) ) .' />';
                $description = preg_replace( "!$term!i", "<span class='srch-rslt'>$term</span>", $product->description );
                $output .= '<'.$wrapper['inner'].' class="shopp-product" id="product-'.$product->id.'">'."\r\n";
                $output .= '<'.$wrapper['title'].'><a href="'.home_url( $segment.'/' ).$product->slug .'">'.$product->name.'</a></'.$wrapper['title'].'>'."\r\n";
                $output .= '<'.$wrapper['description'].'>'.$description.'</'.$wrapper['description'].'>'."\r\n";
                $output .= '</'.$wrapper['inner'].'>'."\r\n";
            endforeach; 
            $output .= '</'.$wrapper['main'].'>'."\r\n"; 
            //include_once ( $this->html5 ) ? 'templates/prod-template-html5.php' : 'templates/prod-template-xhtml.php';
            return $output;
        }
    }
    
    protected function search(){
        $search_results = self::get_title_results();
        $desc = self::get_desc_results();
        if( $desc ){
            $search_results = array_merge( $search_results, $desc );
        }
        return ( !$search_results ) ? false : $search_results;
    }
    
    protected function no_results(){
         return '<div class="notice">
		    <p class="bottom">Sorry, no results were found.</p>
	    </div>';  
    }
    
    protected function get_wrapper_markup(){
        $html5 = array(
            "main"=>"section",
            "heading"=> '<h2>Product Results</h2>',
            "inner"=>"article",
            "title"=>"h3",
            "description"=>"p"
        );
        $xhtml = array(
            "main"=>"div",
            "heading"=>"<h2>Product Results</h2>",
            "inner"=>"div",
            "title"=>"h3",
            "description"=>"p"
        );
        return ( $this->html5 ) ? $html5 : $xhtml;
    }
    
    protected function get_product_image( $id ){
        $sql = "SELECT value FROM " . $this->dbc->prefix . "shopp_meta WHERE type='image' AND parent = $id";
        $rslt = $this->dbc->get_results( $sql );
        $uri = unserialize( $rslt[0]->value );
        return $uri->uri;
    }
    
    protected function get_title_results( ){
        if( !preg_match( '![a-zA-Z0-9 -]*!', $this->term ) ) return false;
        $sql = "SELECT p.id, p.name, p.slug, p.description, p.options FROM " . $this->dbc->prefix . "shopp_product p
                WHERE p.name LIKE '%" . $this->term . "%'";
        $rslts = $this->dbc->get_results( $sql );
        return ( is_array( $rslts ) ) ? $rslts : false;   
    }
    
    protected function get_desc_results(){
        if( !preg_match( '![a-zA-Z0-9 -]*!', $this->term ) ) return false;
        $sql = "SELECT p.id, p.name, p.slug, p.description, p.options FROM " . $this->dbc->prefix . "shopp_product p 
                WHERE p.description LIKE '%" . $this->term . "%'";
        $rslts = $this->dbc->get_results( $sql );
        return ( is_array( $rslts ) ) ? $rslts : false;   
    }
    
    protected function set_term( $url ){
        $term = preg_replace( '![a-z /]*/search/!', '', $url );
        $this->term = str_replace( '+', ' ', $term );
    }
    
}

function simple_shopp_product_search( $posts = false, $html5=false, $product_url_segment='products' ){
    $srch = new ICShoppProductSearch( $_SERVER["REQUEST_URI"], $posts, $html5 );
    echo $srch->display_results( $product_url_segment );
}

